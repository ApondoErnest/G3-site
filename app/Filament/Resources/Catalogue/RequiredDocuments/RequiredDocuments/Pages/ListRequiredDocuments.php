<?php

namespace App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Pages;

use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\RequiredDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRequiredDocuments extends ListRecords
{
    protected static string $resource = RequiredDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
