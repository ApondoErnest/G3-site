<?php

namespace App\Actions\Catalogue;

use App\Actions\Catalogue\Data\PublishedVehicleCategoryEntry;
use App\Models\Catalogue\VehicleCategory;
use App\Support\CacheKeys;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedVehicleCategories
{
    /**
     * @return list<PublishedVehicleCategoryEntry>
     */
    public function __invoke(): array
    {
        /** @var Collection<int, VehicleCategory> $categories */
        $categories = Cache::remember(
            CacheKeys::catalogueCategories(),
            CacheKeys::catalogueTtlSeconds(),
            fn () => VehicleCategory::query()
                ->published()
                ->ordered()
                ->get(),
        );

        return $categories
            ->map(fn (VehicleCategory $category) => new PublishedVehicleCategoryEntry(
                id: $category->id,
                code: $category->code,
                label: $category->label,
                examples: $category->examples,
                description: $category->description,
            ))
            ->values()
            ->all();
    }
}
