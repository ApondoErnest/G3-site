<?php

return [
    'brand' => 'G3 Control',

    'security' => [
        'contact_received' => 'Your message has been received. The G3 Control team will reply.',
        'appointment_received' => 'Your request has been received. Keep your reference.',
        'appointment_outside_hours' => 'This time is outside this centre\'s opening hours.',
        'appointment_required' => 'Enter the requested details to send your request.',
        'appointment_errors' => [
            'centre_id' => 'Select a centre.',
            'service_id' => 'Select the requested service.',
            'vehicle_category_id' => 'Select the vehicle category.',
            'registration' => 'Enter the registration number.',
            'preferred_date' => 'Enter the preferred date.',
            'preferred_period' => 'Select a preferred period.',
            'full_name' => 'Enter your full name.',
            'phone' => 'Enter a phone number.',
            'email' => 'Enter a valid email address.',
            'request_reference' => 'Enter the request reference.',
            'tracking_phone' => 'Enter the phone number used for the request.',
            'form' => 'The request could not be sent. Try again.',
        ],
        'appointment_phone' => 'Enter a valid Cameroon phone number, for example 6XX XXX XXX.',
        'appointment_unavailable' => 'This service is not available for this centre and vehicle category.',
        'tracking_failed' => 'We could not find this request. Check the reference and phone number, then try again.',
        'too_many' => 'Too many attempts. Try again later.',
        'tracking_found' => 'Your request status',
        'tracking_statuses' => [
            'received' => 'Request received',
            'under_review' => 'Under review',
            'confirmed' => 'Appointment confirmed',
            'modification_requested' => 'Action required',
            'completed' => 'Visit completed',
            'cancelled' => 'Request cancelled',
        ],
    ],

    'nav' => [
        'home' => 'Home',
        'about' => 'About',
        'centres' => 'Our centres',
        'services' => 'Services',
        'technical_inspection' => 'Technical inspection',
        'fees' => 'Fees',
        'road_safety' => 'Road safety',
        'contact' => 'Contact',
    ],

    'centres' => [
        'ecole_de_police' => 'École de Police',
        'nomayos' => 'Nomayos',
    ],

    'centres_page' => [
        'overline' => 'G3 Live',
        'title' => 'Which centre suits you today?',
        'lead' => 'Select a centre to see its opening status, hours, and directions.',
        'tabs_label' => 'G3 Control centre selection',
        'map' => [
            'title' => 'Google Maps map of G3 Control centres in Yaoundé',
            'markers_label' => 'G3 Control centre markers',
            'show_all' => 'View both centres',
            'open_google' => 'Open in Google Maps',
        ],
        'status' => [
            'open' => 'Open now',
            'closes_today' => 'Closes today at :time',
        ],
        'labels' => [
            'address' => 'Address',
            'phone' => 'Phone',
            'hours' => 'Hours',
            'holiday' => 'Public holidays',
        ],
        'hours' => [
            'weekday' => 'Monday – Saturday: :hours',
            'sunday' => 'Sunday: :hours',
            'holidays' => 'Public holidays: Open',
        ],
        'actions' => [
            'directions' => 'Directions',
            'call' => 'Call',
            'details' => 'View centre',
            'choose' => 'Choose this centre',
        ],
        'standard' => [
            'overline' => 'The G3 standard',
            'title' => 'Two locations. One inspection standard.',
            'items_label' => 'G3 standard guarantees',
            'items' => [
                'approval' => [
                    'title' => 'Approval No. 0291',
                    'body' => 'G3 Control · since 2020',
                ],
                'procedures' => [
                    'title' => 'Structured procedures',
                    'body' => '',
                ],
                'equipment' => [
                    'title' => 'Specialised equipment',
                    'body' => '',
                ],
                'team' => [
                    'title' => 'Dedicated staff',
                    'body' => '',
                ],
            ],
        ],
        'items' => [
            'ecole_de_police' => [
                'short_name' => 'École de Police',
                'title' => 'G3 Control — École de Police',
                'address' => 'Former Texaco descent, École de Police, Yaoundé',
                'image_alt' => 'Facade of the G3 Control École de Police centre',
            ],
            'nomayos' => [
                'short_name' => 'Nomayos',
                'title' => 'G3 Control — Nomayos',
                'address' => 'Nomayos junction, Yaoundé',
                'image_alt' => 'Reception area of the G3 Control Nomayos centre',
            ],
        ],
    ],

    'pages' => [
        'appointment' => 'Appointment & tracking',
    ],

    'cta' => [
        'appointment' => 'Appointment & tracking',
        'track' => 'Track my request',
    ],

    'home' => [
        'hero' => [
            'overline' => 'Automobile technical inspection centre',
            'title' => 'Safety starts with a rigorous inspection.',
            'lead' => 'G3 Control welcomes you to its two Yaoundé centres for a professional, simple, and transparent technical inspection.',
            'values_label' => 'G3 Control commitments',
            'values' => [
                'safety' => [
                    'label' => 'Safety',
                    'body' => 'Reliable checks for safer roads',
                ],
                'simplicity' => [
                    'label' => 'Simplicity',
                    'body' => 'A clear and quick process',
                ],
                'trust' => [
                    'label' => 'Trust',
                    'body' => 'A trusted partner since 2020',
                ],
            ],
            'primary_cta' => 'Book an appointment',
            'secondary_cta' => 'Find a centre',
            'fees_cta' => 'View fees',
            'live' => [
                'label' => 'G3 Control centre status',
                'title' => 'G3 LIVE',
                'subtitle' => ':count centres in Yaoundé',
                'link' => 'View centres',
                'open_until' => 'Open until :time',
                'opens_at' => 'Opens at :time',
                'closed' => 'Closed today',
            ],
        ],
        'start' => [
            'title' => 'Your visit starts here.',
            'subtitle' => 'Go directly to the service you need.',
            'actions_label' => 'Quick access',
            'actions' => [
                'appointment' => 'Book an appointment',
                'track' => 'Track my request',
                'fees' => 'Find my fee',
                'centre' => 'Choose my centre',
                'prepare' => 'Prepare my visit',
            ],
            'journey_title' => 'A clear visit, from start to finish.',
            'journey' => [
                'prepare' => [
                    'label' => 'Prepare',
                    'body' => 'Documents, fee, and centre',
                ],
                'arrive' => [
                    'label' => 'Arrive',
                    'body' => 'Reception and identification',
                ],
                'inspect' => [
                    'label' => 'Inspect',
                    'body' => 'Technical lane inspection',
                ],
                'analyse' => [
                    'label' => 'Analyse',
                    'body' => 'Measurement and control validation',
                ],
                'leave' => [
                    'label' => 'Leave informed',
                    'body' => 'Result and guidance',
                ],
            ],
        ],
        'control' => [
            'overline' => 'Technical inspection',
            'title' => 'What G3 really checks.',
            'lead' => 'Modern equipment and rigorous procedures for a complete evaluation of your vehicle.',
            'tabs_label' => 'Items checked by G3 Control',
            'learn_more' => 'Learn more',
            'checks' => [
                'braking' => [
                    'label' => 'Braking',
                    'body' => 'Evaluation of the efficiency and balance of the braking system using specialized equipment.',
                ],
                'suspension' => [
                    'label' => 'Suspension',
                    'body' => 'Checks of stability, shock absorbers, and components that support comfort and road handling.',
                ],
                'alignment' => [
                    'label' => 'Side slip / Alignment',
                    'body' => 'Directional behavior checks to detect alignment issues and limit tyre wear.',
                ],
                'lighting' => [
                    'label' => 'Lighting',
                    'body' => 'Checks of lights, intensity, and aim for dependable visibility.',
                ],
                'pollution' => [
                    'label' => 'Pollution',
                    'body' => 'Emissions measurement to confirm the environmental compliance of the vehicle.',
                ],
                'visual' => [
                    'label' => 'Visual inspection',
                    'body' => 'General inspection of safety components, bodywork, and mandatory equipment.',
                ],
            ],
        ],
        'equipment' => [
            'title' => 'Our main equipment',
            'controls_label' => 'Equipment navigation',
            'previous' => 'Previous equipment',
            'next' => 'Next equipment',
            'items' => [
                'brakes' => [
                    'title' => 'Brake tester',
                    'body' => 'Measures braking efficiency',
                ],
                'suspension' => [
                    'title' => 'Suspension tester',
                    'body' => 'Checks road-holding performance',
                ],
                'ripage' => [
                    'title' => 'Side-slip plate',
                    'body' => 'Verifies wheel alignment',
                ],
                'headlamp' => [
                    'title' => 'Headlamp tester',
                    'body' => 'Checks light beam orientation',
                ],
                'gas' => [
                    'title' => 'Gas analyser',
                    'body' => 'Measures pollutant emissions',
                ],
                'plays' => [
                    'title' => 'Play detector',
                    'body' => 'Checks mechanical play',
                ],
                'pit' => [
                    'title' => 'Lift / inspection pit',
                    'body' => 'Visual underbody inspection',
                ],
                'sonometre' => [
                    'title' => 'Sound level meter',
                    'body' => 'Measures noise level',
                ],
                'air_compressor' => [
                    'title' => 'Air compressor',
                    'body' => 'Supplies equipment with air',
                ],
                'opacimeter' => [
                    'title' => 'Opacimeter',
                    'body' => 'Measures smoke opacity',
                ],
            ],
        ],
        'centres' => [
            'overline' => 'Our centres',
            'title' => 'Two centres in Yaoundé. The same G3 standard.',
            'status_open' => 'Open now',
            'today' => 'Today: :hours',
            'weekday' => 'Monday – Saturday',
            'sunday' => 'Sunday',
            'map_title' => 'Our two centres on the map',
            'map_cta' => 'View on Google Maps',
            'actions' => [
                'directions' => 'Directions',
                'call' => 'Call',
                'details' => 'View centre',
            ],
            'items' => [
                'ecole_de_police' => [
                    'title' => 'G3 Control — École de Police',
                    'address' => 'Former Texaco descent, École de Police, Yaoundé',
                ],
                'nomayos' => [
                    'title' => 'G3 Control — Nomayos',
                    'address' => 'Nomayos junction, Yaoundé',
                ],
            ],
        ],
        'road_safety' => [
            'overline' => 'Road safety',
            'title' => 'A safe vehicle helps make the road safer.',
            'lead' => 'Technical inspection is an essential step, but safety also starts with the attention given to the vehicle every day.',
            'cta' => 'Discover Road Safety',
            'items_label' => 'Road-safety watch points',
            'items' => [
                'braking' => [
                    'title' => 'Braking',
                    'body' => 'Notice the signs you should not ignore',
                ],
                'tyres' => [
                    'title' => 'Tyres',
                    'body' => 'Grip, pressure and wear',
                ],
                'visibility' => [
                    'title' => 'Visibility',
                    'body' => 'Lights, glass and wipers',
                ],
            ],
        ],
    ],

    'about' => [
        'identity' => [
            'overline' => 'Our identity',
            'title' => 'A structured approach to technical inspection.',
            'intro' => [
                'G3 Control operates in the field of automobile technical inspection in Yaoundé. Through its École de Police and Nomayos centres, the company provides drivers with an organized environment for the technical evaluation of their vehicles.',
                'Our approach combines structured procedures, specialized equipment, technical personnel, and visitor support. The objective is to make technical inspection rigorous in execution, clear in its process, and useful to road safety.',
            ],
            'cards_label' => 'G3 Control purpose and mission',
            'purpose' => [
                'title' => 'Purpose',
                'subtitle' => 'Why we exist',
                'body' => 'To make a lasting contribution to road safety in Cameroon by ensuring that every vehicle in circulation meets legal, technical, and safety requirements.',
            ],
            'mission' => [
                'title' => 'Mission',
                'subtitle' => 'What we do every day',
                'body' => 'To deliver rigorous, fast, and transparent technical inspections in our École de Police and Nomayos centres, using specialized equipment and highly qualified personnel.',
            ],
            'vision' => [
                'title' => 'Vision',
                'subtitle' => 'Where we are going',
                'body' => 'To become the reference technical inspection network in Yaoundé and Cameroon, recognized for its impartiality, modernized tools, and quality of welcome.',
            ],
            'brief' => [
                'title' => 'G3 at a glance',
                'items' => [
                    'activity' => [
                        'label' => 'Activity',
                        'value' => 'Automobile technical inspection',
                    ],
                    'location' => [
                        'label' => 'Location',
                        'value' => 'Yaoundé, Cameroon',
                    ],
                    'centres' => [
                        'label' => 'Operating centres',
                        'value' => 'École de Police · Nomayos',
                    ],
                    'approval' => [
                        'label' => 'Approval',
                        'value' => 'N°0291',
                    ],
                    'opening' => [
                        'label' => 'Opening',
                        'value' => '7 days',
                    ],
                ],
                'timeline' => [
                    'approval' => [
                        'year' => '2020',
                        'title' => 'Approval No. 0291',
                        'body' => 'A major institutional milestone.',
                    ],
                    'today' => [
                        'year' => 'Today',
                        'title' => 'Two centres in Yaoundé',
                        'body' => 'École de Police and Nomayos.',
                    ],
                ],
            ],
        ],
        'requirements' => [
            'overline' => 'Technical requirement',
            'title' => 'Rigor is visible in the way inspection is carried out.',
            'image_alt' => 'Technical inspection pit inside a G3 Control centre',
            'items_label' => 'G3 Control technical requirements',
            'items' => [
                'procedures' => [
                    'title' => 'Structured procedures',
                    'body' => 'An organized technical process for every vehicle.',
                ],
                'equipment' => [
                    'title' => 'Specialized equipment',
                    'body' => 'Facilities adapted to the different inspection operations.',
                ],
                'team' => [
                    'title' => 'Technical personnel',
                    'body' => 'A team dedicated to the smooth running of operations.',
                ],
                'measures' => [
                    'title' => 'Visual checks & measurements',
                    'body' => 'An approach combining visual checks and instrumented measurements.',
                ],
            ],
        ],
        'values' => [
            'overline' => 'Our values',
            'title' => 'Concrete principles serving road safety.',
            'motto' => 'Safety. Simplicity. Trust.',
            'items_label' => 'G3 Control values',
            'items' => [
                'security' => [
                    'title' => 'Safety',
                    'body' => 'Absolute technical standards as a shared priority.',
                ],
                'simplicity' => [
                    'title' => 'Simplicity',
                    'body' => 'A smooth, clear driver journey with no wasted time.',
                ],
                'trust' => [
                    'title' => 'Trust',
                    'body' => 'Strict professional ethics and full transparency on the vehicle’s condition.',
                ],
                'rigor' => [
                    'title' => 'Rigor',
                    'body' => 'Rigorous respect for the procedures of Approval No. 0291.',
                ],
            ],
        ],
        'team' => [
            'overline' => 'The G3 team',
            'title' => 'Technology does not replace human standards.',
            'body' => 'Behind the equipment, inspection lanes, and procedures is a team that welcomes, supports, and carries out the operations needed for every technical inspection to run smoothly.',
            'image_alt' => 'G3 Control technicians in a technical inspection centre',
            'items_label' => 'G3 team qualities',
            'items' => [
                'welcome' => 'Welcome',
                'rigor' => 'Rigor',
                'responsibility' => 'Responsibility',
            ],
        ],
    ],

    'contact_page' => [
        'centres' => [
            'overline' => 'Our centres',
            'title' => 'Two centres in Yaoundé. Direct access to your team.',
            'lead' => 'Select a centre to view details and the map.',
            'map_tab' => 'Map',
            'list_tab' => 'List',
            'status' => 'Open now',
            'closes' => 'Closes at :time',
            'weekday' => 'Monday - Saturday: :hours',
            'sunday' => 'Sunday: :hours',
            'holidays' => 'Open on public holidays',
            'map_title' => 'Google Maps view of G3 Control centres in Yaoundé',
            'map_note_title' => 'Interactive Google map',
            'map_note' => 'Use the map to explore Google markers for the G3 Control centres.',
            'centres_label' => 'G3 Control centres',
            'details_label' => 'Centre details',
        ],
        'assets' => [
            'ecole_de_police_alt' => 'Facade of the G3 Control École de Police centre',
            'nomayos_alt' => 'Entrance of the G3 Control Nomayos centre',
        ],
        'message' => [
            'overline' => 'Write to us',
            'title' => 'A specific question? Write to us.',
            'lead' => 'Your request is not about an appointment or directions? Send us your message and specify the relevant centre.',
            'appointment_card_title' => 'Need an appointment?',
            'appointment_card_body' => 'Go directly to our appointment platform.',
            'appointment_card_cta' => 'Book an appointment',
            'fees_card_title' => 'View fees',
            'fees_card_body' => 'Find the official G3 Control fee schedule.',
            'fees_card_cta' => 'View fees',
            'name' => 'Full name',
            'name_placeholder' => 'Your full name',
            'phone' => 'Phone / WhatsApp',
            'phone_placeholder' => '6XX XXX XXX',
            'email' => 'Email address',
            'email_placeholder' => 'your@email.com',
            'centre' => 'Relevant centre',
            'centre_placeholder' => 'Select a centre',
            'subject' => 'Subject of your request',
            'subject_placeholder' => 'Select a subject',
            'message' => 'Your message',
            'message_placeholder' => 'Describe your request in a few words...',
            'submit' => 'Send my message',
            'required' => 'required',
            'errors' => [
                'name' => 'Enter your full name.',
                'phone' => 'Enter a valid phone number.',
                'email' => 'Enter a valid email address.',
                'centre' => 'Select a centre.',
                'subject' => 'Select a subject.',
                'message' => 'Enter your message.',
                'form' => 'The message could not be sent. Try again.',
            ],
            'support_title' => 'We are listening',
            'support_body' => 'Our team will respond as soon as possible through the appropriate contact channel.',
            'hours_title' => 'Our centres',
            'hours_weekday' => 'Monday - Saturday: hours by centre',
            'hours_sunday' => 'Sunday: hours by centre',
            'hours_holidays' => 'Open on public holidays',
            'approval' => 'Approval :number since :year',
        ],
        'subjects' => [
            'general' => 'General information',
            'centre' => 'Centre question',
            'documents' => 'Document or preparation',
            'other' => 'Other request',
        ],
    ],

    'utility' => [
        'tagline' => 'Technical inspection · Yaoundé',
        'centres_open' => '2 centres · 7 days',
        'location' => 'Yaoundé, Cameroon',
        'centres_title' => '2 centres at your service',
        'centres_detail' => 'École de Police • Nomayos',
        'open_title' => 'Open 7 days',
        'open_detail' => 'Public holidays included',
        'approval_title' => 'Approval :number',
        'approval_detail' => 'Since :year',
        'whatsapp' => 'WhatsApp',
        'menu' => 'Menu',
        'menu_close' => 'Close',
    ],

    'locale' => [
        'switch' => 'Switch language',
        'fr' => 'FR',
        'en' => 'EN',
    ],

    'footer' => [
        'identity' => 'G3 Control',
        'tagline' => 'Your safety, our commitment.',
        'contact_heading' => 'Contact',
        'centres_heading' => 'Centres',
        'utility_heading' => 'Quick access',
        'legal_business' => 'Automobile Technical Inspection Centre',
        'approval_since' => 'Approval :number since :year',
        'signature' => 'Keeping you safe.',
        'back_to_top' => 'Back to top',
        'rights' => 'All rights reserved.',
        'trust' => 'Approval :number · Open on public holidays',
    ],

    'shell' => [
        'placeholder_title' => 'Page under construction',
        'placeholder_body' => 'This page content will be delivered in the next implementation step.',
    ],
];
