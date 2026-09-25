<?php

namespace Database\Seeders;

use App\Models\Centre\Centre;
use App\Models\Content\BrandAsset;
use Illuminate\Database\Seeder;
use RuntimeException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class IngestRealMediaSeeder extends Seeder
{
    public function run(): void
    {
        $brand = BrandAsset::query()->firstOrCreate(['code' => 'brand']);

        foreach ($this->brandAssets() as $asset) {
            $this->ingest($brand, 'brand', $asset);
        }

        foreach ($this->centreAssets() as $code => $assets) {
            $centre = Centre::query()->where('code', $code)->firstOrFail();

            foreach ($assets as $asset) {
                $this->ingest($centre, 'centres', $asset);
            }
        }
    }

    /**
     * @param  array{source_key: string, path: string, name: string, file_name: string, title: string, alt: array{fr: string, en: string}}  $asset
     */
    private function ingest(HasMedia $model, string $collection, array $asset): void
    {
        $alreadyStored = Media::query()
            ->where('model_type', $model->getMorphClass())
            ->where('model_id', $model->getKey())
            ->where('collection_name', $collection)
            ->where('custom_properties->source_key', $asset['source_key'])
            ->exists();

        if ($alreadyStored) {
            return;
        }

        $path = public_path($asset['path']);

        if (! is_file($path)) {
            throw new RuntimeException("Missing real G3 media file [{$asset['path']}].");
        }

        $model->addMedia($path)
            ->preservingOriginal()
            ->usingName($asset['name'])
            ->usingFileName($asset['file_name'])
            ->withCustomProperties([
                'alt' => $asset['alt'],
                'title' => $asset['title'],
                'is_published' => true,
                'source_key' => $asset['source_key'],
            ])
            ->toMediaCollection($collection, 'local');
    }

    /**
     * @return list<array{source_key: string, path: string, name: string, file_name: string, title: string, alt: array{fr: string, en: string}}>
     */
    private function brandAssets(): array
    {
        return [
            [
                'source_key' => 'brand.logo',
                'path' => 'images/reusable/site-logo.png',
                'name' => 'brand_logo_primary',
                'file_name' => 'brand_logo_primary.png',
                'title' => 'Logo G3 Control',
                'alt' => [
                    'fr' => 'Logo G3 Control',
                    'en' => 'G3 Control logo',
                ],
            ],
            [
                'source_key' => 'brand.mark',
                'path' => 'images/reusable/favicon.png',
                'name' => 'brand_mark',
                'file_name' => 'brand_mark.png',
                'title' => 'Marque G3 Control',
                'alt' => [
                    'fr' => 'Marque G3 Control',
                    'en' => 'G3 Control mark',
                ],
            ],
        ];
    }

    /**
     * @return array<string, list<array{source_key: string, path: string, name: string, file_name: string, title: string, alt: array{fr: string, en: string}}>>
     */
    private function centreAssets(): array
    {
        return [
            'ecole-de-police' => [
                $this->centreAsset(
                    'ecole-de-police.facade',
                    'images/homepage/ecole-de-police.png',
                    'ecole-de-police_facade_wide',
                    'Façade du centre École de Police',
                    'Façade du centre École de Police, Yaoundé',
                    'École de Police centre facade, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.exterior',
                    'images/centers/ecole-de-police/eco-1.png',
                    'ecole-de-police_exterior_street',
                    'Entrée du centre École de Police',
                    'Entrée du centre École de Police depuis la rue, Yaoundé',
                    'Street approach to the École de Police centre, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.lane-vehicle',
                    'images/centers/ecole-de-police/eco-2.png',
                    'ecole-de-police_lane_vehicle',
                    'Ligne de contrôle École de Police',
                    'Ligne de contrôle au centre École de Police, Yaoundé',
                    'Inspection lane at the École de Police centre, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.reception',
                    'images/centers/ecole-de-police/eco-3.png',
                    'ecole-de-police_reception',
                    'Réception École de Police',
                    'Réception du centre École de Police, Yaoundé',
                    'École de Police centre reception, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.reception-wide',
                    'images/homepage/hero-2.png',
                    'ecole-de-police_reception_wide',
                    'Salle d’accueil École de Police',
                    'Salle d’accueil du centre École de Police, Yaoundé',
                    'Waiting area at the École de Police centre, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.lane-equipment',
                    'images/centers/ecole-de-police/eco-4.png',
                    'ecole-de-police_lane_equipment',
                    'Équipements de ligne École de Police',
                    'Équipements de la ligne de contrôle au centre École de Police, Yaoundé',
                    'Inspection-lane equipment at the École de Police centre, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.lane-wide',
                    'images/homepage/hero-4.png',
                    'ecole-de-police_lane_wide',
                    'Ligne de contrôle École de Police',
                    'Ligne de contrôle du centre École de Police, Yaoundé',
                    'Inspection lane at the École de Police centre, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.lane-inspection',
                    'images/homepage/hero-1.png',
                    'ecole-de-police_lane_inspection',
                    'Contrôle sur la ligne École de Police',
                    'Contrôle sur la ligne au centre École de Police, Yaoundé',
                    'Inspection on the lane at the École de Police centre, Yaoundé',
                ),
                $this->centreAsset(
                    'ecole-de-police.team',
                    'images/about/technicians.png',
                    'ecole-de-police_team',
                    'Équipe du centre École de Police',
                    'Équipe au centre École de Police, Yaoundé',
                    'Team at the École de Police centre, Yaoundé',
                ),
            ],
            'nomayos' => [
                $this->centreAsset(
                    'nomayos.facade',
                    'images/homepage/nomayos.png',
                    'nomayos_facade_wide',
                    'Façade du centre Nomayos',
                    'Façade du centre Nomayos, Yaoundé',
                    'Nomayos centre facade, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.sign',
                    'images/centers/nomayos/nom-5.png',
                    'nomayos_exterior_sign',
                    'Enseigne du centre Nomayos',
                    'Enseigne du centre Nomayos, Yaoundé',
                    'Nomayos centre sign, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.approach',
                    'images/centers/nomayos.png',
                    'nomayos_exterior_approach',
                    'Extérieur du centre Nomayos',
                    'Extérieur du centre Nomayos, Yaoundé',
                    'Nomayos centre exterior, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.day',
                    'images/centers/nomayos/nom-2.png',
                    'nomayos_exterior_day',
                    'Approche du centre Nomayos',
                    'Approche du centre Nomayos, Yaoundé',
                    'Approach to the Nomayos centre, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.entrance',
                    'images/centers/nomayos/nom-4.png',
                    'nomayos_entrance',
                    'Entrée du centre Nomayos',
                    'Entrée et accueil du centre Nomayos, Yaoundé',
                    'Entrance and reception of the Nomayos centre, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.lane',
                    'images/centers/nomayos/nom-1.png',
                    'nomayos_lane',
                    'Ligne de contrôle Nomayos',
                    'Ligne de contrôle au centre Nomayos, Yaoundé',
                    'Inspection lane at the Nomayos centre, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.lane-vehicle',
                    'images/centers/nomayos/nom-3.png',
                    'nomayos_lane_vehicle',
                    'Contrôle au centre Nomayos',
                    'Contrôle d’un véhicule au centre Nomayos, Yaoundé',
                    'Vehicle inspection at the Nomayos centre, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.lane-inspection',
                    'images/homepage/g3-control.png',
                    'nomayos_lane_inspection',
                    'Ligne de contrôle Nomayos',
                    'Ligne de contrôle du centre Nomayos, Yaoundé',
                    'Nomayos centre inspection lane, Yaoundé',
                ),
                $this->centreAsset(
                    'nomayos.lane-marked',
                    'images/homepage/hero-5.png',
                    'nomayos_lane_marked',
                    'Ligne marquée G3 Control à Nomayos',
                    'Ligne de contrôle avec le marquage G3 Control au centre Nomayos, Yaoundé',
                    'Inspection lane with the G3 Control mark at the Nomayos centre, Yaoundé',
                ),
            ],
        ];
    }

    /**
     * @return array{source_key: string, path: string, name: string, file_name: string, title: string, alt: array{fr: string, en: string}}
     */
    private function centreAsset(string $sourceKey, string $path, string $name, string $title, string $frenchAlt, string $englishAlt): array
    {
        return [
            'source_key' => $sourceKey,
            'path' => $path,
            'name' => $name,
            'file_name' => $name.'.png',
            'title' => $title,
            'alt' => [
                'fr' => $frenchAlt,
                'en' => $englishAlt,
            ],
        ];
    }
}
