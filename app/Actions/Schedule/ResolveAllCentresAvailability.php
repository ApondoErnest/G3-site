<?php

namespace App\Actions\Schedule;

use App\Domain\Schedule\AvailabilityEngine;
use App\Domain\Schedule\CentreAvailabilitySnapshot;
use App\Models\Centre\Centre;
use App\Support\Clock;
use Carbon\CarbonImmutable;

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
