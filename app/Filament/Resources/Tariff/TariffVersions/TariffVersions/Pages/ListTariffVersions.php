<?php

namespace App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages;

use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\TariffVersionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTariffVersions extends ListRecords
{
    protected static string $resource = TariffVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
