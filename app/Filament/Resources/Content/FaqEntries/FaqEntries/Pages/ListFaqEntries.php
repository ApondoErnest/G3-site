<?php

namespace App\Filament\Resources\Content\FaqEntries\FaqEntries\Pages;

use App\Filament\Resources\Content\FaqEntries\FaqEntries\FaqEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFaqEntries extends ListRecords
{
    protected static string $resource = FaqEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
