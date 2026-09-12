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
