<?php

use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages\CreateTariffVersion;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages\EditTariffVersion;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages\ListTariffVersions;
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

test('operations admin can access tariff management pages', function (): void {
    $user = createAdminUser('operations_admin');

    Livewire::actingAs($user)
        ->test(ListTariffVersions::class)
        ->assertSuccessful()
        ->assertSee(__('admin.tariffs.navigation', locale: 'fr'));
});

test('centre manager can view tariffs but cannot create', function (): void {
    $user = createAdminUser('centre_manager');

    Livewire::actingAs($user)
        ->test(ListTariffVersions::class)
        ->assertSuccessful();

    Livewire::actingAs($user)
        ->test(CreateTariffVersion::class)
        ->assertForbidden();

    expect($user->can('viewAny', TariffVersion::class))->toBeTrue()
        ->and($user->can('create', TariffVersion::class))->toBeFalse();
});

test('creating tariff version draft via livewire', function (): void {
    $user = createAdminUser('operations_admin');

    Livewire::actingAs($user)
        ->test(CreateTariffVersion::class)
        ->fillForm([
            'label' => '2026-02',
            'effective_from' => '2026-02-01',
            'effective_until' => null,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TariffVersion::query()
        ->where('label', '2026-02')
        ->where('status', 'draft')
        ->exists())->toBeTrue();
});

test('mark reviewed and publish workflow via livewire', function (): void {
    $user = createAdminUser('operations_admin');
    $categoryId = insertVehicleCategory(['code' => 'vp']);
    $centreId = centreId('ecole-de-police');

    Livewire::actingAs($user)
        ->test(CreateTariffVersion::class)
        ->fillForm([
            'label' => '2026-03',
            'effective_from' => '2026-03-01',
            'effective_until' => null,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $version = TariffVersion::query()->where('label', '2026-03')->firstOrFail();

    Livewire::actingAs($user)
        ->test(EditTariffVersion::class, ['record' => $version->getRouteKey()])
        ->fillForm([
            'label' => '2026-03',
            'effective_from' => '2026-03-01',
            'effective_until' => null,
            'tariff_items' => [
                [
                    'vehicle_category_id' => $categoryId,
                    'service_id' => null,
                    'amount_xaf' => 25000,
                    'centres' => [$centreId],
                    'validity_notes' => ['fr' => '', 'en' => ''],
                    'sort_order' => 1,
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($version->refresh()->items()->count())->toBe(1);

    Livewire::actingAs($user)
        ->test(EditTariffVersion::class, ['record' => $version->getRouteKey()])
        ->callAction('markReviewed')
        ->assertNotified();

    expect($version->refresh()->status->value)->toBe('reviewed');

    Livewire::actingAs($user)
        ->test(EditTariffVersion::class, ['record' => $version->getRouteKey()])
        ->callAction('publish', data: [
            'confirm_effective_from' => '2026-03-01',
        ])
        ->assertNotified();

    expect($version->refresh()->status->value)->toBe('published');
});
