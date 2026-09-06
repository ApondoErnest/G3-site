<?php

use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('baseline centres seeder creates two centres from docs/01-baseline.md', function () {
    $this->seed(BaselineCentresSeeder::class);

    expect(DB::table('centres')->count())->toBe(2);

    $ecole = DB::table('centres')->where('code', 'ecole-de-police')->first();
    $nomayos = DB::table('centres')->where('code', 'nomayos')->first();

    expect($ecole)->not->toBeNull()
        ->and($nomayos)->not->toBeNull()
        ->and($ecole->status)->toBe('active')
        ->and($nomayos->status)->toBe('active')
        ->and((bool) $ecole->holiday_default_open)->toBeTrue()
        ->and((bool) $nomayos->holiday_default_open)->toBeTrue()
        ->and(json_decode($ecole->name, true))->toBe(['fr' => 'École de Police', 'en' => 'École de Police'])
        ->and(json_decode($nomayos->landmark, true))->toBe(['fr' => 'Carrefour Nomayos', 'en' => 'Nomayos junction']);
});

test('baseline centres seeder creates three phones in e164 per docs/01-baseline.md', function () {
    $this->seed(BaselineCentresSeeder::class);

    expect(DB::table('centre_phones')->count())->toBe(3);

    $ecoleId = DB::table('centres')->where('code', 'ecole-de-police')->value('id');
    $nomayosId = DB::table('centres')->where('code', 'nomayos')->value('id');

    expect(DB::table('centre_phones')->where('centre_id', $ecoleId)->pluck('e164')->all())
        ->toBe(['+237687187516']);

    expect(DB::table('centre_phones')->where('centre_id', $nomayosId)->orderBy('sort_order')->pluck('e164')->all())
        ->toBe(['+237653100801', '+237692242143']);
});

test('baseline centres seeder creates locked weekly hours for both centres', function () {
    $this->seed(BaselineCentresSeeder::class);

    expect(DB::table('centre_weekly_hours')->count())->toBe(14);

    $ecoleId = DB::table('centres')->where('code', 'ecole-de-police')->value('id');
    $nomayosId = DB::table('centres')->where('code', 'nomayos')->value('id');

    $ecoleSaturday = DB::table('centre_weekly_hours')
        ->where('centre_id', $ecoleId)
        ->where('weekday', 6)
        ->first();

    $nomayosSunday = DB::table('centre_weekly_hours')
        ->where('centre_id', $nomayosId)
        ->where('weekday', 7)
        ->first();

    expect($ecoleSaturday->opens_at)->toBe('07:00:00')
        ->and($ecoleSaturday->closes_at)->toBe('20:00:00')
        ->and($nomayosSunday->opens_at)->toBe('07:00:00')
        ->and($nomayosSunday->closes_at)->toBe('15:00:00');
});

test('baseline centres seeder does not invent catalogue or tariff data', function () {
    $this->seed(BaselineCentresSeeder::class);

    expect(DB::table('vehicle_categories')->count())->toBe(0)
        ->and(DB::table('services')->count())->toBe(0)
        ->and(DB::table('tariff_versions')->count())->toBe(0)
        ->and(DB::table('tariff_items')->count())->toBe(0);
});

test('baseline centres seeder is idempotent', function () {
    $this->seed(BaselineCentresSeeder::class);
    $this->seed(BaselineCentresSeeder::class);

    expect(DB::table('centres')->count())->toBe(2)
        ->and(DB::table('centre_phones')->count())->toBe(3)
        ->and(DB::table('centre_weekly_hours')->count())->toBe(14);
});
