@php
    use App\Support\PublicNavigation;

    $isFrench = $locale === 'fr';

    $journeyCopy = [
        'overline' => $isFrench ? 'Votre parcours' : 'Your journey',
        'title' => $isFrench
            ? 'De votre arrivée au résultat, chaque étape est claire.'
            : 'From arrival to result, every step is clear.',
        'lead' => $isFrench
            ? 'Un processus simple et structuré pour un contrôle fiable et transparent.'
            : 'A simple, structured process for a reliable and transparent inspection.',
    ];

    $journeySteps = [
        [
            'number' => '01',
            'slug' => 'preparation',
            'label' => $isFrench ? 'Préparation' : 'Preparation',
            'summary' => $isFrench
                ? 'Je rassemble les documents et je prépare mon véhicule.'
                : 'I gather the documents and prepare my vehicle.',
            'image' => 'preparation.png',
            'eyebrow' => $isFrench ? '01 · Préparation' : '01 · Preparation',
            'title' => $isFrench
                ? 'Un contrôle serein commence par une bonne préparation.'
                : 'A smooth inspection starts with good preparation.',
            'body' => $isFrench
                ? 'Avant de vous rendre au centre, assurez-vous d’avoir les documents nécessaires et que votre véhicule est en bon état général.'
                : 'Before coming to the centre, make sure you have the required documents and that your vehicle is generally ready.',
            'bullets' => [
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Rassemblez les documents requis' : 'Gather the required documents',
                    'body' => null,
                ],
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Vérifiez les éléments essentiels de votre véhicule' : 'Check the key vehicle items',
                    'body' => null,
                ],
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Choisissez votre centre et votre créneau' : 'Choose your centre and time slot',
                    'body' => null,
                ],
            ],
        ],
        [
            'number' => '02',
            'slug' => 'reception',
            'label' => $isFrench ? 'Accueil & identification' : 'Reception & identification',
            'summary' => $isFrench
                ? 'Vérification des documents et enregistrement du véhicule.'
                : 'Document checks and vehicle registration.',
            'image' => 'reception.png',
            'eyebrow' => $isFrench ? '02 · Accueil & identification' : '02 · Reception & identification',
            'title' => $isFrench
                ? 'Un accueil structuré permet un enregistrement fiable du véhicule.'
                : 'A structured reception creates a reliable vehicle registration.',
            'body' => $isFrench
                ? 'À votre arrivée au centre, nos équipes vérifient vos documents, identifient le véhicule et procèdent à son enregistrement avant le passage sur la ligne de contrôle.'
                : 'When you arrive, our team checks your documents, identifies the vehicle and registers it before the inspection lane.',
            'bullets' => [
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Vérification des documents requis' : 'Required document check',
                    'body' => null,
                ],
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Identification et enregistrement du véhicule' : 'Vehicle identification and registration',
                    'body' => null,
                ],
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Orientation vers la ligne de contrôle' : 'Direction to the inspection lane',
                    'body' => null,
                ],
            ],
        ],
        [
            'number' => '03',
            'slug' => 'ligne',
            'label' => $isFrench ? 'Contrôle' : 'Inspection',
            'summary' => $isFrench
                ? 'Ligne de contrôle et mesures techniques.'
                : 'Inspection lane and technical measurements.',
            'image' => 'passge sur la ligne.png',
            'eyebrow' => $isFrench ? '03 · Contrôle' : '03 · Inspection',
            'title' => $isFrench
                ? 'Des mesures précises et un contrôle complet.'
                : 'Precise measurements and a complete inspection.',
            'body' => $isFrench
                ? 'Votre véhicule est dirigé sur la ligne de contrôle où nos équipements réalisent les mesures techniques et nos agents effectuent les contrôles visuels selon les normes en vigueur.'
                : 'Your vehicle moves through the inspection lane where our equipment performs technical measurements and our team completes visual checks.',
            'bullets' => [
                [
                    'icon' => 'journey-vehicle.svg',
                    'title' => $isFrench ? 'Positionnement' : 'Positioning',
                    'body' => $isFrench
                        ? 'Votre véhicule est placé sur la ligne de contrôle adaptée à sa catégorie.'
                        : 'Your vehicle is positioned on the lane adapted to its category.',
                ],
                [
                    'icon' => 'journey-line.svg',
                    'title' => $isFrench ? 'Mesures techniques' : 'Technical measurements',
                    'body' => $isFrench
                        ? 'Les équipements effectuent les mesures : freinage, ripage, émissions, etc.'
                        : 'Equipment performs measurements such as braking, side-slip and emissions.',
                ],
                [
                    'icon' => 'journey-eye.svg',
                    'title' => $isFrench ? 'Contrôle visuel' : 'Visual inspection',
                    'body' => $isFrench
                        ? 'Nos agents vérifient les éléments essentiels de votre véhicule.'
                        : 'Our team checks the essential parts of your vehicle.',
                ],
            ],
        ],
        [
            'number' => '04',
            'slug' => 'analyse',
            'label' => $isFrench ? 'Analyse des résultats' : 'Results analysis',
            'summary' => $isFrench
                ? 'Traitement des données et observations.'
                : 'Data processing and observations.',
            'image' => 'results-analyses.png',
            'eyebrow' => $isFrench ? '04 · Analyse des résultats' : '04 · Results analysis',
            'title' => $isFrench
                ? 'Des données analysées avec rigueur.'
                : 'Data analysed with rigour.',
            'body' => $isFrench
                ? 'Les résultats des différents contrôles sont centralisés, analysés et comparés aux normes en vigueur pour déterminer l’état général de votre véhicule.'
                : 'The results of each check are centralised, analysed and compared with standards to determine your vehicle’s overall condition.',
            'bullets' => [
                [
                    'icon' => 'journey-line.svg',
                    'title' => $isFrench ? 'Traitement des données' : 'Data processing',
                    'body' => $isFrench
                        ? 'Les mesures relevées par les équipements sont analysées automatiquement.'
                        : 'Measurements collected by the equipment are automatically analysed.',
                ],
                [
                    'icon' => 'journey-report.svg',
                    'title' => $isFrench ? 'Analyse des observations' : 'Observation review',
                    'body' => $isFrench
                        ? 'Les points de contrôle visuels sont pris en compte par nos agents techniques.'
                        : 'Visual inspection observations are reviewed by our technical staff.',
                ],
                [
                    'icon' => 'journey-shield.svg',
                    'title' => $isFrench ? 'Détermination du résultat' : 'Result determination',
                    'body' => $isFrench
                        ? 'Un résultat global est établi selon les critères réglementaires en vigueur.'
                        : 'A global result is established using the applicable regulatory criteria.',
                ],
            ],
        ],
        [
            'number' => '05',
            'slug' => 'restitution',
            'label' => $isFrench ? 'Résultat & suite' : 'Result & next steps',
            'summary' => $isFrench
                ? 'Votre résultat et les prochaines étapes.'
                : 'Your result and next steps.',
            'image' => 'restitution et suite.png',
            'eyebrow' => $isFrench ? '05 · Résultat & suite' : '05 · Result & next steps',
            'title' => $isFrench
                ? 'Votre résultat vous est remis avec des explications claires.'
                : 'Your result is handed over with clear explanations.',
            'body' => $isFrench
                ? 'Après le contrôle, nous examinons ensemble les résultats et nous vous expliquons clairement les prochaines étapes à suivre.'
                : 'After the inspection, we review the results with you and clearly explain the next steps.',
            'bullets' => [
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Recevez votre résultat et les documents associés' : 'Receive your result and related documents',
                    'body' => null,
                ],
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Prenez connaissance des observations éventuelles' : 'Review any observations',
                    'body' => null,
                ],
                [
                    'icon' => 'journey-check.svg',
                    'title' => $isFrench ? 'Suivez la suite indiquée ou planifiez une contre-visite si nécessaire' : 'Follow the next step or plan a re-inspection if needed',
                    'body' => null,
                ],
            ],
        ],
    ];

    $controlExplorer = [
        'overline' => $isFrench ? 'Points de contrôle' : 'Control points',
        'title' => $isFrench ? 'Ce que G3 contrôle réellement.' : 'What G3 actually checks.',
        'lead' => $isFrench
            ? 'Des contrôles essentiels pour votre sécurité et celle de tous sur la route.'
            : 'Essential checks for your safety and for everyone on the road.',
        'link' => $isFrench ? 'En savoir plus sur les contrôles' : 'Learn more about the checks',
    ];

    $controlFamilies = [
        [
            'slug' => 'freinage',
            'icon' => 'control-braking.svg',
            'label' => $isFrench ? 'Freinage' : 'Braking',
            'title' => $isFrench ? 'Freinage' : 'Braking',
            'subtitle' => $isFrench ? 'Une sécurité essentielle pour tous.' : 'Essential safety for everyone.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'Vérifier l’efficacité et l’équilibre du système de freinage pour assurer votre sécurité.'
                        : 'Check the efficiency and balance of the braking system to support safe driving.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Mesure des forces de freinage sur un banc à rouleaux, par roue et par essieu.'
                        : 'Measurement of braking forces on a roller brake tester, by wheel and axle.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Banc de freinage.' : 'Brake tester.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'L’efficacité, l’équilibre et l’état général du système de freinage.'
                        : 'Efficiency, balance and the overall condition of the braking system.',
                ],
            ],
        ],
        [
            'slug' => 'ripage',
            'icon' => 'control-ripage.svg',
            'label' => $isFrench ? 'Ripage' : 'Side-slip',
            'title' => $isFrench ? 'Ripage' : 'Side-slip',
            'subtitle' => $isFrench ? 'Un indicateur clé de la tenue de route.' : 'A key indicator of road holding.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'Contrôler la trajectoire naturelle du véhicule et repérer les écarts pouvant affecter la stabilité.'
                        : 'Check the vehicle’s natural trajectory and detect deviations that can affect stability.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Passage sur une plaque à ripage afin d’observer la dérive latérale du train roulant.'
                        : 'Passage over a side-slip plate to observe lateral drift in the running gear.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Plaque à ripage.' : 'Side-slip plate.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'L’alignement, la stabilité directionnelle et les dérives anormales.'
                        : 'Alignment, directional stability and abnormal drift.',
                ],
            ],
        ],
        [
            'slug' => 'suspension',
            'icon' => 'control-suspension.svg',
            'label' => $isFrench ? 'Suspension' : 'Suspension',
            'title' => $isFrench ? 'Suspension' : 'Suspension',
            'subtitle' => $isFrench ? 'Le confort et l’adhérence sous contrôle.' : 'Comfort and grip under control.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'Vérifier la capacité des suspensions à maintenir le contact du véhicule avec la route.'
                        : 'Check the ability of the suspension system to keep the vehicle in contact with the road.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Mesure comparative de la réaction des roues et des essieux sur banc de suspension.'
                        : 'Comparative measurement of wheel and axle response on a suspension tester.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Banc de suspension.' : 'Suspension tester.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'L’adhérence, la stabilité et les déséquilibres éventuels entre les roues.'
                        : 'Grip, stability and possible imbalance between wheels.',
                ],
            ],
        ],
        [
            'slug' => 'liaisons',
            'icon' => 'control-ground.svg',
            'label' => $isFrench ? 'Liaisons au sol' : 'Road contact',
            'title' => $isFrench ? 'Liaisons au sol' : 'Road contact',
            'subtitle' => $isFrench ? 'Les organes qui relient le véhicule à la route.' : 'The systems that connect the vehicle to the road.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'Repérer les jeux, usures ou anomalies pouvant compromettre la stabilité du véhicule.'
                        : 'Detect looseness, wear or anomalies that may compromise vehicle stability.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Contrôles visuels et vérifications des éléments essentiels des trains roulants.'
                        : 'Visual checks and verification of key running gear elements.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Fosse d’inspection et équipements de contrôle.' : 'Inspection pit and control equipment.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'L’état des trains roulants, des pneus et des principaux organes de liaison.'
                        : 'The condition of running gear, tyres and the main contact systems.',
                ],
            ],
        ],
        [
            'slug' => 'eclairage',
            'icon' => 'control-light.svg',
            'label' => $isFrench ? 'Éclairage' : 'Lighting',
            'title' => $isFrench ? 'Éclairage' : 'Lighting',
            'subtitle' => $isFrench ? 'Voir et être vu clairement.' : 'See clearly and be seen.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'S’assurer que les dispositifs d’éclairage et de signalisation restent visibles et correctement réglés.'
                        : 'Ensure lighting and signalling devices remain visible and correctly adjusted.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Contrôle du fonctionnement, de l’intensité apparente et du réglage des feux.'
                        : 'Check operation, apparent intensity and alignment of lights.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Réglophare.' : 'Headlamp tester.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'La visibilité, le réglage et le bon fonctionnement des feux.'
                        : 'Visibility, alignment and correct operation of lights.',
                ],
            ],
        ],
        [
            'slug' => 'emissions',
            'icon' => 'control-emissions.svg',
            'label' => $isFrench ? 'Émissions' : 'Emissions',
            'title' => $isFrench ? 'Émissions' : 'Emissions',
            'subtitle' => $isFrench ? 'Un contrôle utile pour un véhicule plus propre.' : 'A useful check for a cleaner vehicle.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'Vérifier que les émissions restent cohérentes avec les exigences applicables.'
                        : 'Check that emissions remain consistent with applicable requirements.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Mesure instrumentée des gaz ou fumées selon le type de véhicule.'
                        : 'Instrumented measurement of gases or smoke depending on vehicle type.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Analyseur de gaz.' : 'Gas analyser.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'Le niveau d’émissions et le comportement général du moteur.'
                        : 'Emission levels and the general behaviour of the engine.',
                ],
            ],
        ],
        [
            'slug' => 'structure',
            'icon' => 'control-visual.svg',
            'label' => $isFrench ? 'Structure & visuel' : 'Structure & visual',
            'title' => $isFrench ? 'Structure & contrôle visuel' : 'Structure & visual inspection',
            'subtitle' => $isFrench ? 'Une observation professionnelle des éléments visibles.' : 'A professional observation of visible elements.',
            'facts' => [
                [
                    'icon' => 'control-why.svg',
                    'label' => $isFrench ? 'Pourquoi ?' : 'Why?',
                    'body' => $isFrench
                        ? 'Identifier les anomalies visibles qui peuvent affecter la sécurité ou l’usage du véhicule.'
                        : 'Identify visible issues that can affect safety or vehicle use.',
                ],
                [
                    'icon' => 'control-method.svg',
                    'label' => $isFrench ? 'Comment ?' : 'How?',
                    'body' => $isFrench
                        ? 'Inspection méthodique des éléments visibles, de la structure et des organes accessibles.'
                        : 'Methodical inspection of visible elements, structure and accessible parts.',
                ],
                [
                    'icon' => 'control-equipment.svg',
                    'label' => $isFrench ? 'Équipement utilisé' : 'Equipment used',
                    'body' => $isFrench ? 'Fosse d’inspection et contrôle visuel.' : 'Inspection pit and visual checks.',
                ],
                [
                    'icon' => 'control-evaluate.svg',
                    'label' => $isFrench ? 'Ce que cela permet d’évaluer' : 'What it helps assess',
                    'body' => $isFrench
                        ? 'L’état général, les déformations visibles et les anomalies de sécurité.'
                        : 'Overall condition, visible deformations and safety issues.',
                ],
            ],
        ],
    ];

    $insideLine = [
        'overline' => $isFrench ? 'À l’intérieur du centre' : 'Inside the centre',
        'title' => $isFrench
            ? 'La rigueur se voit dans la manière de contrôler.'
            : 'Rigour shows in the way the inspection is carried out.',
        'lead' => $isFrench
            ? 'Des installations modernes et une équipe expérimentée pour des contrôles fiables.'
            : 'Modern facilities and an experienced team for reliable inspections.',
        'videoLabel' => $isFrench ? 'Voir une visite en conditions réelles' : 'See a real inspection visit',
    ];

    $insideProofPoints = [
        [
            'icon' => 'proof-procedures.svg',
            'title' => $isFrench ? 'Procédures structurées' : 'Structured procedures',
            'body' => $isFrench
                ? 'Un parcours technique organisé pour chaque véhicule.'
                : 'An organised technical path for each vehicle.',
        ],
        [
            'icon' => 'proof-equipment.svg',
            'title' => $isFrench ? 'Équipements spécialisés' : 'Specialised equipment',
            'body' => $isFrench
                ? 'Des installations adaptées aux opérations de contrôle.'
                : 'Facilities adapted to inspection operations.',
        ],
        [
            'icon' => 'proof-team.svg',
            'title' => $isFrench ? 'Personnel technique' : 'Technical staff',
            'body' => $isFrench
                ? 'Une équipe dédiée au bon déroulement de la visite.'
                : 'A team dedicated to a smooth inspection visit.',
        ],
        [
            'icon' => 'proof-visual.svg',
            'title' => $isFrench ? 'Contrôle visuel & mesures' : 'Visual checks & measurements',
            'body' => $isFrench
                ? 'Des observations professionnelles complétées par des mesures instrumentées.'
                : 'Professional observations supported by instrumented measurements.',
        ],
    ];

    $equipmentRail = $isFrench
        ? ['Banc de freinage', 'Banc de suspension', 'Plaque à ripage', 'Réglophare', 'Analyseur de gaz', 'Fosse d’inspection']
        : ['Brake tester', 'Suspension tester', 'Side-slip plate', 'Headlamp tester', 'Gas analyser', 'Inspection pit'];

    $feesUrl = PublicNavigation::pageUrl('fees', $locale);
    $appointmentUrl = PublicNavigation::pageUrl('appointment', $locale);

    $visitPreparation = [
        'overline' => $isFrench ? 'Avant votre visite' : 'Before your visit',
        'title' => $isFrench
            ? 'Préparez uniquement ce qui concerne votre véhicule.'
            : 'Prepare only what concerns your vehicle.',
        'lead' => $isFrench
            ? 'Sélectionnez votre véhicule et la prestation pour obtenir la liste des documents et nos conseils.'
            : 'Select your vehicle and service to get the documents list and our advice.',
        'vehicleLabel' => $isFrench ? 'Votre catégorie de véhicule' : 'Your vehicle category',
        'serviceLabel' => $isFrench ? 'Type de visite / prestation' : 'Visit / service type',
        'vehiclePlaceholder' => $isFrench ? 'Choisir une catégorie' : 'Choose a category',
        'servicePlaceholder' => $isFrench ? 'Choisir une prestation' : 'Choose a service',
        'documentsTitle' => $isFrench ? 'Documents à présenter' : 'Documents to present',
        'beforeTitle' => $isFrench ? 'Avant de venir' : 'Before coming',
        'visitTitle' => $isFrench ? 'Votre visite' : 'Your visit',
        'centreLabel' => $isFrench ? 'Centre' : 'Centre',
        'centrePlaceholder' => $isFrench ? 'Choisir un centre' : 'Choose a centre',
        'tariffLabel' => $isFrench ? 'Voir le tarif applicable' : 'See applicable tariff',
        'appointmentLabel' => $isFrench ? 'Prendre rendez-vous' : 'Book an appointment',
    ];

    $vehicleCategories = [
        ['value' => 'light', 'label' => $isFrench ? 'Véhicule léger' : 'Light vehicle'],
        ['value' => 'utility', 'label' => $isFrench ? 'Utilitaire' : 'Utility vehicle'],
        ['value' => 'transport', 'label' => $isFrench ? 'Taxi & transport' : 'Taxi & transport'],
        ['value' => 'heavy', 'label' => $isFrench ? 'Poids lourd' : 'Heavy vehicle'],
    ];

    $serviceTypes = [
        ['value' => 'periodic', 'label' => $isFrench ? 'Visite technique périodique' : 'Periodic technical inspection'],
        ['value' => 'counter', 'label' => $isFrench ? 'Contre-visite' : 'Re-inspection'],
        ['value' => 'specific', 'label' => $isFrench ? 'Contrôle spécifique' : 'Specific check'],
    ];

    $centreOptions = [
        ['value' => 'ecole-de-police', 'label' => $isFrench ? 'École de Police' : 'École de Police'],
        ['value' => 'nomayos', 'label' => $isFrench ? 'Nomayos' : 'Nomayos'],
    ];

    $preparationRules = [
        'default' => [
            'documents' => $isFrench
                ? ['Carte grise du véhicule', 'Pièce d’identité du propriétaire', 'Attestation d’assurance en cours de validité', 'Autres documents (selon votre situation)']
                : ['Vehicle registration card', 'Owner ID document', 'Valid insurance certificate', 'Other documents depending on your situation'],
            'before' => $isFrench
                ? ['Véhicule propre et accessible', 'Éléments d’identification visibles', 'Équipements obligatoires en place', 'Vérifiez l’état général (pneus, éclairage, etc.)']
                : ['Clean and accessible vehicle', 'Visible identification elements', 'Mandatory equipment in place', 'Check the general condition (tyres, lights, etc.)'],
        ],
        'light' => [
            'documents' => $isFrench
                ? ['Carte grise du véhicule', 'Pièce d’identité du propriétaire', 'Attestation d’assurance en cours de validité', 'Autres documents (selon votre situation)']
                : ['Vehicle registration card', 'Owner ID document', 'Valid insurance certificate', 'Other documents depending on your situation'],
            'before' => $isFrench
                ? ['Véhicule propre et accessible', 'Pneus, feux et plaques lisibles', 'Ceintures et équipements obligatoires en place', 'Habitacle dégagé pour faciliter le contrôle']
                : ['Clean and accessible vehicle', 'Readable tyres, lights and plates', 'Seat belts and mandatory equipment in place', 'Clear cabin for easier inspection'],
        ],
        'utility' => [
            'documents' => $isFrench
                ? ['Carte grise du véhicule', 'Pièce d’identité du propriétaire', 'Assurance en cours de validité', 'Documents liés à l’activité si nécessaire']
                : ['Vehicle registration card', 'Owner ID document', 'Valid insurance', 'Activity documents if required'],
            'before' => $isFrench
                ? ['Compartiment de chargement accessible', 'Plaques et marquages visibles', 'Équipements obligatoires en place', 'État général et pneumatiques vérifiés']
                : ['Cargo area accessible', 'Plates and markings visible', 'Mandatory equipment in place', 'General condition and tyres checked'],
        ],
        'transport' => [
            'documents' => $isFrench
                ? ['Carte grise du véhicule', 'Pièce d’identité', 'Assurance en cours de validité', 'Documents d’exploitation ou autorisations applicables']
                : ['Vehicle registration card', 'ID document', 'Valid insurance', 'Operating documents or applicable authorisations'],
            'before' => $isFrench
                ? ['Places passagers accessibles', 'Signalisation et identification visibles', 'Équipements de sécurité en place', 'Vérifiez pneus, éclairage et propreté']
                : ['Passenger seats accessible', 'Signage and identification visible', 'Safety equipment in place', 'Check tyres, lights and cleanliness'],
        ],
        'heavy' => [
            'documents' => $isFrench
                ? ['Carte grise du véhicule', 'Pièce d’identité', 'Assurance en cours de validité', 'Documents complémentaires selon configuration']
                : ['Vehicle registration card', 'ID document', 'Valid insurance', 'Additional documents depending on configuration'],
            'before' => $isFrench
                ? ['Accès technique dégagé', 'Équipements obligatoires vérifiés', 'Identification et plaques visibles', 'État des pneumatiques et éclairages contrôlé']
                : ['Technical access clear', 'Mandatory equipment checked', 'Identification and plates visible', 'Tyres and lights checked'],
        ],
        'counter' => [
            'documents' => $isFrench
                ? ['Rapport de visite précédent', 'Carte grise du véhicule', 'Pièce d’identité', 'Justificatifs liés aux corrections si disponibles']
                : ['Previous inspection report', 'Vehicle registration card', 'ID document', 'Documents related to corrections if available'],
            'before' => $isFrench
                ? ['Corrigez les points signalés', 'Gardez le rapport précédent accessible', 'Vérifiez les éléments concernés', 'Choisissez le centre de suivi']
                : ['Correct the reported points', 'Keep the previous report accessible', 'Check the concerned items', 'Choose the follow-up centre'],
        ],
    ];

    $resultSection = [
        'overline' => $isFrench ? 'Après le contrôle' : 'After the inspection',
        'title' => $isFrench
            ? 'Votre résultat vous indique clairement la suite.'
            : 'Your result clearly shows the next step.',
        'lead' => $isFrench
            ? 'Quelles que soient les observations, G3 Control vous accompagne.'
            : 'Whatever the observations, G3 Control supports you.',
    ];

    $resultPaths = [
        [
            'tone' => 'valid',
            'icon' => 'result-valid.svg',
            'title' => $isFrench ? 'Situation conforme' : 'Compliant situation',
            'body' => $isFrench
                ? 'Votre véhicule répond aux exigences. Vous recevez votre rapport et l’autorisation de circuler selon la durée de validité en vigueur.'
                : 'Your vehicle meets the requirements. You receive your report and authorisation to circulate according to the validity period in force.',
        ],
        [
            'tone' => 'action',
            'icon' => 'result-action.svg',
            'title' => $isFrench ? 'Action requise' : 'Action required',
            'body' => $isFrench
                ? 'Des points nécessitent une correction. Vous êtes informé des éléments à traiter et des prochaines étapes.'
                : 'Some points require correction. You are informed of the items to address and the next steps.',
        ],
        [
            'tone' => 'counter',
            'icon' => 'result-counter.svg',
            'title' => $isFrench ? 'Contre-visite' : 'Re-inspection',
            'body' => $isFrench
                ? 'Une contre-visite peut être nécessaire après correction des défauts constatés. G3 vous explique la marche à suivre et les délais applicables.'
                : 'A re-inspection may be required after correcting observed defects. G3 explains the process and applicable deadlines.',
        ],
    ];
@endphp

@extends('layouts.public')

@section('content')
    <section class="g3-technical-journey" aria-labelledby="technical-journey-title" data-technical-journey>
        <div class="g3-technical-journey__inner">
            <header class="g3-technical-journey__header">
                <p class="g3-technical-journey__overline">{{ $journeyCopy['overline'] }}</p>
                <h1 id="technical-journey-title">{{ $journeyCopy['title'] }}</h1>
                <p>{{ $journeyCopy['lead'] }}</p>
            </header>

            <div class="g3-technical-journey__steps" role="tablist" aria-label="{{ $journeyCopy['overline'] }}">
                @foreach ($journeySteps as $index => $step)
                    @php
                        $isActive = $index === 0;
                    @endphp

                    <button
                        type="button"
                        id="technical-step-tab-{{ $step['slug'] }}"
                        class="g3-technical-journey__step{{ $isActive ? ' g3-technical-journey__step--active' : '' }}"
                        role="tab"
                        aria-selected="{{ $isActive ? 'true' : 'false' }}"
                        aria-controls="technical-step-panel-{{ $step['slug'] }}"
                        tabindex="{{ $isActive ? '0' : '-1' }}"
                        data-technical-step-tab
                        data-technical-step-target="{{ $step['slug'] }}"
                    >
                        <span class="g3-technical-journey__step-number">{{ $step['number'] }}</span>
                        <span class="g3-technical-journey__step-copy">
                            <strong>{{ $step['label'] }}</strong>
                            <span>{{ $step['summary'] }}</span>
                        </span>
                    </button>
                @endforeach
            </div>

            <div class="g3-technical-journey__panels">
                @foreach ($journeySteps as $index => $step)
                    @php
                        $isActive = $index === 0;
                    @endphp

                    <article
                        id="technical-step-panel-{{ $step['slug'] }}"
                        class="g3-technical-journey__panel"
                        role="tabpanel"
                        aria-labelledby="technical-step-tab-{{ $step['slug'] }}"
                        data-technical-step-panel
                        data-technical-step-key="{{ $step['slug'] }}"
                        @if (! $isActive) hidden @endif
                    >
                        <figure class="g3-technical-journey__media">
                            <img
                                src="{{ asset('images/technical-inspection/'.$step['image']) }}"
                                alt=""
                                aria-hidden="true"
                            >
                        </figure>

                        <div class="g3-technical-journey__panel-copy">
                            <p class="g3-technical-journey__panel-overline">{{ $step['eyebrow'] }}</p>
                            <h2>{{ $step['title'] }}</h2>
                            <p class="g3-technical-journey__panel-lead">{{ $step['body'] }}</p>

                            <div class="g3-technical-journey__checks">
                                @foreach ($step['bullets'] as $bullet)
                                    <div class="g3-technical-journey__check">
                                        <img
                                            src="{{ asset('images/technical-inspection/'.$bullet['icon']) }}"
                                            alt=""
                                            aria-hidden="true"
                                        >
                                        <div>
                                            <strong>{{ $bullet['title'] }}</strong>
                                            @if ($bullet['body'] !== null)
                                                <span>{{ $bullet['body'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="g3-control-explorer" id="points-de-controle" aria-labelledby="control-explorer-title" data-inspection-control-explorer>
        <div class="g3-control-explorer__inner">
            <header class="g3-control-explorer__header">
                <div>
                    <p class="g3-control-explorer__overline">{{ $controlExplorer['overline'] }}</p>
                    <h2 id="control-explorer-title">{{ $controlExplorer['title'] }}</h2>
                    <p>{{ $controlExplorer['lead'] }}</p>
                </div>

                <a href="#inspection-line" class="g3-control-explorer__link">
                    {{ $controlExplorer['link'] }}
                    <span aria-hidden="true">→</span>
                </a>
            </header>

            <div class="g3-control-explorer__grid">
                <div class="g3-control-explorer__tabs" role="tablist" aria-label="{{ $controlExplorer['overline'] }}">
                    @foreach ($controlFamilies as $index => $control)
                        @php
                            $isActive = $index === 0;
                        @endphp

                        <button
                            type="button"
                            id="control-family-tab-{{ $control['slug'] }}"
                            class="g3-control-explorer__tab{{ $isActive ? ' g3-control-explorer__tab--active' : '' }}"
                            role="tab"
                            aria-selected="{{ $isActive ? 'true' : 'false' }}"
                            aria-controls="control-family-panel-{{ $control['slug'] }}"
                            tabindex="{{ $isActive ? '0' : '-1' }}"
                            data-inspection-control-tab
                            data-inspection-control-target="{{ $control['slug'] }}"
                        >
                            <img
                                src="{{ asset('images/technical-inspection/'.$control['icon']) }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <span>{{ $control['label'] }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="g3-control-explorer__panels">
                    @foreach ($controlFamilies as $index => $control)
                        @php
                            $isActive = $index === 0;
                        @endphp

                        <article
                            id="control-family-panel-{{ $control['slug'] }}"
                            class="g3-control-explorer__panel"
                            role="tabpanel"
                            aria-labelledby="control-family-tab-{{ $control['slug'] }}"
                            data-inspection-control-panel
                            data-inspection-control-key="{{ $control['slug'] }}"
                            @if (! $isActive) hidden @endif
                        >
                            <p class="g3-control-explorer__panel-kicker">{{ $control['title'] }}</p>
                            <h3>{{ $control['subtitle'] }}</h3>

                            <div class="g3-control-explorer__facts">
                                @foreach ($control['facts'] as $fact)
                                    <div class="g3-control-explorer__fact">
                                        <img
                                            src="{{ asset('images/technical-inspection/'.$fact['icon']) }}"
                                            alt=""
                                            aria-hidden="true"
                                        >
                                        <div>
                                            <strong>{{ $fact['label'] }}</strong>
                                            <span>{{ $fact['body'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>

                <figure class="g3-control-explorer__media">
                    <img
                        src="{{ asset('images/technical-inspection/control-points.png') }}"
                        alt=""
                        aria-hidden="true"
                    >
                </figure>
            </div>
        </div>
    </section>

    <section class="g3-inspection-line" id="inspection-line" aria-labelledby="inspection-line-title">
        <div class="g3-inspection-line__inner">
            <header class="g3-inspection-line__header">
                <p class="g3-inspection-line__overline">{{ $insideLine['overline'] }}</p>
                <h2 id="inspection-line-title">{{ $insideLine['title'] }}</h2>
                <p>{{ $insideLine['lead'] }}</p>
            </header>

            <div class="g3-inspection-line__layout">
                <figure class="g3-inspection-line__video">
                    <video
                        src="{{ asset('images/technical-inspection/inspection.mp4') }}"
                        controls
                        preload="metadata"
                        playsinline
                    ></video>
                    <figcaption>{{ $insideLine['videoLabel'] }}</figcaption>
                </figure>

                <div class="g3-inspection-line__proofs">
                    @foreach ($insideProofPoints as $point)
                        <div class="g3-inspection-line__proof">
                            <img
                                src="{{ asset('images/technical-inspection/'.$point['icon']) }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <div>
                                <h3>{{ $point['title'] }}</h3>
                                <p>{{ $point['body'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="g3-inspection-line__rail" aria-label="{{ $isFrench ? 'Équipements' : 'Equipment' }}">
                @foreach ($equipmentRail as $equipment)
                    <span>{{ $equipment }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="g3-visit-prep" aria-labelledby="visit-prep-title" data-visit-preparation>
        <script type="application/json" data-visit-preparation-config>@json($preparationRules)</script>

        <div class="g3-visit-prep__inner">
            <header class="g3-visit-prep__header">
                <p class="g3-visit-prep__overline">{{ $visitPreparation['overline'] }}</p>
                <h2 id="visit-prep-title">{{ $visitPreparation['title'] }}</h2>
                <p>{{ $visitPreparation['lead'] }}</p>
            </header>

            <div class="g3-visit-prep__card">
                <div class="g3-visit-prep__primary">
                    <div class="g3-visit-prep__selector">
                        <label for="visit-prep-vehicle">{{ $visitPreparation['vehicleLabel'] }}</label>
                        <div class="g3-visit-prep__select-control">
                            <img
                                src="{{ asset('images/technical-inspection/prep-vehicle.svg') }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <select id="visit-prep-vehicle" data-visit-preparation-vehicle>
                                <option value="default">{{ $visitPreparation['vehiclePlaceholder'] }}</option>
                                @foreach ($vehicleCategories as $category)
                                    <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="g3-visit-prep__list-block">
                        <div class="g3-visit-prep__block-title">
                            <img
                                src="{{ asset('images/technical-inspection/prep-documents.svg') }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <h3>{{ $visitPreparation['documentsTitle'] }}</h3>
                        </div>
                        <ul data-visit-preparation-documents>
                            @foreach ($preparationRules['default']['documents'] as $document)
                                <li>
                                    <span aria-hidden="true"></span>
                                    {{ $document }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="g3-visit-prep__primary">
                    <div class="g3-visit-prep__selector">
                        <label for="visit-prep-service">{{ $visitPreparation['serviceLabel'] }}</label>
                        <div class="g3-visit-prep__select-control">
                            <img
                                src="{{ asset('images/technical-inspection/prep-service.svg') }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <select id="visit-prep-service" data-visit-preparation-service>
                                <option value="default">{{ $visitPreparation['servicePlaceholder'] }}</option>
                                @foreach ($serviceTypes as $service)
                                    <option value="{{ $service['value'] }}">{{ $service['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="g3-visit-prep__list-block">
                        <div class="g3-visit-prep__block-title">
                            <img
                                src="{{ asset('images/technical-inspection/prep-before.svg') }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <h3>{{ $visitPreparation['beforeTitle'] }}</h3>
                        </div>
                        <ul data-visit-preparation-before>
                            @foreach ($preparationRules['default']['before'] as $instruction)
                                <li>
                                    <span aria-hidden="true"></span>
                                    {{ $instruction }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <aside class="g3-visit-prep__visit" aria-label="{{ $visitPreparation['visitTitle'] }}">
                    <div class="g3-visit-prep__visit-title">
                        <span aria-hidden="true"></span>
                        <h3>{{ $visitPreparation['visitTitle'] }}</h3>
                    </div>

                    <div class="g3-visit-prep__selector">
                        <label for="visit-prep-centre">{{ $visitPreparation['centreLabel'] }}</label>
                        <div class="g3-visit-prep__select-control">
                            <img
                                src="{{ asset('images/technical-inspection/prep-center.svg') }}"
                                alt=""
                                aria-hidden="true"
                            >
                            <select id="visit-prep-centre" data-visit-preparation-centre>
                                <option value="">{{ $visitPreparation['centrePlaceholder'] }}</option>
                                @foreach ($centreOptions as $centre)
                                    <option value="{{ $centre['value'] }}">{{ $centre['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <a href="{{ $feesUrl }}" class="g3-visit-prep__button g3-visit-prep__button--secondary">
                        <img
                            src="{{ asset('images/technical-inspection/prep-service.svg') }}"
                            alt=""
                            aria-hidden="true"
                        >
                        {{ $visitPreparation['tariffLabel'] }}
                    </a>

                    <a href="{{ $appointmentUrl }}" class="g3-visit-prep__button g3-visit-prep__button--primary">
                        <img
                            src="{{ asset('images/technical-inspection/journey-documents.svg') }}"
                            alt=""
                            aria-hidden="true"
                        >
                        {{ $visitPreparation['appointmentLabel'] }}
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    <section class="g3-result-next" aria-labelledby="result-next-title">
        <div class="g3-result-next__inner">
            <header class="g3-result-next__header">
                <p class="g3-result-next__overline">{{ $resultSection['overline'] }}</p>
                <h2 id="result-next-title">{{ $resultSection['title'] }}</h2>
                <p>{{ $resultSection['lead'] }}</p>
            </header>

            <div class="g3-result-next__grid">
                @foreach ($resultPaths as $path)
                    <article class="g3-result-next__card g3-result-next__card--{{ $path['tone'] }}">
                        <div class="g3-result-next__icon">
                            <img
                                src="{{ asset('images/technical-inspection/'.$path['icon']) }}"
                                alt=""
                                aria-hidden="true"
                            >
                        </div>
                        <div>
                            <h3>{{ $path['title'] }}</h3>
                            <p>{{ $path['body'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
