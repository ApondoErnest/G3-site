<?php

namespace App\Events;

final readonly class AppointmentRequested
{
    public function __construct(
        public int $appointmentId,
        public string $publicReference,
        public int $centreId,
        public string $locale,
    ) {}
}
