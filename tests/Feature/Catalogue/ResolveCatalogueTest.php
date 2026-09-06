<?php

use App\Actions\Catalogue\ResolvePublishedServices;
use App\Actions\Catalogue\ResolvePublishedVehicleCategories;
use App\Actions\Catalogue\ResolveRequiredDocuments;
use App\Actions\Catalogue\ResolveServiceAvailability;
use App\Support\CacheKeys;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
    $this->seed(BaselineCentresSeeder::class);
});

test('resolve published services excludes unpublished entries FR-SV-04', function () {
    $publishedId = insertService([
        'code' => 'visite-technique',
        'is_published' => true,
        'sort_order' => 1,
    ]);
    insertService([
        'code' => 'draft-service',
        'is_published' => false,
        'sort_order' => 2,
    ]);

    linkServiceToCentre($publishedId, centreId('ecole-de-police'));

    $services = app(ResolvePublishedServices::class)();

    expect($services)->toHaveCount(1)
        ->and($services[0]->code)->toBe('visite-technique')
        ->and($services[0]->centreIds)->toBe([centreId('ecole-de-police')]);
});

test('resolve published services includes linked centres FR-SV-05', function () {
    $serviceId = insertService(['code' => 'visite-technique', 'is_published' => true]);

    linkServiceToCentre($serviceId, centreId('ecole-de-police'));
    linkServiceToCentre($serviceId, centreId('nomayos'));

    $services = app(ResolvePublishedServices::class)();

    expect($services[0]->centreIds)->toEqual([
        centreId('ecole-de-police'),
        centreId('nomayos'),
    ]);
});

test('resolve published vehicle categories returns only published FR-VC-02', function () {
    insertVehicleCategory(['code' => 'vp', 'is_published' => true, 'sort_order' => 1]);
    insertVehicleCategory(['code' => 'draft', 'is_published' => false, 'sort_order' => 2]);

    $categories = app(ResolvePublishedVehicleCategories::class)();

    expect($categories)->toHaveCount(1)
        ->and($categories[0]->code)->toBe('vp');
});

test('resolve required documents returns category service and combined targets FR-SV-03', function () {
    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $serviceId = insertService(['code' => 'visite-technique']);

    $categoryDocId = insertRequiredDocument([
        'vehicle_category_id' => $categoryId,
        'service_id' => null,
        'label' => json_encode(['fr' => 'Catégorie seule', 'en' => 'Category only']),
        'sort_order' => 1,
    ]);
    $serviceDocId = insertRequiredDocument([
        'vehicle_category_id' => null,
        'service_id' => $serviceId,
        'label' => json_encode(['fr' => 'Service seul', 'en' => 'Service only']),
        'sort_order' => 2,
    ]);
    $combinedDocId = insertRequiredDocument([
        'vehicle_category_id' => $categoryId,
        'service_id' => $serviceId,
        'label' => json_encode(['fr' => 'Les deux', 'en' => 'Both']),
        'sort_order' => 3,
    ]);
    insertRequiredDocument([
        'vehicle_category_id' => insertVehicleCategory(),
        'service_id' => null,
    ]);

    $documents = app(ResolveRequiredDocuments::class)($serviceId, $categoryId);

    expect(collect($documents)->pluck('id')->all())->toBe([
        $categoryDocId,
        $serviceDocId,
        $combinedDocId,
    ]);
});

test('resolve service availability delegates to domain method BR-SVC-002', function () {
    $centreId = centreId('ecole-de-police');
    $categoryId = insertVehicleCategory(['is_published' => true]);
    $serviceId = insertService(['is_published' => true]);

    linkServiceToCentre($serviceId, $centreId);
    linkServiceToCategory($serviceId, $categoryId);

    expect(app(ResolveServiceAvailability::class)($serviceId, $centreId, $categoryId))
        ->toBeTrue()
        ->and(app(ResolveServiceAvailability::class)($serviceId, centreId('nomayos'), $categoryId))
        ->toBeFalse();
});

test('published catalogue queries are cached', function () {
    insertService(['code' => 'visite-technique', 'is_published' => true]);

    app(ResolvePublishedServices::class)();

    expect(Cache::has(CacheKeys::catalogueServices()))->toBeTrue();

    DB::table('services')->delete();

    expect(app(ResolvePublishedServices::class)())->toHaveCount(1);
});
