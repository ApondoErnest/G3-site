<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('catalogue tables exist with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('vehicle_categories'))->toBeTrue();
    expect(Schema::hasTable('services'))->toBeTrue();
    expect(Schema::hasTable('centre_service'))->toBeTrue();
    expect(Schema::hasTable('service_vehicle_category'))->toBeTrue();
    expect(Schema::hasTable('required_documents'))->toBeTrue();

    expect(Schema::hasColumns('vehicle_categories', [
        'id',
        'code',
        'label',
        'examples',
        'description',
        'sort_order',
        'is_published',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('services', [
        'id',
        'code',
        'title',
        'summary',
        'body',
        'icon',
        'sort_order',
        'is_published',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('centre_service', [
        'centre_id',
        'service_id',
    ]))->toBeTrue();

    expect(Schema::hasColumns('service_vehicle_category', [
        'service_id',
        'vehicle_category_id',
    ]))->toBeTrue();

    expect(Schema::hasColumns('required_documents', [
        'id',
        'label',
        'vehicle_category_id',
        'service_id',
        'sort_order',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('vehicle categories table enforces unique code per uq_vehicle_categories_code', function () {
    insertVehicleCategory(['code' => 'cat-a']);

    expect(fn () => insertVehicleCategory(['code' => 'cat-a']))
        ->toThrow(QueryException::class);
});

test('services table enforces unique code per uq_services_code', function () {
    insertService(['code' => 'visite-technique']);

    expect(fn () => insertService(['code' => 'visite-technique']))
        ->toThrow(QueryException::class);
});

test('centre service pivot enforces composite primary key', function () {
    $centreId = insertCentre();
    $serviceId = insertService();

    DB::table('centre_service')->insert([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
    ]);

    expect(fn () => DB::table('centre_service')->insert([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
    ]))->toThrow(QueryException::class);
});

test('service vehicle category pivot enforces composite primary key', function () {
    $serviceId = insertService();
    $categoryId = insertVehicleCategory();

    DB::table('service_vehicle_category')->insert([
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
    ]);

    expect(fn () => DB::table('service_vehicle_category')->insert([
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
    ]))->toThrow(QueryException::class);
});

test('required documents target check requires category or service per chk_rd_target', function () {
    $categoryId = insertVehicleCategory();
    $serviceId = insertService();

    insertRequiredDocument(['vehicle_category_id' => $categoryId, 'service_id' => null]);
    insertRequiredDocument(['vehicle_category_id' => null, 'service_id' => $serviceId]);
    insertRequiredDocument(['vehicle_category_id' => $categoryId, 'service_id' => $serviceId]);

    expect(fn () => insertRequiredDocument([
        'vehicle_category_id' => null,
        'service_id' => null,
    ]))->toThrow(QueryException::class);
});

test('centre service pivot cascades when centre is removed per fk_centre_service_centres', function () {
    $centreId = insertCentre();
    $serviceId = insertService();

    DB::table('centre_service')->insert([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
    ]);

    DB::table('centres')->where('id', $centreId)->delete();

    expect(DB::table('centre_service')->where('centre_id', $centreId)->exists())->toBeFalse();
});

test('centre service pivot cascades when service is removed per fk_centre_service_services', function () {
    $centreId = insertCentre();
    $serviceId = insertService();

    DB::table('centre_service')->insert([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
    ]);

    DB::table('services')->where('id', $serviceId)->delete();

    expect(DB::table('centre_service')->where('service_id', $serviceId)->exists())->toBeFalse();
});
