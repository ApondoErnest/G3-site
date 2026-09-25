@php
    $assetRoot = 'images/contact';

    $copy = trans('public.contact_page.centres');
    $assetCopy = trans('public.contact_page.assets');

    $centreAssets = [
        'ecole-de-police' => [
            'image' => 'ecole-de-police.png',
            'alt' => $assetCopy['ecole_de_police_alt'],
            'variant' => 'blue',
        ],
        'nomayos' => [
            'image' => 'nomayos.png',
            'alt' => $assetCopy['nomayos_alt'],
            'variant' => 'orange',
        ],
    ];
    $centres = collect($publicCentres ?? [])
        ->map(fn ($centre) => [
            'key' => $centre->key,
            'brand' => 'G3 Control',
            'title' => $centre->shortName,
            'address' => $centre->displayAddress,
            'phone' => $centre->phonesDisplayLine,
            'is_open' => (bool) (($publicLiveStatus ?? [])[$centre->key]['isOpen'] ?? true),
            'status_line' => ($publicLiveStatus ?? [])[$centre->key]['status']
                ?? ($copy['status'].' · '.str_replace(':time', (string) $centre->closeTime, $copy['closes'])),
            'weekday_hours' => $centre->weekdayHours,
            'sunday_hours' => $centre->sundayHours,
            'coordinates' => $centre->coordinates,
            'image' => $centreAssets[$centre->key]['image'] ?? 'ecole-de-police.png',
            'alt' => $centreAssets[$centre->key]['alt'] ?? $centre->name,
            'variant' => $centreAssets[$centre->key]['variant'] ?? 'blue',
        ])
        ->values()
        ->all();

    $latitudeAverage = collect($publicCentres ?? [])->avg(fn ($centre) => $centre->latitude);
    $longitudeAverage = collect($publicCentres ?? [])->avg(fn ($centre) => $centre->longitude);
    $mapEmbedUrl = $latitudeAverage && $longitudeAverage
        ? 'https://maps.google.com/maps?q=G3%20Control%20Yaound%C3%A9&ll='.$latitudeAverage.','.$longitudeAverage.'&z=12&output=embed'
        : 'https://maps.google.com/maps?q=G3%20Control%20Yaound%C3%A9&z=12&output=embed';
    $appointmentUrl = \App\Support\PublicNavigation::pageUrl('appointment', $locale);
    $feesUrl = \App\Support\PublicNavigation::pageUrl('fees', $locale);

    $messageCopy = trans('public.contact_page.message');
    $messageCopy['email_value'] = $publicCompany->email;
    $messageCopy['mailbox'] = $publicCompany->postalAddress;
    $messageCopy['approval'] = __('public.contact_page.message.approval', [
        'number' => $publicCompany->agrementLabel,
        'year' => $publicCompany->agrementYear,
    ]);

    $messageSubjects = trans('public.contact_page.subjects');
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-contact-centres" aria-labelledby="contact-centres-title" data-contact-centres data-contact-view="map">
        <div class="g3-contact-centres__inner">
            <header class="g3-contact-centres__header">
                <div>
                    <p class="g3-contact-centres__overline">{{ $copy['overline'] }}</p>
                    <h1 id="contact-centres-title">{{ $copy['title'] }}</h1>
                    <p>{{ $copy['lead'] }}</p>
                </div>

                <div class="g3-contact-centres__switch" role="tablist" aria-label="{{ $copy['centres_label'] }}">
                    <button
                        type="button"
                        class="g3-contact-centres__switch-button g3-contact-centres__switch-button--active"
                        role="tab"
                        aria-selected="true"
                        data-contact-view-button
                        data-contact-view-target="map"
                    >
                        {{ $copy['map_tab'] }}
                    </button>
                    <button
                        type="button"
                        class="g3-contact-centres__switch-button"
                        role="tab"
                        aria-selected="false"
                        data-contact-view-button
                        data-contact-view-target="list"
                    >
                        {{ $copy['list_tab'] }}
                    </button>
                </div>
            </header>

            <div class="g3-contact-centres__layout">
                <div class="g3-contact-centres__cards" aria-label="{{ $copy['centres_label'] }}">
                    @foreach ($centres as $centre)
                        <article class="g3-contact-centres__card g3-contact-centres__card--{{ $centre['variant'] }}">
                            <img
                                src="{{ asset($assetRoot.'/'.$centre['image']) }}"
                                alt="{{ $centre['alt'] }}"
                                loading="lazy"
                            >

                            <div class="g3-contact-centres__card-copy">
                                <span>{{ $centre['brand'] }}</span>
                                <h2>{{ $centre['title'] }}</h2>

                                <p @class([
                                    'g3-contact-centres__status',
                                    'g3-contact-centres__status--closed' => ! $centre['is_open'],
                                ])>
                                    <span class="g3-contact-centres__status-dot" aria-hidden="true"></span>
                                    <span>{{ $centre['status_line'] }}</span>
                                </p>

                                <dl class="g3-contact-centres__details" aria-label="{{ $copy['details_label'] }}">
                                    <div>
                                        <dt><img src="{{ asset($assetRoot.'/icon-pin.svg') }}" alt="" aria-hidden="true"></dt>
                                        <dd>{{ $centre['address'] }}</dd>
                                    </div>
                                    <div>
                                        <dt><img src="{{ asset($assetRoot.'/icon-phone.svg') }}" alt="" aria-hidden="true"></dt>
                                        <dd>{{ $centre['phone'] }}</dd>
                                    </div>
                                    <div>
                                        <dt><img src="{{ asset($assetRoot.'/icon-clock.svg') }}" alt="" aria-hidden="true"></dt>
                                        <dd>
                                            <span>{{ str_replace(':hours', $centre['weekday_hours'], $copy['weekday']) }}</span>
                                            <span>{{ str_replace(':hours', $centre['sunday_hours'], $copy['sunday']) }}</span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt><img src="{{ asset($assetRoot.'/icon-holiday.svg') }}" alt="" aria-hidden="true"></dt>
                                        <dd>{{ $copy['holidays'] }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="g3-contact-centres__map" data-contact-map-panel>
                    <iframe
                        src="{{ $mapEmbedUrl }}"
                        title="{{ $copy['map_title'] }}"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>

                    <div class="g3-contact-centres__map-list" aria-label="{{ $copy['centres_label'] }}">
                        <strong>{{ $copy['centres_label'] }}</strong>
                        @foreach ($centres as $centre)
                            <div>
                                <span class="g3-contact-centres__map-list-pin g3-contact-centres__map-list-pin--{{ $centre['variant'] }}" aria-hidden="true"></span>
                                <p>
                                    <strong>{{ $centre['title'] }}</strong>
                                    <span>{{ $centre['coordinates'] }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="g3-contact-centres__map-positions" aria-hidden="true">
                        @foreach ($centres as $centre)
                            <span class="g3-contact-centres__map-position g3-contact-centres__map-position--{{ $centre['key'] }} g3-contact-centres__map-position--{{ $centre['variant'] }}"></span>
                        @endforeach
                    </div>

                    <p class="g3-contact-centres__map-note">
                        <img src="{{ asset($assetRoot.'/icon-map.svg') }}" alt="" aria-hidden="true">
                        <span>
                            <strong>{{ $copy['map_note_title'] }}</strong>
                            {{ $copy['map_note'] }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="g3-contact-message" aria-labelledby="contact-message-title" data-contact-message>
        <div class="g3-contact-message__inner">
            <div class="g3-contact-message__intro">
                <p class="g3-contact-message__overline">{{ $messageCopy['overline'] }}</p>
                <h2 id="contact-message-title">{{ $messageCopy['title'] }}</h2>
                <p>{{ $messageCopy['lead'] }}</p>

                <div class="g3-contact-message__guides" aria-label="{{ $messageCopy['overline'] }}">
                    <article class="g3-contact-message__guide">
                        <img src="{{ asset($assetRoot.'/icon-info.svg') }}" alt="" aria-hidden="true">
                        <div>
                            <h3>{{ $messageCopy['appointment_card_title'] }}</h3>
                            <p>{{ $messageCopy['appointment_card_body'] }}</p>
                            <a href="{{ $appointmentUrl }}">
                                <span>{{ $messageCopy['appointment_card_cta'] }}</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>

                    <article class="g3-contact-message__guide">
                        <img src="{{ asset($assetRoot.'/icon-document.svg') }}" alt="" aria-hidden="true">
                        <div>
                            <h3>{{ $messageCopy['fees_card_title'] }}</h3>
                            <p>{{ $messageCopy['fees_card_body'] }}</p>
                            <a href="{{ $feesUrl }}">
                                <span>{{ $messageCopy['fees_card_cta'] }}</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                </div>
            </div>

            <form class="g3-contact-message__form" method="POST" action="{{ route($locale.'.contact.submit') }}" aria-label="{{ $messageCopy['title'] }}" data-contact-form novalidate>
                @csrf
                <div class="g3-honeypot" aria-hidden="true">
                    <label for="contact-website">Website</label>
                    <input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off" value="">
                </div>

                <p class="g3-form-status" role="status" data-contact-status @unless (session('contact_received')) hidden @endunless>{{ session('contact_received') }}</p>

                <div class="g3-form-alert" role="alert" data-contact-alert @unless ($errors->has('form')) hidden @endunless>
                    <p>{{ $errors->first('form') }}</p>
                </div>
                <div class="g3-contact-message__fields">
                    <label>
                        <span>{{ $messageCopy['name'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <input id="contact-name" type="text" name="name" autocomplete="name" placeholder="{{ $messageCopy['name_placeholder'] }}" value="{{ old('name') }}" aria-describedby="contact-name-error" data-required-message="{{ $messageCopy['errors']['name'] }}" @if ($errors->has('name')) aria-invalid="true" @endif required>
                        <p id="contact-name-error" class="g3-contact-message__error" data-contact-error="name" @unless ($errors->has('name')) hidden @endunless>{{ $errors->first('name') }}</p>
                    </label>

                    <label>
                        <span>{{ $messageCopy['phone'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <input id="contact-phone" type="tel" name="phone" autocomplete="tel" placeholder="{{ $messageCopy['phone_placeholder'] }}" value="{{ old('phone') }}" aria-describedby="contact-phone-error" data-required-message="{{ $messageCopy['errors']['phone'] }}" @if ($errors->has('phone')) aria-invalid="true" @endif required>
                        <p id="contact-phone-error" class="g3-contact-message__error" data-contact-error="phone" @unless ($errors->has('phone')) hidden @endunless>{{ $errors->first('phone') }}</p>
                    </label>

                    <label>
                        <span>{{ $messageCopy['email'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <input id="contact-email" type="email" name="email" autocomplete="email" placeholder="{{ $messageCopy['email_placeholder'] }}" value="{{ old('email') }}" aria-describedby="contact-email-error" data-required-message="{{ $messageCopy['errors']['email'] }}" data-invalid-message="{{ $messageCopy['errors']['email'] }}" @if ($errors->has('email')) aria-invalid="true" @endif required>
                        <p id="contact-email-error" class="g3-contact-message__error" data-contact-error="email" @unless ($errors->has('email')) hidden @endunless>{{ $errors->first('email') }}</p>
                    </label>

                    <label>
                        <span>{{ $messageCopy['centre'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <span class="g3-contact-message__select">
                            <select id="contact-centre" name="centre" aria-describedby="contact-centre-error" data-required-message="{{ $messageCopy['errors']['centre'] }}" @if ($errors->has('centre')) aria-invalid="true" @endif required>
                                <option value="">{{ $messageCopy['centre_placeholder'] }}</option>
                                @foreach ($centres as $centre)
                                    <option value="{{ $centre['key'] }}" @selected(old('centre') === $centre['key'])>{{ $centre['title'] }}</option>
                                @endforeach
                            </select>
                        </span>
                        <p id="contact-centre-error" class="g3-contact-message__error" data-contact-error="centre" @unless ($errors->has('centre')) hidden @endunless>{{ $errors->first('centre') }}</p>
                    </label>

                    <label class="g3-contact-message__field--wide">
                        <span>{{ $messageCopy['subject'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <span class="g3-contact-message__select">
                            <select id="contact-subject" name="subject" aria-describedby="contact-subject-error" data-required-message="{{ $messageCopy['errors']['subject'] }}" @if ($errors->has('subject')) aria-invalid="true" @endif required>
                                <option value="">{{ $messageCopy['subject_placeholder'] }}</option>
                                @foreach ($messageSubjects as $subject)
                                    <option value="{{ \Illuminate\Support\Str::slug($subject) }}" @selected(old('subject') === \Illuminate\Support\Str::slug($subject))>{{ $subject }}</option>
                                @endforeach
                            </select>
                        </span>
                        <p id="contact-subject-error" class="g3-contact-message__error" data-contact-error="subject" @unless ($errors->has('subject')) hidden @endunless>{{ $errors->first('subject') }}</p>
                    </label>

                    <label class="g3-contact-message__field--wide">
                        <span>{{ $messageCopy['message'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <textarea id="contact-message" name="message" maxlength="1000" placeholder="{{ $messageCopy['message_placeholder'] }}" aria-describedby="contact-message-error" data-contact-message-text data-required-message="{{ $messageCopy['errors']['message'] }}" @if ($errors->has('message')) aria-invalid="true" @endif required>{{ old('message') }}</textarea>
                        <span class="g3-contact-message__counter" data-contact-message-count>0/1000</span>
                        <p id="contact-message-error" class="g3-contact-message__error" data-contact-error="message" @unless ($errors->has('message')) hidden @endunless>{{ $errors->first('message') }}</p>
                    </label>
                </div>

                <button type="submit" class="g3-contact-message__submit" data-contact-submit data-form-error="{{ $messageCopy['errors']['form'] }}">
                    <img src="{{ asset($assetRoot.'/icon-send.svg') }}" alt="" aria-hidden="true">
                    <span>{{ $messageCopy['submit'] }}</span>
                </button>
            </form>

            <aside class="g3-contact-message__support" aria-label="{{ $messageCopy['support_title'] }}">
                <div class="g3-contact-message__support-head">
                    <img src="{{ asset($assetRoot.'/icon-headset.svg') }}" alt="" aria-hidden="true">
                    <div>
                        <h3>{{ $messageCopy['support_title'] }}</h3>
                        <p>{{ $messageCopy['support_body'] }}</p>
                    </div>
                </div>

                <dl class="g3-contact-message__support-list">
                    <div>
                        <dt><img src="{{ asset($assetRoot.'/icon-mail.svg') }}" alt="" aria-hidden="true"></dt>
                        <dd><a href="mailto:{{ $messageCopy['email_value'] }}">{{ $messageCopy['email_value'] }}</a></dd>
                    </div>
                    <div>
                        <dt><img src="{{ asset($assetRoot.'/icon-pin.svg') }}" alt="" aria-hidden="true"></dt>
                        <dd>{{ $messageCopy['mailbox'] }}</dd>
                    </div>
                    @foreach ($centres as $centre)
                        <div>
                            <dt><img src="{{ asset($assetRoot.'/icon-phone.svg') }}" alt="" aria-hidden="true"></dt>
                            <dd>
                                <strong>{{ $centre['title'] }}</strong>
                                <span>{{ $centre['phone'] }}</span>
                            </dd>
                        </div>
                    @endforeach
                    <div>
                        <dt><img src="{{ asset($assetRoot.'/icon-clock.svg') }}" alt="" aria-hidden="true"></dt>
                        <dd>
                            <strong>{{ $messageCopy['hours_title'] }}</strong>
                            <span>{{ $messageCopy['hours_weekday'] }}</span>
                            <span>{{ $messageCopy['hours_sunday'] }}</span>
                            <span>{{ $messageCopy['hours_holidays'] }}</span>
                        </dd>
                    </div>
                </dl>

                <p class="g3-contact-message__approval">
                    <img src="{{ asset($assetRoot.'/icon-approval.svg') }}" alt="" aria-hidden="true">
                    <span>{{ $messageCopy['approval'] }}</span>
                </p>
            </aside>
        </div>
    </section>
@endsection
