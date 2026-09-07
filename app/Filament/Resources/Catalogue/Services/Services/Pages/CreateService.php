<?php

namespace App\Filament\Resources\Catalogue\Services\Services\Pages;

use App\Filament\Resources\Catalogue\Services\Services\ServiceResource;
use App\Support\CatalogueCache;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

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
        $data['title'] = AdminForm::normalizeBilingual($data['title'] ?? null);
        $data['summary'] = AdminForm::normalizeBilingual($data['summary'] ?? null);
        $data['body'] = AdminForm::normalizeBilingual($data['body'] ?? null);

        return $data;
    }
}
