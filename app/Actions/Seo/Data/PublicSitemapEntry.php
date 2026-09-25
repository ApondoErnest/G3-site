<?php

namespace App\Actions\Seo\Data;

final readonly class PublicSitemapEntry
{
    public function __construct(
        public string $location,
    ) {}
}
