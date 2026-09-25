<?php

namespace App\Actions\Tariff\Data;

final readonly class PublicTariffLine
{
    /**
     * @param  list<string>  $centreNames
     */
    public function __construct(
        public int $id,
        public string $profileId,
        public string $categoryCode,
        public string $selector,
        public string $title,
        public string $label,
        public string $examples,
        public string $plain,
        public string $amount,
        public string $validity,
        public string $image,
        public array $centreNames,
    ) {}
}
