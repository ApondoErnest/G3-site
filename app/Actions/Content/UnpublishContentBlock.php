<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\UnpublishContentBlockData;
use App\Models\Content\ContentBlock;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class UnpublishContentBlock
{
    use AuthorizesContentChanges;

    public function __invoke(UnpublishContentBlockData $data): ContentBlock
    {
        $this->authorizeContentChange($data->actor);

        $block = ContentBlock::query()->findOrFail($data->contentBlockId);

        $block->update([
            'is_published' => false,
            'published_at' => null,
        ]);

        Cache::forget(CacheKeys::contentBlock($block->key));
        Cache::forget(CacheKeys::contentBlocksForPage($block->page->value));

        return $block->refresh();
    }
}
