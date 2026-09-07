<?php

namespace App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Pages;

use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\RequiredDocumentResource;
use App\Support\Filament\AdminForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use InvalidArgumentException;

class EditRequiredDocument extends EditRecord
{
    protected static string $resource = RequiredDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['label'] = AdminForm::bilingualState($data['label'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (blank($data['service_id'] ?? null) && blank($data['vehicle_category_id'] ?? null)) {
            throw new InvalidArgumentException('A required document must be linked to a service and/or a category.');
        }

        $data['label'] = AdminForm::normalizeBilingual($data['label'] ?? null);

        return $data;
    }
}
