@php
    use App\Support\PublicNavigation;

    $centreItems = [
        [
            'key' => 'ecole-de-police',
            'short_name' => __('public.centres_page.items.ecole_de_police.short_name'),
            'title' => __('public.centres_page.items.ecole_de_police.title'),
            'address' => __('public.centres_page.items.ecole_de_police.address'),
            'phone' => __('public.centres_page.items.ecole_de_police.phone'),
            'phone_href' => '+237687187516',
            'close_time' => '20h00',
            'weekday_hours' => __('public.centres_page.items.ecole_de_police.weekday_hours'),
            'sunday_hours' => __('public.centres_page.items.ecole_de_police.sunday_hours'),
            'image' => 'ecole-de-police.png',
            'image_alt' => __('public.centres_page.items.ecole_de_police.image_alt'),
            'details_url' => PublicNavigation::pageUrl('centre_ecole_de_police', $locale),
            'appointment_url' => PublicNavigation::pageUrl('appointment', $locale).'?centre=ecole-de-police',
            'directions_url' => 'https://www.google.com/maps/dir/?api=1&destination=3.8786152,11.5116814&travelmode=driving',
            'marker_variant' => 'orange',
            'marker_position' => 'ecole',
        ],
        [
            'key' => 'nomayos',
            'short_name' => __('public.centres_page.items.nomayos.short_name'),
            'title' => __('public.centres_page.items.nomayos.title'),
            'address' => __('public.centres_page.items.nomayos.address'),
            'phone' => __('public.centres_page.items.nomayos.phone'),
            'phone_href' => '+237653100801',
            'close_time' => '19h00',
            'weekday_hours' => __('public.centres_page.items.nomayos.weekday_hours'),
            'sunday_hours' => __('public.centres_page.items.nomayos.sunday_hours'),
            'image' => 'nomayos.png',
            'image_alt' => __('public.centres_page.items.nomayos.image_alt'),
            'details_url' => PublicNavigation::pageUrl('centre_nomayos', $locale),
            'appointment_url' => PublicNavigation::pageUrl('appointment', $locale).'?centre=nomayos',
            'directions_url' => 'https://www.google.com/maps/dir/?api=1&destination=3.7902275,11.4439448&travelmode=driving',
            'marker_variant' => 'blue',
            'marker_position' => 'nomayos',
        ],
    ];

    $standardItems = [
        [
            'title' => __('public.centres_page.standard.items.approval.title'),
            'body' => __('public.centres_page.standard.items.approval.body'),
            'icon' => 'icon-standard-approval.svg',
        ],
        [
            'title' => __('public.centres_page.standard.items.procedures.title'),
            'body' => __('public.centres_page.standard.items.procedures.body'),
            'icon' => 'icon-standard-procedures.svg',
        ],
        [
            'title' => __('public.centres_page.standard.items.equipment.title'),
            'body' => __('public.centres_page.standard.items.equipment.body'),
            'icon' => 'icon-standard-equipment.svg',
        ],
        [
            'title' => __('public.centres_page.standard.items.team.title'),
            'body' => __('public.centres_page.standard.items.team.body'),
            'icon' => 'icon-standard-team.svg',
        ],
    ];

    $centresGoogleMapsUrl = 'https://www.google.com/maps/dir/?api=1&origin=3.8786152,11.5116814&destination=3.7902275,11.4439448&travelmode=driving';
    $centresGoogleMapsEmbedUrl = 'https://maps.google.com/maps?q=Yaound%C3%A9%2C%20Cameroon&z=12&output=embed';
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-centres-live" aria-labelledby="centres-live-title" data-centres-live>
        <div class="g3-centres-live__inner">
            <div class="g3-centres-live__layout">
                <div class="g3-centres-live__left">
                    <header class="g3-centres-live__header">
                        <p class="g3-centres-live__overline">{{ __('public.centres_page.overline') }}</p>
                        <h1 id="centres-live-title">{{ __('public.centres_page.title') }}</h1>
                        <p>{{ __('public.centres_page.lead') }}</p>
                    </header>

                    <div class="g3-centres-live__map-card">
                        <iframe
                            src="{{ $centresGoogleMapsEmbedUrl }}"
                            title="{{ __('public.centres_page.map.title') }}"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>

                        <div class="g3-centres-live__map-markers" aria-label="{{ __('public.centres_page.map.markers_label') }}">
                            @foreach ($centreItems as $centre)
                                <button
                                    type="button"
                                    @class([
                                        'g3-centres-live__marker',
                                        'g3-centres-live__marker--'.$centre['marker_variant'],
                                        'g3-centres-live__marker--'.$centre['marker_position'],
                                        'g3-centres-live__marker--active' => $loop->first,
                                    ])
                                    data-centre-map-marker
                                    data-centre-target="{{ $centre['key'] }}"
                                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                >
                                    <span class="g3-centres-live__marker-pin" aria-hidden="true">
                                        <svg viewBox="0 0 28 38" fill="none">
                                            <path d="M14 37s13-11.4 13-23A13 13 0 1 0 1 14c0 11.6 13 23 13 23Z" fill="currentColor"/>
                                            <circle cx="14" cy="14" r="4.8" fill="white"/>
                                        </svg>
                                    </span>
                                    <span class="g3-centres-live__marker-label">{{ $centre['short_name'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="g3-centres-live__map-actions">
                            <a href="{{ $centresGoogleMapsUrl }}" target="_blank" rel="noopener">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18 3 21V6l6-3 6 3 6-3v15l-6 3-6-3Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v15M15 6v15"/>
                                </svg>
                                <span>{{ __('public.centres_page.map.show_all') }}</span>
                            </a>

                            <a href="{{ $centresGoogleMapsUrl }}" target="_blank" rel="noopener">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"/>
                                    <circle cx="12" cy="10" r="2.5"/>
                                </svg>
                                <span>{{ __('public.centres_page.map.open_google') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="g3-centres-live__detail-shell">
                    <div class="g3-centres-live__tabs" role="tablist" aria-label="{{ __('public.centres_page.tabs_label') }}">
                        @foreach ($centreItems as $centre)
                            <button
                                type="button"
                                id="centres-live-tab-{{ $centre['key'] }}"
                                @class([
                                    'g3-centres-live__tab',
                                    'g3-centres-live__tab--'.$centre['marker_variant'],
                                    'g3-centres-live__tab--active' => $loop->first,
                                ])
                                role="tab"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="centres-live-panel-{{ $centre['key'] }}"
                                data-centre-tab
                                data-centre-target="{{ $centre['key'] }}"
                            >
                                <span class="g3-centres-live__tab-live-dot" aria-hidden="true"></span>
                                <span class="g3-centres-live__tab-ring" aria-hidden="true"></span>
                                <span>{{ $centre['short_name'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="g3-centres-live__panels">
                        @foreach ($centreItems as $centre)
                            <article
                                id="centres-live-panel-{{ $centre['key'] }}"
                                class="g3-centres-live__panel"
                                role="tabpanel"
                                aria-labelledby="centres-live-tab-{{ $centre['key'] }}"
                                data-centre-panel
                                data-centre-key="{{ $centre['key'] }}"
                                @if (! $loop->first) hidden @endif
                            >
                                <div class="g3-centres-live__panel-top">
                                    <div class="g3-centres-live__panel-main">
                                        <h2>{{ $centre['title'] }}</h2>

                                        <div class="g3-centres-live__status">
                                            <span class="g3-centres-live__status-icon" aria-hidden="true">
                                                <span></span>
                                            </span>
                                            <div>
                                                <strong>{{ __('public.centres_page.status.open') }}</strong>
                                                <span>{{ __('public.centres_page.status.closes_today', ['time' => $centre['close_time']]) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <img
                                        src="{{ asset('images/centers/'.$centre['image']) }}"
                                        alt="{{ $centre['image_alt'] }}"
                                        class="g3-centres-live__image"
                                        loading="lazy"
                                    >
                                </div>

                                <dl class="g3-centres-live__details">
                                    <div>
                                        <dt>
                                            <span class="sr-only">{{ __('public.centres_page.labels.address') }}</span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"/>
                                                <circle cx="12" cy="10" r="2.5"/>
                                            </svg>
                                        </dt>
                                        <dd>{{ $centre['address'] }}</dd>
                                    </div>

                                    <div>
                                        <dt>
                                            <span class="sr-only">{{ __('public.centres_page.labels.phone') }}</span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L8 9.6a16 16 0 0 0 6.4 6.4l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2Z"/>
                                            </svg>
                                        </dt>
                                        <dd>
                                            <a href="tel:{{ $centre['phone_href'] }}">{{ $centre['phone'] }}</a>
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>
                                            <span class="sr-only">{{ __('public.centres_page.labels.hours') }}</span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" aria-hidden="true">
                                                <circle cx="12" cy="12" r="8"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2"/>
                                            </svg>
                                        </dt>
                                        <dd>
                                            <span>{{ __('public.centres_page.hours.weekday', ['hours' => $centre['weekday_hours']]) }}</span>
                                            <span>{{ __('public.centres_page.hours.sunday', ['hours' => $centre['sunday_hours']]) }}</span>
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>
                                            <span class="sr-only">{{ __('public.centres_page.labels.holiday') }}</span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3v4M17 3v4M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 15 2 2 4-5"/>
                                            </svg>
                                        </dt>
                                        <dd>{{ __('public.centres_page.hours.holidays') }}</dd>
                                    </div>
                                </dl>

                                <div class="g3-centres-live__actions">
                                    <a href="{{ $centre['directions_url'] }}" target="_blank" rel="noopener">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 11 18-8-8 18-2-8-8-2Z"/>
                                        </svg>
                                        <span>{{ __('public.centres_page.actions.directions') }}</span>
                                    </a>

                                    <a href="{{ $centre['details_url'] }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                            <circle cx="12" cy="12" r="8"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 12 1.8 1.8 3.4-4"/>
                                        </svg>
                                        <span>{{ __('public.centres_page.actions.details') }}</span>
                                    </a>

                                    <a href="{{ $centre['appointment_url'] }}" class="g3-centres-live__choose">
                                        <span>{{ __('public.centres_page.actions.choose') }}</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h12M13 6l6 6-6 6"/>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section
        class="g3-centres-standard"
        style="--g3-centres-standard-logo: url('{{ asset('images/reusable/site-logo.png') }}')"
        aria-labelledby="centres-standard-title"
    >
        <div class="g3-centres-standard__inner">
            <div class="g3-centres-standard__copy">
                <p>{{ __('public.centres_page.standard.overline') }}</p>
                <h2 id="centres-standard-title">{{ __('public.centres_page.standard.title') }}</h2>
            </div>

            <div class="g3-centres-standard__items" aria-label="{{ __('public.centres_page.standard.items_label') }}">
                @foreach ($standardItems as $item)
                    <article class="g3-centres-standard__item">
                        <img
                            src="{{ asset('images/centers/'.$item['icon']) }}"
                            alt=""
                            loading="lazy"
                        >
                        <h3>{{ $item['title'] }}</h3>
                        @if ($item['body'] !== '')
                            <p>{{ $item['body'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
