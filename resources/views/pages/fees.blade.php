@php
    use App\Support\PublicNavigation;

    $isFrench = $locale === 'fr';
    $appointmentUrl = PublicNavigation::pageUrl('appointment', $locale);

    $copy = [
        'overline' => $isFrench ? 'Trouver mon tarif' : 'Find my fee',
        'title' => $isFrench
            ? 'Quel véhicule présentez-vous au contrôle ?'
            : 'Which vehicle are you bringing for inspection?',
        'lead' => $isFrench
            ? 'Sélectionnez le type de votre véhicule pour obtenir le tarif applicable.'
            : 'Select your vehicle type to see the applicable fee.',
        'unknown' => $isFrench ? 'Je ne connais pas ma catégorie' : 'I do not know my category',
        'yourTariff' => $isFrench ? 'Votre tarif' : 'Your fee',
        'vehicleInfo' => $isFrench ? 'Véhicules concernés' : 'Vehicles concerned',
        'validity' => $isFrench ? 'Validité du contrôle' : 'Inspection validity',
        'centres' => $isFrench ? 'Centres concernés' : 'G3 centres',
        'open' => $isFrench ? 'Ouvert actuellement' : 'Currently open',
        'officialFee' => $isFrench ? 'Tarif officiel' : 'Official fee',
        'feeNote' => $isFrench
            ? 'Tarif applicable à la catégorie sélectionnée.'
            : 'Fee applicable to the selected category.',
        'version' => $isFrench ? 'Version tarifaire' : 'Tariff version',
        'effective' => $isFrench
            ? 'En vigueur depuis le 01 juin 2022'
            : 'Effective since 01 June 2022',
        'reference' => $isFrench
            ? 'Référence : tarifs homologués Ministère des Transports'
            : 'Reference: Ministry of Transport approved fees',
        'cta' => $isFrench ? 'Prendre rendez-vous avec ce tarif' : 'Book with this fee',
        'assistantTitle' => $isFrench ? 'Quelques repères rapides' : 'A few quick cues',
        'assistantLead' => $isFrench
            ? 'Choisissez le cas le plus proche de votre véhicule pour afficher la catégorie officielle.'
            : 'Choose the closest case to your vehicle to display the official category.',
    ];

    $profiles = [
        [
            'id' => 'b',
            'code' => 'B',
            'selector' => $isFrench ? 'Voiture particulière' : 'Private car',
            'title' => $isFrench ? 'Catégorie B' : 'Category B',
            'label' => $isFrench ? 'Véhicule de tourisme' : 'Passenger vehicle',
            'image' => 'light-vehicle.png',
            'examples' => $isFrench
                ? 'Voitures particulières, berlines, SUV, 4x4 à usage privé.'
                : 'Private cars, sedans, SUVs and private-use 4x4 vehicles.',
            'plain' => $isFrench
                ? 'Pour les véhicules de tourisme à usage privé.'
                : 'For private passenger vehicles.',
            'amount' => '17 900 FCFA',
            'validity' => $isFrench ? '12 mois' : '12 months',
        ],
        [
            'id' => 'a',
            'code' => 'A',
            'selector' => $isFrench ? 'Taxi / Auto-école' : 'Taxi / Driving school',
            'title' => $isFrench ? 'Catégorie A' : 'Category A',
            'label' => $isFrench ? 'Taxi / Auto-école' : 'Taxi / Driving school',
            'image' => 'taxi.png',
            'examples' => $isFrench
                ? 'Taxis, véhicules d’auto-école et véhicules assimilés.'
                : 'Taxis, driving-school vehicles and similar vehicles.',
            'plain' => $isFrench
                ? 'Pour les véhicules exploités en taxi ou en auto-école.'
                : 'For vehicles used as taxis or driving-school vehicles.',
            'amount' => '4 900 FCFA',
            'validity' => $isFrench ? '03 mois' : '03 months',
        ],
        [
            'id' => 'b1',
            'code' => 'B1',
            'selector' => $isFrench ? 'Utilitaire' : 'Utility vehicle',
            'title' => $isFrench ? 'Catégorie B1' : 'Category B1',
            'label' => $isFrench ? 'Pickup 3,5 T / Véhicule utilitaire léger' : '3.5 T pickup / Light utility vehicle',
            'image' => 'pickup.png',
            'examples' => $isFrench
                ? 'Pickup 3,5 T, fourgonnettes et véhicules utilitaires légers.'
                : '3.5 T pickups, vans and light utility vehicles.',
            'plain' => $isFrench
                ? 'Pour les utilitaires légers et pickups jusqu’à 3,5 T.'
                : 'For light utility vehicles and pickups up to 3.5 T.',
            'amount' => '15 500 FCFA',
            'validity' => $isFrench ? '06 mois' : '06 months',
        ],
        [
            'id' => 'c-mini',
            'code' => 'C < 3,5T',
            'selector' => $isFrench ? 'Mini-bus' : 'Minibus',
            'title' => $isFrench ? 'Catégorie C < 3,5T' : 'Category C < 3.5T',
            'label' => $isFrench ? 'Mini-bus' : 'Minibus',
            'image' => 'mini bus.png',
            'examples' => $isFrench
                ? 'Mini-bus et transport de personnes de moins de 3,5 T.'
                : 'Minibuses and passenger transport vehicles under 3.5 T.',
            'plain' => $isFrench
                ? 'Pour les mini-bus et petits véhicules de transport de personnes.'
                : 'For minibuses and smaller passenger transport vehicles.',
            'amount' => '15 500 FCFA',
            'validity' => $isFrench ? '03 mois' : '03 months',
        ],
        [
            'id' => 'c-bus',
            'code' => 'C',
            'selector' => $isFrench ? 'Grand bus' : 'Large bus',
            'title' => $isFrench ? 'Catégorie C' : 'Category C',
            'label' => $isFrench ? 'Grand bus / Coaster' : 'Large bus / Coaster',
            'image' => 'large bus.png',
            'examples' => $isFrench
                ? 'Grands bus, coaster et véhicules de transport de personnes.'
                : 'Large buses, coasters and passenger transport vehicles.',
            'plain' => $isFrench
                ? 'Pour les grands véhicules de transport de personnes.'
                : 'For large passenger transport vehicles.',
            'amount' => '19 080 FCFA',
            'validity' => $isFrench ? '03 mois' : '03 months',
        ],
        [
            'id' => 'd-heavy',
            'code' => 'D',
            'selector' => $isFrench ? 'Poids lourd' : 'Heavy vehicle',
            'title' => $isFrench ? 'Catégorie D' : 'Category D',
            'label' => $isFrench ? 'Poids lourd' : 'Heavy vehicle',
            'image' => 'heavy vehicle.png',
            'examples' => $isFrench
                ? 'Camions, tracteurs, semi-remorques et utilitaires lourds.'
                : 'Trucks, tractors, semi-trailers and heavy utility vehicles.',
            'plain' => $isFrench
                ? 'Pour les camions et grands véhicules professionnels.'
                : 'For trucks and large professional vehicles.',
            'amount' => '26 235 FCFA',
            'validity' => $isFrench ? '06 mois' : '06 months',
        ],
        [
            'id' => 'd-other',
            'code' => 'D',
            'selector' => $isFrench ? 'Autre catégorie' : 'Other category',
            'title' => $isFrench ? 'Catégorie D' : 'Category D',
            'label' => $isFrench ? 'Autres engins' : 'Other machinery',
            'image' => 'other-machinery.png',
            'examples' => $isFrench
                ? 'Engins spéciaux et véhicules ne relevant pas des profils courants.'
                : 'Special machinery and vehicles outside the common profiles.',
            'plain' => $isFrench
                ? 'Pour les engins spéciaux à orienter selon la catégorie officielle.'
                : 'For special machinery to classify by official category.',
            'amount' => '41 750 FCFA',
            'validity' => $isFrench ? '12 mois' : '12 months',
        ],
    ];

    $assistantOptions = [
        [
            'target' => 'b',
            'label' => $isFrench ? 'Usage privé' : 'Private use',
            'body' => $isFrench ? 'Voiture personnelle, berline, SUV ou 4x4.' : 'Personal car, sedan, SUV or 4x4.',
        ],
        [
            'target' => 'a',
            'label' => $isFrench ? 'Taxi ou auto-école' : 'Taxi or driving school',
            'body' => $isFrench ? 'Véhicule utilisé pour taxi ou apprentissage.' : 'Vehicle used for taxi or driver training.',
        ],
        [
            'target' => 'b1',
            'label' => $isFrench ? 'Utilitaire léger' : 'Light utility',
            'body' => $isFrench ? 'Pickup 3,5 T, fourgonnette ou utilitaire léger.' : '3.5 T pickup, van or light utility vehicle.',
        ],
        [
            'target' => 'c-mini',
            'label' => $isFrench ? 'Transport de personnes' : 'Passenger transport',
            'body' => $isFrench ? 'Mini-bus, grand bus ou coaster selon le gabarit.' : 'Minibus, large bus or coaster depending on size.',
        ],
        [
            'target' => 'd-heavy',
            'label' => $isFrench ? 'Poids lourd' : 'Heavy vehicle',
            'body' => $isFrench ? 'Camion, tracteur, semi-remorque ou utilitaire lourd.' : 'Truck, tractor, semi-trailer or heavy utility vehicle.',
        ],
        [
            'target' => 'd-other',
            'label' => $isFrench ? 'Engin spécial' : 'Special machinery',
            'body' => $isFrench ? 'Engin ou véhicule hors profils courants.' : 'Machinery or vehicle outside common profiles.',
        ],
    ];

    $integritySection = [
        'overline' => $isFrench ? 'À savoir sur les tarifs' : 'About the fees',
        'title' => $isFrench
            ? 'Des informations claires pour mieux comprendre.'
            : 'Clear information to understand your fee.',
        'items' => [
            [
                'icon' => 'tariff-rules.svg',
                'title' => $isFrench ? 'Pourquoi le tarif varie-t-il ?' : 'Why does the fee vary?',
                'body' => $isFrench
                    ? 'Le tarif dépend de la catégorie officielle de votre véhicule, de son usage et des règles en vigueur.'
                    : 'The fee depends on the official vehicle category, its use and the applicable rules.',
            ],
            [
                'icon' => 'tariff-calendar.svg',
                'title' => $isFrench ? 'Que signifie la validité ?' : 'What does validity mean?',
                'body' => $isFrench
                    ? 'La validité correspond à la durée pendant laquelle votre certificat de visite technique est valable après le contrôle.'
                    : 'Validity is the period during which your technical inspection certificate remains valid after inspection.',
            ],
            [
                'icon' => 'tariff-version.svg',
                'title' => $isFrench ? 'Comment savoir si le tarif est à jour ?' : 'How do I know the fee is current?',
                'body' => $isFrench
                    ? 'Les tarifs affichés correspondent à la version actuellement publiée par le MINT.'
                    : 'The displayed fees correspond to the tariff version currently published by MINT.',
                'note' => $isFrench
                    ? 'Dernière mise à jour identifiée : 01 juin 2022.'
                    : 'Last identified update: 01 June 2022.',
            ],
        ],
    ];
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-tariff-finder" aria-labelledby="tariff-finder-title" data-tariff-navigator>
        <div class="g3-tariff-finder__inner">
            <div class="g3-tariff-finder__header">
                <div>
                    <p class="g3-tariff-finder__overline">{{ $copy['overline'] }}</p>
                    <h1 id="tariff-finder-title">{{ $copy['title'] }}</h1>
                    <p>{{ $copy['lead'] }}</p>
                </div>

                <button class="g3-tariff-finder__unknown" type="button" data-tariff-assistant-toggle aria-expanded="false">
                    <img src="{{ asset('images/tariffs/tariff-help.svg') }}" alt="" aria-hidden="true">
                    <span>{{ $copy['unknown'] }}</span>
                    <span aria-hidden="true">→</span>
                </button>
            </div>

            <div class="g3-tariff-finder__profiles" role="tablist" aria-label="{{ $copy['title'] }}">
                @foreach ($profiles as $index => $profile)
                    <button
                        id="tariff-profile-{{ $profile['id'] }}"
                        class="g3-tariff-finder__profile @if ($index === 0) g3-tariff-finder__profile--active @endif"
                        type="button"
                        role="tab"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-controls="tariff-passport-{{ $profile['id'] }}"
                        tabindex="{{ $index === 0 ? '0' : '-1' }}"
                        data-tariff-profile
                        data-tariff-target="{{ $profile['id'] }}"
                    >
                        <span class="g3-tariff-finder__profile-check" aria-hidden="true">
                            <img src="{{ asset('images/tariffs/tariff-check.svg') }}" alt="">
                        </span>
                        <img class="g3-tariff-finder__profile-image" src="{{ asset('images/tariffs/'.$profile['image']) }}" alt="">
                        <span>{{ $profile['selector'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="g3-tariff-finder__assistant" data-tariff-assistant hidden>
                <div>
                    <h2>{{ $copy['assistantTitle'] }}</h2>
                    <p>{{ $copy['assistantLead'] }}</p>
                </div>

                <div class="g3-tariff-finder__assistant-options">
                    @foreach ($assistantOptions as $option)
                        <button type="button" data-tariff-assistant-select data-tariff-target="{{ $option['target'] }}">
                            <strong>{{ $option['label'] }}</strong>
                            <span>{{ $option['body'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="g3-tariff-finder__passports">
                @foreach ($profiles as $index => $profile)
                    @php
                        $bookingUrl = $appointmentUrl.'?category='.rawurlencode($profile['id']).'&tariff_category='.rawurlencode($profile['code']);
                    @endphp

                    <article
                        id="tariff-passport-{{ $profile['id'] }}"
                        class="g3-tariff-passport"
                        role="tabpanel"
                        aria-labelledby="tariff-profile-{{ $profile['id'] }}"
                        data-tariff-passport
                        data-tariff-key="{{ $profile['id'] }}"
                        @if ($index !== 0) hidden @endif
                    >
                        <div class="g3-tariff-passport__body">
                            <div class="g3-tariff-passport__identity">
                                <span>{{ $copy['yourTariff'] }}</span>
                                <h2>{{ $profile['title'] }}</h2>
                                <p>{{ $profile['label'] }}</p>
                                <img src="{{ asset('images/tariffs/'.$profile['image']) }}" alt="">
                            </div>

                            <div class="g3-tariff-passport__details">
                                <div class="g3-tariff-passport__fact">
                                    <img src="{{ asset('images/tariffs/tariff-vehicle.svg') }}" alt="" aria-hidden="true">
                                    <div>
                                        <h3>{{ $copy['vehicleInfo'] }}</h3>
                                        <p>{{ $profile['examples'] }}</p>
                                    </div>
                                </div>

                                <div class="g3-tariff-passport__fact">
                                    <img src="{{ asset('images/tariffs/tariff-calendar.svg') }}" alt="" aria-hidden="true">
                                    <div>
                                        <h3>{{ $copy['validity'] }}</h3>
                                        <p><strong>{{ $profile['validity'] }}</strong></p>
                                    </div>
                                </div>

                                <div class="g3-tariff-passport__fact">
                                    <img src="{{ asset('images/tariffs/tariff-location.svg') }}" alt="" aria-hidden="true">
                                    <div>
                                        <h3>{{ $copy['centres'] }}</h3>
                                        <ul>
                                            <li><strong>École de Police</strong><span>{{ $copy['open'] }}</span></li>
                                            <li><strong>Nomayos</strong><span>{{ $copy['open'] }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="g3-tariff-passport__price">
                                <div class="g3-tariff-passport__price-heading">
                                    <img src="{{ asset('images/tariffs/tariff-coins.svg') }}" alt="" aria-hidden="true">
                                    <span>{{ $copy['officialFee'] }}</span>
                                </div>
                                <strong>{{ $profile['amount'] }}</strong>
                                <p>{{ $copy['feeNote'] }}</p>

                                <div class="g3-tariff-passport__version">
                                    <img src="{{ asset('images/tariffs/tariff-version.svg') }}" alt="" aria-hidden="true">
                                    <div>
                                        <h3>{{ $copy['version'] }}</h3>
                                        <p>{{ $copy['effective'] }}</p>
                                        <p>{{ $copy['reference'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="g3-tariff-passport__band" aria-hidden="true"></div>

                        <a href="{{ $bookingUrl }}" class="g3-tariff-passport__cta" data-tariff-appointment-link>
                            <img src="{{ asset('images/tariffs/tariff-appointment.svg') }}" alt="" aria-hidden="true">
                            <span>{{ $copy['cta'] }}</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="g3-tariff-integrity" aria-labelledby="tariff-integrity-title">
        <div class="g3-tariff-integrity__inner">
            <div class="g3-tariff-integrity__header">
                <p class="g3-tariff-integrity__overline">{{ $integritySection['overline'] }}</p>
                <h2 id="tariff-integrity-title">{{ $integritySection['title'] }}</h2>
            </div>

            <div class="g3-tariff-integrity__grid">
                @foreach ($integritySection['items'] as $item)
                    <article class="g3-tariff-integrity__item">
                        <img src="{{ asset('images/tariffs/'.$item['icon']) }}" alt="" aria-hidden="true">
                        <div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['body'] }}</p>

                            @isset($item['note'])
                                <p class="g3-tariff-integrity__note">{{ $item['note'] }}</p>
                            @endisset
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
