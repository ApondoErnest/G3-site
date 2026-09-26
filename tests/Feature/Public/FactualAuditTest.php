<?php

use App\Domain\ValueObjects\MoneyXaf;
use App\Settings\CompanySettings;
use Database\Seeders\BaselineCentresSeeder;
use Database\Seeders\OfficialTariffsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('seeded company centres and official fees match the locked baseline', function () {
    $this->seed(BaselineCentresSeeder::class);
    $this->seed(OfficialTariffsSeeder::class);

    $company = app(CompanySettings::class);

    expect($company->legal_name)->toBeNull()
        ->and($company->display_name)->toBe('G3 Control')
        ->and($company->slogan)->toBe([
            'fr' => 'Sécurité. Simplicité. Confiance.',
            'en' => 'Safety. Simplicity. Trust.',
        ])
        ->and($company->agrement_number)->toBe('0291')
        ->and($company->agrement_year)->toBe(2020)
        ->and($company->email)->toBe('g3sarl1@gmail.com')
        ->and($company->secondary_email)->toBe('admin@g3control.com')
        ->and($company->postal_address)->toBe('BP 12775 Yaoundé')
        ->and(DB::table('centres')->count())->toBe(2);

    $officialAmounts = [
        'B' => 17900,
        'A' => 4900,
        'B1' => 15500,
        'C < 3,5T' => 15500,
        'C' => 19080,
        'D' => 26235,
        'D_OTHER' => 41750,
    ];

    $published = DB::table('tariff_items')
        ->join('tariff_versions', 'tariff_versions.id', '=', 'tariff_items.tariff_version_id')
        ->join('vehicle_categories', 'vehicle_categories.id', '=', 'tariff_items.vehicle_category_id')
        ->where('tariff_versions.status', 'published')
        ->pluck('tariff_items.amount_xaf', 'vehicle_categories.code')
        ->all();

    expect($published)->toBe($officialAmounts);

    $ecole = DB::table('centres')->where('code', 'ecole-de-police')->first();
    $nomayos = DB::table('centres')->where('code', 'nomayos')->first();

    expect((float) $ecole->latitude)->toBe(3.8786152)
        ->and((float) $ecole->longitude)->toBe(11.5116814)
        ->and($ecole->email)->toBe('g3sarl1@gmail.com')
        ->and($ecole->secondary_email)->toBe('admin@g3control.com')
        ->and($nomayos->email)->toBe('g3sarl1@gmail.com')
        ->and($nomayos->secondary_email)->toBe('admin@g3control.com')
        ->and((bool) $ecole->holiday_default_open)->toBeTrue()
        ->and((float) $nomayos->latitude)->toBe(3.7902275)
        ->and((float) $nomayos->longitude)->toBe(11.4439448)
        ->and((bool) $nomayos->holiday_default_open)->toBeTrue();

    expect(DB::table('centre_phones')->where('centre_id', $ecole->id)->pluck('e164')->all())
        ->toBe(['+237687187516'])
        ->and(DB::table('centre_phones')->where('centre_id', $nomayos->id)->orderBy('sort_order')->pluck('e164')->all())
        ->toBe(['+237653100801', '+237692242143']);

    expect(DB::table('centre_weekly_hours')->where('centre_id', $ecole->id)->where('weekday', 1)->value('closes_at'))
        ->toBe('20:00:00')
        ->and(DB::table('centre_weekly_hours')->where('centre_id', $nomayos->id)->where('weekday', 1)->value('closes_at'))
        ->toBe('19:00:00')
        ->and(DB::table('centre_weekly_hours')->where('centre_id', $ecole->id)->where('weekday', 7)->value('closes_at'))
        ->toBe('15:00:00')
        ->and(DB::table('centre_weekly_hours')->where('centre_id', $nomayos->id)->where('weekday', 7)->value('closes_at'))
        ->toBe('15:00:00');

    $fees = $this->get('/fr/tarifs')->assertOk();

    foreach ($officialAmounts as $amount) {
        $fees->assertSeeText((new MoneyXaf($amount))->formatted());
    }

    $fees->assertDontSeeText('25 000 FCFA');

    $this->get('/fr/centres')->assertOk()
        ->assertSeeText('687 187 516')
        ->assertSeeText('653 100 801')
        ->assertSeeText('692 242 143')
        ->assertSeeText('07h00 - 20h00')
        ->assertSeeText('07h00 - 19h00')
        ->assertSeeText('07h00 - 15h00');

    $this->get('/fr/accueil')->assertOk()
        ->assertSeeText('Agrément N°0291 depuis 2020');

    $this->get('/fr/a-propos')->assertOk()
        ->assertSeeText('Sécurité. Simplicité. Confiance.');
});
