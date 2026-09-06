<?php

namespace App\Actions\Appointment\Data;

use App\Models\User;

final readonly class AddAppointmentInternalNoteData
{
    public function __construct(
        public int $appointmentId,
        public string $body,
        public User $author,
    ) {}
}
