<?php

namespace App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Pages;

use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\VehicleCategoryResource;
use App\Support\CatalogueCache;
use App\Support\Filament\AdminForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleCategory extends EditRecord
{
    protected static string $resource = VehicleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn (): bool => CatalogueCache::forgetPublished() ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['label'] = AdminForm::bilingualState($data['label'] ?? null);
        $data['examples'] = AdminForm::bilingualState($data['examples'] ?? null);
        $data['description'] = AdminForm::bilingualState($data['description'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['label'] = AdminForm::normalizeBilingual($data['label'] ?? null);
        $data['examples'] = AdminForm::normalizeBilingual($data['examples'] ?? null);
        $data['description'] = AdminForm::normalizeBilingual($data['description'] ?? null);

        return $data;
    }

    protected function afterSave(): void
    {
        CatalogueCache::forgetPublished();
    }
}
