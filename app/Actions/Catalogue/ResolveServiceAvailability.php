<?php

namespace App\Actions\Catalogue;

use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;

final class ResolveServiceAvailability
{
    public function __invoke(int $serviceId, int $centreId, int $vehicleCategoryId): bool
    {
        $service = Service::query()
            ->with(['centres:id', 'vehicleCategories:id'])
            ->findOrFail($serviceId);

        $centre = Centre::query()->findOrFail($centreId);
        $category = VehicleCategory::query()->findOrFail($vehicleCategoryId);

        return $service->isAvailableAt($centre, $category);
    }
}
