<?php

namespace App\Actions\Appointment\Data;

use App\Domain\Enums\AppointmentStatus;
use App\Domain\ValueObjects\TranslatableCopy;
use App\Models\User;

final readonly class TransitionAppointmentStatusData
{
    public function __construct(
        public int $appointmentId,
        public AppointmentStatus $toStatus,
        public User $actor,
        public ?TranslatableCopy $publicNote = null,
    ) {}
}
