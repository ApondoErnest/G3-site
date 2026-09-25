<?php

use App\Filament\Pages\ManageCentreHours;
use App\Filament\Resources\Centre\Centres\CentreResource;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\AppointmentRequestResource;
use App\Filament\Resources\System\Users\Users\UserResource;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\TariffVersionResource;
use App\Models\Centre\Centre;
use App\Models\Identity\AdminUserScope;
use App\Models\Tariff\TariffVersion;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('a centre manager cannot change hours at an unassigned centre', function () {
    $ecoleId = centreId('ecole-de-police');
    $nomayosId = centreId('nomayos');
    $user = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $ecoleId,
        'created_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(ManageCentreHours::class)
        ->set('data.centre_id', $nomayosId)
        ->set('data.weekly_hours', [
            ['weekday' => 1, 'is_open' => true, 'opens_at' => '08:00', 'closes_at' => '12:00'],
        ])
        ->call('save');

    expect(DB::table('centre_weekly_hours')
        ->where('centre_id', $nomayosId)
        ->where('weekday', 1)
        ->value('closes_at'))->toBe('19:00:00');
});

test('a content editor cannot open appointments or confirm them', function () {
    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    $requestId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'status' => 'received',
    ]);
    $editor = createAdminUser('content_editor');

    $this->actingAs($editor)
        ->get(AppointmentRequestResource::getUrl('index'))
        ->assertForbidden();

    $this->actingAs($editor)
        ->get(AppointmentRequestResource::getUrl('view', ['record' => $requestId]))
        ->assertNotFound();
});

test('a reception officer cannot open user management', function () {
    $officer = createAdminUser('reception_officer');

    $this->actingAs($officer)
        ->get(UserResource::getUrl('index'))
        ->assertForbidden();
});

test('a centre manager cannot publish a tariff version', function () {
    $versionId = insertTariffVersion([
        'label' => '2026-auth',
        'status' => 'reviewed',
        'effective_from' => '2026-03-01',
    ]);
    insertTariffItem(['tariff_version_id' => $versionId]);
    $version = TariffVersion::query()->findOrFail($versionId);
    $manager = createAdminUser('centre_manager');

    $this->actingAs($manager)
        ->get(TariffVersionResource::getUrl('edit', ['record' => $version]))
        ->assertForbidden();

    expect($version->refresh()->status->value)->toBe('reviewed');
});

test('a reception officer cannot edit a centre record', function () {
    $centre = Centre::query()->where('code', 'ecole-de-police')->firstOrFail();
    $officer = createAdminUser('reception_officer');

    $this->actingAs($officer)
        ->get(CentreResource::getUrl('edit', ['record' => $centre]))
        ->assertForbidden();
});
