<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\UpdateContentBlockData;
use App\Models\Content\ContentBlock;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class UpdateContentBlock
{
    use AuthorizesContentChanges;

    public function __invoke(UpdateContentBlockData $data): ContentBlock
    {
        $this->authorizeContentChange($data->actor);

        $block = ContentBlock::query()->findOrFail($data->contentBlockId);
        $wasPublished = $block->is_published;

        $block->update([
            'content' => $data->content,
            'locale_status' => $data->localeStatus,
        ]);

        $block->refresh();

        if ($wasPublished && ! $block->isReadyToPublish()) {
            $block->update([
                'is_published' => false,
                'published_at' => null,
            ]);
            $this->invalidateCache($block);
        }

        return $block->refresh();
    }

    private function invalidateCache(ContentBlock $block): void
    {
        Cache::forget(CacheKeys::contentBlock($block->key));
        Cache::forget(CacheKeys::contentBlocksForPage($block->page->value));
    }
}
