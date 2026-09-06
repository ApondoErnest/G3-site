<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PublishedFaqEntry;
use App\Models\Content\FaqEntry;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedFaqEntries
{
    /**
     * @return list<PublishedFaqEntry>
     */
    public function __invoke(?string $categoryCode = null): array
    {
        $cacheKey = CacheKeys::faqPublished($categoryCode);

        /** @var list<PublishedFaqEntry> $entries */
        $entries = Cache::remember(
            $cacheKey,
            CacheKeys::contentTtlSeconds(),
            function () use ($categoryCode): array {
                $query = FaqEntry::query()->published()->ordered();

                if ($categoryCode !== null) {
                    $query->where('category_code', $categoryCode);
                }

                return $query
                    ->get()
                    ->map(fn (FaqEntry $entry) => new PublishedFaqEntry(
                        id: $entry->id,
                        categoryCode: $entry->category_code,
                        question: $entry->question,
                        answer: $entry->answer,
                    ))
                    ->values()
                    ->all();
            },
        );

        return $entries;
    }
}
