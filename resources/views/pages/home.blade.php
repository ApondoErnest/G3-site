@php
    use App\Support\PublicNavigation;

    $heroSlides = [
        'hero-1.png',
        'hero-2.png',
        'hero-3.png',
        'hero-4.png',
        'hero-5.png',
    ];

    $heroValues = [
        [
            'label' => __('public.home.hero.values.safety.label'),
            'body' => __('public.home.hero.values.safety.body'),
            'icon' => 'shield',
        ],
        [
            'label' => __('public.home.hero.values.simplicity.label'),
            'body' => __('public.home.hero.values.simplicity.body'),
            'icon' => 'settings',
        ],
        [
            'label' => __('public.home.hero.values.trust.label'),
            'body' => __('public.home.hero.values.trust.body'),
            'icon' => 'heart',
        ],
    ];

    $heroCentres = $homeHeroCentres ?? [];

    $quickActions = [
        [
            'number' => '01',
            'label' => __('public.home.start.actions.appointment'),
            'icon' => 'icon-calendar.svg',
            'url' => PublicNavigation::pageUrl('appointment', $locale),
            'active' => true,
        ],
        [
            'number' => '02',
            'label' => __('public.home.start.actions.track'),
            'icon' => 'icon-document-search.svg',
            'url' => PublicNavigation::pageUrl('appointment', $locale),
            'active' => false,
        ],
        [
            'number' => '03',
            'label' => __('public.home.start.actions.fees'),
            'icon' => 'icon-tag.svg',
            'url' => PublicNavigation::pageUrl('fees', $locale),
            'active' => false,
        ],
        [
            'number' => '04',
            'label' => __('public.home.start.actions.centre'),
            'icon' => 'icon-map-pin.svg',
            'url' => PublicNavigation::pageUrl('centres', $locale),
            'active' => false,
        ],
        [
            'number' => '05',
            'label' => __('public.home.start.actions.prepare'),
            'icon' => 'icon-clipboard-check.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => false,
        ],
    ];

    $journeySteps = [
        [
            'number' => '01',
            'label' => __('public.home.start.journey.prepare.label'),
            'body' => __('public.home.start.journey.prepare.body'),
            'icon' => 'icon-documents.svg',
            'active' => true,
        ],
        [
            'number' => '02',
            'label' => __('public.home.start.journey.arrive.label'),
            'body' => __('public.home.start.journey.arrive.body'),
            'icon' => 'icon-car.svg',
            'active' => false,
        ],
        [
            'number' => '03',
            'label' => __('public.home.start.journey.inspect.label'),
            'body' => __('public.home.start.journey.inspect.body'),
            'icon' => 'icon-gears.svg',
            'active' => false,
        ],
        [
            'number' => '04',
            'label' => __('public.home.start.journey.analyse.label'),
            'body' => __('public.home.start.journey.analyse.body'),
            'icon' => 'icon-report-pin.svg',
            'active' => false,
        ],
        [
            'number' => '05',
            'label' => __('public.home.start.journey.leave.label'),
            'body' => __('public.home.start.journey.leave.body'),
            'icon' => 'icon-check.svg',
            'active' => false,
        ],
    ];

    $controlChecks = [
        [
            'label' => __('public.home.control.checks.braking.label'),
            'body' => __('public.home.control.checks.braking.body'),
            'icon' => 'icon-braking.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => true,
        ],
        [
            'label' => __('public.home.control.checks.suspension.label'),
            'body' => __('public.home.control.checks.suspension.body'),
            'icon' => 'icon-suspension.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => false,
        ],
        [
            'label' => __('public.home.control.checks.alignment.label'),
            'body' => __('public.home.control.checks.alignment.body'),
            'icon' => 'icon-alignment.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => false,
        ],
        [
            'label' => __('public.home.control.checks.lighting.label'),
            'body' => __('public.home.control.checks.lighting.body'),
            'icon' => 'icon-lighting.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => false,
        ],
        [
            'label' => __('public.home.control.checks.pollution.label'),
            'body' => __('public.home.control.checks.pollution.body'),
            'icon' => 'icon-pollution.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => false,
        ],
        [
            'label' => __('public.home.control.checks.visual.label'),
            'body' => __('public.home.control.checks.visual.body'),
            'icon' => 'icon-visual-check.svg',
            'url' => PublicNavigation::pageUrl('technical_inspection', $locale),
            'active' => false,
        ],
    ];

    $activeControlCheck = $controlChecks[0];

    $equipmentItems = [
        [
            'title' => __('public.home.equipment.items.brakes.title'),
            'body' => __('public.home.equipment.items.brakes.body'),
            'image' => 'brakes.png',
        ],
        [
            'title' => __('public.home.equipment.items.suspension.title'),
            'body' => __('public.home.equipment.items.suspension.body'),
            'image' => 'suspension.png',
        ],
        [
            'title' => __('public.home.equipment.items.ripage.title'),
            'body' => __('public.home.equipment.items.ripage.body'),
            'image' => 'ripage.png',
        ],
        [
            'title' => __('public.home.equipment.items.headlamp.title'),
            'body' => __('public.home.equipment.items.headlamp.body'),
            'image' => 'headlamp-tester.png',
        ],
        [
            'title' => __('public.home.equipment.items.gas.title'),
            'body' => __('public.home.equipment.items.gas.body'),
            'image' => 'gas-analyser.png',
        ],
        [
            'title' => __('public.home.equipment.items.plays.title'),
            'body' => __('public.home.equipment.items.plays.body'),
            'image' => 'plays.png',
        ],
        [
            'title' => __('public.home.equipment.items.pit.title'),
            'body' => __('public.home.equipment.items.pit.body'),
            'image' => 'pit.png',
        ],
        [
            'title' => __('public.home.equipment.items.sonometre.title'),
            'body' => __('public.home.equipment.items.sonometre.body'),
            'image' => 'sonometre.png',
        ],
        [
            'title' => __('public.home.equipment.items.air_compressor.title'),
            'body' => __('public.home.equipment.items.air_compressor.body'),
            'image' => 'air-compressor.png',
        ],
        [
            'title' => __('public.home.equipment.items.opacimeter.title'),
            'body' => __('public.home.equipment.items.opacimeter.body'),
            'image' => 'opacimeter.png',
        ],
    ];

    $centreCards = [
        [
            'key' => 'ecole_de_police',
            'title' => __('public.home.centres.items.ecole_de_police.title'),
            'address' => __('public.home.centres.items.ecole_de_police.address'),
            'hours' => __('public.home.centres.items.ecole_de_police.hours'),
            'phone' => __('public.home.centres.items.ecole_de_police.phone'),
            'phone_href' => '+237687187516',
            'image' => 'ecole-de-police.png',
            'url' => PublicNavigation::pageUrl('centre_ecole_de_police', $locale),
        ],
        [
            'key' => 'nomayos',
            'title' => __('public.home.centres.items.nomayos.title'),
            'address' => __('public.home.centres.items.nomayos.address'),
            'hours' => __('public.home.centres.items.nomayos.hours'),
            'phone' => __('public.home.centres.items.nomayos.phone'),
            'phone_href' => '+237653100801',
            'image' => 'nomayos.png',
            'url' => PublicNavigation::pageUrl('centre_nomayos', $locale),
        ],
    ];

    $centresGoogleMapsUrl = 'https://www.google.com/maps/dir/?api=1&origin=3.8786152,11.5116814&destination=3.7902275,11.4439448&travelmode=driving';
    $centresGoogleMapsEmbedUrl = 'https://maps.google.com/maps?f=d&source=s_d&saddr=3.8786152,11.5116814&daddr=3.7902275,11.4439448&hl=fr&z=12&output=embed';

    $roadSafetyItems = [
        [
            'key' => 'braking',
            'title' => __('public.home.road_safety.items.braking.title'),
            'body' => __('public.home.road_safety.items.braking.body'),
        ],
        [
            'key' => 'tyres',
            'title' => __('public.home.road_safety.items.tyres.title'),
            'body' => __('public.home.road_safety.items.tyres.body'),
        ],
        [
            'key' => 'visibility',
            'title' => __('public.home.road_safety.items.visibility.title'),
            'body' => __('public.home.road_safety.items.visibility.body'),
        ],
    ];

@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-home-hero" aria-labelledby="home-hero-title">
        <div class="g3-home-hero__carousel" aria-hidden="true">
            @foreach ($heroSlides as $slide)
                <img
                    src="{{ asset('images/homepage/'.$slide) }}"
                    alt=""
                    class="g3-home-hero__slide"
                    style="--slide-index: {{ $loop->index }}"
                    @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                >
            @endforeach
        </div>

        <div class="g3-home-hero__scrim" aria-hidden="true"></div>

        <div class="g3-home-hero__content">
            <p class="g3-home-hero__overline">{{ __('public.home.hero.overline') }}</p>

            <h1 id="home-hero-title" class="g3-home-hero__title">
                {{ __('public.home.hero.title') }}
            </h1>

            <p class="g3-home-hero__lead">
                {{ __('public.home.hero.lead') }}
            </p>

            <div class="g3-home-hero__values" aria-label="{{ __('public.home.hero.values_label') }}">
                @foreach ($heroValues as $value)
                    <div class="g3-home-hero__value">
                        <span class="g3-home-hero__value-icon" aria-hidden="true">
                            @if ($value['icon'] === 'shield')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 9.8 4.1-1.4 7-5.3 7-9.8V6l-7-3Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-5"/>
                                </svg>
                            @elseif ($value['icon'] === 'settings')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.08A1.7 1.7 0 0 0 9 19.37a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.08A1.7 1.7 0 0 0 4.63 9a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.08A1.7 1.7 0 0 0 15 4.63a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.14.34.48.58.85.58H21a2 2 0 1 1 0 4h-.08A1.7 1.7 0 0 0 19.4 15Z"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12.6 12 20l-7.5-7.4a5 5 0 0 1 7.1-7.1l.4.4.4-.4a5 5 0 0 1 7.1 7.1Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-5"/>
                                </svg>
                            @endif
                        </span>
                        <span>
                            <strong>{{ $value['label'] }}</strong>
                            <span>{{ $value['body'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>

            <a href="{{ PublicNavigation::pageUrl('fees', $locale) }}" class="g3-home-hero__fees-link">
                <span>{{ __('public.home.hero.fees_cta') }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </a>

            <aside class="g3-home-hero__live" aria-label="{{ __('public.home.hero.live.label') }}">
                <div class="g3-home-hero__live-head">
                    <span class="g3-home-hero__live-title">
                        <span class="g3-home-hero__live-dot" aria-hidden="true"></span>
                        {{ __('public.home.hero.live.title') }}
                    </span>
                    <a href="{{ PublicNavigation::pageUrl('centres', $locale) }}" class="g3-home-hero__live-link">
                        <span class="sr-only">{{ __('public.home.hero.live.link') }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <p class="g3-home-hero__live-subtitle">
                    {{ __('public.home.hero.live.subtitle', ['count' => count($heroCentres)]) }}
                </p>

                <ul class="g3-home-hero__live-list">
                    @foreach ($heroCentres as $centre)
                        <li>
                            <span class="g3-home-hero__centre-name">
                                <span class="g3-home-hero__centre-icon" aria-hidden="true"></span>
                                {{ $centre['name'] }}
                            </span>
                            <span @class([
                                'g3-home-hero__centre-status',
                                'g3-home-hero__centre-status--closed' => ! $centre['isOpen'],
                            ])>
                                {{ $centre['status'] }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </section>

    <section class="g3-home-start" aria-labelledby="home-start-title">
        <div class="g3-home-start__inner">
            <header class="g3-home-start__header">
                <h2 id="home-start-title">{{ __('public.home.start.title') }}</h2>
                <p>{{ __('public.home.start.subtitle') }}</p>
            </header>

            <div class="g3-home-start__actions" aria-label="{{ __('public.home.start.actions_label') }}">
                @foreach ($quickActions as $action)
                    <a
                        href="{{ $action['url'] }}"
                        @class([
                            'g3-home-start__action',
                            'g3-home-start__action--active' => $action['active'],
                        ])
                    >
                        <span
                            class="g3-home-start__action-icon"
                            style="--g3-icon: url('{{ asset('images/homepage/'.$action['icon']) }}')"
                            aria-hidden="true"
                        ></span>
                        <span class="g3-home-start__action-copy">
                            <span class="g3-home-start__action-number">{{ $action['number'] }}</span>
                            <span class="g3-home-start__action-label">{{ $action['label'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="g3-home-start__segments" aria-hidden="true">
                @foreach ($quickActions as $action)
                    <span @class(['is-active' => $action['active']])></span>
                @endforeach
            </div>

            <section class="g3-home-start__journey" aria-labelledby="home-journey-title">
                <h3 id="home-journey-title">{{ __('public.home.start.journey_title') }}</h3>

                <ol class="g3-home-start__timeline">
                    @foreach ($journeySteps as $step)
                        <li @class(['g3-home-start__step', 'g3-home-start__step--active' => $step['active']])>
                            <div class="g3-home-start__step-icon-wrap">
                                <span
                                    class="g3-home-start__step-icon"
                                    style="--g3-icon: url('{{ asset('images/homepage/'.$step['icon']) }}')"
                                    aria-hidden="true"
                                ></span>
                            </div>
                            <div class="g3-home-start__step-copy">
                                <span class="g3-home-start__step-number">{{ $step['number'] }}</span>
                                <strong>{{ $step['label'] }}</strong>
                                <span>{{ $step['body'] }}</span>
                            </div>
                        </li>

                        @if (! $loop->last)
                            <li class="g3-home-start__arrow" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h15M13 6l6 6-6 6"/>
                                </svg>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </section>
        </div>
    </section>

    <section class="g3-home-control" aria-labelledby="home-control-title" data-home-control>
        <div class="g3-home-control__media" aria-hidden="true">
            <img
                src="{{ asset('images/homepage/g3-control.png') }}"
                alt=""
                class="g3-home-control__image"
                loading="lazy"
            >
        </div>
        <div class="g3-home-control__scrim" aria-hidden="true"></div>

        <div class="g3-home-control__inner">
            <header class="g3-home-control__header">
                <p>{{ __('public.home.control.overline') }}</p>
                <h2 id="home-control-title">{{ __('public.home.control.title') }}</h2>
                <span>{{ __('public.home.control.lead') }}</span>
            </header>

            <div class="g3-home-control__body">
                <div class="g3-home-control__tabs" role="tablist" aria-label="{{ __('public.home.control.tabs_label') }}">
                    @foreach ($controlChecks as $check)
                        <button
                            type="button"
                            id="home-control-tab-{{ $loop->index }}"
                            @class([
                                'g3-home-control__tab',
                                'g3-home-control__tab--active' => $check['active'],
                            ])
                            role="tab"
                            aria-selected="{{ $check['active'] ? 'true' : 'false' }}"
                            aria-controls="home-control-panel"
                            data-control-tab
                            data-control-title="{{ $check['label'] }}"
                            data-control-body="{{ $check['body'] }}"
                            data-control-icon="{{ asset('images/homepage/'.$check['icon']) }}"
                        >
                            <span>{{ $check['label'] }}</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h12M13 6l6 6-6 6"/>
                            </svg>
                        </button>
                    @endforeach
                </div>

                <article
                    id="home-control-panel"
                    class="g3-home-control__card"
                    role="tabpanel"
                    aria-labelledby="home-control-tab-0"
                    data-control-panel
                >
                    <span
                        class="g3-home-control__card-icon"
                        style="--g3-icon: url('{{ asset('images/homepage/'.$activeControlCheck['icon']) }}')"
                        data-control-card-icon
                        aria-hidden="true"
                    ></span>

                    <div class="g3-home-control__card-copy">
                        <h3 data-control-card-title>{{ $activeControlCheck['label'] }}</h3>
                        <p data-control-card-body>{{ $activeControlCheck['body'] }}</p>

                        <a href="{{ $activeControlCheck['url'] }}" class="g3-home-control__link">
                            <span>{{ __('public.home.control.learn_more') }}</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h12M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="g3-home-equipment" aria-labelledby="home-equipment-title" data-equipment-carousel>
        <div class="g3-home-equipment__inner">
            <header class="g3-home-equipment__header">
                <h2 id="home-equipment-title">{{ __('public.home.equipment.title') }}</h2>

                <div class="g3-home-equipment__controls" aria-label="{{ __('public.home.equipment.controls_label') }}">
                    <button type="button" class="g3-home-equipment__control" data-equipment-prev hidden disabled aria-disabled="true">
                        <span class="sr-only">{{ __('public.home.equipment.previous') }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 5-7 7 7 7"/>
                        </svg>
                    </button>
                    <button type="button" class="g3-home-equipment__control" data-equipment-next>
                        <span class="sr-only">{{ __('public.home.equipment.next') }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </header>

            <div class="g3-home-equipment__shell">
                <div class="g3-home-equipment__viewport" data-equipment-viewport>
                    <div class="g3-home-equipment__page" data-equipment-page>
                        @foreach ($equipmentItems as $item)
                            <article
                                class="g3-home-equipment__card"
                                data-equipment-card
                                aria-hidden="{{ $loop->iteration > 5 ? 'true' : 'false' }}"
                                @if ($loop->iteration > 5) hidden @endif
                            >
                                <img
                                    src="{{ asset('images/homepage/'.$item['image']) }}"
                                    alt=""
                                    class="g3-home-equipment__image"
                                    loading="lazy"
                                >

                                <div class="g3-home-equipment__copy">
                                    <h3>{{ $item['title'] }}</h3>
                                    <p>{{ $item['body'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="g3-home-equipment__float g3-home-equipment__float--prev" data-equipment-prev hidden disabled aria-disabled="true">
                    <span class="sr-only">{{ __('public.home.equipment.previous') }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 5-7 7 7 7"/>
                    </svg>
                </button>
                <button type="button" class="g3-home-equipment__float g3-home-equipment__float--next" data-equipment-next>
                    <span class="sr-only">{{ __('public.home.equipment.next') }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <section class="g3-home-centres" aria-labelledby="home-centres-title">
        <div class="g3-home-centres__inner">
            <p class="g3-home-centres__overline">{{ __('public.home.centres.overline') }}</p>
            <h2 id="home-centres-title">{{ __('public.home.centres.title') }}</h2>

            <div class="g3-home-centres__grid">
                @foreach ($centreCards as $centre)
                    <article class="g3-home-centres__card">
                        <div class="g3-home-centres__media">
                            <img
                                src="{{ asset('images/homepage/'.$centre['image']) }}"
                                alt=""
                                class="g3-home-centres__image"
                                loading="lazy"
                            >

                            <span class="g3-home-centres__status">
                                <span class="g3-home-centres__status-dot" aria-hidden="true"></span>
                                {{ __('public.home.centres.status_open') }}
                            </span>
                        </div>

                        <div class="g3-home-centres__body">
                            <div class="g3-home-centres__title-row">
                                <h3>{{ $centre['title'] }}</h3>
                                <a href="{{ $centre['url'] }}" aria-label="{{ __('public.home.centres.actions.details') }} — {{ $centre['title'] }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h12M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                            </div>

                            <dl class="g3-home-centres__details">
                                <div>
                                    <dt>
                                        <span class="sr-only">{{ __('public.home.centres.actions.directions') }}</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"/>
                                            <circle cx="12" cy="10" r="2.5"/>
                                        </svg>
                                    </dt>
                                    <dd>{{ $centre['address'] }}</dd>
                                </div>

                                <div>
                                    <dt>
                                        <span class="sr-only">{{ __('public.home.centres.today', ['hours' => $centre['hours']]) }}</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <circle cx="12" cy="12" r="8"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/>
                                        </svg>
                                    </dt>
                                    <dd>
                                        <span>{{ __('public.home.centres.today', ['hours' => '']) }}</span>
                                        <strong>{{ $centre['hours'] }}</strong>
                                    </dd>
                                </div>

                                <div>
                                    <dt>
                                        <span class="sr-only">{{ __('public.home.centres.actions.call') }}</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L8 9.6a16 16 0 0 0 6.4 6.4l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2Z"/>
                                        </svg>
                                    </dt>
                                    <dd>
                                        <a href="tel:{{ $centre['phone_href'] }}">{{ $centre['phone'] }}</a>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="g3-home-centres__actions">
                            <a href="{{ $centre['url'] }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 10.5 1.8 1.8 3.4-4"/>
                                </svg>
                                <span>{{ __('public.home.centres.actions.details') }}</span>
                            </a>
                        </div>
                    </article>
                @endforeach

                <aside class="g3-home-centres__map-card" aria-label="{{ __('public.home.centres.map_title') }}">
                    <h3>{{ __('public.home.centres.map_title') }}</h3>

                    <div class="g3-home-centres__map">
                        <iframe
                            src="{{ $centresGoogleMapsEmbedUrl }}"
                            title="{{ __('public.home.centres.map_title') }}"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>

                    <div class="g3-home-centres__map-action">
                        <a href="{{ $centresGoogleMapsUrl }}" target="_blank" rel="noopener">
                            <span class="g3-home-centres__map-action-icon" aria-hidden="true"></span>
                            <span>{{ __('public.home.centres.map_cta') }}</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h12M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="g3-home-road-safety" aria-labelledby="home-road-safety-title">
        <div class="g3-home-road-safety__media" aria-hidden="true">
            <img
                src="{{ asset('images/homepage/securite-routiere.png') }}"
                alt=""
                width="1448"
                height="1086"
                class="g3-home-road-safety__image"
                loading="lazy"
            >
        </div>

        <div class="g3-home-road-safety__content">
            <p class="g3-home-road-safety__overline">{{ __('public.home.road_safety.overline') }}</p>
            <h2 id="home-road-safety-title">{{ __('public.home.road_safety.title') }}</h2>
            <p class="g3-home-road-safety__lead">{{ __('public.home.road_safety.lead') }}</p>

            <div class="g3-home-road-safety__items" aria-label="{{ __('public.home.road_safety.items_label') }}">
                @foreach ($roadSafetyItems as $item)
                    <article class="g3-home-road-safety__item">
                        <span class="g3-home-road-safety__icon" aria-hidden="true">
                            @if ($item['key'] === 'braking')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12a7 7 0 1 1 14 0"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 16.5 5 19M16.5 16.5 19 19M12 12l3.4-3.4"/>
                                    <circle cx="12" cy="12" r="2"/>
                                </svg>
                            @elseif ($item['key'] === 'tyres')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="7"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path stroke-linecap="round" d="M12 5v3M12 16v3M5 12h3M16 12h3"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16c2-3 4.7-4.5 8-4.5S18 13 20 16"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16h10l-1.6 3H8.6L7 16Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 11.5 7 9M15.5 11.5 17 9"/>
                                </svg>
                            @endif
                        </span>
                        <span class="g3-home-road-safety__item-copy">
                            <strong>{{ $item['title'] }}</strong>
                            <span>{{ $item['body'] }}</span>
                        </span>
                    </article>
                @endforeach
            </div>

            <a href="{{ PublicNavigation::pageUrl('road_safety', $locale) }}" class="g3-home-road-safety__cta">
                <span>{{ __('public.home.road_safety.cta') }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h12M13 6l6 6-6 6"/>
                </svg>
            </a>
        </div>
    </section>
@endsection
