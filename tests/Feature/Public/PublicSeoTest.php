<?php

use App\Domain\Enums\CentreStatus;
use App\Models\Centre\Centre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public pages expose a unique title description canonical and hreflang', function () {
    seedBaselineCentres();
    insertPageSeo([
        'page' => 'home',
        'seo_title' => json_encode(['fr' => 'Accueil G3', 'en' => 'G3 Home']),
        'seo_description' => json_encode(['fr' => 'Description accueil', 'en' => 'Home description']),
    ]);
    insertPageSeo([
        'page' => 'fees',
        'seo_title' => json_encode(['fr' => 'Tarifs G3', 'en' => 'G3 Fees']),
        'seo_description' => json_encode(['fr' => 'Description tarifs', 'en' => 'Fees description']),
    ]);

    $frenchFees = $this->get('/fr/tarifs');
    $englishFees = $this->get('/en/fees');

    $frenchFees->assertOk()
        ->assertSee('<title>Tarifs G3</title>', false)
        ->assertSee('name="description" content="Description tarifs"', false)
        ->assertSee('<link rel="canonical" href="'.route('fr.fees').'">', false)
        ->assertSee('hreflang="fr" href="'.route('fr.fees').'"', false)
        ->assertSee('hreflang="en" href="'.route('en.fees').'"', false)
        ->assertSee('hreflang="x-default" href="'.route('fr.fees').'"', false);

    $englishFees->assertOk()
        ->assertSee('<title>G3 Fees</title>', false)
        ->assertSee('<link rel="canonical" href="'.route('en.fees').'">', false);

    expect($frenchFees->getContent())->not->toContain('<title>Accueil G3</title>');
});

test('appointment tracking query keeps the page canonical without the tab', function () {
    $this->get('/fr/rendez-vous?tab=suivi')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('fr.appointment').'">', false);
});

test('centre seo overrides page seo on centre detail pages', function () {
    seedBaselineCentres();
    insertPageSeo([
        'page' => 'centre_nomayos',
        'seo_title' => json_encode(['fr' => 'Page SEO', 'en' => 'Page SEO']),
        'seo_description' => json_encode(['fr' => 'Page description', 'en' => 'Page description']),
    ]);

    Centre::query()->where('code', 'nomayos')->update([
        'seo_title' => json_encode(['fr' => 'Nomayos SEO', 'en' => 'Nomayos SEO EN']),
        'seo_description' => json_encode(['fr' => 'Description centre', 'en' => 'Centre description']),
    ]);

    $this->get('/fr/centres/nomayos')
        ->assertOk()
        ->assertSee('<title>Nomayos SEO</title>', false)
        ->assertSee('content="Description centre"', false);
});

test('sitemap lists public locale urls and omits admin and inactive centres', function () {
    seedBaselineCentres();

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('content-type', 'application/xml; charset=UTF-8')
        ->assertSee(route('fr.home'), false)
        ->assertSee(route('en.fees'), false)
        ->assertSee(route('fr.centre_nomayos'), false)
        ->assertDontSee('/admin', false)
        ->assertDontSee('tab=', false);

    Centre::query()->where('code', 'nomayos')->update([
        'status' => CentreStatus::Inactive->value,
    ]);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertDontSee(route('fr.centre_nomayos'), false)
        ->assertSee(route('fr.centre_ecole_de_police'), false);
});

test('robots txt disallows admin and points to the sitemap', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertHeader('content-type', 'text/plain; charset=UTF-8')
        ->assertSee('Disallow: /admin')
        ->assertSee('Sitemap: '.url('/sitemap.xml'))
        ->assertDontSee('Disallow: /fr');
});
