<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficialTariffsSeeder extends Seeder
{
    public function run(): void
    {
        $serviceId = $this->upsertService();
        $centreIds = DB::table('centres')
            ->whereIn('code', ['ecole-de-police', 'nomayos'])
            ->pluck('id')
            ->all();

        $versionId = $this->upsertTariffVersion();
        DB::table('tariff_items')->where('tariff_version_id', $versionId)->delete();

        foreach ($this->tariffs() as $index => $tariff) {
            $categoryId = $this->upsertVehicleCategory($tariff, $index + 1);
            $this->linkServiceToCategory($serviceId, $categoryId);

            $itemId = DB::table('tariff_items')->insertGetId([
                'tariff_version_id' => $versionId,
                'vehicle_category_id' => $categoryId,
                'service_id' => $serviceId,
                'amount_xaf' => $tariff['amount_xaf'],
                'validity_notes' => json_encode($tariff['validity']),
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($centreIds as $centreId) {
                $this->linkTariffItemToCentre($itemId, (int) $centreId);
                $this->linkServiceToCentre($serviceId, (int) $centreId);
            }
        }
    }

    private function upsertService(): int
    {
        $attributes = [
            'title' => json_encode([
                'fr' => 'Visite technique périodique',
                'en' => 'Periodic technical inspection',
            ]),
            'summary' => json_encode([
                'fr' => 'Contrôle technique officiel du véhicule.',
                'en' => 'Official vehicle technical inspection.',
            ]),
            'body' => null,
            'icon' => 'icon-service.svg',
            'sort_order' => 1,
            'is_published' => true,
            'updated_at' => now(),
        ];
        $existingId = DB::table('services')->where('code', 'periodic-technical-inspection')->value('id');

        if ($existingId !== null) {
            DB::table('services')->where('id', $existingId)->update($attributes);

            return (int) $existingId;
        }

        return DB::table('services')->insertGetId(array_merge($attributes, [
            'code' => 'periodic-technical-inspection',
            'created_at' => now(),
        ]));
    }

    private function upsertTariffVersion(): int
    {
        $attributes = [
            'status' => 'published',
            'effective_from' => '2022-06-01',
            'effective_until' => null,
            'reviewed_at' => now(),
            'reviewed_by' => null,
            'published_at' => now(),
            'published_by' => null,
            'updated_at' => now(),
        ];
        $existingId = DB::table('tariff_versions')->where('label', 'Tarifs officiels MINT 2022-06-01')->value('id');

        if ($existingId !== null) {
            DB::table('tariff_versions')->where('id', $existingId)->update($attributes);

            return (int) $existingId;
        }

        return DB::table('tariff_versions')->insertGetId(array_merge($attributes, [
            'label' => 'Tarifs officiels MINT 2022-06-01',
            'created_at' => now(),
        ]));
    }

    /**
     * @param  array{code: string, label: array{fr: string, en: string}, examples: array{fr: string, en: string}, description: array{fr: string, en: string}, amount_xaf: int, validity: array{fr: string, en: string}}  $tariff
     */
    private function upsertVehicleCategory(array $tariff, int $sortOrder): int
    {
        $attributes = [
            'label' => json_encode($tariff['label']),
            'examples' => json_encode($tariff['examples']),
            'description' => json_encode($tariff['description']),
            'sort_order' => $sortOrder,
            'is_published' => true,
            'updated_at' => now(),
        ];
        $existingId = DB::table('vehicle_categories')->where('code', $tariff['code'])->value('id');

        if ($existingId !== null) {
            DB::table('vehicle_categories')->where('id', $existingId)->update($attributes);

            return (int) $existingId;
        }

        return DB::table('vehicle_categories')->insertGetId(array_merge($attributes, [
            'code' => $tariff['code'],
            'created_at' => now(),
        ]));
    }

    private function linkServiceToCategory(int $serviceId, int $categoryId): void
    {
        DB::table('service_vehicle_category')->updateOrInsert([
            'service_id' => $serviceId,
            'vehicle_category_id' => $categoryId,
        ]);
    }

    private function linkServiceToCentre(int $serviceId, int $centreId): void
    {
        DB::table('centre_service')->updateOrInsert([
            'centre_id' => $centreId,
            'service_id' => $serviceId,
        ]);
    }

    private function linkTariffItemToCentre(int $itemId, int $centreId): void
    {
        DB::table('tariff_item_centre')->updateOrInsert([
            'tariff_item_id' => $itemId,
            'centre_id' => $centreId,
        ]);
    }

    /**
     * @return list<array{code: string, label: array{fr: string, en: string}, examples: array{fr: string, en: string}, description: array{fr: string, en: string}, amount_xaf: int, validity: array{fr: string, en: string}}>
     */
    private function tariffs(): array
    {
        return [
            [
                'code' => 'B',
                'label' => ['fr' => 'Véhicule de tourisme', 'en' => 'Passenger vehicle'],
                'examples' => ['fr' => 'Voitures particulières, berlines, SUV, 4x4 à usage privé.', 'en' => 'Private cars, sedans, SUVs and private-use 4x4 vehicles.'],
                'description' => ['fr' => 'Pour les véhicules de tourisme à usage privé.', 'en' => 'For private passenger vehicles.'],
                'amount_xaf' => 17900,
                'validity' => ['fr' => '12 mois', 'en' => '12 months'],
            ],
            [
                'code' => 'A',
                'label' => ['fr' => 'Taxi / Auto-école', 'en' => 'Taxi / Driving school'],
                'examples' => ['fr' => 'Taxis, véhicules d’auto-école et véhicules assimilés.', 'en' => 'Taxis, driving-school vehicles and similar vehicles.'],
                'description' => ['fr' => 'Pour les véhicules exploités en taxi ou en auto-école.', 'en' => 'For vehicles used as taxis or driving-school vehicles.'],
                'amount_xaf' => 4900,
                'validity' => ['fr' => '03 mois', 'en' => '03 months'],
            ],
            [
                'code' => 'B1',
                'label' => ['fr' => 'Pickup 3,5 T / Véhicule utilitaire léger', 'en' => '3.5 T pickup / Light utility vehicle'],
                'examples' => ['fr' => 'Pickup 3,5 T, fourgonnettes et véhicules utilitaires légers.', 'en' => '3.5 T pickups, vans and light utility vehicles.'],
                'description' => ['fr' => 'Pour les utilitaires légers et pickups jusqu’à 3,5 T.', 'en' => 'For light utility vehicles and pickups up to 3.5 T.'],
                'amount_xaf' => 15500,
                'validity' => ['fr' => '06 mois', 'en' => '06 months'],
            ],
            [
                'code' => 'C < 3,5T',
                'label' => ['fr' => 'Mini-bus', 'en' => 'Minibus'],
                'examples' => ['fr' => 'Mini-bus et transport de personnes de moins de 3,5 T.', 'en' => 'Minibuses and passenger transport vehicles under 3.5 T.'],
                'description' => ['fr' => 'Pour les mini-bus et petits véhicules de transport de personnes.', 'en' => 'For minibuses and smaller passenger transport vehicles.'],
                'amount_xaf' => 15500,
                'validity' => ['fr' => '03 mois', 'en' => '03 months'],
            ],
            [
                'code' => 'C',
                'label' => ['fr' => 'Grand bus / Coaster', 'en' => 'Large bus / Coaster'],
                'examples' => ['fr' => 'Grands bus, coaster et véhicules de transport de personnes.', 'en' => 'Large buses, coasters and passenger transport vehicles.'],
                'description' => ['fr' => 'Pour les grands véhicules de transport de personnes.', 'en' => 'For large passenger transport vehicles.'],
                'amount_xaf' => 19080,
                'validity' => ['fr' => '03 mois', 'en' => '03 months'],
            ],
            [
                'code' => 'D',
                'label' => ['fr' => 'Poids lourd', 'en' => 'Heavy vehicle'],
                'examples' => ['fr' => 'Camions, tracteurs, semi-remorques et utilitaires lourds.', 'en' => 'Trucks, tractors, semi-trailers and heavy utility vehicles.'],
                'description' => ['fr' => 'Pour les camions et grands véhicules professionnels.', 'en' => 'For trucks and large professional vehicles.'],
                'amount_xaf' => 26235,
                'validity' => ['fr' => '06 mois', 'en' => '06 months'],
            ],
            [
                'code' => 'D_OTHER',
                'label' => ['fr' => 'Autres engins', 'en' => 'Other machinery'],
                'examples' => ['fr' => 'Engins spéciaux et véhicules ne relevant pas des profils courants.', 'en' => 'Special machinery and vehicles outside the common profiles.'],
                'description' => ['fr' => 'Pour les engins spéciaux à orienter selon la catégorie officielle.', 'en' => 'For special machinery to classify by official category.'],
                'amount_xaf' => 41750,
                'validity' => ['fr' => '12 mois', 'en' => '12 months'],
            ],
        ];
    }
}
