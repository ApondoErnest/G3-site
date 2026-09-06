<?php

namespace App\Actions\Catalogue\Data;

final readonly class PublishedVehicleCategoryEntry
{
    /**
     * @param  array{fr: string, en: string}  $label
     * @param  array{fr: string, en: string}|null  $examples
     * @param  array{fr: string, en: string}|null  $description
     */
    public function __construct(
        public int $id,
        public string $code,
        public array $label,
        public ?array $examples,
        public ?array $description,
    ) {}
}
