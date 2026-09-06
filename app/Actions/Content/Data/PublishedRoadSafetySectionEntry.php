<?php

namespace App\Actions\Content\Data;

final readonly class PublishedRoadSafetySectionEntry
{
    /**
     * @param  array{fr: string, en: string}  $title
     * @param  array{fr: string, en: string}  $body
     */
    public function __construct(
        public string $anchor,
        public array $title,
        public array $body,
        public int $sortOrder,
    ) {}
}
