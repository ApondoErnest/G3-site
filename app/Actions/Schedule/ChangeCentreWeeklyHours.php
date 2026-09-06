<?php

namespace App\Actions\Schedule;

use App\Actions\Schedule\Data\ChangeCentreWeeklyHoursData;
use App\Models\Centre\Centre;
use App\Models\Centre\CentreWeeklyHours;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class ChangeCentreWeeklyHours
{
    use AuthorizesCentreScheduleChanges;

    public function __invoke(ChangeCentreWeeklyHoursData $data): void
    {
        $centre = Centre::query()->findOrFail($data->centreId);

        $this->authorizeCentreScheduleChange($data->actor, $centre);

        foreach ($data->weeklyHours as $hours) {
            if ($hours->isOpen && ($hours->opensAt === null || $hours->closesAt === null || $hours->opensAt >= $hours->closesAt)) {
                throw new InvalidArgumentException('Open days require valid open and close times.');
            }
        }

        DB::transaction(function () use ($data, $centre): void {
            foreach ($data->weeklyHours as $hours) {
                CentreWeeklyHours::query()->updateOrCreate(
                    [
                        'centre_id' => $centre->id,
                        'weekday' => $hours->weekday,
                    ],
                    [
                        'is_open' => $hours->isOpen,
                        'opens_at' => $hours->isOpen ? $hours->opensAt : null,
                        'closes_at' => $hours->isOpen ? $hours->closesAt : null,
                    ],
                );
            }
        });

        Cache::forget(CacheKeys::scheduleCentre($centre->id));
    }
}
