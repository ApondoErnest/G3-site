@php
    $isFrench = $locale === 'fr';

    $assetPath = 'images/appointment-and-tracking/';

    $copy = [
        'overline' => $isFrench ? 'Rendez-vous & Suivi' : 'Appointment & tracking',
        'title' => $isFrench ? 'Votre visite technique, simplement.' : 'Your technical inspection, made simple.',
        'lead' => $isFrench
            ? "Demandez votre rendez-vous ou suivez l'état de votre demande en quelques instants."
            : 'Request your appointment or check its status in a few moments.',
        'bookingTab' => $isFrench ? 'Demander un rendez-vous' : 'Request an appointment',
        'trackingTab' => $isFrench ? 'Suivre ma demande' : 'Track my request',
        'expressOverline' => $isFrench ? 'G3 Express Pass' : 'G3 Express Pass',
        'bookingTitle' => $isFrench ? 'Préparez votre rendez-vous' : 'Prepare your appointment',
        'bookingLead' => $isFrench
            ? 'Renseignez les informations essentielles à votre visite technique.'
            : 'Share the essential information for your technical inspection.',
        'badge' => $isFrench ? 'Rapide. Sécurisé.' : 'Fast. Secure.',
        'badgeNote' => $isFrench ? 'Sans paiement en ligne.' : 'No online payment.',
        'centreStep' => $isFrench ? 'Votre centre et prestation' : 'Your centre and service',
        'centreLead' => $isFrench
            ? 'Choisissez le centre où vous souhaitez effectuer votre visite et la prestation souhaitée.'
            : 'Choose the centre where you want to complete your visit and the requested service.',
        'serviceLabel' => $isFrench ? 'Prestation souhaitée' : 'Requested service',
        'service' => $isFrench ? 'Visite technique périodique' : 'Periodic technical inspection',
        'serviceNote' => $isFrench
            ? 'Les prestations disponibles peuvent varier selon le centre sélectionné.'
            : 'Available services may vary depending on the selected centre.',
        'vehicleStep' => $isFrench ? 'Votre véhicule et disponibilité' : 'Your vehicle and availability',
        'vehicleLead' => $isFrench
            ? 'Indiquez les informations de votre véhicule et la date de votre préférence.'
            : 'Add your vehicle information and preferred appointment date.',
        'vehicleCategory' => $isFrench ? 'Catégorie du véhicule' : 'Vehicle category',
        'category' => $isFrench ? 'Catégorie B — Voiture particulière' : 'Category B — Private car',
        'registration' => $isFrench ? 'Numéro d’immatriculation' : 'Registration number',
        'registrationPlaceholder' => $isFrench ? 'Ex : LT 123 AB' : 'E.g. LT 123 AB',
        'date' => $isFrench ? 'Date souhaitée' : 'Preferred date',
        'datePlaceholder' => $isFrench ? 'Sélectionner une date' : 'Select a date',
        'period' => $isFrench ? 'Période souhaitée' : 'Preferred period',
        'morning' => $isFrench ? 'Matin' : 'Morning',
        'morningTime' => '07h – 13h',
        'afternoon' => $isFrench ? 'Après-midi' : 'Afternoon',
        'afternoonTime' => '13h – 20h',
        'flexible' => $isFrench ? 'Je suis flexible' : 'I am flexible',
        'dateNote' => $isFrench
            ? 'La date et la période sélectionnées constituent votre préférence. Votre rendez-vous sera confirmé par notre équipe.'
            : 'The selected date and period are your preference. Your appointment will be confirmed by our team.',
        'contactStep' => $isFrench ? 'Vos coordonnées' : 'Your contact details',
        'contactLead' => $isFrench ? 'Comment pouvons-nous vous contacter ?' : 'How can we contact you?',
        'name' => $isFrench ? 'Nom et prénom' : 'Full name',
        'namePlaceholder' => $isFrench ? 'Ex : Jean Dupont' : 'E.g. John Doe',
        'phone' => $isFrench ? 'Numéro WhatsApp / Téléphone' : 'WhatsApp / phone number',
        'phonePlaceholder' => $isFrench ? 'Ex : 6XX XXX XXX' : 'E.g. 6XX XXX XXX',
        'email' => $isFrench ? 'Adresse e-mail (optionnel)' : 'Email address (optional)',
        'emailPlaceholder' => $isFrench ? 'Ex : jeandupont@email.com' : 'E.g. johndoe@email.com',
        'selection' => $isFrench ? 'Votre sélection' : 'Your selection',
        'tariff' => $isFrench ? 'Tarif indicatif :' : 'Indicative fee:',
        'submit' => $isFrench ? 'Envoyer ma demande' : 'Send my request',
        'secure' => $isFrench
            ? 'Vos informations sont sécurisées et traitées de manière confidentielle.'
            : 'Your information is secure and handled confidentially.',
        'trackingOverline' => $isFrench ? 'Suivre ma demande' : 'Track my request',
        'trackingTitle' => $isFrench
            ? "Retrouvez l’état de votre demande en quelques secondes."
            : 'Find your request status in seconds.',
        'trackingLead' => $isFrench
            ? 'Renseignez votre référence de demande et votre numéro de téléphone pour consulter son statut.'
            : 'Enter your request reference and phone number to check the status.',
        'reference' => $isFrench ? 'Référence de demande' : 'Request reference',
        'referencePlaceholder' => $isFrench ? 'Ex : G3-8942' : 'E.g. G3-8942',
        'trackingSubmit' => $isFrench ? 'Suivre ma demande' : 'Track my request',
        'trackingSecure' => $isFrench
            ? 'Aucun compte requis. Suivi via votre référence et votre numéro.'
            : 'No account required. Tracking uses your reference and number.',
        'statusExamples' => $isFrench ? 'Exemples de statuts de demande' : 'Request status examples',
    ];

    $benefits = [
        [
            'icon' => 'icon-shield.svg',
            'title' => $isFrench ? '2 centres à Yaoundé' : '2 centres in Yaoundé',
            'body' => $isFrench ? 'École de Police · Nomayos' : 'École de Police · Nomayos',
        ],
        [
            'icon' => 'icon-clock.svg',
            'title' => $isFrench ? 'Ouverts 7j/7' : 'Open 7 days',
            'body' => $isFrench ? 'Horaires adaptés' : 'Convenient hours',
        ],
        [
            'icon' => 'icon-users.svg',
            'title' => $isFrench ? 'Confirmation par notre équipe' : 'Confirmed by our team',
            'body' => $isFrench ? 'Service professionnel' : 'Professional service',
        ],
    ];

    $centres = [
        [
            'key' => 'ecole-de-police',
            'name' => $isFrench ? 'G3 Control École de Police' : 'G3 Control École de Police',
            'summary' => $isFrench ? 'École de Police' : 'École de Police',
            'address' => $isFrench ? 'Descente ancien Texaco, Yaoundé' : 'Descente ancien Texaco, Yaoundé',
            'hours' => $isFrench ? 'Lun. – Sam. 07h – 20h' : 'Mon. – Sat. 07h – 20h',
            'sunday' => $isFrench ? 'Dim. 07h – 15h' : 'Sun. 07h – 15h',
            'image' => 'ecole-de-police.png',
            'active' => true,
        ],
        [
            'key' => 'nomayos',
            'name' => $isFrench ? 'G3 Control Nomayos' : 'G3 Control Nomayos',
            'summary' => $isFrench ? 'Nomayos' : 'Nomayos',
            'address' => $isFrench ? 'Carrefour Nomayos, Yaoundé' : 'Carrefour Nomayos, Yaoundé',
            'hours' => $isFrench ? 'Lun. – Sam. 07h – 19h' : 'Mon. – Sat. 07h – 19h',
            'sunday' => $isFrench ? 'Dim. 07h – 15h' : 'Sun. 07h – 15h',
            'image' => 'nomayos.png',
            'active' => false,
        ],
    ];

    $serviceOptions = [
        [
            'value' => 'periodic',
            'label' => $isFrench ? 'Visite technique périodique' : 'Periodic technical inspection',
        ],
        [
            'value' => 'counter-visit',
            'label' => $isFrench ? 'Contre-visite' : 'Counter-visit',
        ],
        [
            'value' => 'light-vehicle',
            'label' => $isFrench ? 'Contrôle des véhicules légers' : 'Light vehicle inspection',
        ],
        [
            'value' => 'utility',
            'label' => $isFrench ? 'Contrôle des utilitaires' : 'Utility vehicle inspection',
        ],
        [
            'value' => 'transport',
            'label' => $isFrench ? 'Contrôle taxis & transport' : 'Taxi and transport inspection',
        ],
        [
            'value' => 'heavy',
            'label' => $isFrench ? 'Contrôle des poids lourds' : 'Heavy vehicle inspection',
        ],
    ];

    $categoryOptions = [
        [
            'value' => 'b',
            'label' => $isFrench ? 'Catégorie B — Voiture particulière' : 'Category B — Private car',
            'summary' => $isFrench ? 'Catégorie B' : 'Category B',
            'tariff' => '17 900 FCFA',
        ],
        [
            'value' => 'a',
            'label' => $isFrench ? 'Catégorie A — Taxi / Auto-école' : 'Category A — Taxi / Driving school',
            'summary' => $isFrench ? 'Catégorie A' : 'Category A',
            'tariff' => '4 900 FCFA',
        ],
        [
            'value' => 'b1',
            'label' => $isFrench ? 'Catégorie B1 — Utilitaire / Pickup 3,5 T' : 'Category B1 — Utility / 3.5 T pickup',
            'summary' => $isFrench ? 'Catégorie B1' : 'Category B1',
            'tariff' => '15 500 FCFA',
        ],
        [
            'value' => 'c-mini',
            'label' => $isFrench ? 'Catégorie C < 3,5 T — Mini-bus' : 'Category C < 3.5 T — Minibus',
            'summary' => $isFrench ? 'Catégorie C < 3,5 T' : 'Category C < 3.5 T',
            'tariff' => '15 500 FCFA',
        ],
        [
            'value' => 'c-bus',
            'label' => $isFrench ? 'Catégorie C — Grand bus / Coaster' : 'Category C — Large bus / Coaster',
            'summary' => $isFrench ? 'Catégorie C' : 'Category C',
            'tariff' => '19 080 FCFA',
        ],
        [
            'value' => 'd-heavy',
            'label' => $isFrench ? 'Catégorie D — Poids lourd' : 'Category D — Heavy vehicle',
            'summary' => $isFrench ? 'Catégorie D' : 'Category D',
            'tariff' => '26 235 FCFA',
        ],
        [
            'value' => 'd-other',
            'label' => $isFrench ? 'Catégorie D — Autres engins' : 'Category D — Other machinery',
            'summary' => $isFrench ? 'Catégorie D' : 'Category D',
            'tariff' => '41 750 FCFA',
        ],
    ];

    $minAppointmentDate = now()->toDateString();

    $statusCards = [
        [
            'type' => 'processing',
            'icon' => 'icon-clock.svg',
            'title' => $isFrench ? 'Demande en cours de traitement' : 'Request under review',
            'body' => $isFrench ? 'Équipe G3 en cours de validation.' : 'G3 team is validating it.',
        ],
        [
            'type' => 'confirmed',
            'icon' => 'icon-check.svg',
            'title' => $isFrench ? 'Rendez-vous confirmé' : 'Appointment confirmed',
            'body' => $isFrench ? 'Votre visite est planifiée.' : 'Your visit is scheduled.',
        ],
        [
            'type' => 'action',
            'icon' => 'icon-alert.svg',
            'title' => $isFrench ? 'Action requise' : 'Action required',
            'body' => $isFrench ? 'Des informations complémentaires sont nécessaires.' : 'Additional information is required.',
        ],
    ];
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-appointment-hub" aria-labelledby="appointment-hub-title" data-appointment-hub>
        <div class="g3-appointment-hub__inner">
            <header class="g3-appointment-hub__header">
                <p class="g3-appointment-hub__overline">{{ $copy['overline'] }}</p>
                <h1 id="appointment-hub-title">{{ $copy['title'] }}</h1>
                <p>{{ $copy['lead'] }}</p>

                <div class="g3-appointment-hub__mode" role="tablist" aria-label="{{ $copy['overline'] }}">
                    <button
                        id="appointment-mode-booking"
                        class="g3-appointment-hub__mode-button g3-appointment-hub__mode-button--active"
                        type="button"
                        role="tab"
                        aria-selected="true"
                        aria-controls="appointment-booking-panel"
                        data-appointment-mode
                        data-appointment-target="booking"
                    >
                        <img src="{{ asset($assetPath.'icon-calendar.svg') }}" alt="" aria-hidden="true">
                        <span>{{ $copy['bookingTab'] }}</span>
                    </button>

                    <button
                        id="appointment-mode-tracking"
                        class="g3-appointment-hub__mode-button"
                        type="button"
                        role="tab"
                        aria-selected="false"
                        aria-controls="appointment-tracking-panel"
                        tabindex="-1"
                        data-appointment-mode
                        data-appointment-target="tracking"
                    >
                        <img src="{{ asset($assetPath.'icon-search.svg') }}" alt="" aria-hidden="true">
                        <span>{{ $copy['trackingTab'] }}</span>
                    </button>
                </div>
            </header>

            <div class="g3-appointment-hub__benefits" data-appointment-benefits>
                @foreach ($benefits as $benefit)
                    <article>
                        <img src="{{ asset($assetPath.$benefit['icon']) }}" alt="" aria-hidden="true">
                        <div>
                            <h2>{{ $benefit['title'] }}</h2>
                            <p>{{ $benefit['body'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="g3-express-pass">
                <section
                    id="appointment-booking-panel"
                    role="tabpanel"
                    aria-labelledby="appointment-mode-booking"
                    data-appointment-panel
                    data-appointment-panel-key="booking"
                >
                    <div class="g3-express-pass__head">
                        <div>
                            <p>{{ $copy['expressOverline'] }}</p>
                            <h2>{{ $copy['bookingTitle'] }}</h2>
                            <span>{{ $copy['bookingLead'] }}</span>
                        </div>

                        <aside class="g3-express-pass__badge" aria-label="{{ $copy['badge'].' '.$copy['badgeNote'] }}">
                            <img src="{{ asset($assetPath.'icon-car.svg') }}" alt="" aria-hidden="true">
                            <strong>{{ $copy['badge'] }}</strong>
                            <span>{{ $copy['badgeNote'] }}</span>
                        </aside>
                    </div>

                    <form class="g3-express-pass__form">
                        <section class="g3-express-pass__step">
                            <div class="g3-express-pass__step-head">
                                <span>1</span>
                                <div>
                                    <h3>{{ $copy['centreStep'] }}</h3>
                                    <p>{{ $copy['centreLead'] }}</p>
                                </div>
                            </div>

                            <div class="g3-express-pass__centres" role="group" aria-label="{{ $copy['centreStep'] }}">
                                @foreach ($centres as $centre)
                                    <button
                                        class="g3-express-pass__centre @if ($centre['active']) g3-express-pass__centre--active @endif"
                                        type="button"
                                        data-appointment-centre
                                        data-centre-label="{{ $centre['summary'] }}"
                                        aria-pressed="{{ $centre['active'] ? 'true' : 'false' }}"
                                    >
                                        <img class="g3-express-pass__centre-photo" src="{{ asset($assetPath.$centre['image']) }}" alt="">
                                        <span class="g3-express-pass__centre-copy">
                                            <strong>{{ $centre['name'] }}</strong>
                                            <span>
                                                <img src="{{ asset($assetPath.'icon-pin.svg') }}" alt="" aria-hidden="true">
                                                {{ $centre['address'] }}
                                            </span>
                                            <span>
                                                <img src="{{ asset($assetPath.'icon-clock-small.svg') }}" alt="" aria-hidden="true">
                                                {{ $centre['hours'] }}<br>{{ $centre['sunday'] }}
                                            </span>
                                        </span>
                                        <span class="g3-express-pass__centre-check" aria-hidden="true">
                                            <img src="{{ asset($assetPath.'icon-check.svg') }}" alt="">
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            <div class="g3-express-pass__service-row">
                                <label class="g3-express-field">
                                    <span>{{ $copy['serviceLabel'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-service.svg') }}" alt="" aria-hidden="true">
                                        <select name="service" data-appointment-service required>
                                            @foreach ($serviceOptions as $serviceOption)
                                                <option value="{{ $serviceOption['value'] }}" @selected($loop->first)>
                                                    {{ $serviceOption['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <img src="{{ asset($assetPath.'icon-chevron.svg') }}" alt="" aria-hidden="true">
                                    </span>
                                </label>

                                <p class="g3-express-pass__info">
                                    <img src="{{ asset($assetPath.'icon-info.svg') }}" alt="" aria-hidden="true">
                                    <span>{{ $copy['serviceNote'] }}</span>
                                </p>
                            </div>
                        </section>

                        <section class="g3-express-pass__step">
                            <div class="g3-express-pass__step-head">
                                <span>2</span>
                                <div>
                                    <h3>{{ $copy['vehicleStep'] }}</h3>
                                    <p>{{ $copy['vehicleLead'] }}</p>
                                </div>
                            </div>

                            <div class="g3-express-pass__grid">
                                <label class="g3-express-field">
                                    <span>{{ $copy['vehicleCategory'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-car-small.svg') }}" alt="" aria-hidden="true">
                                        <select name="vehicle_category" data-appointment-category required>
                                            @foreach ($categoryOptions as $categoryOption)
                                                <option
                                                    value="{{ $categoryOption['value'] }}"
                                                    data-summary="{{ $categoryOption['summary'] }}"
                                                    data-tariff="{{ $categoryOption['tariff'] }}"
                                                    @selected($loop->first)
                                                >
                                                    {{ $categoryOption['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <img src="{{ asset($assetPath.'icon-chevron.svg') }}" alt="" aria-hidden="true">
                                    </span>
                                </label>

                                <label class="g3-express-field">
                                    <span>{{ $copy['registration'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-plate.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="text"
                                            name="registration_number"
                                            placeholder="{{ $copy['registrationPlaceholder'] }}"
                                            autocomplete="off"
                                            required
                                        >
                                    </span>
                                </label>

                                <div class="g3-express-field">
                                    <span id="appointment-date-label">{{ $copy['date'] }}</span>
                                    <div
                                        class="g3-appointment-date"
                                        data-appointment-date-picker
                                        data-locale="{{ $locale }}"
                                        data-min-date="{{ $minAppointmentDate }}"
                                        data-empty-label="{{ $copy['datePlaceholder'] }}"
                                    >
                                        <span class="g3-express-field__control g3-express-field__control--date">
                                            <img src="{{ asset($assetPath.'icon-calendar.svg') }}" alt="" aria-hidden="true">
                                            <button
                                                class="g3-appointment-date__trigger"
                                                type="button"
                                                aria-haspopup="dialog"
                                                aria-expanded="false"
                                                aria-labelledby="appointment-date-label"
                                                data-appointment-date-trigger
                                            >
                                                <span data-appointment-date-display>{{ $copy['datePlaceholder'] }}</span>
                                            </button>
                                            <input type="hidden" name="preferred_date" data-appointment-date-value>
                                        </span>

                                        <div
                                            class="g3-appointment-calendar"
                                            role="dialog"
                                            aria-label="{{ $copy['date'] }}"
                                            data-appointment-calendar
                                            hidden
                                        >
                                            <div class="g3-appointment-calendar__head">
                                                <button type="button" data-appointment-calendar-prev aria-label="{{ $isFrench ? 'Mois précédent' : 'Previous month' }}">‹</button>
                                                <strong data-appointment-calendar-title></strong>
                                                <button type="button" data-appointment-calendar-next aria-label="{{ $isFrench ? 'Mois suivant' : 'Next month' }}">›</button>
                                            </div>

                                            <div class="g3-appointment-calendar__weekdays" aria-hidden="true">
                                                @foreach (($isFrench ? ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) as $weekday)
                                                    <span>{{ $weekday }}</span>
                                                @endforeach
                                            </div>

                                            <div class="g3-appointment-calendar__grid" data-appointment-calendar-grid></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="g3-express-period" role="group" aria-label="{{ $copy['period'] }}">
                                    <span>{{ $copy['period'] }}</span>
                                    <div>
                                        <button type="button" data-appointment-period aria-pressed="false">
                                            <img src="{{ asset($assetPath.'icon-sun.svg') }}" alt="" aria-hidden="true">
                                            <strong>{{ $copy['morning'] }}</strong>
                                            <span>{{ $copy['morningTime'] }}</span>
                                        </button>
                                        <button type="button" data-appointment-period aria-pressed="false">
                                            <img src="{{ asset($assetPath.'icon-sun.svg') }}" alt="" aria-hidden="true">
                                            <strong>{{ $copy['afternoon'] }}</strong>
                                            <span>{{ $copy['afternoonTime'] }}</span>
                                        </button>
                                        <button class="g3-express-period__active" type="button" data-appointment-period aria-pressed="true">
                                            <img src="{{ asset($assetPath.'icon-clock-small.svg') }}" alt="" aria-hidden="true">
                                            <strong>{{ $copy['flexible'] }}</strong>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <p class="g3-express-pass__info g3-express-pass__info--full">
                                <img src="{{ asset($assetPath.'icon-info.svg') }}" alt="" aria-hidden="true">
                                <span>{{ $copy['dateNote'] }}</span>
                            </p>
                        </section>

                        <section class="g3-express-pass__step">
                            <div class="g3-express-pass__step-head">
                                <span>3</span>
                                <div>
                                    <h3>{{ $copy['contactStep'] }}</h3>
                                    <p>{{ $copy['contactLead'] }}</p>
                                </div>
                            </div>

                            <div class="g3-express-pass__grid">
                                <label class="g3-express-field">
                                    <span>{{ $copy['name'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-user.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="text"
                                            name="full_name"
                                            placeholder="{{ $copy['namePlaceholder'] }}"
                                            autocomplete="name"
                                            required
                                        >
                                    </span>
                                </label>

                                <label class="g3-express-field">
                                    <span>{{ $copy['phone'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-phone.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="tel"
                                            name="phone"
                                            placeholder="{{ $copy['phonePlaceholder'] }}"
                                            autocomplete="tel"
                                            required
                                        >
                                    </span>
                                </label>

                                <label class="g3-express-field g3-express-field--wide">
                                    <span>{{ $copy['email'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-mail.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="email"
                                            name="email"
                                            placeholder="{{ $copy['emailPlaceholder'] }}"
                                            autocomplete="email"
                                        >
                                    </span>
                                </label>
                            </div>
                        </section>

                        <section class="g3-express-summary" aria-label="{{ $copy['selection'] }}">
                            <img src="{{ asset($assetPath.'icon-document.svg') }}" alt="" aria-hidden="true">
                            <div>
                                <h3>{{ $copy['selection'] }}</h3>
                                <p>
                                    <strong data-appointment-summary-centre>{{ $centres[0]['summary'] }}</strong>
                                    <span>·</span>
                                    <span data-appointment-summary-service>{{ $serviceOptions[0]['label'] }}</span>
                                    <span>·</span>
                                    <span data-appointment-summary-category>{{ $categoryOptions[0]['summary'] }}</span>
                                </p>
                                <p>{{ $copy['tariff'] }} <strong data-appointment-summary-tariff>{{ $categoryOptions[0]['tariff'] }}</strong></p>
                            </div>
                        </section>

                        <button class="g3-express-pass__submit" type="button">
                            <img src="{{ asset($assetPath.'icon-send.svg') }}" alt="" aria-hidden="true">
                            <span>{{ $copy['submit'] }}</span>
                            <span aria-hidden="true">→</span>
                        </button>

                        <p class="g3-express-pass__secure">
                            <img src="{{ asset($assetPath.'icon-lock.svg') }}" alt="" aria-hidden="true">
                            <span>{{ $copy['secure'] }}</span>
                        </p>
                    </form>
                </section>

                <section
                    id="appointment-tracking-panel"
                    role="tabpanel"
                    aria-labelledby="appointment-mode-tracking"
                    data-appointment-panel
                    data-appointment-panel-key="tracking"
                    hidden
                >
                    <div class="g3-tracking-pass">
                        <p class="g3-tracking-pass__overline">{{ $copy['trackingOverline'] }}</p>
                        <h2>{{ $copy['trackingTitle'] }}</h2>
                        <p>{{ $copy['trackingLead'] }}</p>

                        <form class="g3-tracking-pass__form">
                            <label class="g3-express-field">
                                <span>{{ $copy['reference'] }}</span>
                                <span class="g3-express-field__control">
                                    <img src="{{ asset($assetPath.'icon-document-small.svg') }}" alt="" aria-hidden="true">
                                    <input
                                        type="text"
                                        name="request_reference"
                                        placeholder="{{ $copy['referencePlaceholder'] }}"
                                        autocomplete="off"
                                    >
                                </span>
                            </label>

                            <label class="g3-express-field">
                                <span>{{ $copy['phone'] }}</span>
                                <span class="g3-express-field__control">
                                    <img src="{{ asset($assetPath.'icon-phone.svg') }}" alt="" aria-hidden="true">
                                    <input
                                        type="tel"
                                        name="tracking_phone"
                                        placeholder="{{ $copy['phonePlaceholder'] }}"
                                        autocomplete="tel"
                                    >
                                </span>
                            </label>

                            <button class="g3-tracking-pass__submit" type="button">
                                <span>{{ $copy['trackingSubmit'] }}</span>
                                <span aria-hidden="true">→</span>
                            </button>
                        </form>

                        <p class="g3-tracking-pass__secure">
                            <img src="{{ asset($assetPath.'icon-lock.svg') }}" alt="" aria-hidden="true">
                            <span>{{ $copy['trackingSecure'] }}</span>
                        </p>

                        <div class="g3-tracking-pass__divider" aria-hidden="true"></div>

                        <section class="g3-tracking-statuses" aria-label="{{ $copy['statusExamples'] }}">
                            <h3>{{ $copy['statusExamples'] }}</h3>

                            <div>
                                @foreach ($statusCards as $status)
                                    <article class="g3-tracking-statuses__card g3-tracking-statuses__card--{{ $status['type'] }}">
                                        <img src="{{ asset($assetPath.$status['icon']) }}" alt="" aria-hidden="true">
                                        <div>
                                            <h4>{{ $status['title'] }}</h4>
                                            <p>{{ $status['body'] }}</p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
