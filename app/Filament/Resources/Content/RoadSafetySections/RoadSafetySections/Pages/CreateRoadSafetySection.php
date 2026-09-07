<?php

namespace App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Pages;

use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\RoadSafetySectionResource;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateRoadSafetySection extends CreateRecord
{
    protected static string $resource = RoadSafetySectionResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['title'] = AdminForm::normalizeBilingual($data['title'] ?? null);
        $data['body'] = AdminForm::normalizeBilingual($data['body'] ?? null);

        return $data;
    }

    protected function afterCreate(): void
    {
        Cache::forget(CacheKeys::roadSafetyPublished());
    }
}
