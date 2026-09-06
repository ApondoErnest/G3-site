<?php

namespace App\Actions\Catalogue\Data;

final readonly class RequiredDocumentEntry
{
    /**
     * @param  array{fr: string, en: string}  $label
     */
    public function __construct(
        public int $id,
        public array $label,
        public ?int $vehicleCategoryId,
        public ?int $serviceId,
    ) {}
}
