<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('operational alerts table exists with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('operational_alerts'))->toBeTrue();

    expect(Schema::hasColumns('operational_alerts', [
        'id',
        'severity',
        'message',
        'centre_id',
        'starts_at',
        'expires_at',
        'is_active',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('operational alerts severity check rejects invalid values per chk_oa_severity', function () {
    expect(fn () => insertOperationalAlert(['severity' => 'urgent']))
        ->toThrow(QueryException::class);

    insertOperationalAlert(['severity' => 'info']);
    insertOperationalAlert(['severity' => 'warning']);
    insertOperationalAlert(['severity' => 'critical']);
});

test('operational alerts date check requires expires_at on or after starts_at per chk_oa_dates', function () {
    insertOperationalAlert([
        'starts_at' => '2026-09-01 08:00:00',
        'expires_at' => null,
    ]);

    insertOperationalAlert([
        'starts_at' => '2026-09-01 08:00:00',
        'expires_at' => '2026-09-05 18:00:00',
    ]);

    expect(fn () => insertOperationalAlert([
        'starts_at' => '2026-09-10 08:00:00',
        'expires_at' => '2026-09-05 18:00:00',
    ]))->toThrow(QueryException::class);
});

test('operational alerts null centre_id when centre is removed per fk_operational_alerts_centres', function () {
    $centreId = insertCentre();
    $alertId = insertOperationalAlert(['centre_id' => $centreId]);

    DB::table('centres')->where('id', $centreId)->delete();

    expect(DB::table('operational_alerts')->where('id', $alertId)->value('centre_id'))->toBeNull();
});

/**
 * @param  array<string, mixed>  $overrides
 */
function insertOperationalAlert(array $overrides = []): int
{
    return DB::table('operational_alerts')->insertGetId(array_merge([
        'severity' => 'info',
        'message' => json_encode(['fr' => 'Alerte test', 'en' => 'Test alert']),
        'centre_id' => null,
        'starts_at' => '2026-09-05 08:00:00',
        'expires_at' => null,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}
