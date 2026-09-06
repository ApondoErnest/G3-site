<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('content tables exist with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('content_blocks'))->toBeTrue();
    expect(Schema::hasTable('faq_entries'))->toBeTrue();
    expect(Schema::hasTable('team_members'))->toBeTrue();
    expect(Schema::hasTable('road_safety_sections'))->toBeTrue();
    expect(Schema::hasTable('equipment'))->toBeTrue();
    expect(Schema::hasTable('centre_equipment'))->toBeTrue();
    expect(Schema::hasTable('page_seo'))->toBeTrue();

    expect(Schema::hasColumns('content_blocks', [
        'id',
        'key',
        'page',
        'schema_version',
        'content',
        'locale_status',
        'is_published',
        'published_at',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('faq_entries', [
        'id',
        'category_code',
        'question',
        'answer',
        'sort_order',
        'is_published',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('team_members', [
        'id',
        'name',
        'role_title',
        'bio',
        'display_publicly',
        'sort_order',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('road_safety_sections', [
        'id',
        'anchor',
        'title',
        'body',
        'sort_order',
        'is_published',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('equipment', [
        'id',
        'code',
        'label',
        'sort_order',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('centre_equipment', [
        'centre_id',
        'equipment_id',
    ]))->toBeTrue();

    expect(Schema::hasColumns('page_seo', [
        'page',
        'seo_title',
        'seo_description',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumn('page_seo', 'created_at'))->toBeFalse();
});

test('content blocks table enforces unique key per uq_content_blocks_key', function () {
    insertContentBlock(['key' => 'home.hero']);

    expect(fn () => insertContentBlock(['key' => 'home.hero']))
        ->toThrow(QueryException::class);
});

test('road safety sections table enforces unique anchor per uq_road_safety_sections_anchor', function () {
    insertRoadSafetySection(['anchor' => 'braking']);

    expect(fn () => insertRoadSafetySection(['anchor' => 'braking']))
        ->toThrow(QueryException::class);
});

test('equipment table enforces unique code per uq_equipment_code', function () {
    insertEquipment(['code' => 'brake-tester']);

    expect(fn () => insertEquipment(['code' => 'brake-tester']))
        ->toThrow(QueryException::class);
});

test('centre equipment pivot enforces composite primary key', function () {
    $centreId = insertCentre();
    $equipmentId = insertEquipment();

    DB::table('centre_equipment')->insert([
        'centre_id' => $centreId,
        'equipment_id' => $equipmentId,
    ]);

    expect(fn () => DB::table('centre_equipment')->insert([
        'centre_id' => $centreId,
        'equipment_id' => $equipmentId,
    ]))->toThrow(QueryException::class);
});

test('centre equipment pivot cascades when centre is removed per fk_centre_equipment_centres', function () {
    $centreId = insertCentre();
    $equipmentId = insertEquipment();

    DB::table('centre_equipment')->insert([
        'centre_id' => $centreId,
        'equipment_id' => $equipmentId,
    ]);

    DB::table('centres')->where('id', $centreId)->delete();

    expect(DB::table('centre_equipment')->where('centre_id', $centreId)->exists())->toBeFalse();
});

test('centre equipment pivot cascades when equipment is removed per fk_centre_equipment_equipment', function () {
    $centreId = insertCentre();
    $equipmentId = insertEquipment();

    DB::table('centre_equipment')->insert([
        'centre_id' => $centreId,
        'equipment_id' => $equipmentId,
    ]);

    DB::table('equipment')->where('id', $equipmentId)->delete();

    expect(DB::table('centre_equipment')->where('equipment_id', $equipmentId)->exists())->toBeFalse();
});

test('page seo uses page as primary key', function () {
    DB::table('page_seo')->insert([
        'page' => 'home',
        'seo_title' => json_encode(['fr' => 'Accueil', 'en' => 'Home']),
        'seo_description' => json_encode(['fr' => 'Description', 'en' => 'Description']),
        'updated_at' => now(),
    ]);

    expect(fn () => DB::table('page_seo')->insert([
        'page' => 'home',
        'seo_title' => json_encode(['fr' => 'Doublon', 'en' => 'Duplicate']),
        'seo_description' => json_encode(['fr' => 'Description', 'en' => 'Description']),
        'updated_at' => now(),
    ]))->toThrow(QueryException::class);
});
