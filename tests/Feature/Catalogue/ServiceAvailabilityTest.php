<?php

use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BaselineCentresSeeder::class);
});

test('service is available only when published and linked to centre and category BR-SVC-002', function () {
    $centre = Centre::query()->where('code', 'ecole-de-police')->firstOrFail();
    $otherCentre = Centre::query()->where('code', 'nomayos')->firstOrFail();

    $categoryId = insertVehicleCategory(['code' => 'vp', 'is_published' => true]);
    $serviceId = insertService(['code' => 'visite-technique', 'is_published' => true]);

    linkServiceToCentre($serviceId, $centre->id);
    linkServiceToCategory($serviceId, $categoryId);

    $service = Service::query()->with(['centres', 'vehicleCategories'])->findOrFail($serviceId);
    $category = VehicleCategory::query()->findOrFail($categoryId);

    expect($service->isAvailableAt($centre, $category))->toBeTrue()
        ->and($service->isAvailableAt($otherCentre, $category))->toBeFalse();
});

test('unpublished service or category is never available BR-SVC-001', function () {
    $centre = Centre::query()->where('code', 'ecole-de-police')->firstOrFail();

    $categoryId = insertVehicleCategory(['is_published' => false]);
    $serviceId = insertService(['is_published' => true]);

    linkServiceToCentre($serviceId, $centre->id);
    linkServiceToCategory($serviceId, $categoryId);

    $service = Service::query()->with(['centres', 'vehicleCategories'])->findOrFail($serviceId);
    $category = VehicleCategory::query()->findOrFail($categoryId);

    expect($service->isAvailableAt($centre, $category))->toBeFalse();
});
