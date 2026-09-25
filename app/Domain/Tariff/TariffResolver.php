<?php

namespace App\Domain\Tariff;

use App\Domain\Enums\TariffVersionStatus;
use App\Domain\ValueObjects\MoneyXaf;
use App\Models\Tariff\TariffItem;
use App\Models\Tariff\TariffVersion;
use App\Support\CacheKeys;
use App\Support\Clock;
use Carbon\CarbonImmutable;
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

        /** @var array{id: int, label: string, effective_from: string, effective_until: string|null}|null $version */
        $version = Cache::remember(
            CacheKeys::tariffEffective($dateKey),
            CacheKeys::tariffTtlSeconds(),
            function () use ($asOfDate): ?array {
                $version = $this->findEffectiveVersion($asOfDate);

                if ($version === null) {
                    return null;
                }

                return [
                    'id' => $version->id,
                    'label' => $version->label,
                    'effective_from' => $version->effective_from->toDateString(),
                    'effective_until' => $version->effective_until?->toDateString(),
                ];
            },
        );

        if ($version === null) {
            return EffectiveTariffResult::empty();
        }

        $items = $this->resolveItems((int) $version['id'], $centreId, $vehicleCategoryId, $serviceId);

        return new EffectiveTariffResult(
            version: new EffectiveTariffVersionDto(
                id: $version['id'],
                label: $version['label'],
                effectiveFrom: $version['effective_from'],
                effectiveUntil: $version['effective_until'],
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
        int $versionId,
        ?int $centreId,
        ?int $vehicleCategoryId,
        ?int $serviceId,
    ): array {
        /** @var list<array{id: int, vehicle_category_id: int, service_id: int|null, amount_xaf: int, validity_notes: array{fr: string, en: string}|null, centre_ids: list<int>}> $items */
        $items = Cache::remember(
            CacheKeys::tariffMatrix($versionId),
            CacheKeys::tariffTtlSeconds(),
            fn () => TariffItem::query()
                ->where('tariff_version_id', $versionId)
                ->with('centres:id')
                ->orderBy('sort_order')
                ->get()
                ->map(fn (TariffItem $item): array => [
                    'id' => $item->id,
                    'vehicle_category_id' => $item->vehicle_category_id,
                    'service_id' => $item->service_id,
                    'amount_xaf' => $item->amount_xaf,
                    'validity_notes' => $item->validity_notes,
                    'centre_ids' => $item->centres->pluck('id')->all(),
                ])
                ->values()
                ->all(),
        );

        return collect($items)
            ->filter(function (array $item) use ($centreId, $vehicleCategoryId, $serviceId): bool {
                if ($vehicleCategoryId !== null && $item['vehicle_category_id'] !== $vehicleCategoryId) {
                    return false;
                }

                if ($centreId !== null && ! in_array($centreId, $item['centre_ids'], true)) {
                    return false;
                }

                if ($item['service_id'] !== null && ($serviceId === null || $item['service_id'] !== $serviceId)) {
                    return false;
                }

                return true;
            })
            ->map(fn (array $item): TariffLineDto => new TariffLineDto(
                id: $item['id'],
                vehicleCategoryId: $item['vehicle_category_id'],
                serviceId: $item['service_id'],
                amount: new MoneyXaf($item['amount_xaf']),
                validityNotes: $item['validity_notes'],
                centreIds: $item['centre_ids'],
            ))
            ->values()
            ->all();
    }
}
