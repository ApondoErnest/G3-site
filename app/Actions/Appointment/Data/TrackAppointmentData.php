<?php

namespace App\Actions\Appointment\Data;

final readonly class TrackAppointmentData
{
    public function __construct(
        public string $publicReference,
        public string $phoneOrRegistration,
        public ?string $rateLimitKey = null,
    ) {}
}
