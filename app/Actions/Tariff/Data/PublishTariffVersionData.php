<?php

namespace App\Actions\Tariff\Data;

use App\Models\User;
use Carbon\CarbonImmutable;

final readonly class PublishTariffVersionData
{
    public function __construct(
        public int $tariffVersionId,
        public CarbonImmutable $confirmEffectiveFrom,
        public User $actor,
    ) {}
}
