<?php

namespace App\Actions\Tariff;

use App\Actions\Tariff\Data\PublishTariffVersionData;
use App\Domain\Enums\TariffVersionStatus;
use App\Models\Tariff\TariffVersion;
use App\Support\CacheKeys;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class PublishTariffVersion
{
    public function __invoke(PublishTariffVersionData $data): TariffVersion
    {
        if (! $data->actor->hasRole(['super_admin', 'operations_admin'])) {
            throw new AuthorizationException('Only operations administrators may publish tariff versions.');
        }

        $version = TariffVersion::query()->findOrFail($data->tariffVersionId);

        if (! $version->canPublish()) {
            throw new InvalidArgumentException('Tariff version must be reviewed and contain items before publishing.');
        }

        if ($version->effective_from->toDateString() !== $data->confirmEffectiveFrom->toDateString()) {
            throw new InvalidArgumentException('Effective from date must be explicitly confirmed.');
        }

        DB::transaction(function () use ($version, $data): void {
            $this->archiveOverlappingPublishedVersions($version);

            $version->update([
                'status' => TariffVersionStatus::Published,
                'published_at' => now(),
                'published_by' => $data->actor->id,
            ]);
        });

        $this->invalidateTariffCache($version);

        return $version->refresh();
    }

    private function archiveOverlappingPublishedVersions(TariffVersion $version): void
    {
        $from = $version->effective_from->toDateString();
        $until = $version->effective_until?->toDateString();

        TariffVersion::query()
            ->where('status', TariffVersionStatus::Published)
            ->where('id', '!=', $version->id)
            ->where('effective_from', '<=', $until ?? '9999-12-31')
            ->where(function ($query) use ($from): void {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', $from);
            })
            ->lockForUpdate()
            ->get()
            ->each(fn (TariffVersion $published) => $published->archive());
    }

    private function invalidateTariffCache(TariffVersion $version): void
    {
        Cache::forget(CacheKeys::tariffMatrix($version->id));

        $start = CarbonImmutable::parse($version->effective_from);
        $end = $version->effective_until
            ? CarbonImmutable::parse($version->effective_until)
            : $start->addYear();

        for ($date = $start; $date->lessThanOrEqualTo($end); $date = $date->addDay()) {
            Cache::forget(CacheKeys::tariffEffective($date->toDateString()));
        }
    }
}
