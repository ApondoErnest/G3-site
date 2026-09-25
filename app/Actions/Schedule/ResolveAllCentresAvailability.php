<?php

namespace App\Actions\Schedule;

use App\Domain\Schedule\AvailabilityEngine;
use App\Domain\Schedule\CentreAvailabilitySnapshot;
use App\Models\Centre\Centre;
use App\Support\CacheKeys;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

final class ResolveAllCentresAvailability
{
    public function __construct(
        private AvailabilityEngine $engine,
    ) {}

    /**
     * @return array<int, CentreAvailabilitySnapshot>
     */
    public function __invoke(?CarbonImmutable $at = null): array
    {
        $at ??= Clock::nowDisplay();

        $cached = Cache::remember(
            CacheKeys::availabilityAll($at->format('Y-m-d-H-i')),
            60,
            fn (): array => array_map(
                fn (CentreAvailabilitySnapshot $snapshot): array => $snapshot->toCacheArray(),
                $this->snapshots($at),
            ),
        );

        $snapshots = [];

        foreach ($cached as $centreId => $payload) {
            $snapshots[(int) $centreId] = CentreAvailabilitySnapshot::fromCacheArray($payload);
        }

        return $snapshots;
    }

    /**
     * @return array<int, CentreAvailabilitySnapshot>
     */
    private function snapshots(CarbonImmutable $at): array
    {
        $snapshots = [];

        $centres = Centre::query()
            ->active()
            ->with('weeklyHours')
            ->orderBy('sort_order')
            ->get();

        foreach ($centres as $centre) {
            $snapshots[$centre->id] = $this->engine->snapshot($centre, $at);
        }

        return $snapshots;
    }
}
