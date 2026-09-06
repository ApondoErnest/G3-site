<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\PublishedRoadSafetySectionEntry;
use App\Models\Content\RoadSafetySection;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedRoadSafetySections
{
    /**
     * @return list<PublishedRoadSafetySectionEntry>
     */
    public function __invoke(): array
    {
        /** @var list<PublishedRoadSafetySectionEntry> $entries */
        $entries = Cache::remember(
            CacheKeys::roadSafetyPublished(),
            CacheKeys::contentTtlSeconds(),
            fn () => RoadSafetySection::query()
                ->published()
                ->ordered()
                ->get()
                ->map(fn (RoadSafetySection $section) => new PublishedRoadSafetySectionEntry(
                    anchor: $section->anchor,
                    title: $section->title,
                    body: $section->body,
                    sortOrder: $section->sort_order,
                ))
                ->values()
                ->all(),
        );

        return $entries;
    }
}
