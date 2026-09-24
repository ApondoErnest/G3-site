@php
    $isFrench = $locale === 'fr';
    $assetRoot = 'images/contact';

    $copy = [
        'overline' => $isFrench ? 'Nos centres' : 'Our centres',
        'title' => $isFrench
            ? 'Deux centres à Yaoundé. Un accès direct à votre équipe.'
            : 'Two centres in Yaoundé. Direct access to your team.',
        'lead' => $isFrench
            ? 'Sélectionnez un centre pour voir les détails et la carte.'
            : 'Select a centre to view details and the map.',
        'map_tab' => $isFrench ? 'Carte' : 'Map',
        'list_tab' => $isFrench ? 'Liste' : 'List',
        'status' => $isFrench ? 'Ouvert actuellement' : 'Open now',
        'closes' => $isFrench ? 'Ferme à :time' : 'Closes at :time',
        'weekday' => $isFrench ? 'Lundi - Samedi : :hours' : 'Monday - Saturday: :hours',
        'sunday' => $isFrench ? 'Dimanche : :hours' : 'Sunday: :hours',
        'holidays' => $isFrench ? 'Ouvert les jours fériés' : 'Open on public holidays',
        'map_title' => $isFrench ? 'Carte Google Maps des centres G3 Control à Yaoundé' : 'Google Maps view of G3 Control centres in Yaoundé',
        'map_note_title' => $isFrench ? 'Carte Google interactive' : 'Interactive Google map',
        'map_note' => $isFrench
            ? 'Utilisez la carte pour explorer les repères Google des centres G3 Control.'
            : 'Use the map to explore Google markers for the G3 Control centres.',
        'centres_label' => $isFrench ? 'Centres G3 Control' : 'G3 Control centres',
        'details_label' => $isFrench ? 'Détails du centre' : 'Centre details',
    ];

    $centres = [
        [
            'key' => 'ecole-de-police',
            'brand' => 'G3 Control',
            'title' => $isFrench ? 'École de Police' : 'École de Police',
            'address' => $isFrench ? 'Descente ancien Texaco, École de Police, Yaoundé' : 'Former Texaco descent, École de Police, Yaoundé',
            'phone' => '687 187 516',
            'close_time' => '20h00',
            'weekday_hours' => $isFrench ? '07h00 - 20h00' : '07:00 - 20:00',
            'sunday_hours' => $isFrench ? '07h00 - 15h00' : '07:00 - 15:00',
            'coordinates' => '3.8786152, 11.5116814',
            'image' => 'ecole-de-police.png',
            'alt' => $isFrench ? 'Façade du centre G3 Control École de Police' : 'Facade of the G3 Control École de Police centre',
            'variant' => 'blue',
        ],
        [
            'key' => 'nomayos',
            'brand' => 'G3 Control',
            'title' => 'Nomayos',
            'address' => $isFrench ? 'Carrefour Nomayos, Yaoundé' : 'Nomayos junction, Yaoundé',
            'phone' => '653 100 801 / 692 242 143',
            'close_time' => '19h00',
            'weekday_hours' => $isFrench ? '07h00 - 19h00' : '07:00 - 19:00',
            'sunday_hours' => $isFrench ? '07h00 - 15h00' : '07:00 - 15:00',
            'coordinates' => '3.7902275, 11.4439448',
            'image' => 'nomayos.png',
            'alt' => $isFrench ? 'Entrée du centre G3 Control Nomayos' : 'Entrance of the G3 Control Nomayos centre',
            'variant' => 'orange',
        ],
    ];

    $mapEmbedUrl = 'https://maps.google.com/maps?q=G3%20Control%20Yaound%C3%A9&ll=3.834421,11.477813&z=12&output=embed';
    $appointmentUrl = \App\Support\PublicNavigation::pageUrl('appointment', $locale);
    $feesUrl = \App\Support\PublicNavigation::pageUrl('fees', $locale);

    $messageCopy = [
        'overline' => $isFrench ? 'Écrivez-nous' : 'Write to us',
        'title' => $isFrench ? 'Une question particulière ? Écrivez-nous.' : 'A specific question? Write to us.',
        'lead' => $isFrench
            ? 'Votre demande ne concerne ni un rendez-vous ni un itinéraire ? Envoyez-nous votre message en précisant le centre concerné.'
            : 'Your request is not about an appointment or directions? Send us your message and specify the relevant centre.',
        'appointment_card_title' => $isFrench ? 'Besoin d’un rendez-vous ?' : 'Need an appointment?',
        'appointment_card_body' => $isFrench ? 'Accédez directement à notre plateforme de rendez-vous.' : 'Go directly to our appointment platform.',
        'appointment_card_cta' => $isFrench ? 'Prendre rendez-vous' : 'Book an appointment',
        'fees_card_title' => $isFrench ? 'Consulter les tarifs' : 'View fees',
        'fees_card_body' => $isFrench ? 'Retrouvez la grille tarifaire officielle de G3 Control.' : 'Find the official G3 Control fee schedule.',
        'fees_card_cta' => $isFrench ? 'Voir les tarifs' : 'View fees',
        'name' => $isFrench ? 'Nom et prénom' : 'Full name',
        'name_placeholder' => $isFrench ? 'Votre nom complet' : 'Your full name',
        'phone' => $isFrench ? 'Téléphone / WhatsApp' : 'Phone / WhatsApp',
        'phone_placeholder' => '6XX XXX XXX',
        'email' => $isFrench ? 'Adresse e-mail' : 'Email address',
        'email_placeholder' => 'votre@email.com',
        'centre' => $isFrench ? 'Centre concerné' : 'Relevant centre',
        'centre_placeholder' => $isFrench ? 'Sélectionner un centre' : 'Select a centre',
        'subject' => $isFrench ? 'Objet de votre demande' : 'Subject of your request',
        'subject_placeholder' => $isFrench ? 'Sélectionner un sujet' : 'Select a subject',
        'message' => $isFrench ? 'Votre message' : 'Your message',
        'message_placeholder' => $isFrench ? 'Décrivez votre demande en quelques mots...' : 'Describe your request in a few words...',
        'submit' => $isFrench ? 'Envoyer mon message' : 'Send my message',
        'required' => $isFrench ? 'obligatoire' : 'required',
        'support_title' => $isFrench ? 'Nous sommes à votre écoute' : 'We are listening',
        'support_body' => $isFrench
            ? 'Notre équipe vous répond dans les meilleurs délais par le moyen de contact approprié.'
            : 'Our team will respond as soon as possible through the appropriate contact channel.',
        'email_value' => 'g3sarl1@gmail.com',
        'mailbox' => 'BP 12775 Yaoundé',
        'hours_title' => $isFrench ? 'Nos centres' : 'Our centres',
        'hours_weekday' => $isFrench ? 'Lundi - Samedi : 07h00 - 20h00' : 'Monday - Saturday: 07:00 - 20:00',
        'hours_sunday' => $isFrench ? 'Dimanche: 07h00 - 15h00' : 'Sunday: 07:00 - 15:00',
        'hours_holidays' => $isFrench ? 'Ouverts les jours fériés' : 'Open on public holidays',
        'approval' => $isFrench ? 'Agrément N°0291 depuis 2020' : 'Approval N°0291 since 2020',
    ];

    $messageSubjects = [
        $isFrench ? 'Information générale' : 'General information',
        $isFrench ? 'Question sur un centre' : 'Centre question',
        $isFrench ? 'Document ou préparation' : 'Document or preparation',
        $isFrench ? 'Autre demande' : 'Other request',
    ];
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

                                <p class="g3-contact-centres__status">
                                    <img src="{{ asset($assetRoot.'/icon-status.svg') }}" alt="" aria-hidden="true">
                                    <span>{{ $copy['status'] }} · {{ str_replace(':time', $centre['close_time'], $copy['closes']) }}</span>
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

            <form class="g3-contact-message__form" aria-label="{{ $messageCopy['title'] }}">
                <div class="g3-contact-message__fields">
                    <label>
                        <span>{{ $messageCopy['name'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <input type="text" name="name" autocomplete="name" placeholder="{{ $messageCopy['name_placeholder'] }}" required>
                    </label>

                    <label>
                        <span>{{ $messageCopy['phone'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <input type="tel" name="phone" autocomplete="tel" placeholder="{{ $messageCopy['phone_placeholder'] }}" required>
                    </label>

                    <label>
                        <span>{{ $messageCopy['email'] }}</span>
                        <input type="email" name="email" autocomplete="email" placeholder="{{ $messageCopy['email_placeholder'] }}">
                    </label>

                    <label>
                        <span>{{ $messageCopy['centre'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <span class="g3-contact-message__select">
                            <select name="centre" required>
                                <option value="">{{ $messageCopy['centre_placeholder'] }}</option>
                                @foreach ($centres as $centre)
                                    <option value="{{ $centre['key'] }}">{{ $centre['title'] }}</option>
                                @endforeach
                            </select>
                        </span>
                    </label>

                    <label class="g3-contact-message__field--wide">
                        <span>{{ $messageCopy['subject'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <span class="g3-contact-message__select">
                            <select name="subject" required>
                                <option value="">{{ $messageCopy['subject_placeholder'] }}</option>
                                @foreach ($messageSubjects as $subject)
                                    <option value="{{ \Illuminate\Support\Str::slug($subject) }}">{{ $subject }}</option>
                                @endforeach
                            </select>
                        </span>
                    </label>

                    <label class="g3-contact-message__field--wide">
                        <span>{{ $messageCopy['message'] }} <abbr title="{{ $messageCopy['required'] }}">*</abbr></span>
                        <textarea name="message" maxlength="1000" placeholder="{{ $messageCopy['message_placeholder'] }}" required data-contact-message-text></textarea>
                        <span class="g3-contact-message__counter" data-contact-message-count>0/1000</span>
                    </label>
                </div>

                <button type="button" class="g3-contact-message__submit">
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
                    <div>
                        <dt><img src="{{ asset($assetRoot.'/icon-phone.svg') }}" alt="" aria-hidden="true"></dt>
                        <dd>
                            <strong>École de Police</strong>
                            <span>687 187 516</span>
                        </dd>
                    </div>
                    <div>
                        <dt><img src="{{ asset($assetRoot.'/icon-phone.svg') }}" alt="" aria-hidden="true"></dt>
                        <dd>
                            <strong>Nomayos</strong>
                            <span>653 100 801 / 692 242 143</span>
                        </dd>
                    </div>
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
