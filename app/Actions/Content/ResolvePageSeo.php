<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PageSeoEntry;
use App\Domain\Enums\ContentPage;
use App\Models\Content\PageSeo;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePageSeo
{
    public function __invoke(ContentPage $page): ?PageSeoEntry
    {
        /** @var PageSeoEntry|null $entry */
        $entry = Cache::remember(
            CacheKeys::pageSeo($page->value),
            CacheKeys::contentTtlSeconds(),
            function () use ($page): ?PageSeoEntry {
                $seo = PageSeo::query()->find($page->value);

                if ($seo === null) {
                    return null;
                }

                return new PageSeoEntry(
                    page: $seo->page,
                    seoTitle: $seo->seo_title,
                    seoDescription: $seo->seo_description,
                );
            },
        );

        return $entry;
    }
}
