<?php

namespace App\Actions\Appointment;

use App\Actions\Appointment\Data\TransitionAppointmentStatusData;
use App\Domain\Appointment\AppointmentStateMachine;
use App\Domain\Enums\HistoryActorType;
use App\Events\AppointmentStatusChanged;
use App\Models\Appointment\AppointmentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

final class TransitionAppointmentStatus
{
    use AuthorizesAppointmentChanges;

    public function __construct(
        private AppointmentStateMachine $stateMachine,
    ) {}

    public function __invoke(TransitionAppointmentStatusData $data): AppointmentRequest
    {
        $appointment = AppointmentRequest::query()->findOrFail($data->appointmentId);

        $this->authorizeAppointmentChange($data->actor, $appointment);

        $from = $appointment->status;
        $this->stateMachine->assertCanTransition($from, $data->toStatus);

        DB::transaction(function () use ($appointment, $data, $from): void {
            $appointment->update([
                'status' => $data->toStatus,
                'finalized_at' => $data->toStatus->isFinal() ? now() : null,
            ]);

            $appointment->recordTransition(
                $data->toStatus,
                HistoryActorType::User,
                $data->actor,
                $data->publicNote?->toArray(),
            );

            DB::afterCommit(function () use ($appointment, $from, $data): void {
                Event::dispatch(new AppointmentStatusChanged(
                    appointmentId: $appointment->id,
                    from: $from,
                    to: $data->toStatus,
                    actorUserId: $data->actor->id,
                ));
            });
        });

        return $appointment->refresh();
    }
}
