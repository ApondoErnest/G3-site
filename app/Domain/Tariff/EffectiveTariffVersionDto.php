<?php

namespace App\Domain\Tariff;

final readonly class EffectiveTariffVersionDto
{
    public function __construct(
        public int $id,
        public string $label,
        public string $effectiveFrom,
        public ?string $effectiveUntil,
    ) {}
}
