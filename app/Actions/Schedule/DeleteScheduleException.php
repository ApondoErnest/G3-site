<?php

namespace App\Actions\Schedule;

use App\Actions\Schedule\Data\DeleteScheduleExceptionData;
use App\Models\Centre\Centre;
use App\Models\Centre\ScheduleException;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class DeleteScheduleException
{
    use AuthorizesCentreScheduleChanges;

    public function __invoke(DeleteScheduleExceptionData $data): void
    {
        $exception = ScheduleException::query()->findOrFail($data->exceptionId);

        if ($exception->applies_to_all_centres) {
            $this->authorizeCentreScheduleChange($data->actor, Centre::query()->active()->orderBy('id')->firstOrFail());
        } else {
            $centre = Centre::query()->findOrFail($exception->centre_id);
            $this->authorizeCentreScheduleChange($data->actor, $centre);
        }

        $wasGlobal = $exception->applies_to_all_centres;
        $centreId = $exception->centre_id;

        $exception->delete();

        if ($wasGlobal) {
            Centre::query()->pluck('id')->each(
                fn (int $id) => Cache::forget(CacheKeys::scheduleCentre($id)),
            );

            return;
        }

        Cache::forget(CacheKeys::scheduleCentre((int) $centreId));
    }
}
