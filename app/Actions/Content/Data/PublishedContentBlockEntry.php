<?php

namespace App\Actions\Content\Data;

use App\Domain\Enums\ContentPage;

final readonly class PublishedContentBlockEntry
{
    /**
     * @param  array{fr: array<string, mixed>, en: array<string, mixed>}  $content
     */
    public function __construct(
        public string $key,
        public ContentPage $page,
        public int $schemaVersion,
        public array $content,
    ) {}
}
