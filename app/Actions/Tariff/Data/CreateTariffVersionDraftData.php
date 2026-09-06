<?php

namespace App\Actions\Tariff\Data;

use App\Models\User;
use Carbon\CarbonImmutable;

final readonly class CreateTariffVersionDraftData
{
    public function __construct(
        public string $label,
        public CarbonImmutable $effectiveFrom,
        public ?CarbonImmutable $effectiveUntil,
        public User $actor,
    ) {}
}
