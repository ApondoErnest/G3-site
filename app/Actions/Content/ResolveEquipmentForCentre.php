<?php

namespace App\Actions\Content;

use App\Actions\Content\Data\EquipmentEntry;
use App\Models\Content\Equipment;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

final class ResolveEquipmentForCentre
{
    /**
     * @return list<EquipmentEntry>
     */
    public function __invoke(int $centreId): array
    {
        /** @var list<EquipmentEntry> $entries */
        $entries = Cache::remember(
            CacheKeys::equipmentForCentre($centreId),
            CacheKeys::contentTtlSeconds(),
            fn () => Equipment::query()
                ->ordered()
                ->whereHas('centres', fn ($query) => $query->where('centres.id', $centreId))
                ->get()
                ->map(fn (Equipment $equipment) => new EquipmentEntry(
                    code: $equipment->code,
                    label: $equipment->label,
                ))
                ->values()
                ->all(),
        );

        return $entries;
    }
}
