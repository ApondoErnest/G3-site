<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('schedule exceptions table exists with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('schedule_exceptions'))->toBeTrue();

    expect(Schema::hasColumns('schedule_exceptions', [
        'id',
        'applies_to_all_centres',
        'centre_id',
        'starts_on',
        'ends_on',
        'is_open',
        'opens_at',
        'closes_at',
        'reason',
        'created_by',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('schedule exceptions scope check requires centre_id alignment per chk_se_scope', function () {
    $centreId = insertCentre();

    insertScheduleException([
        'applies_to_all_centres' => true,
        'centre_id' => null,
    ]);

    insertScheduleException([
        'applies_to_all_centres' => false,
        'centre_id' => $centreId,
    ]);

    expect(fn () => insertScheduleException([
        'applies_to_all_centres' => true,
        'centre_id' => $centreId,
    ]))->toThrow(QueryException::class);

    expect(fn () => insertScheduleException([
        'applies_to_all_centres' => false,
        'centre_id' => null,
    ]))->toThrow(QueryException::class);
});

test('schedule exceptions date check requires ends_on on or after starts_on per chk_se_dates', function () {
    insertScheduleException([
        'starts_on' => '2026-01-01',
        'ends_on' => null,
    ]);

    insertScheduleException([
        'starts_on' => '2026-01-01',
        'ends_on' => '2026-01-05',
    ]);

    expect(fn () => insertScheduleException([
        'starts_on' => '2026-01-10',
        'ends_on' => '2026-01-05',
    ]))->toThrow(QueryException::class);
});

test('schedule exceptions open times check requires hours when open per chk_se_open_times', function () {
    insertScheduleException([
        'is_open' => false,
        'opens_at' => null,
        'closes_at' => null,
    ]);

    insertScheduleException([
        'is_open' => true,
        'opens_at' => '08:00:00',
        'closes_at' => '12:00:00',
    ]);

    expect(fn () => insertScheduleException([
        'is_open' => true,
        'opens_at' => null,
        'closes_at' => '12:00:00',
    ]))->toThrow(QueryException::class);
});

test('schedule exceptions restrict centre deletion when exceptions exist per fk_schedule_exceptions_centres', function () {
    $centreId = insertCentre();

    insertScheduleException([
        'applies_to_all_centres' => false,
        'centre_id' => $centreId,
    ]);

    expect(fn () => DB::table('centres')->where('id', $centreId)->delete())
        ->toThrow(QueryException::class);
});

test('schedule exceptions null created_by when user is removed per fk_schedule_exceptions_users', function () {
    $userId = DB::table('users')->insertGetId([
        'name' => 'Scheduler',
        'email' => 'scheduler@example.com',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $exceptionId = insertScheduleException([
        'created_by' => $userId,
    ]);

    DB::table('users')->where('id', $userId)->delete();

    expect(DB::table('schedule_exceptions')->where('id', $exceptionId)->value('created_by'))->toBeNull();
});

/**
 * @param  array<string, mixed>  $overrides
 */
function insertScheduleException(array $overrides = []): int
{
    return DB::table('schedule_exceptions')->insertGetId(array_merge([
        'applies_to_all_centres' => true,
        'centre_id' => null,
        'starts_on' => '2026-09-05',
        'ends_on' => null,
        'is_open' => false,
        'opens_at' => null,
        'closes_at' => null,
        'reason' => json_encode(['fr' => 'Fermeture', 'en' => 'Closed']),
        'created_by' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}
