@php
    $isFrench = $locale === 'fr';

    if (! session()->has('appointment_idempotency')) {
        session(['appointment_idempotency' => (string) \Illuminate\Support\Str::uuid()]);
    }

    $showTracking = session()->has('tracking_result') || $errors->has('tracking');

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
        'morningTime' => $isFrench ? '07h – 13h' : '7:00 AM – 1:00 PM',
        'afternoon' => $isFrench ? 'Après-midi' : 'Afternoon',
        'afternoonTime' => $isFrench ? '13h – 20h' : '1:00 PM – 8:00 PM',
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
    $tariffUnavailable = $isFrench ? 'Tarif non publié' : 'Fee not published';
    $tariffAmountFor = function (string $code) use ($publicTariffCatalogue, $tariffUnavailable): string {
        return collect($publicTariffCatalogue->lines)
            ->first(fn ($line): bool => $line->categoryCode === $code)
            ?->amount ?? $tariffUnavailable;
    };

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

    $centreAssets = [
        'ecole-de-police' => 'ecole-de-police.png',
        'nomayos' => 'nomayos.png',
    ];
    $requestedCentre = $appointmentHandoff['centre'] ?? '';
    $centreKeys = collect($publicCentres ?? [])->map(fn ($centre) => $centre->key);
    $centres = collect($publicCentres ?? [])
        ->values()
        ->map(fn ($centre, $index) => [
            'key' => $centre->key,
            'name' => 'G3 Control '.$centre->shortName,
            'summary' => $centre->shortName,
            'address' => $centre->displayAddress,
            'status' => ($publicLiveStatus ?? [])[$centre->key]['status'] ?? '',
            'isOpen' => (bool) (($publicLiveStatus ?? [])[$centre->key]['isOpen'] ?? false),
            'hours' => ($isFrench ? 'Lun. - Sam. ' : 'Mon. - Sat. ').$centre->weekdayHours,
            'sunday' => ($isFrench ? 'Dim. ' : 'Sun. ').$centre->sundayHours,
            'id' => $centre->id,
            'image' => $centreAssets[$centre->key] ?? 'ecole-de-police.png',
            'active' => filled(old('centre_id'))
                ? (string) $centre->id === (string) old('centre_id')
                : ($centreKeys->contains($requestedCentre)
                    ? $centre->key === $requestedCentre
                    : $index === 0),
        ])
        ->all();

    $serviceOptions = collect($publishedServices ?? [])
        ->map(fn ($service): array => [
            'id' => $service->id,
            'label' => $service->title[$locale] ?? $service->title['fr'] ?? $service->code,
            'centreIds' => implode(',', $service->centreIds),
            'categoryIds' => implode(',', $service->categoryIds),
        ])
        ->all();
    $categoryIds = collect($publishedVehicleCategories ?? [])
        ->mapWithKeys(fn ($category): array => [$category->code => $category->id]);

    $categoryOptions = [
        [
            'id' => $categoryIds['B'] ?? null,
            'value' => 'b',
            'label' => $isFrench ? 'Catégorie B — Voiture particulière' : 'Category B — Private car',
            'summary' => $isFrench ? 'Catégorie B' : 'Category B',
            'tariff' => $tariffAmountFor('B'),
        ],
        [
            'id' => $categoryIds['A'] ?? null,
            'value' => 'a',
            'label' => $isFrench ? 'Catégorie A — Taxi / Auto-école' : 'Category A — Taxi / Driving school',
            'summary' => $isFrench ? 'Catégorie A' : 'Category A',
            'tariff' => $tariffAmountFor('A'),
        ],
        [
            'id' => $categoryIds['B1'] ?? null,
            'value' => 'b1',
            'label' => $isFrench ? 'Catégorie B1 — Utilitaire / Pickup 3,5 T' : 'Category B1 — Utility / 3.5 T pickup',
            'summary' => $isFrench ? 'Catégorie B1' : 'Category B1',
            'tariff' => $tariffAmountFor('B1'),
        ],
        [
            'id' => $categoryIds['C < 3,5T'] ?? null,
            'value' => 'c-mini',
            'label' => $isFrench ? 'Catégorie C < 3,5 T — Mini-bus' : 'Category C < 3.5 T — Minibus',
            'summary' => $isFrench ? 'Catégorie C < 3,5 T' : 'Category C < 3.5 T',
            'tariff' => $tariffAmountFor('C < 3,5T'),
        ],
        [
            'id' => $categoryIds['C'] ?? null,
            'value' => 'c-bus',
            'label' => $isFrench ? 'Catégorie C — Grand bus / Coaster' : 'Category C — Large bus / Coaster',
            'summary' => $isFrench ? 'Catégorie C' : 'Category C',
            'tariff' => $tariffAmountFor('C'),
        ],
        [
            'id' => $categoryIds['D'] ?? null,
            'value' => 'd-heavy',
            'label' => $isFrench ? 'Catégorie D — Poids lourd' : 'Category D — Heavy vehicle',
            'summary' => $isFrench ? 'Catégorie D' : 'Category D',
            'tariff' => $tariffAmountFor('D'),
        ],
        [
            'id' => $categoryIds['D_OTHER'] ?? null,
            'value' => 'd-other',
            'label' => $isFrench ? 'Catégorie D — Autres engins' : 'Category D — Other machinery',
            'summary' => $isFrench ? 'Catégorie D' : 'Category D',
            'tariff' => $tariffAmountFor('D_OTHER'),
        ],
    ];
    $requestedCategory = $appointmentHandoff['category'] ?? '';
    $selectedCategory = collect($categoryOptions)->firstWhere('value', $requestedCategory) ?? $categoryOptions[0];
    $selectedCentre = collect($centres)->firstWhere('active', true) ?? ($centres[0] ?? null);

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
                        class="g3-appointment-hub__mode-button @unless ($showTracking) g3-appointment-hub__mode-button--active @endunless"
                        type="button"
                        role="tab"
                        aria-selected="{{ $showTracking ? 'false' : 'true' }}"
                        aria-controls="appointment-booking-panel"
                        @if ($showTracking) tabindex="-1" @endif
                        data-appointment-mode
                        data-appointment-target="booking"
                    >
                        <img src="{{ asset($assetPath.'icon-calendar.svg') }}" alt="" aria-hidden="true">
                        <span>{{ $copy['bookingTab'] }}</span>
                    </button>

                    <button
                        id="appointment-mode-tracking"
                        class="g3-appointment-hub__mode-button @if ($showTracking) g3-appointment-hub__mode-button--active @endif"
                        type="button"
                        role="tab"
                        aria-selected="{{ $showTracking ? 'true' : 'false' }}"
                        aria-controls="appointment-tracking-panel"
                        @unless ($showTracking) tabindex="-1" @endunless
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
                    @if ($showTracking) hidden @endif
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

                    <form class="g3-express-pass__form" method="POST" action="{{ route($locale.'.appointment.store') }}" data-appointment-form novalidate>
                        @csrf
                        <input type="hidden" name="centre_id" value="{{ old('centre_id', $selectedCentre['id'] ?? '') }}" data-appointment-centre-id data-required-message="{{ __('public.security.appointment_errors.centre_id') }}">
                        <input type="hidden" name="vehicle_category_id" value="{{ old('vehicle_category_id', $selectedCategory['id'] ?? '') }}" data-appointment-category-id data-required-message="{{ __('public.security.appointment_errors.vehicle_category_id') }}">
                        <input type="hidden" name="preferred_period" value="{{ old('preferred_period', 'any') }}" data-appointment-period-value>

                        <p class="g3-form-status" role="status" data-form-status @unless (session('appointment_received')) hidden @endunless>
                            <span data-form-status-message>{{ session('appointment_received.message') }}</span>
                            <strong data-form-status-reference>{{ session('appointment_received.reference') }}</strong>
                        </p>

                        <p class="g3-form-alert" role="alert" data-form-alert @unless ($errors->has('form') || $errors->has('centre_id')) hidden @endunless>
                            <span data-form-alert-message>{{ $errors->first('form') ?: $errors->first('centre_id') }}</span>
                        </p>
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
                                        data-centre-id="{{ $centre['id'] }}"
                                        data-centre-key="{{ $centre['key'] }}"
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
                                            <span @class([
                                                'g3-express-pass__centre-status',
                                                'g3-express-pass__centre-status--closed' => ! $centre['isOpen'],
                                            ])>
                                                <img src="{{ asset($assetPath.'icon-clock-small.svg') }}" alt="" aria-hidden="true">
                                                {{ $centre['status'] }}
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
                                        <select name="service_id" data-appointment-service data-required-message="{{ __('public.security.appointment_errors.service_id') }}" @if ($errors->has('service_id')) aria-invalid="true" @endif required>
                                            @foreach ($serviceOptions as $serviceOption)
                                                <option
                                                    value="{{ $serviceOption['id'] }}"
                                                    data-centre-ids="{{ $serviceOption['centreIds'] }}"
                                                    data-category-ids="{{ $serviceOption['categoryIds'] }}"
                                                    @selected((string) old('service_id') === (string) $serviceOption['id'] || ($loop->first && ! old('service_id')))
                                                >
                                                    {{ $serviceOption['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <img src="{{ asset($assetPath.'icon-chevron.svg') }}" alt="" aria-hidden="true">
                                    </span>
                                    <p class="g3-express-field__error" data-field-error="service_id" @unless ($errors->has('service_id')) hidden @endunless>{{ $errors->first('service_id') }}</p>
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
                                        <select name="vehicle_category" data-appointment-category @if ($errors->has('vehicle_category_id')) aria-invalid="true" @endif required>
                                            @foreach ($categoryOptions as $categoryOption)
                                                <option
                                                    value="{{ $categoryOption['value'] }}"
                                                    data-category-id="{{ $categoryOption['id'] }}"
                                                    data-summary="{{ $categoryOption['summary'] }}"
                                                    data-tariff="{{ $categoryOption['tariff'] }}"
                                                    @selected($categoryOption['value'] === $selectedCategory['value'])
                                                >
                                                    {{ $categoryOption['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <img src="{{ asset($assetPath.'icon-chevron.svg') }}" alt="" aria-hidden="true">
                                    </span>
                                    <p class="g3-express-field__error" data-field-error="vehicle_category_id" @unless ($errors->has('vehicle_category_id')) hidden @endunless>{{ $errors->first('vehicle_category_id') }}</p>
                                </label>

                                <label class="g3-express-field">
                                    <span>{{ $copy['registration'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-plate.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="text"
                                            name="registration"
                                            value="{{ old('registration') }}"
                                            placeholder="{{ $copy['registrationPlaceholder'] }}"
                                            autocomplete="off"
                                            data-required-message="{{ __('public.security.appointment_errors.registration') }}"
                                            @if ($errors->has('registration')) aria-invalid="true" @endif
                                            required
                                        >
                                    </span>
                                    <p class="g3-express-field__error" data-field-error="registration" @unless ($errors->has('registration')) hidden @endunless>{{ $errors->first('registration') }}</p>
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
                                            <input type="hidden" name="preferred_date" value="{{ old('preferred_date') }}" data-appointment-date-value data-required-message="{{ __('public.security.appointment_errors.preferred_date') }}" @if ($errors->has('preferred_date')) aria-invalid="true" @endif required>
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
                                    <p class="g3-express-field__error" data-field-error="preferred_date" @unless ($errors->has('preferred_date')) hidden @endunless>{{ $errors->first('preferred_date') }}</p>
                                </div>

                                <div class="g3-express-period" role="group" aria-label="{{ $copy['period'] }}">
                                    <span>{{ $copy['period'] }}</span>
                                    <div>
                                        <button type="button" data-appointment-period data-period="morning" aria-pressed="{{ old('preferred_period') === 'morning' ? 'true' : 'false' }}" @class(['g3-express-period__active' => old('preferred_period') === 'morning'])>
                                            <img src="{{ asset($assetPath.'icon-sun.svg') }}" alt="" aria-hidden="true">
                                            <strong>{{ $copy['morning'] }}</strong>
                                            <span>{{ $copy['morningTime'] }}</span>
                                        </button>
                                        <button type="button" data-appointment-period data-period="afternoon" aria-pressed="{{ old('preferred_period') === 'afternoon' ? 'true' : 'false' }}" @class(['g3-express-period__active' => old('preferred_period') === 'afternoon'])>
                                            <img src="{{ asset($assetPath.'icon-sun.svg') }}" alt="" aria-hidden="true">
                                            <strong>{{ $copy['afternoon'] }}</strong>
                                            <span>{{ $copy['afternoonTime'] }}</span>
                                        </button>
                                        <button @class(['g3-express-period__active' => old('preferred_period', 'any') === 'any']) type="button" data-appointment-period data-period="any" aria-pressed="{{ old('preferred_period', 'any') === 'any' ? 'true' : 'false' }}">
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
                                            value="{{ old('full_name') }}"
                                            placeholder="{{ $copy['namePlaceholder'] }}"
                                            autocomplete="name"
                                            data-required-message="{{ __('public.security.appointment_errors.full_name') }}"
                                            @if ($errors->has('full_name')) aria-invalid="true" @endif
                                            required
                                        >
                                    </span>
                                    <p class="g3-express-field__error" data-field-error="full_name" @unless ($errors->has('full_name')) hidden @endunless>{{ $errors->first('full_name') }}</p>
                                </label>

                                <label class="g3-express-field">
                                    <span>{{ $copy['phone'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-phone.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="tel"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            placeholder="{{ $copy['phonePlaceholder'] }}"
                                            autocomplete="tel"
                                            data-required-message="{{ __('public.security.appointment_errors.phone') }}"
                                            @if ($errors->has('phone')) aria-invalid="true" @endif
                                            required
                                        >
                                    </span>
                                    <p class="g3-express-field__error" data-field-error="phone" @unless ($errors->has('phone')) hidden @endunless>{{ $errors->first('phone') }}</p>
                                </label>

                                <label class="g3-express-field g3-express-field--wide">
                                    <span>{{ $copy['email'] }}</span>
                                    <span class="g3-express-field__control">
                                        <img src="{{ asset($assetPath.'icon-mail.svg') }}" alt="" aria-hidden="true">
                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="{{ $copy['emailPlaceholder'] }}"
                                            autocomplete="email"
                                            data-invalid-message="{{ __('public.security.appointment_errors.email') }}"
                                            @if ($errors->has('email')) aria-invalid="true" @endif
                                        >
                                    </span>
                                    <p class="g3-express-field__error" data-field-error="email" @unless ($errors->has('email')) hidden @endunless>{{ $errors->first('email') }}</p>
                                </label>
                            </div>
                        </section>

                        <section class="g3-express-summary" aria-label="{{ $copy['selection'] }}">
                            <img src="{{ asset($assetPath.'icon-document.svg') }}" alt="" aria-hidden="true">
                            <div>
                                <h3>{{ $copy['selection'] }}</h3>
                                <p>
                                    <strong data-appointment-summary-centre>{{ $selectedCentre['summary'] ?? '' }}</strong>
                                    <span>·</span>
                                    <span data-appointment-summary-service>{{ $serviceOptions[0]['label'] ?? '' }}</span>
                                    <span>·</span>
                                    <span data-appointment-summary-category>{{ $selectedCategory['summary'] }}</span>
                                </p>
                                <p>{{ $copy['tariff'] }} <strong data-appointment-summary-tariff>{{ $selectedCategory['tariff'] }}</strong></p>
                            </div>
                        </section>

                        <button class="g3-express-pass__submit" type="submit" data-form-error="{{ __('public.security.appointment_errors.form') }}">
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
                    @unless ($showTracking) hidden @endunless
                >
                    <div class="g3-tracking-pass">
                        <p class="g3-tracking-pass__overline">{{ $copy['trackingOverline'] }}</p>
                        <h2>{{ $copy['trackingTitle'] }}</h2>
                        <p>{{ $copy['trackingLead'] }}</p>

                        <form class="g3-tracking-pass__form" method="POST" action="{{ route($locale.'.appointment.track') }}" data-appointment-form novalidate>
                            @csrf

                            <p class="g3-form-status g3-form-banner" role="status" data-form-status @unless (session('tracking_result')) hidden @endunless>
                                <span data-form-status-message>{{ session('tracking_result') ? __('public.security.tracking_found') : '' }}</span>
                                <strong data-form-status-reference>{{ session('tracking_result.reference') }}</strong>
                                <span data-form-status-detail>@if (session('tracking_result')) — {{ session('tracking_result.status') }} — {{ session('tracking_result.centre') }} @endif</span>
                            </p>

                            <p class="g3-form-alert g3-form-banner" role="alert" data-form-alert @unless ($errors->has('tracking')) hidden @endunless>
                                <span data-form-alert-message>{{ $errors->first('tracking') }}</span>
                            </p>
                            <label class="g3-express-field">
                                <span>{{ $copy['reference'] }}</span>
                                <span class="g3-express-field__control">
                                    <img src="{{ asset($assetPath.'icon-document-small.svg') }}" alt="" aria-hidden="true">
                                    <input
                                        type="text"
                                        name="request_reference"
                                        placeholder="{{ $copy['referencePlaceholder'] }}"
                                        value="{{ old('request_reference', session('appointment_received.reference')) }}"
                                        data-required-message="{{ __('public.security.appointment_errors.request_reference') }}"
                                        @if ($errors->has('request_reference')) aria-invalid="true" @endif
                                        required
                                        autocomplete="off"
                                    >
                                </span>
                                <p class="g3-express-field__error" data-field-error="request_reference" @unless ($errors->has('request_reference')) hidden @endunless>{{ $errors->first('request_reference') }}</p>
                            </label>

                            <label class="g3-express-field">
                                <span>{{ $copy['phone'] }}</span>
                                <span class="g3-express-field__control">
                                    <img src="{{ asset($assetPath.'icon-phone.svg') }}" alt="" aria-hidden="true">
                                    <input
                                        type="tel"
                                        name="tracking_phone"
                                        placeholder="{{ $copy['phonePlaceholder'] }}"
                                        value="{{ old('tracking_phone') }}"
                                        data-required-message="{{ __('public.security.appointment_errors.tracking_phone') }}"
                                        @if ($errors->has('tracking_phone')) aria-invalid="true" @endif
                                        required
                                        autocomplete="tel"
                                    >
                                </span>
                                <p class="g3-express-field__error" data-field-error="tracking_phone" @unless ($errors->has('tracking_phone')) hidden @endunless>{{ $errors->first('tracking_phone') }}</p>
                            </label>

                            <button class="g3-tracking-pass__submit" type="submit" data-form-error="{{ __('public.security.appointment_errors.form') }}">
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
