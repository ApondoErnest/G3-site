@php
    $isFrench = $locale === 'fr';
    $assetRoot = 'images/road-safety';

    $copy = [
        'hub' => [
            'title' => $isFrench ? 'Quatre essentiels, adaptés à votre trajet' : 'Four essentials, adapted to your journey',
            'lead' => $isFrench
                ? 'Sélectionnez un contexte pour voir les points d’attention prioritaires.'
                : 'Select a context to see the priority watch points.',
            'learn_more' => $isFrench ? 'En savoir plus' : 'Learn more',
            'reduce' => $isFrench ? 'Réduire' : 'Collapse',
        ],
    ];

    $reflex = [
        'overline' => $isFrench ? 'Le réflexe 60 secondes' : 'The 60-second reflex',
        'title' => $isFrench ? '4 vérifications simples avant de démarrer.' : '4 simple checks before starting.',
        'lead' => $isFrench
            ? 'Un rapide coup d’œil peut faire la différence.'
            : 'A quick look can make the difference.',
        'default_status' => $isFrench ? '4 points à vérifier' : '4 points to check',
        'progress_singular' => $isFrench ? ':count point vérifié' : ':count point checked',
        'progress_plural' => $isFrench ? ':count points vérifiés' : ':count points checked',
        'instruction' => $isFrench ? 'Cochez chaque point au fur et à mesure' : 'Tick each point as you go',
        'complete' => $isFrench ? 'Vos 4 vérifications rapides sont terminées.' : 'Your 4 quick checks are complete.',
        'reminder_label' => $isFrench ? 'Rappel :' : 'Reminder:',
        'reminder' => $isFrench
            ? 'Ce réflexe quotidien ne remplace pas un entretien professionnel ni votre contrôle technique obligatoire.'
            : 'This daily habit does not replace professional maintenance or your mandatory technical inspection.',
    ];

    $reflexChecks = [
        [
            'label' => $isFrench ? 'Pneumatiques' : 'Tyres',
            'icon' => 'icon-tyre.svg',
        ],
        [
            'label' => $isFrench ? 'Éclairage' : 'Lighting',
            'icon' => 'icon-light.svg',
        ],
        [
            'label' => $isFrench ? 'Pare-brise' : 'Windscreen',
            'icon' => 'icon-windshield.svg',
        ],
        [
            'label' => $isFrench ? 'Rétroviseurs' : 'Mirrors',
            'icon' => 'icon-mirror.svg',
        ],
    ];

    $tabs = [
        'essentials' => [
            'label' => $isFrench ? 'Tous les essentiels' : 'All essentials',
            'icon' => 'icon-grid.svg',
        ],
        'rain' => [
            'label' => $isFrench ? 'Pluie' : 'Rain',
            'icon' => 'icon-rain.svg',
        ],
        'night' => [
            'label' => $isFrench ? 'Nuit' : 'Night',
            'icon' => 'icon-moon.svg',
        ],
        'long-distance' => [
            'label' => $isFrench ? 'Long trajet' : 'Long journey',
            'icon' => 'icon-road.svg',
        ],
    ];

    $contexts = [
        'essentials' => [
            [
                'number' => '01',
                'icon' => 'icon-brake.svg',
                'title' => $isFrench ? 'Freinage & adhérence' : 'Braking & grip',
                'body' => $isFrench
                    ? 'Un freinage efficace et des pneus en bon état garantissent votre maîtrise du véhicule.'
                    : 'Effective braking and tyres in good condition help you keep control of the vehicle.',
                'points' => $isFrench
                    ? ['Comportement au freinage', 'État visible et pression mesurée des pneumatiques', 'Tenue de route']
                    : ['Braking behaviour', 'Visible tyre condition and measured pressure', 'Road holding'],
                'details' => $isFrench
                    ? 'Si le freinage devient inhabituel, si le véhicule tire d’un côté ou si les pneus présentent une usure anormale, faites vérifier le véhicule avant de poursuivre un trajet exigeant.'
                    : 'If braking feels unusual, the vehicle pulls to one side or tyres show abnormal wear, have the vehicle checked before continuing a demanding journey.',
                'image' => 'essentials/1.png',
                'alt' => $isFrench ? 'Pneu de véhicule vu de près' : 'Close-up of a vehicle tyre',
            ],
            [
                'number' => '02',
                'icon' => 'icon-light.svg',
                'title' => $isFrench ? 'Visibilité & éclairage' : 'Visibility & lighting',
                'body' => $isFrench
                    ? 'Bien voir et être bien vu, de jour comme de nuit.'
                    : 'See clearly and be seen, day and night.',
                'points' => $isFrench
                    ? ['Fonctionnement des feux et signalisation', 'Propreté du pare-brise', 'Essuie-glaces et rétroviseurs']
                    : ['Lights and signalling working correctly', 'Clean windscreen', 'Wipers and mirrors'],
                'details' => $isFrench
                    ? 'Contrôlez les feux, les clignotants, la visibilité à travers les vitrages et l’état des balais d’essuie-glace, surtout avant la pluie ou un départ de nuit.'
                    : 'Check lights, indicators, visibility through the glass and wiper condition, especially before rain or night driving.',
                'image' => 'essentials/2.png',
                'alt' => $isFrench ? 'Optique de phare de voiture' : 'Car headlight lens',
            ],
            [
                'number' => '03',
                'icon' => 'icon-engine.svg',
                'title' => $isFrench ? 'Signaux du véhicule' : 'Vehicle signals',
                'body' => $isFrench
                    ? 'Votre véhicule communique : soyez attentif aux signes inhabituels.'
                    : 'Your vehicle communicates: pay attention to unusual signs.',
                'points' => $isFrench
                    ? ['Voyants du tableau de bord', 'Bruits et vibrations', 'Changements de comportement']
                    : ['Dashboard warning lights', 'Noises and vibrations', 'Changes in behaviour'],
                'details' => $isFrench
                    ? 'Un voyant, une vibration ou un bruit nouveau ne doit pas être interprété à distance. Notez le contexte et demandez un diagnostic adapté.'
                    : 'A warning light, new vibration or noise should not be interpreted remotely. Note the context and seek the right diagnosis.',
                'image' => 'essentials/3.png',
                'alt' => $isFrench ? 'Voyant moteur allumé sur un tableau de bord' : 'Engine warning light on a dashboard',
            ],
            [
                'number' => '04',
                'icon' => 'icon-seatbelt.svg',
                'title' => $isFrench ? 'Équipements & préparation' : 'Equipment & preparation',
                'body' => $isFrench
                    ? 'Des équipements fonctionnels pour des trajets plus sûrs.'
                    : 'Functional equipment for safer journeys.',
                'points' => $isFrench
                    ? ['Équipements de sécurité (triangle, gilet...)', 'Roue de secours et outillage', 'Préparation avant un long trajet']
                    : ['Safety equipment (triangle, vest...)', 'Spare wheel and tools', 'Preparation before a long journey'],
                'details' => $isFrench
                    ? 'Avant le départ, vérifiez que les équipements utiles sont présents, accessibles et adaptés au trajet prévu.'
                    : 'Before departure, check that useful equipment is present, accessible and suited to the journey.',
                'image' => 'essentials/4.png',
                'alt' => $isFrench ? 'Triangle de sécurité sur la chaussée' : 'Warning triangle on the road',
            ],
        ],
        'rain' => [
            [
                'number' => '01',
                'icon' => 'icon-brake.svg',
                'title' => $isFrench ? 'Freinage & adhérence' : 'Braking & grip',
                'body' => $isFrench
                    ? 'Sur route mouillée, l’adhérence est réduite. Anticipez et adaptez votre conduite.'
                    : 'On wet roads, grip is reduced. Anticipate and adapt your driving.',
                'points' => $isFrench
                    ? ['Vérifiez l’état visible et la pression mesurée des pneus', 'Augmentez la distance de freinage', 'Évitez les freinages brusques']
                    : ['Check visible tyre condition and measured pressure', 'Increase braking distance', 'Avoid sudden braking'],
                'details' => $isFrench
                    ? 'Sous la pluie, la profondeur des sculptures, la pression mesurée et la souplesse des gestes ont un impact direct sur la stabilité.'
                    : 'In rain, tread depth, measured pressure and smooth inputs directly affect stability.',
                'image' => 'rain/1.png',
                'alt' => $isFrench ? 'Roue de voiture sur route mouillée' : 'Car wheel on a wet road',
            ],
            [
                'number' => '02',
                'icon' => 'icon-light.svg',
                'title' => $isFrench ? 'Visibilité & éclairage' : 'Visibility & lighting',
                'body' => $isFrench
                    ? 'Une bonne visibilité est essentielle par temps de pluie.'
                    : 'Good visibility is essential in rainy weather.',
                'points' => $isFrench
                    ? ['Des essuie-glaces en bon état', 'Un pare-brise propre et désembué', 'Des feux allumés en cas de faible visibilité']
                    : ['Wipers in good condition', 'A clean, demisted windscreen', 'Lights on in low visibility'],
                'details' => $isFrench
                    ? 'Nettoyez les vitrages, remplacez les balais fatigués et utilisez l’éclairage adapté dès que la route devient moins lisible.'
                    : 'Clean the glass, replace tired wipers and use suitable lighting as soon as the road becomes harder to read.',
                'image' => 'rain/2.png',
                'alt' => $isFrench ? 'Essuie-glace sur pare-brise mouillé' : 'Wiper on a wet windscreen',
            ],
            [
                'number' => '03',
                'icon' => 'icon-drops.svg',
                'title' => $isFrench ? 'Aquaplanage' : 'Aquaplaning',
                'body' => $isFrench
                    ? 'L’eau sur la chaussée peut réduire fortement l’adhérence.'
                    : 'Water on the roadway can sharply reduce grip.',
                'points' => $isFrench
                    ? ['Soyez attentif aux flaques d’eau', 'Sentez les réactions de la direction', 'Surveillez les marquages au sol']
                    : ['Watch for standing water', 'Feel steering reactions', 'Watch road markings'],
                'details' => $isFrench
                    ? 'Si le véhicule flotte ou répond moins bien, réduisez progressivement votre vitesse et évitez les gestes brusques.'
                    : 'If the vehicle feels light or responds less clearly, reduce speed progressively and avoid sudden inputs.',
                'image' => 'rain/3.png',
                'alt' => $isFrench ? 'Pneu projetant de l’eau sur une route mouillée' : 'Tyre spraying water on a wet road',
            ],
            [
                'number' => '04',
                'icon' => 'icon-seatbelt.svg',
                'title' => $isFrench ? 'Réflexes sous la pluie' : 'Rain driving habits',
                'body' => $isFrench
                    ? 'Adaptez votre conduite pour plus de sécurité.'
                    : 'Adapt your driving for greater safety.',
                'points' => $isFrench
                    ? ['Réduisez votre vitesse', 'Gardez une distance de sécurité suffisante', 'Adoptez des gestes souples (direction, frein, accélération)']
                    : ['Reduce your speed', 'Keep a sufficient safety distance', 'Use smooth steering, braking and acceleration'],
                'details' => $isFrench
                    ? 'La pluie demande plus d’anticipation : laissez de l’espace, rendez vos intentions lisibles et reportez un trajet si les conditions deviennent dangereuses.'
                    : 'Rain calls for more anticipation: leave room, make your intentions clear and postpone a journey if conditions become dangerous.',
                'image' => 'rain/4.png',
                'alt' => $isFrench ? 'Véhicules roulant sous une forte pluie' : 'Vehicles driving in heavy rain',
            ],
        ],
        'night' => [
            [
                'number' => '01',
                'icon' => 'icon-light.svg',
                'title' => $isFrench ? 'Visibilité & éclairage' : 'Visibility & lighting',
                'body' => $isFrench
                    ? 'Des feux en bon état sont essentiels pour voir et être vu la nuit.'
                    : 'Lights in good condition are essential to see and be seen at night.',
                'points' => $isFrench
                    ? ['Vérifiez le bon fonctionnement des feux de croisement, feux arrière et clignotants', 'Assurez-vous que les phares sont propres et bien réglés', 'Remplacez toute ampoule défectueuse']
                    : ['Check dipped beams, rear lights and indicators', 'Make sure headlights are clean and correctly aimed', 'Replace any faulty bulb'],
                'details' => $isFrench
                    ? 'Un éclairage insuffisant ou mal réglé peut masquer un danger ou gêner les autres usagers. Faites corriger un défaut avant de rouler de nuit.'
                    : 'Insufficient or poorly aimed lighting can hide danger or disturb other road users. Correct faults before night driving.',
                'image' => 'night/1.png',
                'alt' => $isFrench ? 'Phare allumé d’un véhicule de nuit' : 'Vehicle headlight lit at night',
            ],
            [
                'number' => '02',
                'icon' => 'icon-eye.svg',
                'title' => $isFrench ? 'Vision du conducteur' : 'Driver vision',
                'body' => $isFrench
                    ? 'Une bonne vision réduit les risques et la fatigue visuelle.'
                    : 'Good vision reduces risk and visual fatigue.',
                'points' => $isFrench
                    ? ['Gardez un pare-brise propre et sans traces', 'Réglez correctement vos rétroviseurs', 'Utilisez l’anti-éblouissement (rétroviseur intérieur)', 'Évitez de fixer les phares des véhicules en sens inverse']
                    : ['Keep the windscreen clean and streak-free', 'Adjust your mirrors correctly', 'Use the anti-glare interior mirror', 'Avoid staring at oncoming headlights'],
                'details' => $isFrench
                    ? 'Un vitrage propre, des rétroviseurs bien réglés et une gestion de l’éblouissement améliorent la lecture de la route.'
                    : 'Clean glass, correctly adjusted mirrors and glare management improve road reading.',
                'image' => 'night/2.png',
                'alt' => $isFrench ? 'Vue de route nocturne depuis l’habitacle' : 'Night road view from inside a car',
            ],
            [
                'number' => '03',
                'icon' => 'icon-coffee.svg',
                'title' => $isFrench ? 'Fatigue & vigilance' : 'Fatigue & vigilance',
                'body' => $isFrench
                    ? 'La conduite de nuit demande plus de concentration.'
                    : 'Night driving requires more concentration.',
                'points' => $isFrench
                    ? ['Faites des pauses régulières', 'Soyez attentif aux premiers signes de fatigue', 'Restez concentré : la vigilance diminue naturellement la nuit', 'Évitez de conduire tard si possible']
                    : ['Take regular breaks', 'Watch for early signs of fatigue', 'Stay focused: vigilance naturally drops at night', 'Avoid late driving when possible'],
                'details' => $isFrench
                    ? 'Si la fatigue apparaît, la bonne action est de s’arrêter dans un endroit sûr. Les réflexes diminuent avant que le conducteur ne s’en rende compte.'
                    : 'If fatigue appears, the right action is to stop somewhere safe. Reactions slow before the driver fully notices.',
                'image' => 'night/3.png',
                'alt' => $isFrench ? 'Voitures circulant sur une route de nuit' : 'Cars driving on a road at night',
            ],
            [
                'number' => '04',
                'icon' => 'icon-road.svg',
                'title' => $isFrench ? 'Signalisation & perception' : 'Signs & perception',
                'body' => $isFrench
                    ? 'La nuit, les distances et les contrastes sont plus difficiles à évaluer.'
                    : 'At night, distances and contrasts are harder to judge.',
                'points' => $isFrench
                    ? ['Adaptez votre vitesse aux conditions de visibilité', 'Soyez attentif aux marquages au sol et aux panneaux réfléchissants', 'Anticipez la présence de piétons, cyclistes ou animaux', 'Gardez une distance de sécurité suffisante']
                    : ['Adapt speed to visibility conditions', 'Watch road markings and reflective signs', 'Anticipate pedestrians, cyclists or animals', 'Keep a sufficient safety distance'],
                'details' => $isFrench
                    ? 'Réduire l’allure permet de retrouver du temps de réaction lorsque la perception des obstacles devient moins évidente.'
                    : 'Reducing speed gives you more reaction time when obstacles become harder to perceive.',
                'image' => 'night/4.png',
                'alt' => $isFrench ? 'Panneau de signalisation visible sur une route de nuit' : 'Road sign visible on a road at night',
            ],
        ],
        'long-distance' => [
            [
                'number' => '01',
                'icon' => 'icon-brake.svg',
                'title' => $isFrench ? 'Freinage & adhérence' : 'Braking & grip',
                'body' => $isFrench
                    ? 'Un freinage efficace est essentiel sur de longues distances.'
                    : 'Effective braking is essential over long distances.',
                'points' => $isFrench
                    ? ['Distance de freinage allongée', 'Pression et usure des pneus', 'Adhérence optimale']
                    : ['Longer braking distance', 'Tyre pressure and wear', 'Optimal grip'],
                'details' => $isFrench
                    ? 'Avant un trajet prolongé, l’usure visible ne suffit pas : mesurez aussi la pression selon les recommandations du véhicule.'
                    : 'Before a long journey, visible wear is not enough: also measure pressure according to the vehicle recommendations.',
                'image' => 'long-distance/1.png',
                'alt' => $isFrench ? 'Pneu de véhicule avant un long trajet' : 'Vehicle tyre before a long journey',
            ],
            [
                'number' => '02',
                'icon' => 'icon-light.svg',
                'title' => $isFrench ? 'Éclairage & visibilité' : 'Lighting & visibility',
                'body' => $isFrench
                    ? 'Voir et être vu, partout et en toutes circonstances.'
                    : 'See and be seen, everywhere and in all conditions.',
                'points' => $isFrench
                    ? ['Feux avant et arrière en bon état', 'Pare-brise propre et dégagé', 'Balais d’essuie-glace efficaces']
                    : ['Front and rear lights in good condition', 'Clean and clear windscreen', 'Effective wiper blades'],
                'details' => $isFrench
                    ? 'Un long trajet multiplie les contextes : pluie, poussière, nuit ou circulation dense. La visibilité doit rester constante.'
                    : 'A long journey multiplies contexts: rain, dust, night or dense traffic. Visibility must stay consistent.',
                'image' => 'long-distance/2.png',
                'alt' => $isFrench ? 'Phare de voiture avant un départ' : 'Car headlight before departure',
            ],
            [
                'number' => '03',
                'icon' => 'icon-luggage.svg',
                'title' => $isFrench ? 'Charge & stabilité' : 'Load & stability',
                'body' => $isFrench
                    ? 'Un véhicule bien chargé garde une meilleure tenue de route.'
                    : 'A properly loaded vehicle keeps better road holding.',
                'points' => $isFrench
                    ? ['Répartition de la charge', 'Coffre non surchargé', 'Stabilité et tenue de route']
                    : ['Load distribution', 'Boot not overloaded', 'Stability and road holding'],
                'details' => $isFrench
                    ? 'Répartissez la charge, fixez les objets lourds et évitez d’obstruer la visibilité arrière.'
                    : 'Distribute the load, secure heavy objects and avoid blocking rear visibility.',
                'image' => 'long-distance/3.png',
                'alt' => $isFrench ? 'Bagages rangés dans un coffre de voiture' : 'Luggage arranged in a car boot',
            ],
            [
                'number' => '04',
                'icon' => 'icon-seatbelt.svg',
                'title' => $isFrench ? 'Confort & sécurité du trajet' : 'Journey comfort & safety',
                'body' => $isFrench
                    ? 'Un conducteur reposé roule plus en sécurité.'
                    : 'A rested driver drives more safely.',
                'points' => $isFrench
                    ? ['Ceinture pour tous les passagers', 'Niveau de carburant suffisant', 'Faire des pauses régulières', 'Documents à bord (permis, carte grise)']
                    : ['Seatbelt for every passenger', 'Sufficient fuel level', 'Take regular breaks', 'Documents on board (licence, registration)'],
                'details' => $isFrench
                    ? 'Préparez aussi le conducteur : pauses, hydratation, documents utiles et itinéraire lisible réduisent les imprévus.'
                    : 'Prepare the driver too: breaks, hydration, useful documents and a clear route reduce surprises.',
                'image' => 'long-distance/4.png',
                'alt' => $isFrench ? 'Route dégagée pour un long trajet' : 'Open road for a long journey',
            ],
        ],
    ];

@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-road-hub" aria-labelledby="road-hub-title" data-road-safety-hub>
        <div class="g3-road-hub__inner">
            <header class="g3-road-section-header">
                <div>
                    <h2 id="road-hub-title">{{ $copy['hub']['title'] }}</h2>
                </div>
            </header>

            <div class="g3-road-tabs-wrap">
                <div class="g3-road-tabs" role="tablist" aria-label="{{ $isFrench ? 'Contextes de conduite' : 'Driving contexts' }}">
                    @foreach ($tabs as $key => $tab)
                        <button
                            type="button"
                            id="road-tab-{{ $key }}"
                            class="g3-road-tabs__button{{ $loop->first ? ' g3-road-tabs__button--active' : '' }}"
                            role="tab"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            aria-controls="road-panel-{{ $key }}"
                            tabindex="{{ $loop->first ? '0' : '-1' }}"
                            data-road-safety-tab
                            data-road-safety-target="{{ $key }}"
                        >
                            <img src="{{ asset($assetRoot.'/'.$tab['icon']) }}" alt="" aria-hidden="true">
                            <span>{{ $tab['label'] }}</span>
                        </button>
                    @endforeach
                </div>

                <p class="g3-road-tabs-wrap__hint">{{ $copy['hub']['lead'] }}</p>
            </div>

            @foreach ($contexts as $key => $cards)
                <div
                    id="road-panel-{{ $key }}"
                    class="g3-road-grid"
                    role="tabpanel"
                    aria-labelledby="road-tab-{{ $key }}"
                    data-road-safety-context-panel="{{ $key }}"
                    @if (! $loop->first) hidden @endif
                >
                    @foreach ($cards as $card)
                        @php($detailsId = 'road-card-'.$key.'-'.$card['number'].'-details')
                        <article class="g3-road-card" data-road-safety-card>
                            <div class="g3-road-card__content">
                                <div class="g3-road-card__icon">
                                    <img src="{{ asset($assetRoot.'/'.$card['icon']) }}" alt="" aria-hidden="true">
                                </div>

                                <div class="g3-road-card__copy">
                                    <span class="g3-road-card__number">{{ $card['number'] }}</span>
                                    <h3>{{ $card['title'] }}</h3>
                                    <p>{{ $card['body'] }}</p>
                                </div>

                                <ul class="g3-road-card__points" aria-label="{{ $card['title'] }}">
                                    @foreach ($card['points'] as $point)
                                        <li>
                                            <img src="{{ asset($assetRoot.'/icon-check.svg') }}" alt="" aria-hidden="true">
                                            <span>{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <button
                                    type="button"
                                    class="g3-road-card__link"
                                    aria-expanded="false"
                                    aria-controls="{{ $detailsId }}"
                                    data-road-safety-details-toggle
                                    data-label-open="{{ $copy['hub']['learn_more'] }}"
                                    data-label-close="{{ $copy['hub']['reduce'] }}"
                                >
                                    <span>{{ $copy['hub']['learn_more'] }}</span>
                                    <img src="{{ asset($assetRoot.'/icon-arrow.svg') }}" alt="" aria-hidden="true">
                                </button>

                                <div id="{{ $detailsId }}" class="g3-road-card__details" data-road-safety-details hidden>
                                    <p>{{ $card['details'] }}</p>
                                </div>
                            </div>

                            <figure class="g3-road-card__media">
                                <img src="{{ asset($assetRoot.'/'.$card['image']) }}" alt="{{ $card['alt'] }}">
                                <span aria-hidden="true">
                                    <img src="{{ asset($assetRoot.'/icon-arrow.svg') }}" alt="">
                                </span>
                            </figure>
                        </article>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="g3-road-reflex" aria-labelledby="road-reflex-title" data-road-reflex>
        <div class="g3-road-reflex__inner">
            <header class="g3-road-reflex__intro">
                <p>{{ $reflex['overline'] }}</p>
                <span aria-hidden="true"></span>
                <h2 id="road-reflex-title">{{ $reflex['title'] }}</h2>
                <strong>{{ $reflex['lead'] }}</strong>
            </header>

            <div class="g3-road-reflex__panel">
                <div class="g3-road-reflex__checks">
                    @foreach ($reflexChecks as $item)
                        <label class="g3-road-reflex__card" data-road-reflex-card>
                            <img src="{{ asset($assetRoot.'/'.$item['icon']) }}" alt="" aria-hidden="true">
                            <span>{{ $item['label'] }}</span>
                            <input
                                type="checkbox"
                                data-road-reflex-check
                                aria-label="{{ $item['label'] }}"
                            >
                            <i aria-hidden="true"></i>
                        </label>
                    @endforeach
                </div>

                <div class="g3-road-reflex__status" aria-live="polite">
                    <span class="g3-road-reflex__status-ring" aria-hidden="true" data-road-reflex-ring></span>
                    <strong
                        data-road-reflex-status
                        data-default="{{ $reflex['default_status'] }}"
                        data-progress-singular="{{ $reflex['progress_singular'] }}"
                        data-progress-plural="{{ $reflex['progress_plural'] }}"
                    >{{ $reflex['default_status'] }}</strong>
                    <span class="g3-road-reflex__status-arrow" aria-hidden="true">→</span>
                    <em
                        data-road-reflex-guidance
                        data-default="{{ $reflex['instruction'] }}"
                        data-complete="{{ $reflex['complete'] }}"
                    >{{ $reflex['instruction'] }}</em>
                </div>

                <p class="g3-road-reflex__reminder">
                    <span aria-hidden="true">i</span>
                    <strong>{{ $reflex['reminder_label'] }}</strong>
                    {{ $reflex['reminder'] }}
                </p>
            </div>
        </div>
    </section>
@endsection
