<?php

use App\Actions\Tariff\Data\PublishTariffVersionData;
use App\Actions\Tariff\PublishTariffVersion;
use App\Models\Tariff\TariffItem;
use App\Models\Tariff\TariffVersion;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('draft and reviewed tariff amounts stay off the public fees page FR-TA-04', function () {
    $this->seed(BaselineCentresSeeder::class);
    $centreId = centreId('ecole-de-police');
    $categoryId = insertVehicleCategory(['code' => 'B']);

    $publishedId = insertTariffVersion([
        'label' => '2026-published',
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    $publishedItemId = insertTariffItem([
        'tariff_version_id' => $publishedId,
        'vehicle_category_id' => $categoryId,
        'amount_xaf' => 17900,
    ]);
    linkTariffItemToCentre($publishedItemId, $centreId);

    foreach (['draft' => 32100, 'reviewed' => 28000] as $status => $amount) {
        $versionId = insertTariffVersion([
            'label' => '2026-'.$status,
            'status' => $status,
            'effective_from' => '2026-01-01',
        ]);
        $itemId = insertTariffItem([
            'tariff_version_id' => $versionId,
            'vehicle_category_id' => $categoryId,
            'amount_xaf' => $amount,
        ]);
        linkTariffItemToCentre($itemId, $centreId);
    }

    $this->get('/fr/tarifs')
        ->assertOk()
        ->assertSeeText('17 900 FCFA')
        ->assertDontSeeText('32 100 FCFA')
        ->assertDontSeeText('28 000 FCFA');
});

test('publishing a replacement archives the previous version without deleting it BR-TARIFF-003', function () {
    $this->seed(BaselineCentresSeeder::class);
    $actor = createAdminUser('operations_admin');
    $categoryId = insertVehicleCategory(['code' => 'B']);

    $previousId = insertTariffVersion([
        'label' => '2026-a',
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    $previousItemId = insertTariffItem([
        'tariff_version_id' => $previousId,
        'vehicle_category_id' => $categoryId,
        'amount_xaf' => 17900,
    ]);

    $replacementId = insertTariffVersion([
        'label' => '2026-b',
        'status' => 'reviewed',
        'effective_from' => '2026-06-01',
    ]);
    insertTariffItem([
        'tariff_version_id' => $replacementId,
        'vehicle_category_id' => $categoryId,
        'amount_xaf' => 19000,
    ]);

    app(PublishTariffVersion::class)(new PublishTariffVersionData(
        tariffVersionId: $replacementId,
        confirmEffectiveFrom: CarbonImmutable::parse('2026-06-01', 'Africa/Douala'),
        actor: $actor,
    ));

    $previous = TariffVersion::query()->find($previousId);

    expect($previous)->not->toBeNull()
        ->and($previous->status->value)->toBe('archived')
        ->and(TariffItem::query()->find($previousItemId)?->amount_xaf)->toBe(17900)
        ->and(TariffVersion::query()->find($replacementId)?->status->value)->toBe('published');
});

test('a rejected publish leaves the current published version in place', function () {
    $this->seed(BaselineCentresSeeder::class);
    $actor = createAdminUser('operations_admin');

    $publishedId = insertTariffVersion([
        'label' => '2026-current',
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    insertTariffItem(['tariff_version_id' => $publishedId]);

    $reviewedId = insertTariffVersion([
        'label' => '2026-reviewed',
        'status' => 'reviewed',
        'effective_from' => '2026-06-01',
    ]);
    insertTariffItem(['tariff_version_id' => $reviewedId]);

    expect(fn () => app(PublishTariffVersion::class)(new PublishTariffVersionData(
        tariffVersionId: $reviewedId,
        confirmEffectiveFrom: CarbonImmutable::parse('2026-07-01', 'Africa/Douala'),
        actor: $actor,
    )))->toThrow(InvalidArgumentException::class);

    $draftId = insertTariffVersion([
        'label' => '2026-draft',
        'status' => 'draft',
        'effective_from' => '2026-01-01',
    ]);
    insertTariffItem(['tariff_version_id' => $draftId]);

    expect(fn () => app(PublishTariffVersion::class)(new PublishTariffVersionData(
        tariffVersionId: $draftId,
        confirmEffectiveFrom: CarbonImmutable::parse('2026-01-01', 'Africa/Douala'),
        actor: $actor,
    )))->toThrow(InvalidArgumentException::class);

    expect(TariffVersion::query()->find($publishedId)?->status->value)->toBe('published')
        ->and(TariffVersion::query()->find($reviewedId)?->status->value)->toBe('reviewed')
        ->and(TariffVersion::query()->find($draftId)?->status->value)->toBe('draft');
});

test('public fees and the appointment summary show the same grouped price FR-TA-05', function () {
    $this->seed(BaselineCentresSeeder::class);
    seedPublishedPublicTariffs();

    $this->get('/fr/tarifs')->assertOk()->assertSeeText('17 900 FCFA');
    $this->get('/fr/rendez-vous')->assertOk()->assertSee('data-tariff="17 900 FCFA"', false);
});

test('an empty catalogue shows the unpublished fee state FR-TA-09', function () {
    $this->seed(BaselineCentresSeeder::class);

    $this->get('/fr/tarifs')
        ->assertOk()
        ->assertSeeText('Tarif non publié')
        ->assertSeeText('Aucune version tarifaire publiée')
        ->assertDontSeeText('17 900 FCFA');
});

test('appointment handoff selects the centre and category from the fees link FR-TA-06', function () {
    $this->seed(BaselineCentresSeeder::class);
    seedPublishedPublicTariffs();

    $this->get('/fr/tarifs')
        ->assertOk()
        ->assertSee('category=a', false)
        ->assertSee('tariff_category=A', false);

    $this->get('/fr/rendez-vous?centre=nomayos&category=a')
        ->assertOk()
        ->assertSee('data-centre-key="nomayos"', false)
        ->assertSee('aria-pressed="true"', false)
        ->assertSee('data-appointment-summary-centre', false)
        ->assertSeeText('Nomayos')
        ->assertSee('data-appointment-summary-category', false)
        ->assertSeeText('Catégorie A')
        ->assertSeeText('4 900 FCFA')
        ->assertSee('value="a"', false);
});
