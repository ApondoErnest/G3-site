<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('centres tables exist with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('centres'))->toBeTrue();
    expect(Schema::hasTable('centre_phones'))->toBeTrue();
    expect(Schema::hasTable('centre_weekly_hours'))->toBeTrue();

    expect(Schema::hasColumns('centres', [
        'id',
        'code',
        'name',
        'address',
        'landmark',
        'latitude',
        'longitude',
        'email',
        'secondary_email',
        'postal_code',
        'status',
        'sort_order',
        'holiday_default_open',
        'seo_title',
        'seo_description',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('centre_phones', [
        'id',
        'centre_id',
        'label',
        'e164',
        'is_whatsapp',
        'sort_order',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('centre_weekly_hours', [
        'id',
        'centre_id',
        'weekday',
        'is_open',
        'opens_at',
        'closes_at',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('centres table enforces unique code per uq_centres_code', function () {
    insertCentre(['code' => 'ecole-de-police']);

    expect(fn () => insertCentre(['code' => 'ecole-de-police']))
        ->toThrow(QueryException::class);
});

test('centre weekly hours enforces one row per centre and weekday', function () {
    $centreId = insertCentre();

    insertWeeklyHours($centreId, weekday: 1);

    expect(fn () => insertWeeklyHours($centreId, weekday: 1))
        ->toThrow(QueryException::class);
});

test('centre weekly hours check constraint requires valid open times per chk_cwh_open_times', function () {
    $centreId = insertCentre();

    insertWeeklyHours($centreId, weekday: 1, isOpen: true, opensAt: '07:00:00', closesAt: '20:00:00');

    expect(fn () => insertWeeklyHours($centreId, weekday: 2, isOpen: true, opensAt: null, closesAt: '20:00:00'))
        ->toThrow(QueryException::class);
});

test('centre phones cascade delete when centre is removed per fk_centre_phones_centres', function () {
    $centreId = insertCentre();

    DB::table('centre_phones')->insert([
        'centre_id' => $centreId,
        'label' => 'primary',
        'e164' => '+237687187516',
        'is_whatsapp' => false,
        'sort_order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('centres')->where('id', $centreId)->delete();

    expect(DB::table('centre_phones')->where('centre_id', $centreId)->exists())->toBeFalse();
});

function insertWeeklyHours(
    int $centreId,
    int $weekday,
    bool $isOpen = true,
    ?string $opensAt = '07:00:00',
    ?string $closesAt = '20:00:00',
): void {
    DB::table('centre_weekly_hours')->insert([
        'centre_id' => $centreId,
        'weekday' => $weekday,
        'is_open' => $isOpen,
        'opens_at' => $opensAt,
        'closes_at' => $closesAt,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
