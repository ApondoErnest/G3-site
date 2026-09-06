<?php

namespace App\Actions\Appointment\Data;

use App\Domain\Enums\Locale;
use App\Domain\Enums\PreferredChannel;
use App\Domain\Enums\PreferredPeriod;
use Carbon\CarbonImmutable;

final readonly class CreateAppointmentRequestData
{
    public function __construct(
        public int $centreId,
        public int $serviceId,
        public int $vehicleCategoryId,
        public string $registration,
        public CarbonImmutable $preferredDate,
        public PreferredPeriod $preferredPeriod,
        public string $contactName,
        public string $contactPhone,
        public ?string $contactEmail,
        public ?PreferredChannel $preferredChannel,
        public Locale $locale,
        public ?string $idempotencyKey = null,
        public ?string $rateLimitKey = null,
    ) {}
}
