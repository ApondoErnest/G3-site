<?php

namespace App\Actions\Tariff;

use App\Actions\Tariff\Data\CreateTariffVersionDraftData;
use App\Domain\Enums\TariffVersionStatus;
use App\Models\Tariff\TariffVersion;

final class CreateTariffVersionDraft
{
    public function __invoke(CreateTariffVersionDraftData $data): TariffVersion
    {
        return TariffVersion::query()->create([
            'label' => $data->label,
            'status' => TariffVersionStatus::Draft,
            'effective_from' => $data->effectiveFrom->toDateString(),
            'effective_until' => $data->effectiveUntil?->toDateString(),
        ]);
    }
}
