<?php

namespace App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Pages;

use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\RoadSafetySectionResource;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditRoadSafetySection extends EditRecord
{
    protected static string $resource = RoadSafetySectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn (): bool => Cache::forget(CacheKeys::roadSafetyPublished()) ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['title'] = AdminForm::bilingualState($data['title'] ?? null);
        $data['body'] = AdminForm::bilingualState($data['body'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['title'] = AdminForm::normalizeBilingual($data['title'] ?? null);
        $data['body'] = AdminForm::normalizeBilingual($data['body'] ?? null);

        return $data;
    }

    protected function afterSave(): void
    {
        Cache::forget(CacheKeys::roadSafetyPublished());
    }
}
