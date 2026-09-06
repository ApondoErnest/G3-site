<?php

namespace App\Actions\Appointment\Data;

use App\Domain\Enums\AppointmentStatus;
use Carbon\CarbonImmutable;

final readonly class TrackingTimelineEntry
{
    public function __construct(
        public AppointmentStatus $status,
        public string $labelKey,
        public CarbonImmutable $at,
    ) {}
}
