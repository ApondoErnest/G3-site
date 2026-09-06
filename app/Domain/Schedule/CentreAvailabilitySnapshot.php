<?php

namespace App\Domain\Schedule;

use App\Domain\Enums\LiveCentreState;
use Carbon\CarbonImmutable;

final readonly class CentreAvailabilitySnapshot
{
    public function __construct(
        public LiveCentreState $state,
        public bool $isOpenNow,
        public ?CarbonImmutable $nextCloseAt,
        public ?CarbonImmutable $nextOpenAt,
        public ?string $reasonKey = null,
    ) {}
}
