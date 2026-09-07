<?php

namespace App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Pages;

use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\RequiredDocumentResource;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\CreateRecord;
use InvalidArgumentException;

class CreateRequiredDocument extends CreateRecord
{
    protected static string $resource = RequiredDocumentResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        self::assertTarget($data);

        $data['label'] = AdminForm::normalizeBilingual($data['label'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function assertTarget(array $data): void
    {
        if (blank($data['service_id'] ?? null) && blank($data['vehicle_category_id'] ?? null)) {
            throw new InvalidArgumentException('A required document must be linked to a service and/or a category.');
        }
    }
}
