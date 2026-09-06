<?php

namespace App\Actions\Schedule\Data;

use App\Models\User;
use Carbon\CarbonImmutable;

final readonly class CreateScheduleExceptionData
{
    /**
     * @param  array<string, mixed>|null  $reason
     */
    public function __construct(
        public bool $appliesToAllCentres,
        public ?int $centreId,
        public CarbonImmutable $startsOn,
        public ?CarbonImmutable $endsOn,
        public bool $isOpen,
        public ?string $opensAt,
        public ?string $closesAt,
        public ?array $reason,
        public User $actor,
    ) {}
}
