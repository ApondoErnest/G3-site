<?php

namespace App\Events;

use App\Domain\Enums\AppointmentStatus;

final readonly class AppointmentStatusChanged
{
    public function __construct(
        public int $appointmentId,
        public AppointmentStatus $from,
        public AppointmentStatus $to,
        public ?int $actorUserId,
    ) {}
}
