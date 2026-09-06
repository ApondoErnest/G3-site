<?php

namespace App\Actions\Appointment\Data;

use App\Domain\Enums\AppointmentStatus;

final readonly class TrackingResult
{
    /**
     * @param  list<TrackingTimelineEntry>  $timeline
     */
    public function __construct(
        public string $publicReference,
        public AppointmentStatus $currentStatus,
        public array $timeline,
        public string $centreName,
    ) {}
}
