<?php

namespace App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Pages;

use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\VehicleCategoryResource;
use App\Support\CatalogueCache;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicleCategory extends CreateRecord
{
    protected static string $resource = VehicleCategoryResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return self::normalize($data);
    }

    protected function afterCreate(): void
    {
        CatalogueCache::forgetPublished();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function normalize(array $data): array
    {
        $data['label'] = AdminForm::normalizeBilingual($data['label'] ?? null);
        $data['examples'] = AdminForm::normalizeBilingual($data['examples'] ?? null);
        $data['description'] = AdminForm::normalizeBilingual($data['description'] ?? null);

        return $data;
    }
}
