<?php

namespace App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Pages;

use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\RoadSafetySectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoadSafetySections extends ListRecords
{
    protected static string $resource = RoadSafetySectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
