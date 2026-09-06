<?php

namespace App\Domain\Tariff;

final readonly class EffectiveTariffResult
{
    /**
     * @param  list<TariffLineDto>  $items
     */
    public function __construct(
        public ?EffectiveTariffVersionDto $version,
        public array $items,
        public bool $isEmpty,
    ) {}

    public static function empty(): self
    {
        return new self(null, [], true);
    }
}
