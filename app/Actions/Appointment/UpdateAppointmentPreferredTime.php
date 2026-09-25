<?php

namespace App\Actions\Appointment;

use App\Actions\Appointment\Data\UpdateAppointmentPreferredTimeData;
use App\Actions\Schedule\ResolveCentreAvailability;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\HistoryActorType;
use App\Models\Appointment\AppointmentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class UpdateAppointmentPreferredTime
{
    use AuthorizesAppointmentChanges;

    public function __construct(
        private ResolveCentreAvailability $resolveCentreAvailability,
    ) {}

    public function __invoke(UpdateAppointmentPreferredTimeData $data): AppointmentRequest
    {
        $appointment = AppointmentRequest::query()->findOrFail($data->appointmentId);

        $this->authorizeAppointmentChange($data->actor, $appointment);
        $this->assertRescheduleAllowed($appointment->status);

        if (! $this->resolveCentreAvailability->isBookableOnDate(
            $appointment->centre_id,
            $data->preferredDate,
            $data->preferredPeriod,
        )) {
            throw ValidationException::withMessages([
                'preferred_date' => [__('public.security.appointment_outside_hours')],
            ]);
        }

        DB::transaction(function () use ($appointment, $data): void {
            $appointment->update([
                'preferred_date' => $data->preferredDate->toDateString(),
                'preferred_period' => $data->preferredPeriod,
            ]);

            if ($data->publicNote !== null) {
                $appointment->recordTransition(
                    $appointment->status,
                    HistoryActorType::User,
                    $data->actor,
                    $data->publicNote->toArray(),
                );
            }
        });

        return $appointment->refresh();
    }

    private function assertRescheduleAllowed(AppointmentStatus $status): void
    {
        if ($status->isFinal()) {
            throw ValidationException::withMessages([
                'appointmentId' => ['Completed or cancelled appointments cannot be rescheduled.'],
            ]);
        }
    }
}
