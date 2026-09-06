<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PublishedContentBlockEntry;
use App\Models\Content\ContentBlock;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedContentBlock
{
    public function __invoke(string $key): ?PublishedContentBlockEntry
    {
        /** @var PublishedContentBlockEntry|null $entry */
        $entry = Cache::remember(
            CacheKeys::contentBlock($key),
            CacheKeys::contentTtlSeconds(),
            function () use ($key): ?PublishedContentBlockEntry {
                $block = ContentBlock::query()
                    ->published()
                    ->where('key', $key)
                    ->first();

                if ($block === null) {
                    return null;
                }

                return new PublishedContentBlockEntry(
                    key: $block->key,
                    page: $block->page,
                    schemaVersion: $block->schema_version,
                    content: $block->content,
                );
            },
        );

        return $entry;
    }
}
