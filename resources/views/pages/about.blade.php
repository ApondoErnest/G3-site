@php
    $identityCards = [
        [
            'title' => __('public.about.identity.purpose.title'),
            'subtitle' => __('public.about.identity.purpose.subtitle'),
            'body' => __('public.about.identity.purpose.body'),
            'icon' => 'icon-target.svg',
            'accent' => 'orange',
        ],
        [
            'title' => __('public.about.identity.mission.title'),
            'subtitle' => __('public.about.identity.mission.subtitle'),
            'body' => __('public.about.identity.mission.body'),
            'icon' => 'icon-gear.svg',
            'accent' => 'blue',
        ],
    ];

    $briefItems = [
        [
            'label' => __('public.about.identity.brief.items.activity.label'),
            'value' => __('public.about.identity.brief.items.activity.value'),
            'icon' => 'icon-car.svg',
        ],
        [
            'label' => __('public.about.identity.brief.items.location.label'),
            'value' => __('public.about.identity.brief.items.location.value'),
            'icon' => 'icon-map-pin.svg',
        ],
        [
            'label' => __('public.about.identity.brief.items.centres.label'),
            'value' => __('public.about.identity.brief.items.centres.value'),
            'icon' => 'icon-centres.svg',
        ],
        [
            'label' => __('public.about.identity.brief.items.approval.label'),
            'value' => __('public.about.identity.brief.items.approval.value'),
            'icon' => 'icon-shield.svg',
        ],
        [
            'label' => __('public.about.identity.brief.items.opening.label'),
            'value' => __('public.about.identity.brief.items.opening.value'),
            'icon' => 'icon-briefcase.svg',
        ],
    ];

    $technicalRequirements = [
        [
            'title' => __('public.about.requirements.items.procedures.title'),
            'body' => __('public.about.requirements.items.procedures.body'),
            'icon' => 'icon-procedure.svg',
        ],
        [
            'title' => __('public.about.requirements.items.equipment.title'),
            'body' => __('public.about.requirements.items.equipment.body'),
            'icon' => 'icon-gear.svg',
        ],
        [
            'title' => __('public.about.requirements.items.team.title'),
            'body' => __('public.about.requirements.items.team.body'),
            'icon' => 'icon-team.svg',
        ],
        [
            'title' => __('public.about.requirements.items.measures.title'),
            'body' => __('public.about.requirements.items.measures.body'),
            'icon' => 'icon-measure.svg',
        ],
    ];

    $values = [
        [
            'title' => __('public.about.values.items.security.title'),
            'body' => __('public.about.values.items.security.body'),
            'icon' => 'icon-value-security.svg',
        ],
        [
            'title' => __('public.about.values.items.simplicity.title'),
            'body' => __('public.about.values.items.simplicity.body'),
            'icon' => 'icon-value-simplicity.svg',
        ],
        [
            'title' => __('public.about.values.items.trust.title'),
            'body' => __('public.about.values.items.trust.body'),
            'icon' => 'icon-value-trust.svg',
        ],
        [
            'title' => __('public.about.values.items.rigor.title'),
            'body' => __('public.about.values.items.rigor.body'),
            'icon' => 'icon-value-rigor.svg',
        ],
    ];

    $teamQualities = [
        [
            'label' => __('public.about.team.items.welcome'),
            'icon' => 'icon-team-welcome.svg',
        ],
        [
            'label' => __('public.about.team.items.rigor'),
            'icon' => 'icon-team-rigor.svg',
        ],
        [
            'label' => __('public.about.team.items.responsibility'),
            'icon' => 'icon-team-responsibility.svg',
        ],
    ];
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-about-identity" aria-labelledby="about-identity-title">
        <div class="g3-about-identity__inner">
            <div class="g3-about-identity__main">
                <p class="g3-about-identity__overline">{{ __('public.about.identity.overline') }}</p>
                <h1 id="about-identity-title">{{ __('public.about.identity.title') }}</h1>

                <div class="g3-about-identity__intro">
                    @foreach (__('public.about.identity.intro') as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <div class="g3-about-identity__cards" aria-label="{{ __('public.about.identity.cards_label') }}">
                    @foreach ($identityCards as $card)
                        <article class="g3-about-identity__card g3-about-identity__card--{{ $card['accent'] }}">
                            <div class="g3-about-identity__card-head">
                                <img
                                    src="{{ asset('images/about/'.$card['icon']) }}"
                                    alt=""
                                    class="g3-about-identity__card-icon"
                                    loading="lazy"
                                >
                                <div>
                                    <h2>{{ $card['title'] }}</h2>
                                    <p>{{ $card['subtitle'] }}</p>
                                </div>
                            </div>
                            <p class="g3-about-identity__card-body">{{ $card['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="g3-about-identity__side">
                <article class="g3-about-identity__vision">
                    <img
                        src="{{ asset('images/about/icon-eye.svg') }}"
                        alt=""
                        class="g3-about-identity__vision-icon"
                        loading="lazy"
                    >
                    <div>
                        <h2>{{ __('public.about.identity.vision.title') }}</h2>
                        <p class="g3-about-identity__vision-subtitle">{{ __('public.about.identity.vision.subtitle') }}</p>
                        <p class="g3-about-identity__vision-body">{{ __('public.about.identity.vision.body') }}</p>
                    </div>
                </article>

                <article class="g3-about-identity__brief">
                    <h2>{{ __('public.about.identity.brief.title') }}</h2>

                    <dl class="g3-about-identity__brief-list">
                        @foreach ($briefItems as $item)
                            <div>
                                <dt>
                                    <img
                                        src="{{ asset('images/about/'.$item['icon']) }}"
                                        alt=""
                                        loading="lazy"
                                    >
                                    <span>{{ $item['label'] }}</span>
                                </dt>
                                <dd>{{ $item['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="g3-about-identity__timeline" aria-label="{{ __('public.about.identity.brief.title') }}">
                        <div class="g3-about-identity__timeline-track" aria-hidden="true">
                            <span></span>
                            <span></span>
                        </div>

                        <div class="g3-about-identity__timeline-items">
                            <div>
                                <strong>{{ __('public.about.identity.brief.timeline.approval.year') }}</strong>
                                <span>{{ __('public.about.identity.brief.timeline.approval.title') }}</span>
                                <p>{{ __('public.about.identity.brief.timeline.approval.body') }}</p>
                            </div>
                            <div>
                                <strong>{{ __('public.about.identity.brief.timeline.today.year') }}</strong>
                                <span>{{ __('public.about.identity.brief.timeline.today.title') }}</span>
                                <p>{{ __('public.about.identity.brief.timeline.today.body') }}</p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="g3-about-requirements" aria-labelledby="about-requirements-title">
        <div class="g3-about-requirements__inner">
            <div class="g3-about-requirements__image">
                <img
                    src="{{ asset('images/about/technical-requirements.png') }}"
                    alt="{{ __('public.about.requirements.image_alt') }}"
                    loading="lazy"
                >
            </div>

            <div class="g3-about-requirements__content">
                <p class="g3-about-requirements__overline">{{ __('public.about.requirements.overline') }}</p>
                <h2 id="about-requirements-title">{{ __('public.about.requirements.title') }}</h2>

                <div class="g3-about-requirements__list" aria-label="{{ __('public.about.requirements.items_label') }}">
                    @foreach ($technicalRequirements as $item)
                        <article class="g3-about-requirements__item">
                            <img
                                src="{{ asset('images/about/'.$item['icon']) }}"
                                alt=""
                                loading="lazy"
                            >
                            <div>
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['body'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section
        class="g3-about-values"
        style="--g3-about-values-logo: url('{{ asset('images/reusable/site-logo.png') }}')"
        aria-labelledby="about-values-title"
    >
        <div class="g3-about-values__inner">
            <div class="g3-about-values__header">
                <div>
                    <p class="g3-about-values__overline">{{ __('public.about.values.overline') }}</p>
                    <h2 id="about-values-title">{{ __('public.about.values.title') }}</h2>
                </div>

                <p class="g3-about-values__motto">{{ __('public.about.values.motto') }}</p>
            </div>

            <div class="g3-about-values__grid" aria-label="{{ __('public.about.values.items_label') }}">
                @foreach ($values as $value)
                    <article class="g3-about-values__card">
                        <img
                            src="{{ asset('images/about/'.$value['icon']) }}"
                            alt=""
                            loading="lazy"
                        >
                        <h3>{{ $value['title'] }}</h3>
                        <p>{{ $value['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="g3-about-team" aria-labelledby="about-team-title">
        <div class="g3-about-team__inner">
            <div class="g3-about-team__content">
                <p class="g3-about-team__overline">{{ __('public.about.team.overline') }}</p>
                <h2 id="about-team-title">{{ __('public.about.team.title') }}</h2>
                <p class="g3-about-team__body">{{ __('public.about.team.body') }}</p>

                <div class="g3-about-team__qualities" aria-label="{{ __('public.about.team.items_label') }}">
                    @foreach ($teamQualities as $quality)
                        <div class="g3-about-team__quality">
                            <span>
                                <img
                                    src="{{ asset('images/about/'.$quality['icon']) }}"
                                    alt=""
                                    loading="lazy"
                                >
                            </span>
                            <strong>{{ $quality['label'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="g3-about-team__image">
                <img
                    src="{{ asset('images/about/technicians.png') }}"
                    alt="{{ __('public.about.team.image_alt') }}"
                    loading="lazy"
                >
            </div>
        </div>
    </section>
@endsection
