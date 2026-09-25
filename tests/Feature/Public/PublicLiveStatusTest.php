<?php

use App\Support\Clock;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

afterEach(function (): void {
    Clock::unfreeze();
});

test('homepage centre page and appointment step 1 show the same live status FR-CE-08', function () {
    $this->seed(BaselineCentresSeeder::class);
    freezeDisplayTime('2026-09-07 10:00:00');

    $ecole = __('public.home.hero.live.open_until', ['time' => '20h00'], 'fr');
    $nomayos = __('public.home.hero.live.open_until', ['time' => '19h00'], 'fr');

    $this->get('/fr/accueil')->assertOk()->assertSeeText($ecole)->assertSeeText($nomayos);
    $this->get('/fr/centres/ecole-de-police')->assertOk()->assertSeeText($ecole)->assertDontSeeText($nomayos);
    $this->get('/fr/centres/nomayos')->assertOk()->assertSeeText($nomayos);
    $this->get('/fr/rendez-vous')->assertOk()->assertSeeText($ecole)->assertSeeText($nomayos);
});

test('nomayos is closed at 19:06 on a weekday while ecole de police stays open until 20:00', function () {
    $this->seed(BaselineCentresSeeder::class);
    freezeDisplayTime('2026-09-24 19:06:00');

    $ecoleOpen = __('public.home.hero.live.open_until', ['time' => '20h00'], 'fr');
    $nomayosClosed = __('public.home.hero.live.opens_at', ['time' => '07h00'], 'fr');

    $this->get('/fr/accueil')
        ->assertOk()
        ->assertSeeText($ecoleOpen)
        ->assertSeeText($nomayosClosed)
        ->assertSeeText(__('public.home.centres.weekday', [], 'fr'))
        ->assertSeeText(__('public.home.centres.sunday', [], 'fr'))
        ->assertSeeText('07h00 - 20h00')
        ->assertSeeText('07h00 - 19h00')
        ->assertSeeText('07h00 - 15h00')
        ->assertDontSeeText(__('public.home.centres.status_open', [], 'fr'))
        ->assertDontSeeText(__('public.home.hero.live.open_until', ['time' => '19h00'], 'fr'));

    $this->get('/fr/centres')
        ->assertOk()
        ->assertSeeText($ecoleOpen)
        ->assertSeeText($nomayosClosed)
        ->assertDontSeeText(__('public.centres_page.status.open', [], 'fr'));

    $this->get('/fr/centres/nomayos')->assertOk()->assertSeeText($nomayosClosed);
    $this->get('/fr/tarifs')->assertOk()->assertSeeText($nomayosClosed)->assertDontSeeText(__('public.centres_page.status.open', [], 'fr'));
});

test('english pages show centre times in am and pm', function () {
    $this->seed(BaselineCentresSeeder::class);
    freezeDisplayTime('2026-09-25 01:56:00');

    $opens = __('public.home.hero.live.opens_at', ['time' => '7:00 AM'], 'en');

    $this->get('/en/home')
        ->assertOk()
        ->assertSeeText($opens)
        ->assertSeeText('7:00 AM - 8:00 PM')
        ->assertSeeText('7:00 AM - 7:00 PM')
        ->assertSeeText('7:00 AM - 3:00 PM')
        ->assertDontSee('07:00')
        ->assertDontSee('07h00');

    $this->get('/en/appointment')
        ->assertOk()
        ->assertSeeText('7:00 AM – 1:00 PM')
        ->assertSeeText('1:00 PM – 8:00 PM');

    $this->get('/fr/accueil')
        ->assertOk()
        ->assertSeeText('Ouvre à 07h00')
        ->assertSeeText('07h00 - 20h00')
        ->assertDontSee('AM');
});
