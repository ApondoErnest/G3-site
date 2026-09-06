<?php

namespace App\Actions\Catalogue;

use App\Actions\Catalogue\Data\PublishedServiceEntry;
use App\Models\Catalogue\Service;
use App\Support\CacheKeys;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class ResolvePublishedServices
{
    /**
     * @return list<PublishedServiceEntry>
     */
    public function __invoke(): array
    {
        /** @var Collection<int, Service> $services */
        $services = Cache::remember(
            CacheKeys::catalogueServices(),
            CacheKeys::catalogueTtlSeconds(),
            fn () => Service::query()
                ->published()
                ->ordered()
                ->with('centres:id')
                ->get(),
        );

        return $services
            ->map(fn (Service $service) => new PublishedServiceEntry(
                id: $service->id,
                code: $service->code,
                title: $service->title,
                summary: $service->summary,
                icon: $service->icon,
                centreIds: $service->centres->pluck('id')->all(),
            ))
            ->values()
            ->all();
    }
}
