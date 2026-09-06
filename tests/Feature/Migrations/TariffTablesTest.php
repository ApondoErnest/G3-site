<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('tariff tables exist with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('tariff_versions'))->toBeTrue();
    expect(Schema::hasTable('tariff_items'))->toBeTrue();
    expect(Schema::hasTable('tariff_item_centre'))->toBeTrue();

    expect(Schema::hasColumns('tariff_versions', [
        'id',
        'label',
        'status',
        'effective_from',
        'effective_until',
        'reviewed_at',
        'reviewed_by',
        'published_at',
        'published_by',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('tariff_items', [
        'id',
        'tariff_version_id',
        'vehicle_category_id',
        'service_id',
        'amount_xaf',
        'validity_notes',
        'sort_order',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('tariff_item_centre', [
        'tariff_item_id',
        'centre_id',
    ]))->toBeTrue();
});

test('tariff versions table enforces unique label per uq_tariff_versions_label', function () {
    insertTariffVersion(['label' => '2026-01']);

    expect(fn () => insertTariffVersion(['label' => '2026-01']))
        ->toThrow(QueryException::class);
});

test('tariff versions date check requires effective_until on or after effective_from per chk_tv_dates', function () {
    insertTariffVersion([
        'effective_from' => '2026-01-01',
        'effective_until' => null,
    ]);

    insertTariffVersion([
        'label' => '2026-02',
        'effective_from' => '2026-01-01',
        'effective_until' => '2026-12-31',
    ]);

    expect(fn () => insertTariffVersion([
        'label' => '2026-03',
        'effective_from' => '2026-06-01',
        'effective_until' => '2026-01-01',
    ]))->toThrow(QueryException::class);
});

test('tariff items amount check requires positive xaf per chk_ti_amount', function () {
    insertTariffItem(['amount_xaf' => 25000]);

    expect(fn () => insertTariffItem(['amount_xaf' => 0]))
        ->toThrow(QueryException::class);
});

test('tariff item centre pivot enforces composite primary key', function () {
    $itemId = insertTariffItem();
    $centreId = insertCentre();

    DB::table('tariff_item_centre')->insert([
        'tariff_item_id' => $itemId,
        'centre_id' => $centreId,
    ]);

    expect(fn () => DB::table('tariff_item_centre')->insert([
        'tariff_item_id' => $itemId,
        'centre_id' => $centreId,
    ]))->toThrow(QueryException::class);
});

test('tariff items cascade when version is removed per fk_tariff_items_tariff_versions', function () {
    $versionId = insertTariffVersion();
    $itemId = insertTariffItem(['tariff_version_id' => $versionId]);

    DB::table('tariff_versions')->where('id', $versionId)->delete();

    expect(DB::table('tariff_items')->where('id', $itemId)->exists())->toBeFalse();
});

test('tariff item centre pivot restricts centre deletion per fk_tariff_item_centre_centres', function () {
    $itemId = insertTariffItem();
    $centreId = insertCentre();

    DB::table('tariff_item_centre')->insert([
        'tariff_item_id' => $itemId,
        'centre_id' => $centreId,
    ]);

    expect(fn () => DB::table('centres')->where('id', $centreId)->delete())
        ->toThrow(QueryException::class);
});

test('tariff item centre pivot cascades when item is removed per fk_tariff_item_centre_tariff_items', function () {
    $itemId = insertTariffItem();
    $centreId = insertCentre();

    DB::table('tariff_item_centre')->insert([
        'tariff_item_id' => $itemId,
        'centre_id' => $centreId,
    ]);

    DB::table('tariff_items')->where('id', $itemId)->delete();

    expect(DB::table('tariff_item_centre')->where('tariff_item_id', $itemId)->exists())->toBeFalse();
});
