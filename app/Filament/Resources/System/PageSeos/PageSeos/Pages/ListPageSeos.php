<?php

namespace App\Filament\Resources\System\PageSeos\PageSeos\Pages;

use App\Filament\Resources\System\PageSeos\PageSeos\PageSeoResource;
use Filament\Resources\Pages\ListRecords;

class ListPageSeos extends ListRecords
{
    protected static string $resource = PageSeoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
