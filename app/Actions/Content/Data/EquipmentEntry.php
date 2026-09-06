<?php

namespace App\Actions\Content\Data;

final readonly class EquipmentEntry
{
    /**
     * @param  array{fr: string, en: string}  $label
     */
    public function __construct(
        public string $code,
        public array $label,
    ) {}
}
