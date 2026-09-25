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
        $key = CacheKeys::pageSeo($page->value);
        $cached = Cache::get($key);

        if (is_object($cached)) {
            Cache::forget($key);
            $cached = null;
        }

        if (! is_array($cached)) {
            /** @var array{page: string, seo_title: array<string, string>, seo_description: array<string, string>}|null $cached */
            $cached = Cache::remember(
                $key,
                CacheKeys::contentTtlSeconds(),
                fn (): ?array => $this->payload($page),
            );
        }

        if (! is_array($cached)) {
            return null;
        }

        return new PageSeoEntry(
            page: ContentPage::from($cached['page']),
            seoTitle: $cached['seo_title'],
            seoDescription: $cached['seo_description'],
        );
    }

    /**
     * @return array{page: string, seo_title: array<string, string>, seo_description: array<string, string>}|null
     */
    private function payload(ContentPage $page): ?array
    {
        $seo = PageSeo::query()->find($page->value);

        if ($seo === null) {
            return null;
        }

        return [
            'page' => $seo->page->value,
            'seo_title' => $seo->seo_title,
            'seo_description' => $seo->seo_description,
        ];
    }
}
