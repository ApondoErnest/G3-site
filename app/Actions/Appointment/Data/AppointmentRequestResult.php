<?php

namespace App\Actions\Appointment\Data;

use App\Domain\Enums\AppointmentStatus;

final readonly class AppointmentRequestResult
{
    public function __construct(
        public string $publicReference,
        public AppointmentStatus $status,
        public string $messageKey,
        public bool $wasExisting = false,
    ) {}
}
