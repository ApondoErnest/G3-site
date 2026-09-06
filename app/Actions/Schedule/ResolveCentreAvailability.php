<?php

namespace App\Actions\Schedule;

use App\Domain\Enums\PreferredPeriod;
use App\Domain\Schedule\AvailabilityEngine;
use App\Domain\Schedule\CentreAvailabilitySnapshot;
use App\Models\Centre\Centre;
use App\Support\Clock;
use Carbon\CarbonImmutable;

final class ResolveCentreAvailability
{
    public function __construct(
        private AvailabilityEngine $engine,
    ) {}

    public function snapshot(int $centreId, ?CarbonImmutable $at = null): CentreAvailabilitySnapshot
    {
        $centre = Centre::query()->with('weeklyHours')->findOrFail($centreId);

        return $this->engine->snapshot($centre, $at ?? Clock::nowDisplay());
    }

    public function isBookableOnDate(
        int $centreId,
        CarbonImmutable $date,
        PreferredPeriod $period,
    ): bool {
        $centre = Centre::query()->with('weeklyHours')->findOrFail($centreId);

        return $this->engine->isBookableOnDate(
            $centre,
            $date->timezone(Clock::displayTimezone())->startOfDay(),
            $period,
        );
    }
}
