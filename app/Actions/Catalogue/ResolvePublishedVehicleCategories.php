<?php

namespace App\Actions\Catalogue;

use App\Actions\Catalogue\Data\PublishedVehicleCategoryEntry;
use App\Models\Catalogue\VehicleCategory;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedVehicleCategories
{
    /**
     * @return list<PublishedVehicleCategoryEntry>
     */
    public function __invoke(): array
    {
        /** @var list<array{id: int, code: string, label: array{fr: string, en: string}, examples: array{fr: string, en: string}|null, description: array{fr: string, en: string}|null}> $categories */
        $categories = Cache::remember(
            CacheKeys::catalogueCategories(),
            CacheKeys::catalogueTtlSeconds(),
            fn () => VehicleCategory::query()
                ->published()
                ->ordered()
                ->get()
                ->map(fn (VehicleCategory $category): array => [
                    'id' => $category->id,
                    'code' => $category->code,
                    'label' => $category->label,
                    'examples' => $category->examples,
                    'description' => $category->description,
                ])
                ->values()
                ->all(),
        );

        return collect($categories)
            ->map(fn (array $category): PublishedVehicleCategoryEntry => new PublishedVehicleCategoryEntry(
                id: $category['id'],
                code: $category['code'],
                label: $category['label'],
                examples: $category['examples'],
                description: $category['description'],
            ))
            ->all();
    }
}
