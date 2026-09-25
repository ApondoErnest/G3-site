<?php

namespace App\Filament\Resources\Centre\Centres\Pages;

use App\Filament\Resources\Centre\Centres\CentreResource;
use App\Support\PublicPageCache;
use Filament\Resources\Pages\EditRecord;

class EditCentre extends EditRecord
{
    protected static string $resource = CentreResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        PublicPageCache::forgetCentres();
    }
}
