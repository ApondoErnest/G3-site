<?php

namespace App\Domain\Tariff;

use App\Domain\Enums\TariffVersionStatus;
use App\Domain\ValueObjects\MoneyXaf;
use App\Models\Tariff\TariffItem;
use App\Models\Tariff\TariffVersion;
use App\Support\CacheKeys;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class TariffResolver
{
    public function resolveEffective(
        ?CarbonImmutable $asOfDate = null,
        ?int $centreId = null,
        ?int $vehicleCategoryId = null,
        ?int $serviceId = null,
    ): EffectiveTariffResult {
        $asOfDate ??= Clock::nowDisplay();
        $dateKey = $asOfDate->toDateString();

        $version = Cache::remember(
            CacheKeys::tariffEffective($dateKey),
            CacheKeys::tariffTtlSeconds(),
            fn () => $this->findEffectiveVersion($asOfDate),
        );

        if ($version === null) {
            return EffectiveTariffResult::empty();
        }

        $items = $this->resolveItems($version, $centreId, $vehicleCategoryId, $serviceId);

        return new EffectiveTariffResult(
            version: new EffectiveTariffVersionDto(
                id: $version->id,
                label: $version->label,
                effectiveFrom: $version->effective_from->toDateString(),
                effectiveUntil: $version->effective_until?->toDateString(),
            ),
            items: $items,
            isEmpty: $items === [],
        );
    }

    public function findPrice(
        CarbonImmutable $asOfDate,
        int $centreId,
        int $vehicleCategoryId,
        ?int $serviceId = null,
    ): ?MoneyXaf {
        $result = $this->resolveEffective($asOfDate, $centreId, $vehicleCategoryId, $serviceId);

        if ($result->isEmpty || $result->items === []) {
            return null;
        }

        return $result->items[0]->amount;
    }

    private function findEffectiveVersion(CarbonImmutable $asOfDate): ?TariffVersion
    {
        $day = $asOfDate->toDateString();

        return TariffVersion::query()
            ->where('status', TariffVersionStatus::Published)
            ->where('effective_from', '<=', $day)
            ->where(function ($query) use ($day): void {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', $day);
            })
            ->orderByDesc('effective_from')
            ->first();
    }

    /**
     * @return list<TariffLineDto>
     */
    private function resolveItems(
        TariffVersion $version,
        ?int $centreId,
        ?int $vehicleCategoryId,
        ?int $serviceId,
    ): array {
        /** @var Collection<int, TariffItem> $items */
        $items = Cache::remember(
            CacheKeys::tariffMatrix($version->id),
            CacheKeys::tariffTtlSeconds(),
            fn () => TariffItem::query()
                ->where('tariff_version_id', $version->id)
                ->with('centres:id')
                ->orderBy('sort_order')
                ->get(),
        );

        return $items
            ->filter(function (TariffItem $item) use ($centreId, $vehicleCategoryId, $serviceId): bool {
                if ($vehicleCategoryId !== null && $item->vehicle_category_id !== $vehicleCategoryId) {
                    return false;
                }

                if ($centreId !== null && ! $item->appliesAtCentre($centreId)) {
                    return false;
                }

                if (! $item->appliesToService($serviceId)) {
                    return false;
                }

                return true;
            })
            ->map(fn (TariffItem $item) => new TariffLineDto(
                id: $item->id,
                vehicleCategoryId: $item->vehicle_category_id,
                serviceId: $item->service_id,
                amount: new MoneyXaf($item->amount_xaf),
                validityNotes: $item->validity_notes,
                centreIds: $item->centres->pluck('id')->all(),
            ))
            ->values()
            ->all();
    }
}
