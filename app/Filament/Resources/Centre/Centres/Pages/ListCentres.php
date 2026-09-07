<?php

namespace App\Filament\Resources\Centre\Centres\Pages;

use App\Filament\Resources\Centre\Centres\CentreResource;
use Filament\Resources\Pages\ListRecords;

class ListCentres extends ListRecords
{
    protected static string $resource = CentreResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
