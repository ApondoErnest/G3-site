<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PublishedContentBlockEntry;
use App\Domain\Enums\ContentPage;
use App\Models\Content\ContentBlock;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedContentBlocksForPage
{
    /**
     * @return list<PublishedContentBlockEntry>
     */
    public function __invoke(ContentPage $page): array
    {
        /** @var list<PublishedContentBlockEntry> $entries */
        $entries = Cache::remember(
            CacheKeys::contentBlocksForPage($page->value),
            CacheKeys::contentTtlSeconds(),
            function () use ($page): array {
                return ContentBlock::query()
                    ->published()
                    ->where('page', $page)
                    ->orderBy('key')
                    ->get()
                    ->map(fn (ContentBlock $block) => new PublishedContentBlockEntry(
                        key: $block->key,
                        page: $block->page,
                        schemaVersion: $block->schema_version,
                        content: $block->content,
                    ))
                    ->values()
                    ->all();
            },
        );

        return $entries;
    }
}
