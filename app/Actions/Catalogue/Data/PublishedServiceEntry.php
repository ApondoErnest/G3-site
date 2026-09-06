<?php

namespace App\Actions\Catalogue\Data;

final readonly class PublishedServiceEntry
{
    /**
     * @param  array{fr: string, en: string}  $title
     * @param  array{fr: string, en: string}|null  $summary
     * @param  list<int>  $centreIds
     */
    public function __construct(
        public int $id,
        public string $code,
        public array $title,
        public ?array $summary,
        public ?string $icon,
        public array $centreIds,
    ) {}
}
