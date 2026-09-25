<?php

namespace App\Actions\Schedule;

use App\Actions\Schedule\Data\CreateScheduleExceptionData;
use App\Models\Centre\Centre;
use App\Models\Centre\ScheduleException;
use App\Support\CacheKeys;
use App\Support\PublicPageCache;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

final class CreateScheduleException
{
    use AuthorizesCentreScheduleChanges;

    public function __invoke(CreateScheduleExceptionData $data): ScheduleException
    {
        $this->validateScope($data->appliesToAllCentres, $data->centreId);

        if ($data->appliesToAllCentres) {
            $this->authorizeCentreScheduleChange($data->actor, Centre::query()->active()->orderBy('id')->firstOrFail());
        } else {
            $centre = Centre::query()->findOrFail($data->centreId);
            $this->authorizeCentreScheduleChange($data->actor, $centre);
        }

        $this->validateOpenTimes($data->isOpen, $data->opensAt, $data->closesAt);

        $exception = ScheduleException::query()->create([
            'applies_to_all_centres' => $data->appliesToAllCentres,
            'centre_id' => $data->appliesToAllCentres ? null : $data->centreId,
            'starts_on' => $data->startsOn->toDateString(),
            'ends_on' => $data->endsOn?->toDateString(),
            'is_open' => $data->isOpen,
            'opens_at' => $data->isOpen ? $data->opensAt : null,
            'closes_at' => $data->isOpen ? $data->closesAt : null,
            'reason' => $data->reason,
            'created_by' => $data->actor->id,
        ]);

        $this->invalidateCaches($exception);

        return $exception;
    }

    private function validateScope(bool $appliesToAllCentres, ?int $centreId): void
    {
        if ($appliesToAllCentres && $centreId !== null) {
            throw new InvalidArgumentException('Global exceptions cannot target a centre.');
        }

        if (! $appliesToAllCentres && $centreId === null) {
            throw new InvalidArgumentException('Centre-specific exceptions require a centre.');
        }
    }

    private function validateOpenTimes(bool $isOpen, ?string $opensAt, ?string $closesAt): void
    {
        if (! $isOpen) {
            return;
        }

        if ($opensAt === null || $closesAt === null) {
            throw new InvalidArgumentException('Open exceptions require open and close times.');
        }
    }

    private function invalidateCaches(ScheduleException $exception): void
    {
        PublicPageCache::forgetLiveStatus();

        if ($exception->applies_to_all_centres) {
            Centre::query()->pluck('id')->each(
                fn (int $centreId) => Cache::forget(CacheKeys::scheduleCentre($centreId)),
            );

            return;
        }

        Cache::forget(CacheKeys::scheduleCentre((int) $exception->centre_id));
    }
}
