<?php

namespace App\Filament\Resources\Centre\Centres\Pages;

use App\Domain\Enums\Weekday;
use App\Filament\Resources\Centre\Centres\CentreResource;
use App\Models\Centre\Centre;
use App\Models\Centre\CentreWeeklyHours;
use App\Support\PublicPageCache;
use Filament\Resources\Pages\CreateRecord;

class CreateCentre extends CreateRecord
{
    protected static string $resource = CentreResource::class;

    protected function afterCreate(): void
    {
        $centre = $this->getRecord();

        if ($centre instanceof Centre) {
            foreach (Weekday::cases() as $weekday) {
                CentreWeeklyHours::query()->create([
                    'centre_id' => $centre->id,
                    'weekday' => $weekday,
                    'is_open' => false,
                    'opens_at' => null,
                    'closes_at' => null,
                ]);
            }
        }

        PublicPageCache::forgetCentres();
    }
}
