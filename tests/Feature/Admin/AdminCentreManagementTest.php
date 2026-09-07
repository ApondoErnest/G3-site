<?php

use App\Filament\Pages\ManageCentreHours;
use App\Filament\Resources\Centre\Centres\CentreResource;
use App\Filament\Resources\Centre\OperationalAlerts\OperationalAlertResource;
use App\Filament\Resources\Centre\OperationalAlerts\Pages\CreateOperationalAlert;
use App\Filament\Resources\Centre\ScheduleExceptions\ScheduleExceptionResource;
use App\Models\Centre\Centre;
use App\Models\Centre\OperationalAlert;
use App\Models\Centre\ScheduleException;
use App\Models\Identity\AdminUserScope;
use App\Support\CacheKeys;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('operations admin can access centre management pages', function (): void {
    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get(CentreResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.centres.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(ManageCentreHours::getUrl())
        ->assertOk()
        ->assertSee(__('admin.hours.title', locale: 'fr'));

    $this->actingAs($user)
        ->get(ScheduleExceptionResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.exceptions.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(OperationalAlertResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.alerts.navigation', locale: 'fr'));
});

test('centre manager can access scoped centres and hours but not alerts', function (): void {
    $centre = Centre::query()->orderBy('sort_order')->firstOrFail();

    $user = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $centre->id,
        'created_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(CentreResource::getUrl('index'))
        ->assertOk();

    $this->actingAs($user)
        ->get(ManageCentreHours::getUrl())
        ->assertOk();

    $this->actingAs($user)
        ->get(ScheduleExceptionResource::getUrl('index'))
        ->assertOk();

    $this->actingAs($user)
        ->get(OperationalAlertResource::getUrl('index'))
        ->assertForbidden();
});

test('centre manager cannot edit unassigned centre', function (): void {
    $centres = Centre::query()->orderBy('sort_order')->get();
    $assigned = $centres[0];
    $other = $centres[1];

    $user = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $assigned->id,
        'created_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(CentreResource::getUrl('edit', ['record' => $other]))
        ->assertNotFound();
});

test('manage centre hours saves weekly schedule through domain action', function (): void {
    $centre = Centre::query()->orderBy('sort_order')->firstOrFail();
    $user = createAdminUser('operations_admin');

    Livewire::actingAs($user)
        ->test(ManageCentreHours::class)
        ->set('data.centre_id', $centre->id)
        ->set('data.weekly_hours', [
            ['weekday' => 1, 'is_open' => true, 'opens_at' => '08:00', 'closes_at' => '17:00'],
            ['weekday' => 2, 'is_open' => true, 'opens_at' => '08:00', 'closes_at' => '17:00'],
            ['weekday' => 3, 'is_open' => true, 'opens_at' => '08:00', 'closes_at' => '17:00'],
            ['weekday' => 4, 'is_open' => true, 'opens_at' => '08:00', 'closes_at' => '17:00'],
            ['weekday' => 5, 'is_open' => true, 'opens_at' => '08:00', 'closes_at' => '17:00'],
            ['weekday' => 6, 'is_open' => false, 'opens_at' => null, 'closes_at' => null],
            ['weekday' => 7, 'is_open' => false, 'opens_at' => null, 'closes_at' => null],
        ])
        ->call('save')
        ->assertHasNoErrors();

    expect(DB::table('centre_weekly_hours')
        ->where('centre_id', $centre->id)
        ->where('weekday', 1)
        ->value('opens_at'))->toBe('08:00:00');
});

test('creating operational alert clears active alerts cache', function (): void {
    Cache::put(CacheKeys::alertsActive(), ['cached' => true], 60);

    $user = createAdminUser('operations_admin');

    Livewire::actingAs($user)
        ->test(CreateOperationalAlert::class)
        ->fillForm([
            'severity' => 'warning',
            'message' => [
                'fr' => 'Travaux en cours',
                'en' => 'Work in progress',
            ],
            'starts_at' => '2026-09-10 08:00:00',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Cache::has(CacheKeys::alertsActive()))->toBeFalse()
        ->and(OperationalAlert::query()->count())->toBe(1);
});

test('centre and schedule exception policies respect role scopes FR-AD-03', function (): void {
    $centres = Centre::query()->orderBy('sort_order')->get();
    $assignedCentre = $centres[0];
    $otherCentre = $centres[1];

    $manager = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $manager->id,
        'centre_id' => $assignedCentre->id,
        'created_at' => now(),
    ]);

    $scopedException = ScheduleException::query()->create([
        'applies_to_all_centres' => false,
        'centre_id' => $assignedCentre->id,
        'starts_on' => '2026-09-10',
        'ends_on' => null,
        'is_open' => false,
        'opens_at' => null,
        'closes_at' => null,
        'reason' => ['fr' => 'Fermeture', 'en' => 'Closed'],
        'created_by' => null,
    ]);

    $otherException = ScheduleException::query()->create([
        'applies_to_all_centres' => false,
        'centre_id' => $otherCentre->id,
        'starts_on' => '2026-09-11',
        'ends_on' => null,
        'is_open' => false,
        'opens_at' => null,
        'closes_at' => null,
        'reason' => ['fr' => 'Fermeture', 'en' => 'Closed'],
        'created_by' => null,
    ]);

    expect($manager->can('update', $assignedCentre))->toBeTrue()
        ->and($manager->can('update', $otherCentre))->toBeFalse()
        ->and($manager->can('update', $scopedException))->toBeTrue()
        ->and($manager->can('update', $otherException))->toBeFalse()
        ->and($manager->can('viewAny', OperationalAlert::class))->toBeFalse();

    $ops = createAdminUser('operations_admin');

    expect($ops->can('viewAny', OperationalAlert::class))->toBeTrue()
        ->and($ops->can('create', OperationalAlert::class))->toBeTrue();
});
