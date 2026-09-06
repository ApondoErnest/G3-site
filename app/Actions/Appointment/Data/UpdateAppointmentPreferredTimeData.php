<?php

namespace App\Actions\Appointment\Data;

use App\Domain\Enums\PreferredPeriod;
use App\Domain\ValueObjects\TranslatableCopy;
use App\Models\User;
use Carbon\CarbonImmutable;

final readonly class UpdateAppointmentPreferredTimeData
{
    public function __construct(
        public int $appointmentId,
        public CarbonImmutable $preferredDate,
        public PreferredPeriod $preferredPeriod,
        public User $actor,
        public ?TranslatableCopy $publicNote = null,
    ) {}
}
