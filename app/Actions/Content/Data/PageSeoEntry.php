<?php

namespace App\Actions\Content\Data;

use App\Domain\Enums\ContentPage;

final readonly class PageSeoEntry
{
    /**
     * @param  array{fr: string, en: string}  $seoTitle
     * @param  array{fr: string, en: string}  $seoDescription
     */
    public function __construct(
        public ContentPage $page,
        public array $seoTitle,
        public array $seoDescription,
    ) {}
}
