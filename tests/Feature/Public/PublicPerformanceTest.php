<?php

use App\Actions\Tariff\Data\TariffItemData;
use App\Actions\Tariff\Data\UpdateTariffVersionItemsData;
use App\Actions\Tariff\UpdateTariffVersionItems;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('content pages do not load the tariff catalogue', function () {
    seedBaselineCentres();
    seedPublishedPublicTariffs();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->get('/fr/a-propos')->assertOk();

    $queries = collect(DB::getQueryLog())->pluck('query')->implode("\n");

    expect($queries)->not->toContain('tariff_items')
        ->and($queries)->not->toContain('tariff_versions');
});

test('the public fee page reuses the cached tariff after the first request', function () {
    seedBaselineCentres();
    seedPublishedPublicTariffs();

    $this->get('/fr/tarifs')->assertOk();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->get('/fr/tarifs')->assertOk()->assertSeeText('03 mois');

    $queries = collect(DB::getQueryLog())->pluck('query')->implode("\n");

    expect($queries)->not->toContain('tariff_items');
});

test('saving a published validity replaces the cached public fee', function () {
    seedBaselineCentres();
    seedPublishedPublicTariffs();

    $this->get('/fr/tarifs')->assertOk()->assertSeeText('03 mois');

    $versionId = (int) DB::table('tariff_versions')->where('status', 'published')->value('id');
    $items = DB::table('tariff_items')
        ->where('tariff_version_id', $versionId)
        ->orderBy('sort_order')
        ->get();
    $actor = User::factory()->create();

    app(UpdateTariffVersionItems::class)(new UpdateTariffVersionItemsData(
        tariffVersionId: $versionId,
        items: $items->map(function (object $item): TariffItemData {
            $notes = json_decode((string) $item->validity_notes, true);
            $categoryCode = DB::table('vehicle_categories')->where('id', $item->vehicle_category_id)->value('code');

            if ($categoryCode === 'A') {
                $notes = ['fr' => '09 mois', 'en' => '09 months'];
            }

            return new TariffItemData(
                vehicleCategoryId: (int) $item->vehicle_category_id,
                amountXaf: (int) $item->amount_xaf,
                centreIds: DB::table('tariff_item_centre')->where('tariff_item_id', $item->id)->pluck('centre_id')->map(fn ($id): int => (int) $id)->all(),
                serviceId: $item->service_id !== null ? (int) $item->service_id : null,
                validityNotes: is_array($notes) ? $notes : null,
                sortOrder: (int) $item->sort_order,
            );
        })->all(),
        actor: $actor,
    ));

    $this->get('/fr/tarifs')
        ->assertOk()
        ->assertSeeText('09 mois')
        ->assertSeeText('12 mois');

    $this->get('/en/fees')
        ->assertOk()
        ->assertSeeText('09 months')
        ->assertSeeText('12 months');
});

test('the homepage preloads its hero and does not embed video', function () {
    $html = $this->get('/fr/accueil')->assertOk()->getContent();

    expect($html)
        ->toContain('rel="preload" as="image"')
        ->toContain('fetchpriority="high"')
        ->not->toContain('<video')
        ->not->toContain('.mp4');

    preg_match_all('/<iframe\b[^>]*>/i', $html, $iframes);

    foreach ($iframes[0] as $iframe) {
        expect($iframe)->toContain('loading="lazy"');
    }
});
