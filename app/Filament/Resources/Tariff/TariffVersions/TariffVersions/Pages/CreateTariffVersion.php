<?php

namespace App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages;

use App\Actions\Tariff\CreateTariffVersionDraft;
use App\Actions\Tariff\Data\CreateTariffVersionDraftData;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\TariffVersionResource;
use Carbon\CarbonImmutable;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTariffVersion extends CreateRecord
{
    protected static string $resource = TariffVersionResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateTariffVersionDraft::class)(new CreateTariffVersionDraftData(
            label: $data['label'],
            effectiveFrom: CarbonImmutable::parse($data['effective_from']),
            effectiveUntil: filled($data['effective_until'] ?? null)
                ? CarbonImmutable::parse($data['effective_until'])
                : null,
            actor: auth()->user(),
        ));
    }
}
