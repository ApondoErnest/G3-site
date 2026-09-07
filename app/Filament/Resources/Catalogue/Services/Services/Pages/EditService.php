<?php

namespace App\Filament\Resources\Catalogue\Services\Services\Pages;

use App\Filament\Resources\Catalogue\Services\Services\ServiceResource;
use App\Support\CatalogueCache;
use App\Support\Filament\AdminForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

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
        $data['title'] = AdminForm::bilingualState($data['title'] ?? null);
        $data['summary'] = AdminForm::bilingualState($data['summary'] ?? null);
        $data['body'] = AdminForm::bilingualState($data['body'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['title'] = AdminForm::normalizeBilingual($data['title'] ?? null);
        $data['summary'] = AdminForm::normalizeBilingual($data['summary'] ?? null);
        $data['body'] = AdminForm::normalizeBilingual($data['body'] ?? null);

        return $data;
    }

    protected function afterSave(): void
    {
        CatalogueCache::forgetPublished();
    }
}
