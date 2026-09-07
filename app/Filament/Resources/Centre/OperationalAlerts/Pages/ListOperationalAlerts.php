<?php

namespace App\Filament\Resources\Centre\OperationalAlerts\Pages;

use App\Filament\Resources\Centre\OperationalAlerts\OperationalAlertResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperationalAlerts extends ListRecords
{
    protected static string $resource = OperationalAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
