<?php

namespace App\Actions\Tariff;

use App\Actions\Tariff\Data\UpdateTariffVersionItemsData;
use App\Domain\Enums\TariffVersionStatus;
use App\Models\Tariff\TariffItem;
use App\Models\Tariff\TariffVersion;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class UpdateTariffVersionItems
{
    public function __invoke(UpdateTariffVersionItemsData $data): void
    {
        $version = TariffVersion::query()->findOrFail($data->tariffVersionId);

        if (! in_array($version->status, [TariffVersionStatus::Draft, TariffVersionStatus::Reviewed], true)) {
            throw new AuthorizationException('Published or archived tariff versions cannot be edited.');
        }

        foreach ($data->items as $item) {
            if ($item->centreIds === []) {
                throw new InvalidArgumentException('Each tariff item must be linked to at least one centre.');
            }
        }

        DB::transaction(function () use ($version, $data): void {
            $existingItemIds = $version->items()->pluck('id');

            if ($existingItemIds->isNotEmpty()) {
                DB::table('tariff_item_centre')->whereIn('tariff_item_id', $existingItemIds)->delete();
                $version->items()->delete();
            }

            foreach ($data->items as $itemData) {
                $item = TariffItem::query()->create([
                    'tariff_version_id' => $version->id,
                    'vehicle_category_id' => $itemData->vehicleCategoryId,
                    'service_id' => $itemData->serviceId,
                    'amount_xaf' => $itemData->amountXaf,
                    'validity_notes' => $itemData->validityNotes,
                    'sort_order' => $itemData->sortOrder,
                ]);

                $item->centres()->sync($itemData->centreIds);
            }
        });
    }
}
