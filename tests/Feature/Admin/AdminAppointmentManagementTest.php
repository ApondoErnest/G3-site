<?php

use App\Domain\Enums\AppointmentStatus;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\AppointmentRequestResource;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Pages\ListAppointmentRequests;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Pages\ViewAppointmentRequest;
use App\Models\Appointment\AppointmentRequest;
use App\Models\Identity\AdminUserScope;
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

test('operations roles can access appointment list FR-AD-02', function (): void {
    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'public_reference' => 'G3-26-LIST1',
    ]);

    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get(AppointmentRequestResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.appointments.navigation', locale: 'fr'))
        ->assertSee('G3-26-LIST1');
});

test('centre manager list is scoped to assigned centre FR-AD-03', function (): void {
    $ecoleId = centreId('ecole-de-police');
    $nomayosId = centreId('nomayos');
    $catalogue = seedBookableCatalogue($ecoleId);

    $ownId = insertAppointmentRequest([
        'centre_id' => $ecoleId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'public_reference' => 'G3-26-SCOP1',
    ]);
    $otherId = insertAppointmentRequest([
        'centre_id' => $nomayosId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'public_reference' => 'G3-26-SCOP2',
    ]);

    $user = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $ecoleId,
        'created_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(ListAppointmentRequests::class)
        ->assertCanSeeTableRecords([AppointmentRequest::query()->findOrFail($ownId)])
        ->assertCanNotSeeTableRecords([AppointmentRequest::query()->findOrFail($otherId)]);

    $this->actingAs($user)
        ->get(AppointmentRequestResource::getUrl('view', ['record' => $otherId]))
        ->assertNotFound();
});

test('illegal transition is blocked in ui and action BR-APPT-003', function (): void {
    $centreId = centreId('ecole-de-police');
    $catalogue = seedBookableCatalogue($centreId);
    $receivedId = insertAppointmentRequest([
        'centre_id' => $centreId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'status' => 'received',
    ]);
    $completedId = insertAppointmentRequest([
        'centre_id' => $centreId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'status' => 'completed',
        'finalized_at' => now(),
    ]);

    $user = createAdminUser('reception_officer');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $centreId,
        'created_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(ViewAppointmentRequest::class, ['record' => $completedId])
        ->assertActionDoesNotExist('transition_under_review')
        ->assertActionDoesNotExist('transition_cancelled');

    Livewire::actingAs($user)
        ->test(ViewAppointmentRequest::class, ['record' => $receivedId])
        ->call('transitionStatus', AppointmentStatus::Completed)
        ->assertNotified(
            __('admin.appointments.notifications.invalid_transition'),
        );

    expect(AppointmentRequest::query()->findOrFail($receivedId)->status)->toBe(AppointmentStatus::Received);
});

test('allowed transition updates appointment status', function (): void {
    $centreId = centreId('ecole-de-police');
    $catalogue = seedBookableCatalogue($centreId);
    $requestId = insertAppointmentRequest([
        'centre_id' => $centreId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'status' => 'received',
    ]);

    $user = createAdminUser('reception_officer');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $centreId,
        'created_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(ViewAppointmentRequest::class, ['record' => $requestId])
        ->callAction('transition_under_review')
        ->assertNotified(__('admin.appointments.notifications.transitioned'));

    expect(AppointmentRequest::query()->findOrFail($requestId)->status)->toBe(AppointmentStatus::UnderReview)
        ->and(DB::table('appointment_status_histories')->where('appointment_request_id', $requestId)->count())
        ->toBe(1);
});
