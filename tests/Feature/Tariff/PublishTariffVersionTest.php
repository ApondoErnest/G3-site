<?php

use App\Actions\Tariff\Data\MarkTariffVersionReviewedData;
use App\Actions\Tariff\Data\PublishTariffVersionData;
use App\Actions\Tariff\Data\TariffItemData;
use App\Actions\Tariff\Data\UpdateTariffVersionItemsData;
use App\Actions\Tariff\MarkTariffVersionReviewed;
use App\Actions\Tariff\PublishTariffVersion;
use App\Actions\Tariff\UpdateTariffVersionItems;
use App\Models\User;
use App\Support\CacheKeys;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
    $this->seed(BaselineCentresSeeder::class);
});

test('update tariff version items replaces lines on draft versions', function () {
    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $versionId = insertTariffVersion(['status' => 'draft']);
    $user = User::factory()->create();

    app(UpdateTariffVersionItems::class)(new UpdateTariffVersionItemsData(
        tariffVersionId: $versionId,
        items: [
            new TariffItemData(
                vehicleCategoryId: $categoryId,
                amountXaf: 25000,
                centreIds: [centreId('ecole-de-police')],
            ),
        ],
        actor: $user,
    ));

    expect(DB::table('tariff_items')->where('tariff_version_id', $versionId)->count())->toBe(1)
        ->and(DB::table('tariff_item_centre')->count())->toBe(1);
});

test('update tariff version items changes one published category and keeps the other', function () {
    $firstCategoryId = insertVehicleCategory(['code' => 'vp']);
    $secondCategoryId = insertVehicleCategory(['code' => 'pl']);
    $versionId = insertTariffVersion(['status' => 'published', 'published_at' => now()]);
    $centreId = centreId('ecole-de-police');
    $user = User::factory()->create();

    Cache::put(CacheKeys::tariffMatrix($versionId), ['stale'], 3600);

    app(UpdateTariffVersionItems::class)(new UpdateTariffVersionItemsData(
        tariffVersionId: $versionId,
        items: [
            new TariffItemData(
                vehicleCategoryId: $firstCategoryId,
                amountXaf: 30000,
                centreIds: [$centreId],
            ),
            new TariffItemData(
                vehicleCategoryId: $secondCategoryId,
                amountXaf: 45000,
                centreIds: [$centreId],
                sortOrder: 2,
            ),
        ],
        actor: $user,
    ));

    expect(DB::table('tariff_items')->where('tariff_version_id', $versionId)->orderBy('sort_order')->pluck('amount_xaf')->all())
        ->toBe([30000, 45000])
        ->and(Cache::has(CacheKeys::tariffMatrix($versionId)))->toBeFalse();
});

test('update tariff version items rejects archived versions', function () {
    $versionId = insertTariffVersion(['status' => 'archived']);
    $user = User::factory()->create();

    expect(fn () => app(UpdateTariffVersionItems::class)(new UpdateTariffVersionItemsData(
        tariffVersionId: $versionId,
        items: [],
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});

test('mark tariff version reviewed requires at least one item', function () {
    $versionId = insertTariffVersion(['status' => 'draft']);
    $user = User::factory()->create();

    expect(fn () => app(MarkTariffVersionReviewed::class)(new MarkTariffVersionReviewedData(
        tariffVersionId: $versionId,
        actor: $user,
    )))->toThrow(InvalidArgumentException::class);
});

test('publish tariff version requires elevated role BR-TARIFF-005', function () {
    Role::create(['name' => 'centre_manager', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('centre_manager');

    $versionId = insertTariffVersion(['status' => 'reviewed']);
    insertTariffItem(['tariff_version_id' => $versionId]);

    expect(fn () => app(PublishTariffVersion::class)(new PublishTariffVersionData(
        tariffVersionId: $versionId,
        confirmEffectiveFrom: CarbonImmutable::parse('2026-01-01', 'Africa/Douala'),
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});

test('publish workflow draft to reviewed to published succeeds', function () {
    Role::create(['name' => 'operations_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('operations_admin');

    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $versionId = insertTariffVersion([
        'label' => '2026-01',
        'status' => 'draft',
        'effective_from' => '2026-01-01',
    ]);

    app(UpdateTariffVersionItems::class)(new UpdateTariffVersionItemsData(
        tariffVersionId: $versionId,
        items: [
            new TariffItemData(
                vehicleCategoryId: $categoryId,
                amountXaf: 25000,
                centreIds: [centreId('ecole-de-police'), centreId('nomayos')],
            ),
        ],
        actor: $user,
    ));

    app(MarkTariffVersionReviewed::class)(new MarkTariffVersionReviewedData(
        tariffVersionId: $versionId,
        actor: $user,
    ));

    app(PublishTariffVersion::class)(new PublishTariffVersionData(
        tariffVersionId: $versionId,
        confirmEffectiveFrom: CarbonImmutable::parse('2026-01-01', 'Africa/Douala'),
        actor: $user,
    ));

    expect(DB::table('tariff_versions')->where('id', $versionId)->value('status'))->toBe('published');
});
