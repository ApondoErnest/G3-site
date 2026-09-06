<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PublishContentBlockData;
use App\Models\Content\ContentBlock;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

final class PublishContentBlock
{
    use AuthorizesContentChanges;

    public function __invoke(PublishContentBlockData $data): ContentBlock
    {
        $this->authorizeContentChange($data->actor);

        $block = ContentBlock::query()->findOrFail($data->contentBlockId);

        if (! $block->isReadyToPublish()) {
            throw new InvalidArgumentException('Content block cannot be published until both locales are complete.');
        }

        $block->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->invalidateCache($block);

        return $block->refresh();
    }

    private function invalidateCache(ContentBlock $block): void
    {
        Cache::forget(CacheKeys::contentBlock($block->key));
        Cache::forget(CacheKeys::contentBlocksForPage($block->page->value));
    }
}
