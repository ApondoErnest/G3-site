<?php

use App\Settings\CompanySettings;
use App\Support\PublicNavigation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('french shell renders navigation footer and locale switcher', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSeeText(__('public.nav.home', [], 'fr'))
        ->assertSeeText(__('public.nav.centres', [], 'fr'))
        ->assertSeeText(__('public.cta.appointment', [], 'fr'))
        ->assertSee('data-g3-header', escape: false)
        ->assertSee('data-mobile-nav-toggle', escape: false)
        ->assertSee('g3-public-header__accent--orange', escape: false)
        ->assertSee('g3-header-appointment', escape: false)
        ->assertSee('g3-public-header__mobile-booking', escape: false)
        ->assertDontSee('g3-header-locale', escape: false)
        ->assertSee('g3-site-footer__shell', escape: false)
        ->assertSeeText(__('public.footer.tagline', [], 'fr'))
        ->assertSeeText(__('public.footer.contact_heading', [], 'fr'));
});

test('english shell renders translated navigation', function () {
    $response = $this->get('/en/home');

    $response->assertOk()
        ->assertSeeText(__('public.nav.home', [], 'en'))
        ->assertSeeText(__('public.cta.appointment', [], 'en'));
});

test('french home renders the hero carousel', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSee('g3-home-hero', escape: false)
        ->assertSee('images/homepage/hero-1.png', escape: false)
        ->assertSeeText(__('public.home.hero.title', [], 'fr'))
        ->assertSeeText(__('public.home.hero.live.title', [], 'fr'));
});

test('french home renders the quick start section', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSee('g3-home-start', escape: false)
        ->assertSee('images/homepage/icon-calendar.svg', escape: false)
        ->assertSeeText(__('public.home.start.title', [], 'fr'))
        ->assertSeeText(__('public.home.start.journey_title', [], 'fr'));
});

test('french home renders the technical control section', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSee('g3-home-control', escape: false)
        ->assertSee('data-home-control', escape: false)
        ->assertSee('data-control-tab', escape: false)
        ->assertSee('images/homepage/g3-control.png', escape: false)
        ->assertSee('images/homepage/icon-braking.svg', escape: false)
        ->assertSeeText(__('public.home.control.title', [], 'fr'))
        ->assertSeeText(__('public.home.control.checks.braking.label', [], 'fr'))
        ->assertSeeText(__('public.home.control.learn_more', [], 'fr'));
});

test('french home renders the equipment carousel section', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSee('g3-home-equipment', escape: false)
        ->assertSee('data-equipment-carousel', escape: false)
        ->assertSee('data-equipment-page', escape: false)
        ->assertSee('data-equipment-card', escape: false)
        ->assertSee('images/homepage/brakes.png', escape: false)
        ->assertSee('images/homepage/headlamp-tester.png', escape: false)
        ->assertSee('images/homepage/plays.png', escape: false)
        ->assertSee('images/homepage/opacimeter.png', escape: false)
        ->assertSeeText(__('public.home.equipment.title', [], 'fr'))
        ->assertSeeText(__('public.home.equipment.items.brakes.title', [], 'fr'))
        ->assertSeeText(__('public.home.equipment.items.plays.title', [], 'fr'));
});

test('french home renders the centres section', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSee('g3-home-centres', escape: false)
        ->assertSee('images/homepage/ecole-de-police.png', escape: false)
        ->assertSee('images/homepage/nomayos.png', escape: false)
        ->assertSee('href="tel:+237687187516"', escape: false)
        ->assertSee(PublicNavigation::pageUrl('centre_nomayos', 'fr'), escape: false)
        ->assertSee('https://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr=3.8786152,11.5116814&amp;daddr=3.7902275,11.4439448&amp;hl=fr&amp;z=12&amp;output=embed', escape: false)
        ->assertSee('origin=3.8786152,11.5116814&amp;destination=3.7902275,11.4439448', escape: false)
        ->assertSeeText(__('public.home.centres.title', [], 'fr'))
        ->assertSeeText(__('public.home.centres.items.ecole_de_police.title', [], 'fr'))
        ->assertSeeText(__('public.home.centres.items.nomayos.title', [], 'fr'))
        ->assertSeeText(__('public.home.centres.map_title', [], 'fr'));
});

test('french home renders the road safety section', function () {
    $response = $this->get('/fr/accueil');

    $response->assertOk()
        ->assertSee('g3-home-road-safety', escape: false)
        ->assertSee('images/homepage/securite-routiere.png', escape: false)
        ->assertSee(PublicNavigation::pageUrl('road_safety', 'fr'), escape: false)
        ->assertSeeText(__('public.home.road_safety.title', [], 'fr'))
        ->assertSeeText(__('public.home.road_safety.items.braking.title', [], 'fr'))
        ->assertSeeText(__('public.home.road_safety.items.tyres.title', [], 'fr'))
        ->assertSeeText(__('public.home.road_safety.cta', [], 'fr'));
});

test('top strip renders public service details from baseline data', function () {
    seedBaselineCentres();

    $this->get('/fr/accueil')
        ->assertOk()
        ->assertSeeText('Yaoundé, Cameroun')
        ->assertSeeText('2 centres à votre service')
        ->assertSeeText('École de Police • Nomayos')
        ->assertSeeText('Ouvert 7j/7')
        ->assertSeeText('Jours fériés inclus')
        ->assertSeeText('Agrément N°0291')
        ->assertSeeText('Depuis 2020')
        ->assertSeeText('+237 687 187 516')
        ->assertSee('href="tel:+237687187516"', escape: false)
        ->assertDontSee('https://wa.me/237687187516', escape: false)
        ->assertDontSeeText('WhatsApp');
});

test('top strip keeps the public phone available before centres are seeded', function () {
    $this->get('/fr/accueil')
        ->assertOk()
        ->assertSeeText('+237 687 187 516')
        ->assertSee('href="tel:+237687187516"', escape: false)
        ->assertDontSee('https://wa.me/237687187516', escape: false);
});

test('locale switcher links to equivalent page in other language', function () {
    $response = $this->get('/fr/tarifs');

    $response->assertOk()
        ->assertSee(PublicNavigation::switchLocaleUrl('en', 'fees'), escape: false);
});

test('centres nav is active on centre detail pages', function () {
    $response = $this->get('/fr/centres/nomayos');

    $response->assertOk()
        ->assertSee('g3-nav-link--active', escape: false);
});

test('shell includes company contact details from settings', function () {
    $settings = app(CompanySettings::class);

    $this->get('/fr/accueil')
        ->assertOk()
        ->assertSee($settings->email, escape: false)
        ->assertSee($settings->agrementLabel(), escape: false);
});
