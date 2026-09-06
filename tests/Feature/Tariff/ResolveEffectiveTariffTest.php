<?php

use App\Actions\Tariff\Data\PublishTariffVersionData;
use App\Actions\Tariff\PublishTariffVersion;
use App\Actions\Tariff\ResolveEffectiveTariff;
use App\Models\User;
use App\Support\CacheKeys;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
    $this->seed(BaselineCentresSeeder::class);
});

test('draft and reviewed versions are invisible on public resolver FR-TA-04', function () {
    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $centreId = centreId('ecole-de-police');

    $draftId = insertTariffVersion(['label' => '2026-draft', 'status' => 'draft']);
    $draftItemId = insertTariffItem(['tariff_version_id' => $draftId, 'vehicle_category_id' => $categoryId]);
    linkTariffItemToCentre($draftItemId, $centreId);

    expect(app(ResolveEffectiveTariff::class)()->isEmpty)->toBeTrue();

    $reviewedId = insertTariffVersion(['label' => '2026-reviewed', 'status' => 'reviewed']);
    $reviewedItemId = insertTariffItem(['tariff_version_id' => $reviewedId, 'vehicle_category_id' => $categoryId]);
    linkTariffItemToCentre($reviewedItemId, $centreId);

    expect(app(ResolveEffectiveTariff::class)()->isEmpty)->toBeTrue();
});

test('published effective version returns tariff lines FR-TA-04', function () {
    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $centreId = centreId('ecole-de-police');

    $versionId = insertTariffVersion([
        'label' => '2026-01',
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    $itemId = insertTariffItem([
        'tariff_version_id' => $versionId,
        'vehicle_category_id' => $categoryId,
        'amount_xaf' => 25000,
    ]);
    linkTariffItemToCentre($itemId, $centreId);

    $result = app(ResolveEffectiveTariff::class)(
        CarbonImmutable::parse('2026-06-01', 'Africa/Douala'),
        $centreId,
        $categoryId,
    );

    expect($result->isEmpty)->toBeFalse()
        ->and($result->version?->label)->toBe('2026-01')
        ->and($result->items)->toHaveCount(1)
        ->and($result->items[0]->amount->amount)->toBe(25000);
});

test('empty tariff returns honest empty state FR-TA-09', function () {
    $result = app(ResolveEffectiveTariff::class)();

    expect($result->isEmpty)->toBeTrue()
        ->and($result->version)->toBeNull()
        ->and($result->items)->toBe([]);
});

test('item not linked to centre is excluded BR-TARIFF-004', function () {
    $categoryId = insertVehicleCategory(['code' => 'vp']);

    $versionId = insertTariffVersion([
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    $itemId = insertTariffItem([
        'tariff_version_id' => $versionId,
        'vehicle_category_id' => $categoryId,
    ]);
    linkTariffItemToCentre($itemId, centreId('nomayos'));

    $result = app(ResolveEffectiveTariff::class)(
        CarbonImmutable::parse('2026-06-01', 'Africa/Douala'),
        centreId('ecole-de-police'),
        $categoryId,
    );

    expect($result->isEmpty)->toBeTrue();
});

test('finder and resolver return the same price FR-TA-05', function () {
    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $centreId = centreId('ecole-de-police');
    $asOf = CarbonImmutable::parse('2026-06-01', 'Africa/Douala');

    $versionId = insertTariffVersion([
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    $itemId = insertTariffItem([
        'tariff_version_id' => $versionId,
        'vehicle_category_id' => $categoryId,
        'amount_xaf' => 30000,
    ]);
    linkTariffItemToCentre($itemId, $centreId);

    $resolver = app(ResolveEffectiveTariff::class);
    $matrix = $resolver($asOf, $centreId, $categoryId);
    $finder = $resolver->findPrice($centreId, $categoryId, null, $asOf);

    expect($finder?->amount)->toBe($matrix->items[0]->amount->amount);
});

test('effective tariff query is cached', function () {
    insertTariffVersion([
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);

    app(ResolveEffectiveTariff::class)(CarbonImmutable::parse('2026-06-01', 'Africa/Douala'));

    expect(Cache::has(CacheKeys::tariffEffective('2026-06-01')))->toBeTrue();
});

test('publish archives overlapping published version BR-TARIFF-003', function () {
    $user = publishTariffActor();

    $versionA = insertTariffVersion([
        'label' => '2026-a',
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    insertTariffItem(['tariff_version_id' => $versionA]);

    $versionB = insertTariffVersion([
        'label' => '2026-b',
        'status' => 'reviewed',
        'effective_from' => '2026-06-01',
    ]);
    insertTariffItem(['tariff_version_id' => $versionB]);

    app(PublishTariffVersion::class)(new PublishTariffVersionData(
        tariffVersionId: $versionB,
        confirmEffectiveFrom: CarbonImmutable::parse('2026-06-01', 'Africa/Douala'),
        actor: $user,
    ));

    expect(DB::table('tariff_versions')->where('id', $versionA)->value('status'))->toBe('archived')
        ->and(DB::table('tariff_versions')->where('id', $versionB)->value('status'))->toBe('published');
});

function publishTariffActor(): User
{
    Role::create(['name' => 'operations_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('operations_admin');

    return $user;
}
