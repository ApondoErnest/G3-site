<?php

namespace App\Actions\Tariff;

use App\Actions\Tariff\Data\MarkTariffVersionReviewedData;
use App\Domain\Enums\TariffVersionStatus;
use App\Models\Tariff\TariffVersion;
use InvalidArgumentException;

final class MarkTariffVersionReviewed
{
    public function __invoke(MarkTariffVersionReviewedData $data): TariffVersion
    {
        $version = TariffVersion::query()->findOrFail($data->tariffVersionId);

        if ($version->status !== TariffVersionStatus::Draft) {
            throw new InvalidArgumentException('Only draft tariff versions can be marked reviewed.');
        }

        if (! $version->items()->exists()) {
            throw new InvalidArgumentException('Tariff version must contain at least one item.');
        }

        $version->update([
            'status' => TariffVersionStatus::Reviewed,
            'reviewed_at' => now(),
            'reviewed_by' => $data->actor->id,
        ]);

        return $version->refresh();
    }
}
