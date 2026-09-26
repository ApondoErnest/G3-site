@php
    use App\Support\PublicNavigation;

    $isFrench = $locale === 'fr';
    $centre = ($publicCentres ?? [])['ecole-de-police'] ?? null;
    $live = ($publicLiveStatus ?? [])['ecole-de-police'] ?? null;
    $liveStatus = $live['status'] ?? null;
    $isOpen = (bool) ($live['isOpen'] ?? false);

    $copy = [
        'overline' => $isFrench ? 'G3 Control — École de Police' : 'G3 Control — École de Police',
        'title' => $isFrench ? 'Votre centre G3 à l’École de Police.' : 'Your G3 centre at École de Police.',
        'lead' => $isFrench
            ? 'Retrouvez notre centre à la descente ancien Texaco, École de Police, pour votre visite technique automobile, avec une ouverture 7j/7 et des horaires adaptés.'
            : 'Visit our centre at the former Texaco descent, École de Police, for your vehicle technical inspection, with 7-day opening and practical hours.',
        'status_title' => $liveStatus ?? ($isFrench ? 'Horaires à confirmer' : 'Hours to confirm'),
        'status_detail' => '',
        'location_label' => $isFrench ? 'Localisation' : 'Location',
        'location' => $centre?->displayAddress ?? '',
        'phone_label' => $isFrench ? 'Téléphone' : 'Phone',
        'phone' => $centre?->phonesDisplayLine ?? '',
        'email_label' => $isFrench ? 'E-mail' : 'Email',
        'emails' => $centre?->emails ?? [],
        'hours_label' => $isFrench ? 'Horaires d’ouverture' : 'Opening hours',
        'weekday_label' => $isFrench ? 'Lundi – Samedi' : 'Monday – Saturday',
        'weekday_hours' => $centre?->weekdayHours ?? '',
        'sunday_label' => $isFrench ? 'Dimanche' : 'Sunday',
        'sunday_hours' => $centre?->sundayHours ?? '',
        'holiday_label' => $isFrench ? 'Jours fériés' : 'Public holidays',
        'holiday_hours' => $centre?->holidayHours ?? '',
        'appointment' => $isFrench ? 'Prendre rendez-vous' : 'Book an appointment',
        'approval' => $isFrench ? 'G3 Control · Agrément N°0291 depuis 2020' : 'G3 Control · Approval No. 0291 since 2020',
        'carousel_label' => $isFrench ? 'Photos du centre École de Police' : 'École de Police centre photos',
        'previous' => $isFrench ? 'Photo précédente' : 'Previous photo',
        'next' => $isFrench ? 'Photo suivante' : 'Next photo',
    ];

    $slides = [
        ['image' => 'eco-1.png', 'alt' => $isFrench ? 'Façade du centre G3 Control École de Police' : 'Facade of the G3 Control École de Police centre'],
        ['image' => 'eco-2.png', 'alt' => $isFrench ? 'Vue extérieure du centre G3 Control École de Police' : 'Exterior view of the G3 Control École de Police centre'],
        ['image' => 'eco-3.png', 'alt' => $isFrench ? 'Zone d’accueil du centre G3 Control École de Police' : 'Reception area of the G3 Control École de Police centre'],
        ['image' => 'eco-4.png', 'alt' => $isFrench ? 'Équipements du centre G3 Control École de Police' : 'Equipment at the G3 Control École de Police centre'],
    ];

    $preparationCopy = [
        'overline' => $isFrench ? 'Préparer ma visite' : 'Prepare my visit',
        'title' => $isFrench ? 'Quelques vérifications avant de venir.' : 'A few checks before you come.',
        'lead' => $isFrench
            ? 'Un centre bien préparé, pour une visite plus simple et plus rapide.'
            : 'A well-prepared visit makes your stop simpler and faster.',
    ];

    $preparationCards = [
        [
            'icon' => 'icon-documents.svg',
            'title' => $isFrench ? 'Préparez vos documents' : 'Prepare your documents',
            'body' => $isFrench
                ? 'Munissez-vous des documents requis pour la visite technique de votre véhicule.'
                : 'Bring the required documents for your vehicle technical inspection.',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'cta' => $isFrench ? 'Voir la liste des documents' : 'See the document list',
        ],
        [
            'icon' => 'icon-vehicle.svg',
            'title' => $isFrench ? 'Préparez votre véhicule' : 'Prepare your vehicle',
            'body' => $isFrench
                ? 'Assurez-vous que votre véhicule est en bon état pour le contrôle technique.'
                : 'Make sure your vehicle is in good condition for the technical inspection.',
            'url' => PublicNavigation::pageUrl('road_safety', $locale),
            'cta' => $isFrench ? 'Nos conseils pratiques' : 'Practical advice',
        ],
        [
            'icon' => 'icon-clock.svg',
            'title' => $isFrench ? 'Choisissez votre moment' : 'Choose your time',
            'hours' => [
                [$copy['weekday_label'], $copy['weekday_hours']],
                [$copy['sunday_label'], $copy['sunday_hours']],
                [$copy['holiday_label'], $copy['holiday_hours']],
            ],
        ],
    ];
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-centre-hero" aria-labelledby="centre-ecole-title">
        <div class="g3-centre-hero__inner">
            <div
                class="g3-centre-hero__carousel"
                data-centre-hero-carousel
                aria-label="{{ $copy['carousel_label'] }}"
            >
                <div class="g3-centre-hero__slides">
                    @foreach ($slides as $slide)
                        <img
                            src="{{ asset('images/centers/ecole-de-police/'.$slide['image']) }}"
                            alt="{{ $slide['alt'] }}"
                            @class([
                                'g3-centre-hero__slide',
                                'g3-centre-hero__slide--active' => $loop->first,
                            ])
                            data-centre-hero-slide
                            @if (! $loop->first) aria-hidden="true" @endif
                            @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                        >
                    @endforeach
                </div>

                <div class="g3-centre-hero__image-copy">
                    <strong>G3 Control — École de Police</strong>
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                        {{ $copy['location'] }}
                    </span>
                </div>

                <div class="g3-centre-hero__carousel-controls">
                    <div class="g3-centre-hero__dots" aria-label="{{ $copy['carousel_label'] }}">
                        @foreach ($slides as $slide)
                            <button
                                type="button"
                                @class([
                                    'g3-centre-hero__dot',
                                    'g3-centre-hero__dot--active' => $loop->first,
                                ])
                                data-centre-hero-dot
                                aria-label="{{ $isFrench ? 'Photo '.($loop->iteration) : 'Photo '.($loop->iteration) }}"
                                @if ($loop->first) aria-current="true" @endif
                            ></button>
                        @endforeach
                    </div>

                    <div class="g3-centre-hero__arrows">
                        <button type="button" data-centre-hero-prev aria-label="{{ $copy['previous'] }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
                            </svg>
                        </button>
                        <button type="button" data-centre-hero-next aria-label="{{ $copy['next'] }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <article class="g3-centre-hero__panel">
                <p class="g3-centre-hero__overline">{{ $copy['overline'] }}</p>

                <h1 id="centre-ecole-title">{{ $copy['title'] }}</h1>

                <p class="g3-centre-hero__lead">{{ $copy['lead'] }}</p>

                <div @class([
                    'g3-centre-hero__status',
                    'g3-centre-hero__status--closed' => ! $isOpen,
                ])>
                    <span class="g3-centre-hero__status-icon" aria-hidden="true">
                        <span></span>
                    </span>
                    <div>
                        <strong>{{ $copy['status_title'] }}</strong>
                        <span>
                            {{ $copy['status_detail'] }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <circle cx="12" cy="12" r="8"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="g3-centre-hero__facts">
                    <div class="g3-centre-hero__fact">
                        <span class="g3-centre-hero__fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"/>
                                <circle cx="12" cy="10" r="2.5"/>
                            </svg>
                        </span>
                        <div>
                            <strong>{{ $copy['location_label'] }}</strong>
                            <span>{{ $copy['location'] }}</span>
                        </div>
                    </div>

                    <div class="g3-centre-hero__fact">
                        <span class="g3-centre-hero__fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L8 9.6a16 16 0 0 0 6.4 6.4l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2Z"/>
                            </svg>
                        </span>
                        <div>
                            <strong>{{ $copy['phone_label'] }}</strong>
                            <span>{{ $copy['phone'] }}</span>
                        </div>
                    </div>

                    @if ($copy['emails'] !== [])
                        <div class="g3-centre-hero__fact">
                            <span class="g3-centre-hero__fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                                </svg>
                            </span>
                            <div>
                                <strong>{{ $copy['email_label'] }}</strong>
                                @foreach ($copy['emails'] as $email)
                                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="g3-centre-hero__fact g3-centre-hero__fact--wide">
                        <span class="g3-centre-hero__fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1">
                                <circle cx="12" cy="12" r="8"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/>
                            </svg>
                        </span>
                        <div>
                            <strong>{{ $copy['hours_label'] }}</strong>
                            <dl>
                                <div>
                                    <dt>{{ $copy['weekday_label'] }}</dt>
                                    <dd>{{ $copy['weekday_hours'] }}</dd>
                                </div>
                                <div>
                                    <dt>{{ $copy['sunday_label'] }}</dt>
                                    <dd>{{ $copy['sunday_hours'] }}</dd>
                                </div>
                                <div>
                                    <dt>{{ $copy['holiday_label'] }}</dt>
                                    <dd>{{ $copy['holiday_hours'] }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <a href="{{ PublicNavigation::pageUrl('appointment', $locale).'?centre=ecole-de-police' }}" class="g3-centre-hero__appointment">
                    <span>{{ $copy['appointment'] }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>

                <p class="g3-centre-hero__approval">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 9.8 4.1-1.4 7-5.3 7-9.8V6l-7-3Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-5"/>
                    </svg>
                    <span>{{ $copy['approval'] }}</span>
                </p>
            </article>
        </div>
    </section>

    <section class="g3-centre-prep" aria-labelledby="centre-prep-title">
        <div class="g3-centre-prep__inner">
            <header class="g3-centre-prep__header">
                <p class="g3-centre-prep__overline">{{ $preparationCopy['overline'] }}</p>
                <h2 id="centre-prep-title">{{ $preparationCopy['title'] }}</h2>
                <p>{{ $preparationCopy['lead'] }}</p>
            </header>

            <div class="g3-centre-prep__cards">
                @foreach ($preparationCards as $card)
                    <article class="g3-centre-prep__card">
                        <img
                            src="{{ asset('images/centers/ecole-de-police/'.$card['icon']) }}"
                            alt=""
                            class="g3-centre-prep__icon"
                            aria-hidden="true"
                        >

                        <div class="g3-centre-prep__card-copy">
                            <h3>{{ $card['title'] }}</h3>

                            @isset($card['body'])
                                <p>{{ $card['body'] }}</p>
                            @endisset

                            @isset($card['url'])
                                <a href="{{ $card['url'] }}" class="g3-centre-prep__link">
                                    <span>{{ $card['cta'] }}</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                            @endisset

                            @isset($card['hours'])
                                <dl class="g3-centre-prep__hours">
                                    @foreach ($card['hours'] as [$label, $value])
                                        <div>
                                            <dt>{{ $label }}</dt>
                                            <dd>{{ $value }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endisset
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
