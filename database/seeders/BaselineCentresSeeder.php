<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaselineCentresSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCentre(
            code: 'ecole-de-police',
            name: ['fr' => 'École de Police', 'en' => 'École de Police'],
            landmark: [
                'fr' => 'Descente ancien Texaco, École de Police',
                'en' => 'Former Texaco descent, École de Police',
            ],
            latitude: 3.8786152,
            longitude: 11.5116814,
            sortOrder: 1,
            phones: [
                ['label' => 'primary', 'e164' => '+237687187516', 'is_whatsapp' => false, 'sort_order' => 1],
            ],
            weekdayHours: [
                ['weekday' => 1, 'opens_at' => '07:00:00', 'closes_at' => '20:00:00'],
                ['weekday' => 2, 'opens_at' => '07:00:00', 'closes_at' => '20:00:00'],
                ['weekday' => 3, 'opens_at' => '07:00:00', 'closes_at' => '20:00:00'],
                ['weekday' => 4, 'opens_at' => '07:00:00', 'closes_at' => '20:00:00'],
                ['weekday' => 5, 'opens_at' => '07:00:00', 'closes_at' => '20:00:00'],
                ['weekday' => 6, 'opens_at' => '07:00:00', 'closes_at' => '20:00:00'],
                ['weekday' => 7, 'opens_at' => '07:00:00', 'closes_at' => '15:00:00'],
            ],
        );

        $this->seedCentre(
            code: 'nomayos',
            name: ['fr' => 'Nomayos', 'en' => 'Nomayos'],
            landmark: [
                'fr' => 'Carrefour Nomayos',
                'en' => 'Nomayos junction',
            ],
            latitude: 3.7902275,
            longitude: 11.4439448,
            sortOrder: 2,
            phones: [
                ['label' => 'primary', 'e164' => '+237653100801', 'is_whatsapp' => false, 'sort_order' => 1],
                ['label' => 'secondary', 'e164' => '+237692242143', 'is_whatsapp' => false, 'sort_order' => 2],
            ],
            weekdayHours: [
                ['weekday' => 1, 'opens_at' => '07:00:00', 'closes_at' => '19:00:00'],
                ['weekday' => 2, 'opens_at' => '07:00:00', 'closes_at' => '19:00:00'],
                ['weekday' => 3, 'opens_at' => '07:00:00', 'closes_at' => '19:00:00'],
                ['weekday' => 4, 'opens_at' => '07:00:00', 'closes_at' => '19:00:00'],
                ['weekday' => 5, 'opens_at' => '07:00:00', 'closes_at' => '19:00:00'],
                ['weekday' => 6, 'opens_at' => '07:00:00', 'closes_at' => '19:00:00'],
                ['weekday' => 7, 'opens_at' => '07:00:00', 'closes_at' => '15:00:00'],
            ],
        );
    }

    /**
     * @param  array{fr: string, en: string}  $name
     * @param  array{fr: string, en: string}  $landmark
     * @param  list<array{label: string, e164: string, is_whatsapp: bool, sort_order: int}>  $phones
     * @param  list<array{weekday: int, opens_at: string, closes_at: string}>  $weekdayHours
     */
    private function seedCentre(
        string $code,
        array $name,
        array $landmark,
        float $latitude,
        float $longitude,
        int $sortOrder,
        array $phones,
        array $weekdayHours,
    ): void {
        $centreId = $this->upsertCentre($code, $name, $landmark, $latitude, $longitude, $sortOrder);

        DB::table('centre_phones')->where('centre_id', $centreId)->delete();
        DB::table('centre_weekly_hours')->where('centre_id', $centreId)->delete();

        foreach ($phones as $phone) {
            DB::table('centre_phones')->insert([
                'centre_id' => $centreId,
                'label' => $phone['label'],
                'e164' => $phone['e164'],
                'is_whatsapp' => $phone['is_whatsapp'],
                'sort_order' => $phone['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($weekdayHours as $hours) {
            DB::table('centre_weekly_hours')->insert([
                'centre_id' => $centreId,
                'weekday' => $hours['weekday'],
                'is_open' => true,
                'opens_at' => $hours['opens_at'],
                'closes_at' => $hours['closes_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * @param  array{fr: string, en: string}  $name
     * @param  array{fr: string, en: string}  $landmark
     */
    private function upsertCentre(
        string $code,
        array $name,
        array $landmark,
        float $latitude,
        float $longitude,
        int $sortOrder,
    ): int {
        $attributes = [
            'name' => json_encode($name),
            'address' => json_encode(['fr' => 'Yaoundé', 'en' => 'Yaoundé']),
            'landmark' => json_encode($landmark),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'email' => 'g3sarl1@gmail.com',
            'postal_code' => '12775 Yaoundé',
            'status' => 'active',
            'sort_order' => $sortOrder,
            'holiday_default_open' => true,
            'seo_title' => null,
            'seo_description' => null,
            'updated_at' => now(),
        ];

        $existingId = DB::table('centres')->where('code', $code)->value('id');

        if ($existingId !== null) {
            DB::table('centres')->where('id', $existingId)->update($attributes);

            return $existingId;
        }

        return DB::table('centres')->insertGetId(array_merge($attributes, [
            'code' => $code,
            'created_at' => now(),
        ]));
    }
}
