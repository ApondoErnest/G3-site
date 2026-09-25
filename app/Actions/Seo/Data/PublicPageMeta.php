<?php

namespace App\Actions\Seo\Data;

final readonly class PublicPageMeta
{
    /**
     * @param  array<string, string>  $alternates
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public array $alternates,
        public string $defaultLocaleUrl,
    ) {}
}
