<?php

namespace App\Actions\Tariff\Data;

final readonly class TariffItemData
{
    /**
     * @param  list<int>  $centreIds
     * @param  array{fr: string, en: string}|null  $validityNotes
     */
    public function __construct(
        public int $vehicleCategoryId,
        public int $amountXaf,
        public array $centreIds,
        public ?int $serviceId = null,
        public ?array $validityNotes = null,
        public int $sortOrder = 1,
    ) {}
}
