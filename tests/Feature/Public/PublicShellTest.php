<?php

use App\Settings\CompanySettings;
use App\Support\PublicNavigation;
use Database\Seeders\OfficialTariffsSeeder;
use Database\Seeders\PublicAdminBaselineSeeder;
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
    seedBaselineCentres();

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

test('french centres page renders the live selector section', function () {
    seedBaselineCentres();

    $response = $this->get('/fr/centres');

    $response->assertOk()
        ->assertSee('g3-centres-live', escape: false)
        ->assertSee('data-centres-live', escape: false)
        ->assertSee('data-centre-tab', escape: false)
        ->assertSee('data-centre-map-marker', escape: false)
        ->assertSee('g3-centres-standard', escape: false)
        ->assertSee('images/reusable/site-logo.png', escape: false)
        ->assertSee('images/centers/ecole-de-police.png', escape: false)
        ->assertSee('images/centers/nomayos.png', escape: false)
        ->assertSee('images/centers/icon-standard-approval.svg', escape: false)
        ->assertSee('images/centers/icon-standard-procedures.svg', escape: false)
        ->assertSee('images/centers/icon-standard-equipment.svg', escape: false)
        ->assertSee('images/centers/icon-standard-team.svg', escape: false)
        ->assertSee('https://maps.google.com/maps?q=Yaound%C3%A9%2C%20Cameroon&amp;z=12&amp;output=embed', escape: false)
        ->assertSee('destination=3.8786152,11.5116814', escape: false)
        ->assertSee('destination=3.7902275,11.4439448', escape: false)
        ->assertSee('href="tel:+237687187516"', escape: false)
        ->assertSee('href="tel:+237653100801"', escape: false)
        ->assertSee(PublicNavigation::pageUrl('centre_ecole_de_police', 'fr'), escape: false)
        ->assertSee(PublicNavigation::pageUrl('centre_nomayos', 'fr'), escape: false)
        ->assertSee(PublicNavigation::pageUrl('appointment', 'fr').'?centre=ecole-de-police', escape: false)
        ->assertSeeText(__('public.centres_page.title', [], 'fr'))
        ->assertSeeText(__('public.centres_page.items.ecole_de_police.title', [], 'fr'))
        ->assertSeeText(__('public.centres_page.items.nomayos.title', [], 'fr'))
        ->assertSeeText(__('public.centres_page.map.open_google', [], 'fr'))
        ->assertSeeText(__('public.centres_page.standard.title', [], 'fr'))
        ->assertSeeText(__('public.centres_page.standard.items.approval.title', [], 'fr'))
        ->assertSeeText(__('public.centres_page.standard.items.equipment.title', [], 'fr'))
        ->assertDontSeeText(__('public.centres_page.actions.call', [], 'fr'));
});

test('french services page renders published admin services when seeded', function () {
    seedBaselineCentres();
    $this->seed(OfficialTariffsSeeder::class);
    $this->seed(PublicAdminBaselineSeeder::class);

    DB::table('services')
        ->where('code', 'heavy-vehicle-inspection')
        ->update([
            'title' => json_encode([
                'fr' => 'Service poids lourds publié',
                'en' => 'Published heavy service',
            ]),
            'summary' => json_encode([
                'fr' => 'Résumé administrable visible sur la page publique.',
                'en' => 'Admin-managed summary visible on the public page.',
            ]),
        ]);

    $response = $this->get('/fr/services');

    $response->assertOk()
        ->assertSeeText('Service poids lourds publié')
        ->assertSeeText('Résumé administrable visible sur la page publique.')
        ->assertSee('images/services/service-heavy.svg', escape: false);
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

test('french road safety page renders the contextual guide and sixty-second reflex', function () {
    $response = $this->get('/fr/securite-routiere');

    $response->assertOk()
        ->assertSee('g3-road-hub', escape: false)
        ->assertSee('data-road-safety-hub', escape: false)
        ->assertSee('data-road-safety-target="rain"', escape: false)
        ->assertSee('data-road-safety-target="night"', escape: false)
        ->assertSee('data-road-safety-context-panel="long-distance"', escape: false)
        ->assertSee('images/road-safety/icon-grid.svg', escape: false)
        ->assertSee('images/road-safety/essentials/1.png', escape: false)
        ->assertSee('images/road-safety/rain/4.png', escape: false)
        ->assertSee('images/road-safety/night/2.png', escape: false)
        ->assertSee('images/road-safety/long-distance/4.png', escape: false)
        ->assertSeeText('Freinage & adhérence')
        ->assertSeeText('Réflexes sous la pluie')
        ->assertSeeText('Fatigue & vigilance')
        ->assertSeeText('Charge & stabilité')
        ->assertDontSeeText('02 · Bento Safety Hub')
        ->assertSee('g3-road-reflex', escape: false)
        ->assertSee('data-road-reflex', escape: false)
        ->assertSee('data-road-reflex-check', escape: false)
        ->assertSee('images/road-safety/icon-tyre.svg', escape: false)
        ->assertSeeText('Le réflexe 60 secondes')
        ->assertSeeText('4 vérifications simples avant de démarrer.')
        ->assertSeeText('Pneumatiques')
        ->assertSeeText('4 points à vérifier')
        ->assertSeeText('Rappel :')
        ->assertDontSee('g3-road-hero', escape: false)
        ->assertDontSee('data-road-safety-check', escape: false)
        ->assertDontSee('g3-road-gateway', escape: false)
        ->assertDontSeeText('Quatre vérifications rapides avant le départ.')
        ->assertDontSeeText('Un doute sur l’état de votre véhicule ?');
});

test('english road safety page renders the translated guide', function () {
    $response = $this->get('/en/road-safety');

    $response->assertOk()
        ->assertSee('g3-road-hub', escape: false)
        ->assertSee('data-road-safety-target="long-distance"', escape: false)
        ->assertSee('images/road-safety/long-distance/4.png', escape: false)
        ->assertSeeText('Four essentials, adapted to your journey')
        ->assertSeeText('All essentials')
        ->assertSeeText('Rain')
        ->assertSeeText('Night')
        ->assertSeeText('Long journey')
        ->assertDontSeeText('02 · Bento Safety Hub')
        ->assertSee('g3-road-reflex', escape: false)
        ->assertSee('data-road-reflex-check', escape: false)
        ->assertSeeText('The 60-second reflex')
        ->assertSeeText('4 simple checks before starting.')
        ->assertSeeText('4 points to check')
        ->assertSeeText('Reminder:')
        ->assertDontSee('g3-road-hero', escape: false)
        ->assertDontSee('data-road-safety-check', escape: false)
        ->assertDontSee('g3-road-gateway', escape: false)
        ->assertDontSeeText('Four quick checks before departure.')
        ->assertDontSeeText('Unsure about your vehicle’s condition?');
});

test('french contact page renders the centres map section', function () {
    seedBaselineCentres();

    $response = $this->get('/fr/contact');

    $response->assertOk()
        ->assertSee('g3-contact-centres', escape: false)
        ->assertSee('data-contact-centres', escape: false)
        ->assertSee('data-contact-view="map"', escape: false)
        ->assertSee('data-contact-view-target="list"', escape: false)
        ->assertSee('g3-contact-message', escape: false)
        ->assertSee('data-contact-message-text', escape: false)
        ->assertSee('images/contact/ecole-de-police.png', escape: false)
        ->assertSee('images/contact/nomayos.png', escape: false)
        ->assertSee('images/contact/icon-pin.svg', escape: false)
        ->assertSee('images/contact/icon-phone.svg', escape: false)
        ->assertSee('images/contact/icon-headset.svg', escape: false)
        ->assertSee('images/contact/icon-send.svg', escape: false)
        ->assertSee('https://maps.google.com/maps?q=G3%20Control%20Yaound%C3%A9&amp;ll=', escape: false)
        ->assertSeeText('Deux centres à Yaoundé. Un accès direct à votre équipe.')
        ->assertSeeText('Une question particulière ? Écrivez-nous.')
        ->assertSeeText('Besoin d’un rendez-vous ?')
        ->assertSeeText('Consulter les tarifs')
        ->assertSeeText('Objet de votre demande')
        ->assertSeeText('Envoyer mon message')
        ->assertSeeText('Nous sommes à votre écoute')
        ->assertSeeText('Agrément N°0291 depuis 2020')
        ->assertSeeText('Carte')
        ->assertSeeText('Liste')
        ->assertSeeText('École de Police')
        ->assertSeeText('Nomayos')
        ->assertSeeText('Descente ancien Texaco, École de Police, Yaoundé')
        ->assertSeeText('Carrefour Nomayos, Yaoundé')
        ->assertDontSeeText('Page en construction')
        ->assertDontSeeText('Appeler')
        ->assertDontSeeText('Itinéraire');
});

test('english contact page renders the translated centres section', function () {
    seedBaselineCentres();

    $response = $this->get('/en/contact');

    $response->assertOk()
        ->assertSee('g3-contact-centres', escape: false)
        ->assertSee('data-contact-view-target="map"', escape: false)
        ->assertSee('g3-contact-message', escape: false)
        ->assertSee('data-contact-message-count', escape: false)
        ->assertSee('images/contact/ecole-de-police.png', escape: false)
        ->assertSee('images/contact/icon-map.svg', escape: false)
        ->assertSee('images/contact/icon-document.svg', escape: false)
        ->assertSee('images/contact/icon-mail.svg', escape: false)
        ->assertSee('https://maps.google.com/maps?q=G3%20Control%20Yaound%C3%A9&amp;ll=', escape: false)
        ->assertSeeText('Two centres in Yaoundé. Direct access to your team.')
        ->assertSeeText('A specific question? Write to us.')
        ->assertSeeText('Need an appointment?')
        ->assertSeeText('View fees')
        ->assertSeeText('Subject of your request')
        ->assertSeeText('Send my message')
        ->assertSeeText('We are listening')
        ->assertSeeText('Map')
        ->assertSeeText('List')
        ->assertSeeText('Former Texaco descent, École de Police, Yaoundé')
        ->assertSeeText('Nomayos junction, Yaoundé')
        ->assertDontSeeText('Page under construction')
        ->assertDontSeeText('Call')
        ->assertDontSeeText('Directions');
});

test('french about renders the identity section', function () {
    $response = $this->get('/fr/a-propos');

    $response->assertOk()
        ->assertSee('g3-about-identity', escape: false)
        ->assertSee('images/about/icon-eye.svg', escape: false)
        ->assertSee('images/about/icon-target.svg', escape: false)
        ->assertSee('images/about/icon-briefcase.svg', escape: false)
        ->assertSeeText(__('public.about.identity.title', [], 'fr'))
        ->assertSeeText(__('public.about.identity.purpose.title', [], 'fr'))
        ->assertSeeText(__('public.about.identity.mission.title', [], 'fr'))
        ->assertSeeText(__('public.about.identity.vision.title', [], 'fr'))
        ->assertSeeText(__('public.about.identity.brief.title', [], 'fr'))
        ->assertSee('g3-about-requirements', escape: false)
        ->assertSee('images/about/technical-requirements.png', escape: false)
        ->assertSee('images/about/icon-procedure.svg', escape: false)
        ->assertSee('images/about/icon-team.svg', escape: false)
        ->assertSee('images/about/icon-measure.svg', escape: false)
        ->assertSeeText(__('public.about.requirements.title', [], 'fr'))
        ->assertSeeText(__('public.about.requirements.items.procedures.title', [], 'fr'))
        ->assertSeeText(__('public.about.requirements.items.measures.title', [], 'fr'))
        ->assertSee('g3-about-values', escape: false)
        ->assertSee('images/reusable/site-logo.png', escape: false)
        ->assertSee('images/about/icon-value-security.svg', escape: false)
        ->assertSee('images/about/icon-value-simplicity.svg', escape: false)
        ->assertSee('images/about/icon-value-trust.svg', escape: false)
        ->assertSee('images/about/icon-value-rigor.svg', escape: false)
        ->assertSeeText(__('public.about.values.title', [], 'fr'))
        ->assertSeeText(__('public.about.values.motto', [], 'fr'))
        ->assertSeeText(__('public.about.values.items.trust.title', [], 'fr'))
        ->assertSee('g3-about-team', escape: false)
        ->assertSee('images/about/technicians.png', escape: false)
        ->assertSee('images/about/icon-team-welcome.svg', escape: false)
        ->assertSee('images/about/icon-team-rigor.svg', escape: false)
        ->assertSee('images/about/icon-team-responsibility.svg', escape: false)
        ->assertSeeText(__('public.about.team.title', [], 'fr'))
        ->assertSeeText(__('public.about.team.items.welcome', [], 'fr'))
        ->assertSeeText(__('public.about.team.items.responsibility', [], 'fr'));
});

test('french appointment page renders express pass and tracking modes', function () {
    seedBaselineCentres();
    seedPublishedPublicTariffs();
    insertService([
        'title' => json_encode(['fr' => 'Contre-visite', 'en' => 'Follow-up inspection']),
        'is_published' => true,
    ]);

    $response = $this->get('/fr/rendez-vous');

    $response->assertOk()
        ->assertSee('g3-appointment-hub', escape: false)
        ->assertSee('data-appointment-hub', escape: false)
        ->assertSee('data-appointment-mode', escape: false)
        ->assertSee('data-appointment-panel-key="booking"', escape: false)
        ->assertSee('data-appointment-panel-key="tracking"', escape: false)
        ->assertSee('images/appointment-and-tracking/ecole-de-police.png', escape: false)
        ->assertSee('images/appointment-and-tracking/nomayos.png', escape: false)
        ->assertSee('images/appointment-and-tracking/icon-calendar.svg', escape: false)
        ->assertSee('images/appointment-and-tracking/icon-search.svg', escape: false)
        ->assertSee('name="service_id"', escape: false)
        ->assertSee('name="vehicle_category"', escape: false)
        ->assertSee('data-appointment-date-picker', escape: false)
        ->assertSee('data-appointment-calendar', escape: false)
        ->assertSee('name="preferred_date"', escape: false)
        ->assertDontSee('type="date"', escape: false)
        ->assertSee('data-appointment-summary-tariff', escape: false)
        ->assertSeeText('Votre visite technique, simplement.')
        ->assertSeeText('Préparez votre rendez-vous')
        ->assertSeeText('Contre-visite')
        ->assertSeeText('Catégorie D — Poids lourd')
        ->assertSee('data-tariff="41 750 FCFA"', escape: false)
        ->assertSeeText('Retrouvez l’état de votre demande en quelques secondes.');
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
    seedBaselineCentres();

    $response = $this->get('/fr/centres/nomayos');

    $response->assertOk()
        ->assertSee('g3-nav-link--active', escape: false);
});

test('shell includes company contact details from settings', function () {
    $settings = app(CompanySettings::class);

    $this->get('/fr/accueil')
        ->assertOk()
        ->assertSee($settings->email, escape: false)
        ->assertSee($settings->secondary_email, escape: false)
        ->assertSee($settings->agrementLabel(), escape: false);
});

test('public centre and tariff pages render operational data from records', function () {
    seedBaselineCentres();

    $centreId = centreId('ecole-de-police');
    DB::table('centre_phones')
        ->where('centre_id', $centreId)
        ->where('sort_order', 1)
        ->update(['e164' => '+237699111222']);
    DB::table('centre_weekly_hours')
        ->where('centre_id', $centreId)
        ->where('weekday', 1)
        ->update(['closes_at' => '18:30:00']);

    $versionId = insertTariffVersion([
        'label' => 'Tarif public test',
        'status' => 'published',
        'effective_from' => '2026-01-01',
        'published_at' => now(),
    ]);
    $categoryId = insertVehicleCategory([
        'code' => 'B',
        'label' => json_encode(['fr' => 'Véhicule de tourisme', 'en' => 'Passenger vehicle']),
        'examples' => json_encode(['fr' => 'Voiture test', 'en' => 'Test car']),
        'description' => json_encode(['fr' => 'Description test', 'en' => 'Test description']),
        'is_published' => true,
    ]);
    $itemId = insertTariffItem([
        'tariff_version_id' => $versionId,
        'vehicle_category_id' => $categoryId,
        'amount_xaf' => 32100,
    ]);
    linkTariffItemToCentre($itemId, $centreId);

    freezeDisplayTime('2026-09-25 10:00:00');

    $this->get('/fr/contact')
        ->assertOk()
        ->assertSeeText('699 111 222')
        ->assertSeeText('g3sarl1@gmail.com')
        ->assertSeeText('admin@g3control.com')
        ->assertSeeText('07h00 - 18h30')
        ->assertSeeText(__('public.home.hero.live.open_until', ['time' => '20h00'], 'fr'))
        ->assertDontSee('g3-contact-centres__status--closed', false);

    freezeDisplayTime('2026-09-25 03:00:00');

    $this->get('/fr/contact')
        ->assertOk()
        ->assertDontSeeText('Ouvert actuellement')
        ->assertSeeText(__('public.home.hero.live.opens_at', ['time' => '07h00'], 'fr'))
        ->assertSee('g3-contact-centres__status--closed', false);

    $this->get('/fr/centres/ecole-de-police')
        ->assertOk()
        ->assertSeeText('g3sarl1@gmail.com')
        ->assertSeeText('admin@g3control.com');

    $this->get('/fr/centres/nomayos')
        ->assertOk()
        ->assertSeeText('g3sarl1@gmail.com')
        ->assertSeeText('admin@g3control.com');

    $this->get('/fr/tarifs')
        ->assertOk()
        ->assertSeeText('32 100 FCFA')
        ->assertDontSeeText('17 900 FCFA');
});

test('public fees page shows a changed validity and keeps the other categories', function () {
    seedBaselineCentres();
    seedPublishedPublicTariffs();

    $versionId = DB::table('tariff_versions')->where('status', 'published')->value('id');
    $categoryId = DB::table('vehicle_categories')->where('code', 'A')->value('id');

    DB::table('tariff_items')
        ->where('tariff_version_id', $versionId)
        ->where('vehicle_category_id', $categoryId)
        ->update([
            'validity_notes' => json_encode(['fr' => '09 mois', 'en' => '09 months']),
        ]);

    $this->get('/fr/tarifs')
        ->assertOk()
        ->assertSeeText('09 mois')
        ->assertSeeText('12 mois')
        ->assertSeeText('06 mois');

    $this->get('/en/fees')
        ->assertOk()
        ->assertSeeText('09 months')
        ->assertSeeText('12 months');
});
