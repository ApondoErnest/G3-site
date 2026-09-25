<?php

namespace App\Actions\Tariff\Data;

final readonly class PublicTariffCatalogue
{
    /**
     * @param  list<PublicTariffLine>  $lines
     */
    public function __construct(
        public ?string $versionLabel,
        public ?string $effectiveLine,
        public array $lines,
    ) {}

    public function isEmpty(): bool
    {
        return $this->lines === [];
    }
}
