<?php

namespace App\Actions\Appointment;

use App\Actions\Appointment\Data\AddAppointmentInternalNoteData;
use App\Models\Appointment\AppointmentInternalNote;
use App\Models\Appointment\AppointmentRequest;

final class AddAppointmentInternalNote
{
    use AuthorizesAppointmentChanges;

    public function __invoke(AddAppointmentInternalNoteData $data): AppointmentInternalNote
    {
        $appointment = AppointmentRequest::query()->findOrFail($data->appointmentId);

        $this->authorizeAppointmentChange($data->author, $appointment);

        return $appointment->internalNotes()->create([
            'author_id' => $data->author->id,
            'body' => $data->body,
        ]);
    }
}
