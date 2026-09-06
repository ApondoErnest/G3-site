<?php

namespace App\Domain\Schedule;

use App\Domain\Enums\CentreStatus;
use App\Domain\Enums\LiveCentreState;
use App\Domain\Enums\PreferredPeriod;
use App\Domain\Enums\Weekday;
use App\Domain\ValueObjects\TimeWindow;
use App\Models\Centre\Centre;
use App\Models\Centre\ScheduleException;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class AvailabilityEngine
{
    private const LOOKAHEAD_DAYS = 14;

    public function snapshot(Centre $centre, ?CarbonImmutable $at = null): CentreAvailabilitySnapshot
    {
        $at ??= Clock::nowDisplay();
        $centre->loadMissing('weeklyHours');

        $exceptions = $this->loadExceptions($centre, $at);

        if ($centre->status !== CentreStatus::Active) {
            return new CentreAvailabilitySnapshot(
                state: LiveCentreState::Closed,
                isOpenNow: false,
                nextCloseAt: null,
                nextOpenAt: $this->findNextOpenAt($centre, $at, $exceptions),
            );
        }

        $todayWindows = $this->openWindowsForDate($centre, $at->startOfDay(), $exceptions);
        $isOpenNow = $this->isInstantOpen($todayWindows, $at);

        $nextCloseAt = null;
        if ($isOpenNow) {
            foreach ($todayWindows as $window) {
                if ($window->contains($at)) {
                    $nextCloseAt = $window->closes;
                    break;
                }
            }
        }

        $nextOpenAt = $isOpenNow ? null : $this->findNextOpenAt($centre, $at, $exceptions);

        return new CentreAvailabilitySnapshot(
            state: $isOpenNow ? LiveCentreState::OpenNormalHours : LiveCentreState::Closed,
            isOpenNow: $isOpenNow,
            nextCloseAt: $nextCloseAt,
            nextOpenAt: $nextOpenAt,
        );
    }

    public function isBookableOnDate(
        Centre $centre,
        CarbonImmutable $date,
        PreferredPeriod $period,
    ): bool {
        if ($centre->status !== CentreStatus::Active) {
            return false;
        }

        $centre->loadMissing('weeklyHours');
        $exceptions = $this->loadExceptions($centre, $date->startOfDay());

        $windows = $this->openWindowsForDate($centre, $date->startOfDay(), $exceptions);

        if ($windows === []) {
            return false;
        }

        if ($period === PreferredPeriod::Any) {
            return true;
        }

        $dayClose = $this->latestCloseTime($windows);
        $periodWindow = $this->periodWindow($date, $period, $dayClose);

        foreach ($windows as $window) {
            if ($window->intersects($periodWindow)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<TimeWindow>
     */
    private function openWindowsForDate(
        Centre $centre,
        CarbonImmutable $date,
        Collection $exceptions,
    ): array {
        if ($centre->status !== CentreStatus::Active) {
            return [];
        }

        $exception = $this->resolveExceptionForDate($centre, $date, $exceptions);

        if ($exception !== null) {
            if (! $exception->is_open) {
                return [];
            }

            return [
                $this->windowFromTimes($date, $exception->opens_at, $exception->closes_at),
            ];
        }

        $weekday = Weekday::fromCarbon($date);
        $hours = $centre->weeklyHours->first(
            fn ($row) => $row->weekday === $weekday,
        );

        if ($hours === null || ! $hours->is_open) {
            return [];
        }

        return [
            $this->windowFromTimes($date, $hours->opens_at, $hours->closes_at),
        ];
    }

    /**
     * @param  list<TimeWindow>  $windows
     */
    private function isInstantOpen(array $windows, CarbonImmutable $instant): bool
    {
        foreach ($windows as $window) {
            if ($window->contains($instant)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  Collection<int, ScheduleException>  $exceptions
     */
    private function findNextOpenAt(
        Centre $centre,
        CarbonImmutable $at,
        Collection $exceptions,
    ): ?CarbonImmutable {
        for ($offset = 0; $offset <= self::LOOKAHEAD_DAYS; $offset++) {
            $date = $at->startOfDay()->addDays($offset);
            $windows = $this->openWindowsForDate($centre, $date, $exceptions);

            foreach ($windows as $window) {
                if ($window->opens->greaterThan($at)) {
                    return $window->opens;
                }
            }
        }

        return null;
    }

    /**
     * Centre-specific exceptions win over global ones; newest created_at wins ties.
     *
     * @param  Collection<int, ScheduleException>  $exceptions
     */
    private function resolveExceptionForDate(
        Centre $centre,
        CarbonImmutable $date,
        Collection $exceptions,
    ): ?ScheduleException {
        $applicable = $exceptions
            ->filter(fn (ScheduleException $exception) => $this->exceptionAppliesOnDate($exception, $date, $centre))
            ->sortByDesc('created_at');

        $centreSpecific = $applicable->first(
            fn (ScheduleException $exception) => ! $exception->applies_to_all_centres,
        );

        if ($centreSpecific !== null) {
            return $centreSpecific;
        }

        return $applicable->first(
            fn (ScheduleException $exception) => $exception->applies_to_all_centres,
        );
    }

    private function exceptionAppliesOnDate(
        ScheduleException $exception,
        CarbonImmutable $date,
        Centre $centre,
    ): bool {
        $day = $date->toDateString();
        $starts = $exception->starts_on->toDateString();
        $ends = ($exception->ends_on ?? $exception->starts_on)->toDateString();

        if ($day < $starts || $day > $ends) {
            return false;
        }

        if ($exception->applies_to_all_centres) {
            return true;
        }

        return $exception->centre_id === $centre->id;
    }

    /**
     * @return Collection<int, ScheduleException>
     */
    private function loadExceptions(Centre $centre, CarbonImmutable $referenceDate): Collection
    {
        $rangeStart = $referenceDate->startOfDay()->toDateString();
        $rangeEnd = $referenceDate->startOfDay()->addDays(self::LOOKAHEAD_DAYS)->toDateString();

        return ScheduleException::query()
            ->where(function ($query) use ($centre): void {
                $query->where('applies_to_all_centres', true)
                    ->orWhere('centre_id', $centre->id);
            })
            ->where('starts_on', '<=', $rangeEnd)
            ->where(function ($query) use ($rangeStart): void {
                $query->whereNull('ends_on')
                    ->orWhere('ends_on', '>=', $rangeStart);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    private function windowFromTimes(
        CarbonImmutable $date,
        mixed $opensAt,
        mixed $closesAt,
    ): TimeWindow {
        return TimeWindow::forDate(
            $date->toDateString(),
            $this->normalizeTime($opensAt),
            $this->normalizeTime($closesAt),
            Clock::displayTimezone(),
        );
    }

    private function normalizeTime(mixed $time): string
    {
        if ($time instanceof \DateTimeInterface) {
            return $time->format('H:i:s');
        }

        $time = (string) $time;

        return strlen($time) === 5 ? "{$time}:00" : $time;
    }

    /**
     * @param  list<TimeWindow>  $windows
     */
    private function latestCloseTime(array $windows): string
    {
        $latest = '12:00:00';

        foreach ($windows as $window) {
            $close = $window->closes->format('H:i:s');
            if ($close > $latest) {
                $latest = $close;
            }
        }

        return $latest;
    }

    private function periodWindow(
        CarbonImmutable $date,
        PreferredPeriod $period,
        string $dayClose,
    ): TimeWindow {
        return match ($period) {
            PreferredPeriod::Morning => TimeWindow::forDate(
                $date->toDateString(),
                '07:00:00',
                '12:00:00',
                Clock::displayTimezone(),
            ),
            PreferredPeriod::Afternoon => TimeWindow::forDate(
                $date->toDateString(),
                '12:00:00',
                $dayClose,
                Clock::displayTimezone(),
            ),
            PreferredPeriod::Any => throw new \InvalidArgumentException('Any period has no intersection window.'),
        };
    }
}
