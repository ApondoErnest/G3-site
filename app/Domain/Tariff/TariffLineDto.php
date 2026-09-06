<?php

namespace App\Domain\Tariff;

use App\Domain\ValueObjects\MoneyXaf;

final readonly class TariffLineDto
{
    /**
     * @param  array{fr: string, en: string}|null  $validityNotes
     * @param  list<int>  $centreIds
     */
    public function __construct(
        public int $id,
        public int $vehicleCategoryId,
        public ?int $serviceId,
        public MoneyXaf $amount,
        public ?array $validityNotes,
        public array $centreIds,
    ) {}
}
