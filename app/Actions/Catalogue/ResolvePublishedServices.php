<?php

namespace App\Actions\Catalogue;

use App\Actions\Catalogue\Data\PublishedServiceEntry;
use App\Models\Catalogue\Service;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedServices
{
    /**
     * @return list<PublishedServiceEntry>
     */
    public function __invoke(): array
    {
        /** @var list<array{id: int, code: string, title: array{fr: string, en: string}, summary: array{fr: string, en: string}|null, icon: string|null, centre_ids: list<int>, category_ids: list<int>}> $services */
        $services = Cache::remember(
            CacheKeys::catalogueServices(),
            CacheKeys::catalogueTtlSeconds(),
            fn () => Service::query()
                ->published()
                ->ordered()
                ->with(['centres:id', 'vehicleCategories:id'])
                ->get()
                ->map(fn (Service $service): array => [
                    'id' => $service->id,
                    'code' => $service->code,
                    'title' => $service->title,
                    'summary' => $service->summary,
                    'icon' => $service->icon,
                    'centre_ids' => $service->centres->pluck('id')->all(),
                    'category_ids' => $service->vehicleCategories->pluck('id')->all(),
                ])
                ->values()
                ->all(),
        );

        return collect($services)
            ->map(fn (array $service): PublishedServiceEntry => new PublishedServiceEntry(
                id: $service['id'],
                code: $service['code'],
                title: $service['title'],
                summary: $service['summary'],
                icon: $service['icon'],
                centreIds: $service['centre_ids'],
                categoryIds: $service['category_ids'],
            ))
            ->all();
    }
}
