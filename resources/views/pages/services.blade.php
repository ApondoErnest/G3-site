@php
    use App\Support\PublicNavigation;

    $isFrench = $locale === 'fr';
    $translated = fn (?array $value, string $fallback = ''): string => $value[$locale] ?? $value['fr'] ?? $value['en'] ?? $fallback;

    $overview = [
        'overline' => $isFrench ? 'Votre service, en un coup d’œil' : 'Your service, at a glance',
        'title' => $isFrench
            ? 'Les informations essentielles, sans chercher partout.'
            : 'The essential information, without searching everywhere.',
        'lead' => $isFrench
            ? 'Un aperçu clair de ce qui vous attend, pour un parcours simple et transparent.'
            : 'A clear overview of what to expect, for a simple and transparent visit.',
    ];

    $passportCells = [
        [
            'icon' => 'passport-service.svg',
            'label' => $isFrench ? 'Service' : 'Service',
            'title' => $isFrench ? 'Visite technique périodique' : 'Periodic technical inspection',
            'body' => $isFrench ? 'Contrôle réglementaire de votre véhicule' : 'Regulatory inspection for your vehicle',
        ],
        [
            'icon' => 'passport-vehicles.svg',
            'label' => $isFrench ? 'Véhicules concernés' : 'Vehicles concerned',
            'title' => $isFrench ? 'Véhicules légers' : 'Light vehicles',
            'body' => $isFrench ? 'Particuliers · 4×4 · SUV' : 'Private cars · 4×4 · SUVs',
        ],
        [
            'icon' => 'passport-centres.svg',
            'label' => $isFrench ? 'Centres disponibles' : 'Available centres',
            'title' => $isFrench ? 'École de Police · Nomayos' : 'École de Police · Nomayos',
            'body' => $isFrench ? 'Deux centres à Yaoundé' : 'Two centres in Yaoundé',
        ],
        [
            'icon' => 'passport-documents.svg',
            'label' => $isFrench ? 'Documents nécessaires' : 'Required documents',
            'title' => $isFrench ? 'Carte grise' : 'Registration card',
            'body' => $isFrench ? 'Pièce d’identité · autres selon le cas' : 'ID document · others depending on the case',
        ],
        [
            'icon' => 'passport-tariff.svg',
            'label' => $isFrench ? 'Tarif applicable' : 'Applicable tariff',
            'title' => $isFrench ? 'Voir le tarif officiel' : 'See the official tariff',
            'body' => $isFrench ? 'Selon le type de véhicule' : 'Depending on vehicle type',
            'url' => PublicNavigation::pageUrl('fees', $locale),
        ],
        [
            'icon' => 'passport-action.svg',
            'label' => $isFrench ? 'Action suivante' : 'Next action',
            'title' => $isFrench ? 'Prendre rendez-vous' : 'Book an appointment',
            'body' => $isFrench ? 'En ligne ou sur place' : 'Online or on site',
            'url' => PublicNavigation::pageUrl('appointment', $locale),
        ],
    ];

    $technicalInspectionUrl = PublicNavigation::pageUrl('technical_inspection', $locale);

    $serviceSection = [
        'overline' => $isFrench ? 'Nos prestations' : 'Our services',
        'title' => $isFrench ? 'Des services clairement organisés.' : 'Clearly organised services.',
        'lead' => $isFrench
            ? 'Des prestations adaptées à chaque catégorie de véhicule, pour une route plus sûre.'
            : 'Services adapted to each vehicle category, for a safer road.',
    ];

    $servicePortfolio = [
        [
            'icon' => 'service-periodic.svg',
            'title' => $isFrench ? 'Visite technique périodique' : 'Periodic technical inspection',
            'description' => $isFrench
                ? 'Contrôle complet et réglementaire de votre véhicule.'
                : 'Complete regulatory inspection for your vehicle.',
            'vehicles' => $isFrench ? 'Tous types de véhicules' : 'All vehicle types',
        ],
        [
            'icon' => 'service-recheck.svg',
            'title' => $isFrench ? 'Contre-visite' : 'Re-inspection',
            'description' => $isFrench
                ? 'Vérification suite à une visite avec réserves.'
                : 'Verification after an inspection with reservations.',
            'vehicles' => $isFrench ? 'Tous types de véhicules' : 'All vehicle types',
        ],
        [
            'icon' => 'service-light.svg',
            'title' => $isFrench ? 'Contrôle des véhicules légers' : 'Light vehicle inspection',
            'description' => $isFrench
                ? 'Pour les voitures particulières, citadines et 4×4.'
                : 'For private cars, city cars and 4×4 vehicles.',
            'vehicles' => $isFrench ? 'Véhicules légers' : 'Light vehicles',
        ],
        [
            'icon' => 'service-utility.svg',
            'title' => $isFrench ? 'Contrôle des utilitaires' : 'Utility vehicle inspection',
            'description' => $isFrench
                ? 'Contrôle adapté aux véhicules utilitaires.'
                : 'Inspection adapted to utility vehicles.',
            'vehicles' => $isFrench ? 'Fourgonnettes, pick-up, utilitaires' : 'Vans, pick-ups, utility vehicles',
        ],
        [
            'icon' => 'service-transport.svg',
            'title' => $isFrench ? 'Contrôle des taxis & transport' : 'Taxi and transport inspection',
            'description' => $isFrench
                ? 'Pour les taxis et véhicules de transport de personnes.'
                : 'For taxis and passenger transport vehicles.',
            'vehicles' => $isFrench ? 'Taxis, bus, autocars' : 'Taxis, buses, coaches',
        ],
        [
            'icon' => 'service-heavy.svg',
            'title' => $isFrench ? 'Contrôle des poids lourds' : 'Heavy goods vehicle inspection',
            'description' => $isFrench
                ? 'Une expertise dédiée aux grands gabarits.'
                : 'Dedicated expertise for large-format vehicles.',
            'vehicles' => $isFrench ? 'Poids lourds, camions, semi-remorques' : 'Heavy goods vehicles, trucks, semi-trailers',
        ],
    ];
    $serviceVehicles = [
        'periodic-technical-inspection' => $isFrench ? 'Tous types de véhicules' : 'All vehicle types',
        're-inspection' => $isFrench ? 'Tous types de véhicules' : 'All vehicle types',
        'light-vehicle-inspection' => $isFrench ? 'Véhicules légers' : 'Light vehicles',
        'utility-vehicle-inspection' => $isFrench ? 'Fourgonnettes, pick-up, utilitaires' : 'Vans, pick-ups, utility vehicles',
        'taxi-transport-inspection' => $isFrench ? 'Taxis, bus, autocars' : 'Taxis, buses, coaches',
        'heavy-vehicle-inspection' => $isFrench ? 'Poids lourds, camions, semi-remorques' : 'Heavy goods vehicles, trucks, semi-trailers',
    ];
    $serviceIconFallbacks = collect($servicePortfolio)
        ->mapWithKeys(fn (array $service): array => [$service['title'] => $service['icon']])
        ->all();
    $centreNamesById = collect($publicCentres ?? [])
        ->mapWithKeys(fn ($centre): array => [$centre->id => $centre->shortName])
        ->all();
    $servicePortfolioFromRecords = collect($publishedServices ?? [])
        ->map(function ($service) use ($centreNamesById, $isFrench, $serviceIconFallbacks, $serviceVehicles, $translated): array {
            $title = $translated($service->title, $service->code);
            $centreNames = collect($service->centreIds)
                ->map(fn (int $centreId): ?string => $centreNamesById[$centreId] ?? null)
                ->filter()
                ->values()
                ->all();

            return [
                'icon' => $service->icon ?: ($serviceIconFallbacks[$title] ?? 'service-periodic.svg'),
                'title' => $title,
                'description' => $translated($service->summary, ''),
                'vehicles' => $serviceVehicles[$service->code] ?? ($isFrench ? 'Selon catégorie' : 'By category'),
                'centres' => $centreNames === [] ? 'École de Police · Nomayos' : implode(' · ', $centreNames),
            ];
        })
        ->all();

    if ($servicePortfolioFromRecords !== []) {
        $servicePortfolio = $servicePortfolioFromRecords;
    }

    $proofSection = [
        'overline' => $isFrench ? 'Moyens & exigence' : 'Resources & standards',
        'title' => $isFrench
            ? 'Des services appuyés par une infrastructure réelle.'
            : 'Services backed by real infrastructure.',
        'lead' => $isFrench
            ? 'Des équipements performants, une équipe qualifiée et des procédures rigoureuses pour des contrôles fiables.'
            : 'Reliable equipment, a qualified team and structured procedures for dependable inspections.',
        'image_alt' => $isFrench
            ? 'Voie de contrôle technique G3 avec fosses et équipements spécialisés'
            : 'G3 technical inspection lane with pits and specialist equipment',
        'link' => $isFrench ? 'Comprendre la visite technique' : 'Understand the technical inspection',
    ];

    $proofPoints = [
        [
            'icon' => 'proof-equipment.svg',
            'title' => $isFrench ? 'Équipements spécialisés' : 'Specialised equipment',
            'body' => $isFrench
                ? 'Des outils de contrôle conformes aux normes en vigueur.'
                : 'Inspection tools aligned with applicable standards.',
        ],
        [
            'icon' => 'proof-team.svg',
            'title' => $isFrench ? 'Personnel technique' : 'Technical staff',
            'body' => $isFrench
                ? 'Une équipe qualifiée et expérimentée à votre service.'
                : 'A qualified and experienced team at your service.',
        ],
        [
            'icon' => 'proof-centres.svg',
            'title' => $isFrench ? 'Deux centres à Yaoundé' : 'Two centres in Yaoundé',
            'body' => $isFrench
                ? 'École de Police et Nomayos, facilement accessibles.'
                : 'École de Police and Nomayos, easy to access.',
        ],
        [
            'icon' => 'proof-procedures.svg',
            'title' => $isFrench ? 'Procédures structurées' : 'Structured procedures',
            'body' => $isFrench
                ? 'Des processus clairs pour des résultats fiables et transparents.'
                : 'Clear processes for reliable, transparent results.',
        ],
    ];

    $equipmentRail = $isFrench
        ? ['Banc de freinage', 'Banc de suspension', 'Plaque à ripage', 'Réglophare', 'Analyseur de gaz']
        : ['Brake tester', 'Suspension tester', 'Side-slip plate', 'Headlight tester', 'Gas analyser'];
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-services-overview" aria-labelledby="services-overview-title">
        <div class="g3-services-overview__inner">
            <header class="g3-services-overview__header">
                <div>
                    <p class="g3-services-overview__overline">{{ $overview['overline'] }}</p>
                    <h1 id="services-overview-title">{{ $overview['title'] }}</h1>
                </div>

                <p>{{ $overview['lead'] }}</p>
            </header>

            <div class="g3-services-passport" aria-label="{{ $overview['overline'] }}">
                @foreach ($passportCells as $cell)
                    <article class="g3-services-passport__cell">
                        <img
                            src="{{ asset('images/services/'.$cell['icon']) }}"
                            alt=""
                            aria-hidden="true"
                            class="g3-services-passport__icon"
                        >

                        <h2>{{ $cell['label'] }}</h2>

                        @isset($cell['url'])
                            <a href="{{ $cell['url'] }}" class="g3-services-passport__title">
                                <span>{{ $cell['title'] }}</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h13M12 6l6 6-6 6"/>
                                </svg>
                            </a>
                        @else
                            <p class="g3-services-passport__title">{{ $cell['title'] }}</p>
                        @endisset

                        <p class="g3-services-passport__body">{{ $cell['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="g3-services-catalog" aria-labelledby="services-catalog-title">
        <div class="g3-services-catalog__inner">
            <header class="g3-services-catalog__header">
                <div>
                    <p class="g3-services-catalog__overline">{{ $serviceSection['overline'] }}</p>
                    <h2 id="services-catalog-title">{{ $serviceSection['title'] }}</h2>
                </div>

                <p>{{ $serviceSection['lead'] }}</p>
            </header>

            <div class="g3-services-list" aria-label="{{ $serviceSection['overline'] }}">
                @foreach ($servicePortfolio as $service)
                    <article class="g3-services-list__item">
                        <div class="g3-services-list__icon-wrap">
                            <img
                                src="{{ asset('images/services/'.$service['icon']) }}"
                                alt=""
                                aria-hidden="true"
                                class="g3-services-list__icon"
                            >
                        </div>

                        <div class="g3-services-list__copy">
                            <h3>{{ $service['title'] }}</h3>
                            <p>{{ $service['description'] }}</p>

                            <dl class="g3-services-list__meta">
                                <div>
                                    <dt>{{ $isFrench ? 'Centres' : 'Centres' }}</dt>
                                    <dd>{{ $service['centres'] ?? 'École de Police · Nomayos' }}</dd>
                                </div>
                                <div>
                                    <dt>{{ $isFrench ? 'Véhicules' : 'Vehicles' }}</dt>
                                    <dd>{{ $service['vehicles'] }}</dd>
                                </div>
                            </dl>

                            <a href="{{ $technicalInspectionUrl }}" class="g3-services-list__link">
                                <span>{{ $isFrench ? 'Explorer ce service' : 'Explore this service' }}</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h13M12 6l6 6-6 6"/>
                                </svg>
                            </a>
                        </div>

                        <span class="g3-services-list__chevron" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </article>
                @endforeach
            </div>

            <div class="g3-services-proof" aria-labelledby="services-proof-title">
                <figure class="g3-services-proof__media">
                    <img
                        src="{{ asset('images/services/eco-4.png') }}"
                        alt="{{ $proofSection['image_alt'] }}"
                        class="g3-services-proof__image"
                    >
                </figure>

                <div class="g3-services-proof__content">
                    <p class="g3-services-catalog__overline">{{ $proofSection['overline'] }}</p>
                    <h2 id="services-proof-title">{{ $proofSection['title'] }}</h2>
                    <p class="g3-services-proof__lead">{{ $proofSection['lead'] }}</p>

                    <div class="g3-services-proof__points">
                        @foreach ($proofPoints as $point)
                            <article class="g3-services-proof__point">
                                <img
                                    src="{{ asset('images/services/'.$point['icon']) }}"
                                    alt=""
                                    aria-hidden="true"
                                >
                                <div>
                                    <h3>{{ $point['title'] }}</h3>
                                    <p>{{ $point['body'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <p class="g3-services-proof__rail">
                        @foreach ($equipmentRail as $item)
                            <span>{{ $item }}</span>
                        @endforeach
                    </p>

                    <a href="{{ $technicalInspectionUrl }}" class="g3-services-proof__link">
                        <span>{{ $proofSection['link'] }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h13M12 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
