<?php

namespace Database\Seeders;

use App\Domain\Enums\ContentPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicAdminBaselineSeeder extends Seeder
{
    public function run(): void
    {
        $centreIds = DB::table('centres')
            ->whereIn('code', ['ecole-de-police', 'nomayos'])
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $categoryIds = DB::table('vehicle_categories')
            ->pluck('id', 'code')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $serviceIds = $this->seedServices($centreIds, $categoryIds);
        $this->seedRequiredDocuments($serviceIds);
        $this->seedEquipment($centreIds);
        $this->seedFaqEntries();
        $this->seedRoadSafetySections();
        $this->seedPageSeo();
        $this->seedContentBlocks();
    }

    /**
     * @param  list<int>  $centreIds
     * @param  array<string, int>  $categoryIds
     * @return array<string, int>
     */
    private function seedServices(array $centreIds, array $categoryIds): array
    {
        $serviceIds = [];

        foreach ($this->services() as $index => $service) {
            $attributes = [
                'title' => json_encode($service['title']),
                'summary' => json_encode($service['summary']),
                'body' => json_encode($service['body']),
                'icon' => $service['icon'],
                'sort_order' => $index + 1,
                'is_published' => true,
                'updated_at' => now(),
            ];
            $existingId = DB::table('services')->where('code', $service['code'])->value('id');

            if ($existingId !== null) {
                DB::table('services')->where('id', $existingId)->update($attributes);
                $serviceId = (int) $existingId;
            } else {
                $serviceId = DB::table('services')->insertGetId(array_merge($attributes, [
                    'code' => $service['code'],
                    'created_at' => now(),
                ]));
            }

            $serviceIds[$service['code']] = $serviceId;

            foreach ($centreIds as $centreId) {
                DB::table('centre_service')->updateOrInsert([
                    'centre_id' => $centreId,
                    'service_id' => $serviceId,
                ]);
            }

            foreach ($service['category_codes'] as $categoryCode) {
                if (! isset($categoryIds[$categoryCode])) {
                    continue;
                }

                DB::table('service_vehicle_category')->updateOrInsert([
                    'service_id' => $serviceId,
                    'vehicle_category_id' => $categoryIds[$categoryCode],
                ]);
            }
        }

        return $serviceIds;
    }

    /**
     * @param  array<string, int>  $serviceIds
     */
    private function seedRequiredDocuments(array $serviceIds): void
    {
        $documents = [
            [
                'label' => ['fr' => 'Carte grise', 'en' => 'Registration card'],
            ],
            [
                'label' => ['fr' => 'Pièce d’identité', 'en' => 'Identity document'],
            ],
            [
                'label' => ['fr' => 'Ancien certificat de visite technique si disponible', 'en' => 'Previous technical inspection certificate if available'],
            ],
        ];

        foreach ($serviceIds as $serviceId) {
            foreach ($documents as $index => $document) {
                DB::table('required_documents')->updateOrInsert([
                    'service_id' => $serviceId,
                    'vehicle_category_id' => null,
                    'sort_order' => $index + 1,
                ], [
                    'label' => json_encode($document['label']),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }
    }

    /**
     * @param  list<int>  $centreIds
     */
    private function seedEquipment(array $centreIds): void
    {
        foreach ($this->equipment() as $index => $equipment) {
            $attributes = [
                'label' => json_encode($equipment['label']),
                'sort_order' => $index + 1,
                'updated_at' => now(),
            ];
            $existingId = DB::table('equipment')->where('code', $equipment['code'])->value('id');

            if ($existingId !== null) {
                DB::table('equipment')->where('id', $existingId)->update($attributes);
                $equipmentId = (int) $existingId;
            } else {
                $equipmentId = DB::table('equipment')->insertGetId(array_merge($attributes, [
                    'code' => $equipment['code'],
                    'created_at' => now(),
                ]));
            }

            foreach ($centreIds as $centreId) {
                DB::table('centre_equipment')->updateOrInsert([
                    'centre_id' => $centreId,
                    'equipment_id' => $equipmentId,
                ]);
            }
        }
    }

    private function seedFaqEntries(): void
    {
        foreach ($this->faqEntries() as $index => $entry) {
            DB::table('faq_entries')->updateOrInsert([
                'category_code' => $entry['category_code'],
                'sort_order' => $index + 1,
            ], [
                'question' => json_encode($entry['question']),
                'answer' => json_encode($entry['answer']),
                'is_published' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }

    private function seedRoadSafetySections(): void
    {
        foreach ($this->roadSafetySections() as $index => $section) {
            DB::table('road_safety_sections')->updateOrInsert([
                'anchor' => $section['anchor'],
            ], [
                'title' => json_encode($section['title']),
                'body' => json_encode($section['body']),
                'sort_order' => $index + 1,
                'is_published' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }

    private function seedPageSeo(): void
    {
        foreach ($this->pageSeo() as $page => $seo) {
            DB::table('page_seo')->updateOrInsert([
                'page' => $page,
            ], [
                'seo_title' => json_encode($seo['title']),
                'seo_description' => json_encode($seo['description']),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedContentBlocks(): void
    {
        foreach ($this->contentBlocks() as $block) {
            DB::table('content_blocks')->updateOrInsert([
                'key' => $block['key'],
            ], [
                'page' => $block['page'],
                'schema_version' => 1,
                'content' => json_encode([
                    'fr' => $block['fr'],
                    'en' => $block['en'],
                ]),
                'locale_status' => json_encode(['fr' => 'complete', 'en' => 'complete']),
                'is_published' => true,
                'published_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }

    /**
     * @return list<array{code: string, title: array{fr: string, en: string}, summary: array{fr: string, en: string}, body: array{fr: string, en: string}, icon: string, category_codes: list<string>}>
     */
    private function services(): array
    {
        return [
            [
                'code' => 'periodic-technical-inspection',
                'title' => ['fr' => 'Visite technique périodique', 'en' => 'Periodic technical inspection'],
                'summary' => ['fr' => 'Contrôle complet et réglementaire de votre véhicule.', 'en' => 'Complete regulatory inspection for your vehicle.'],
                'body' => ['fr' => 'Contrôle réglementaire de sécurité, d’identification et d’état général du véhicule.', 'en' => 'Regulatory safety, identification and general-condition inspection for the vehicle.'],
                'icon' => 'service-periodic.svg',
                'category_codes' => ['B', 'A', 'B1', 'C < 3,5T', 'C', 'D', 'D_OTHER'],
            ],
            [
                'code' => 're-inspection',
                'title' => ['fr' => 'Contre-visite', 'en' => 'Re-inspection'],
                'summary' => ['fr' => 'Vérification suite à une visite avec réserves.', 'en' => 'Verification after an inspection with reservations.'],
                'body' => ['fr' => 'Suivi des points signalés lors d’une visite technique précédente.', 'en' => 'Follow-up of points identified during a previous technical inspection.'],
                'icon' => 'service-recheck.svg',
                'category_codes' => ['B', 'A', 'B1', 'C < 3,5T', 'C', 'D', 'D_OTHER'],
            ],
            [
                'code' => 'light-vehicle-inspection',
                'title' => ['fr' => 'Contrôle des véhicules légers', 'en' => 'Light vehicle inspection'],
                'summary' => ['fr' => 'Pour les voitures particulières, citadines et 4×4.', 'en' => 'For private cars, city cars and 4×4 vehicles.'],
                'body' => ['fr' => 'Contrôle adapté aux véhicules de tourisme à usage privé.', 'en' => 'Inspection adapted to private passenger vehicles.'],
                'icon' => 'service-light.svg',
                'category_codes' => ['B'],
            ],
            [
                'code' => 'utility-vehicle-inspection',
                'title' => ['fr' => 'Contrôle des utilitaires', 'en' => 'Utility vehicle inspection'],
                'summary' => ['fr' => 'Contrôle adapté aux véhicules utilitaires.', 'en' => 'Inspection adapted to utility vehicles.'],
                'body' => ['fr' => 'Contrôle des fourgonnettes, pick-up et utilitaires selon leur catégorie officielle.', 'en' => 'Inspection for vans, pick-ups and utility vehicles according to their official category.'],
                'icon' => 'service-utility.svg',
                'category_codes' => ['B1'],
            ],
            [
                'code' => 'taxi-transport-inspection',
                'title' => ['fr' => 'Contrôle des taxis & transport', 'en' => 'Taxi and transport inspection'],
                'summary' => ['fr' => 'Pour les taxis et véhicules de transport de personnes.', 'en' => 'For taxis and passenger transport vehicles.'],
                'body' => ['fr' => 'Contrôle des véhicules de transport de personnes selon leur usage et catégorie.', 'en' => 'Inspection for passenger transport vehicles according to use and category.'],
                'icon' => 'service-transport.svg',
                'category_codes' => ['A', 'C < 3,5T', 'C'],
            ],
            [
                'code' => 'heavy-vehicle-inspection',
                'title' => ['fr' => 'Contrôle des poids lourds', 'en' => 'Heavy goods vehicle inspection'],
                'summary' => ['fr' => 'Une expertise dédiée aux grands gabarits.', 'en' => 'Dedicated expertise for large-format vehicles.'],
                'body' => ['fr' => 'Contrôle des poids lourds, camions, semi-remorques et autres engins.', 'en' => 'Inspection for heavy goods vehicles, trucks, semi-trailers and other machinery.'],
                'icon' => 'service-heavy.svg',
                'category_codes' => ['D', 'D_OTHER'],
            ],
        ];
    }

    /**
     * @return list<array{code: string, label: array{fr: string, en: string}}>
     */
    private function equipment(): array
    {
        return [
            ['code' => 'brake-tester', 'label' => ['fr' => 'Banc de freinage', 'en' => 'Brake tester']],
            ['code' => 'suspension-tester', 'label' => ['fr' => 'Banc de suspension', 'en' => 'Suspension tester']],
            ['code' => 'side-slip-plate', 'label' => ['fr' => 'Plaque à ripage', 'en' => 'Side-slip plate']],
            ['code' => 'headlight-tester', 'label' => ['fr' => 'Réglophare', 'en' => 'Headlight tester']],
            ['code' => 'gas-analyser', 'label' => ['fr' => 'Analyseur de gaz', 'en' => 'Gas analyser']],
            ['code' => 'inspection-lane', 'label' => ['fr' => 'Ligne de contrôle', 'en' => 'Inspection lane']],
        ];
    }

    /**
     * @return list<array{category_code: string, question: array{fr: string, en: string}, answer: array{fr: string, en: string}}>
     */
    private function faqEntries(): array
    {
        return [
            [
                'category_code' => 'appointment',
                'question' => ['fr' => 'Puis-je préparer mon rendez-vous en ligne ?', 'en' => 'Can I prepare my appointment online?'],
                'answer' => ['fr' => 'Oui. Le formulaire de rendez-vous permet de choisir un centre, un service et une catégorie de véhicule.', 'en' => 'Yes. The appointment form lets you choose a centre, service and vehicle category.'],
            ],
            [
                'category_code' => 'documents',
                'question' => ['fr' => 'Quels documents dois-je prévoir ?', 'en' => 'Which documents should I prepare?'],
                'answer' => ['fr' => 'Prévoyez au minimum la carte grise, une pièce d’identité et l’ancien certificat de visite technique si disponible.', 'en' => 'Prepare at least the registration card, an identity document and the previous technical inspection certificate if available.'],
            ],
            [
                'category_code' => 'tariffs',
                'question' => ['fr' => 'Les tarifs affichés sont-ils officiels ?', 'en' => 'Are the displayed fees official?'],
                'answer' => ['fr' => 'Les tarifs publics sont organisés par catégorie de véhicule selon la version publiée dans l’administration.', 'en' => 'Public fees are organised by vehicle category according to the version published in the admin.'],
            ],
            [
                'category_code' => 'centres',
                'question' => ['fr' => 'Les deux centres ont-ils les mêmes horaires ?', 'en' => 'Do both centres have the same hours?'],
                'answer' => ['fr' => 'Les horaires sont gérés par centre dans l’administration et affichés sur les pages publiques.', 'en' => 'Hours are managed per centre in the admin and displayed on the public pages.'],
            ],
        ];
    }

    /**
     * @return list<array{anchor: string, title: array{fr: string, en: string}, body: array{fr: string, en: string}}>
     */
    private function roadSafetySections(): array
    {
        return [
            [
                'anchor' => 'braking-grip',
                'title' => ['fr' => 'Freinage & adhérence', 'en' => 'Braking & grip'],
                'body' => ['fr' => 'Un freinage efficace et des pneus en bon état aident à garder la maîtrise du véhicule.', 'en' => 'Effective braking and tyres in good condition help keep control of the vehicle.'],
            ],
            [
                'anchor' => 'visibility-lighting',
                'title' => ['fr' => 'Visibilité & éclairage', 'en' => 'Visibility & lighting'],
                'body' => ['fr' => 'Bien voir et être bien vu reste essentiel de jour, de nuit et sous la pluie.', 'en' => 'Seeing clearly and being seen remain essential by day, at night and in rain.'],
            ],
            [
                'anchor' => 'vehicle-signals',
                'title' => ['fr' => 'Signaux du véhicule', 'en' => 'Vehicle signals'],
                'body' => ['fr' => 'Voyants, bruits, vibrations et changements de comportement doivent attirer votre attention.', 'en' => 'Warning lights, noises, vibrations and behaviour changes should get your attention.'],
            ],
            [
                'anchor' => 'equipment-preparation',
                'title' => ['fr' => 'Équipements & préparation', 'en' => 'Equipment & preparation'],
                'body' => ['fr' => 'Les équipements de sécurité et une préparation simple rendent le trajet plus sûr.', 'en' => 'Safety equipment and simple preparation make the journey safer.'],
            ],
            [
                'anchor' => 'sixty-second-check',
                'title' => ['fr' => 'Le réflexe 60 secondes', 'en' => 'The 60-second reflex'],
                'body' => ['fr' => 'Pneumatiques, éclairage, pare-brise et rétroviseurs peuvent être vérifiés rapidement avant le départ.', 'en' => 'Tyres, lighting, windscreen and mirrors can be checked quickly before departure.'],
            ],
        ];
    }

    /**
     * @return array<string, array{title: array{fr: string, en: string}, description: array{fr: string, en: string}}>
     */
    private function pageSeo(): array
    {
        return [
            ContentPage::Home->value => ['title' => ['fr' => 'Accueil', 'en' => 'Home'], 'description' => ['fr' => 'G3 Control, centres de visite technique automobile à Yaoundé.', 'en' => 'G3 Control, vehicle technical inspection centres in Yaoundé.']],
            ContentPage::About->value => ['title' => ['fr' => 'À propos', 'en' => 'About'], 'description' => ['fr' => 'Découvrez G3 Control, son agrément et son engagement pour la sécurité routière.', 'en' => 'Learn about G3 Control, its approval and its commitment to road safety.']],
            ContentPage::Centres->value => ['title' => ['fr' => 'Nos centres', 'en' => 'Our centres'], 'description' => ['fr' => 'Retrouvez les centres G3 Control École de Police et Nomayos à Yaoundé.', 'en' => 'Find G3 Control École de Police and Nomayos centres in Yaoundé.']],
            ContentPage::CentreEcoleDePolice->value => ['title' => ['fr' => 'Centre École de Police', 'en' => 'École de Police centre'], 'description' => ['fr' => 'Informations pratiques du centre G3 Control École de Police.', 'en' => 'Practical information for the G3 Control École de Police centre.']],
            ContentPage::CentreNomayos->value => ['title' => ['fr' => 'Centre Nomayos', 'en' => 'Nomayos centre'], 'description' => ['fr' => 'Informations pratiques du centre G3 Control Nomayos.', 'en' => 'Practical information for the G3 Control Nomayos centre.']],
            ContentPage::Services->value => ['title' => ['fr' => 'Services', 'en' => 'Services'], 'description' => ['fr' => 'Services de visite technique et contrôles proposés par G3 Control.', 'en' => 'Technical inspection services and checks offered by G3 Control.']],
            ContentPage::TechnicalInspection->value => ['title' => ['fr' => 'Visite technique', 'en' => 'Technical inspection'], 'description' => ['fr' => 'Comprendre le parcours de visite technique chez G3 Control.', 'en' => 'Understand the technical inspection journey at G3 Control.']],
            ContentPage::Fees->value => ['title' => ['fr' => 'Tarifs', 'en' => 'Fees'], 'description' => ['fr' => 'Tarifs officiels par catégorie de véhicule.', 'en' => 'Official fees by vehicle category.']],
            ContentPage::Appointment->value => ['title' => ['fr' => 'Rendez-vous', 'en' => 'Appointment'], 'description' => ['fr' => 'Préparez votre rendez-vous ou suivez votre demande G3 Control.', 'en' => 'Prepare your appointment or track your G3 Control request.']],
            ContentPage::RoadSafety->value => ['title' => ['fr' => 'Sécurité routière', 'en' => 'Road safety'], 'description' => ['fr' => 'Conseils essentiels de sécurité routière avant le départ.', 'en' => 'Essential road safety guidance before departure.']],
            ContentPage::Contact->value => ['title' => ['fr' => 'Contact', 'en' => 'Contact'], 'description' => ['fr' => 'Contactez les centres G3 Control à Yaoundé.', 'en' => 'Contact G3 Control centres in Yaoundé.']],
        ];
    }

    /**
     * @return list<array{key: string, page: string, fr: array{headline: string, body: string}, en: array{headline: string, body: string}}>
     */
    private function contentBlocks(): array
    {
        return [
            [
                'key' => 'home.hero',
                'page' => ContentPage::Home->value,
                'fr' => [
                    'headline' => 'La sécurité commence par un contrôle rigoureux.',
                    'body' => 'G3 Control vous accueille dans ses deux centres de Yaoundé pour une visite technique professionnelle, simple et transparente.',
                ],
                'en' => [
                    'headline' => 'Safety starts with a rigorous inspection.',
                    'body' => 'G3 Control welcomes you to its two Yaoundé centres for a professional, simple, and transparent technical inspection.',
                ],
            ],
            [
                'key' => 'home.live_strip',
                'page' => ContentPage::Home->value,
                'fr' => [
                    'headline' => 'État des centres G3 Control',
                    'body' => 'Deux centres à Yaoundé. Une même exigence G3.',
                ],
                'en' => [
                    'headline' => 'G3 Control centre status',
                    'body' => 'Two centres in Yaoundé. The same G3 standard.',
                ],
            ],
            [
                'key' => 'home.proposition',
                'page' => ContentPage::Home->value,
                'fr' => [
                    'headline' => 'Sécurité. Simplicité. Confiance.',
                    'body' => 'Des contrôles fiables, un processus clair, et un partenaire de confiance depuis 2020.',
                ],
                'en' => [
                    'headline' => 'Safety. Simplicity. Trust.',
                    'body' => 'Reliable checks for safer roads, a clear and quick process, and a trusted partner since 2020.',
                ],
            ],
            [
                'key' => 'about.mission',
                'page' => ContentPage::About->value,
                'fr' => [
                    'headline' => 'Une approche structurée du contrôle technique.',
                    'body' => 'G3 Control exerce dans le domaine de la visite technique automobile à Yaoundé. À travers ses centres d’École de Police et de Nomayos, l’entreprise met à disposition des usagers un environnement organisé pour l’évaluation technique de leurs véhicules.',
                ],
                'en' => [
                    'headline' => 'A structured approach to technical inspection.',
                    'body' => 'G3 Control operates in the field of automobile technical inspection in Yaoundé. Through its École de Police and Nomayos centres, the company provides drivers with an organized environment for the technical evaluation of their vehicles.',
                ],
            ],
            [
                'key' => 'about.agrement',
                'page' => ContentPage::About->value,
                'fr' => [
                    'headline' => 'Agrément N°0291',
                    'body' => 'Agrément N°0291 depuis 2020. L’agrément est délivré à l’entreprise, pour l’ensemble de ses centres.',
                ],
                'en' => [
                    'headline' => 'Approval No. 0291',
                    'body' => 'Approval No. 0291 since 2020. The approval is granted to the company, for all of its centres.',
                ],
            ],
            [
                'key' => 'centres.intro',
                'page' => ContentPage::Centres->value,
                'fr' => [
                    'headline' => 'Nos centres',
                    'body' => 'Retrouvez les centres G3 Control École de Police et Nomayos à Yaoundé.',
                ],
                'en' => [
                    'headline' => 'Our centres',
                    'body' => 'Find G3 Control École de Police and Nomayos centres in Yaoundé.',
                ],
            ],
            [
                'key' => 'centre_ecole_de_police.intro',
                'page' => ContentPage::CentreEcoleDePolice->value,
                'fr' => [
                    'headline' => 'G3 Control — École de Police',
                    'body' => 'Descente ancien Texaco, École de Police, Yaoundé.',
                ],
                'en' => [
                    'headline' => 'G3 Control — École de Police',
                    'body' => 'Former Texaco descent, École de Police, Yaoundé.',
                ],
            ],
            [
                'key' => 'centre_nomayos.intro',
                'page' => ContentPage::CentreNomayos->value,
                'fr' => [
                    'headline' => 'G3 Control — Nomayos',
                    'body' => 'Carrefour Nomayos, Yaoundé.',
                ],
                'en' => [
                    'headline' => 'G3 Control — Nomayos',
                    'body' => 'Nomayos junction, Yaoundé.',
                ],
            ],
            [
                'key' => 'services.intro',
                'page' => ContentPage::Services->value,
                'fr' => [
                    'headline' => 'Services',
                    'body' => 'Services de visite technique et contrôles proposés par G3 Control.',
                ],
                'en' => [
                    'headline' => 'Services',
                    'body' => 'Technical inspection services and checks offered by G3 Control.',
                ],
            ],
            [
                'key' => 'technical_inspection.intro',
                'page' => ContentPage::TechnicalInspection->value,
                'fr' => [
                    'headline' => 'Visite technique',
                    'body' => 'Comprendre le parcours de visite technique chez G3 Control.',
                ],
                'en' => [
                    'headline' => 'Technical inspection',
                    'body' => 'Understand the technical inspection journey at G3 Control.',
                ],
            ],
            [
                'key' => 'technical_inspection.video',
                'page' => ContentPage::TechnicalInspection->value,
                'fr' => [
                    'headline' => 'Visite technique',
                    'body' => 'Comprendre le parcours de visite technique chez G3 Control.',
                ],
                'en' => [
                    'headline' => 'Technical inspection',
                    'body' => 'Understand the technical inspection journey at G3 Control.',
                ],
            ],
            [
                'key' => 'fees.intro',
                'page' => ContentPage::Fees->value,
                'fr' => [
                    'headline' => 'Tarifs',
                    'body' => 'Tarifs officiels par catégorie de véhicule.',
                ],
                'en' => [
                    'headline' => 'Fees',
                    'body' => 'Official fees by vehicle category.',
                ],
            ],
            [
                'key' => 'appointment.intro',
                'page' => ContentPage::Appointment->value,
                'fr' => [
                    'headline' => 'Rendez-vous',
                    'body' => 'Préparez votre rendez-vous ou suivez votre demande G3 Control.',
                ],
                'en' => [
                    'headline' => 'Appointment',
                    'body' => 'Prepare your appointment or track your G3 Control request.',
                ],
            ],
            [
                'key' => 'road_safety.intro',
                'page' => ContentPage::RoadSafety->value,
                'fr' => [
                    'headline' => 'Un véhicule sûr contribue à une route plus sûre.',
                    'body' => 'Le contrôle technique est une étape essentielle, mais la sécurité commence aussi par l’attention portée quotidiennement à l’état du véhicule.',
                ],
                'en' => [
                    'headline' => 'A safe vehicle helps make the road safer.',
                    'body' => 'Technical inspection is an essential step, but safety also starts with the attention given to the vehicle every day.',
                ],
            ],
            [
                'key' => 'contact.intro',
                'page' => ContentPage::Contact->value,
                'fr' => [
                    'headline' => 'Contact',
                    'body' => 'Contactez les centres G3 Control à Yaoundé.',
                ],
                'en' => [
                    'headline' => 'Contact',
                    'body' => 'Contact G3 Control centres in Yaoundé.',
                ],
            ],
        ];
    }
}
