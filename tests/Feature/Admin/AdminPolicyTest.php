<?php

use App\Models\Appointment\AppointmentRequest;
use App\Models\Contact\ContactMessage;
use App\Models\Content\ContentBlock;
use App\Models\Identity\AdminUserScope;
use App\Models\Tariff\TariffVersion;
use App\Models\User;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BaselineCentresSeeder::class);
    seedAdminRoles();
});

test('super admin can manage all policy surfaces FR-AD-03', function () {
    $user = createAdminUser('super_admin');
    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    $appointmentId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
    ]);
    $appointment = AppointmentRequest::query()->findOrFail($appointmentId);
    $contactId = insertContactMessage(['centre_id' => centreId('nomayos')]);
    $contact = ContactMessage::query()->findOrFail($contactId);
    $tariffVersion = TariffVersion::query()->create([
        'label' => '2026-Q1',
        'status' => 'draft',
        'effective_from' => '2026-01-01',
        'effective_until' => null,
        'created_by' => $user->id,
    ]);

    expect($user->can('viewAny', AppointmentRequest::class))->toBeTrue()
        ->and($user->can('update', $appointment))->toBeTrue()
        ->and($user->can('update', $contact))->toBeTrue()
        ->and($user->can('viewAny', User::class))->toBeTrue()
        ->and($user->can('publish', $tariffVersion))->toBeTrue()
        ->and($user->can('viewAny', ContentBlock::class))->toBeTrue();
});

test('content editor cannot manage appointments FR-AD-03', function () {
    $user = createAdminUser('content_editor');
    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    $appointmentId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
    ]);
    $appointment = AppointmentRequest::query()->findOrFail($appointmentId);

    expect($user->can('viewAny', AppointmentRequest::class))->toBeFalse()
        ->and($user->can('update', $appointment))->toBeFalse()
        ->and($user->can('viewAny', ContentBlock::class))->toBeTrue();
});

test('centre manager is scoped to assigned centre FR-AD-03', function () {
    $user = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => centreId('ecole-de-police'),
        'created_at' => now(),
    ]);

    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    $ownAppointmentId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
    ]);
    $otherAppointmentId = insertAppointmentRequest([
        'centre_id' => centreId('nomayos'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
    ]);
    $tariffVersion = TariffVersion::query()->create([
        'label' => '2026-Q2',
        'status' => 'draft',
        'effective_from' => '2026-01-01',
        'effective_until' => null,
        'created_by' => $user->id,
    ]);

    expect($user->can('update', AppointmentRequest::query()->findOrFail($ownAppointmentId)))->toBeTrue()
        ->and($user->can('update', AppointmentRequest::query()->findOrFail($otherAppointmentId)))->toBeFalse()
        ->and($user->can('publish', $tariffVersion))->toBeFalse();
});

test('reception officer cannot manage users or publish tariffs FR-AD-03', function () {
    $user = createAdminUser('reception_officer');
    $tariffVersion = TariffVersion::query()->create([
        'label' => '2026-Q3',
        'status' => 'draft',
        'effective_from' => '2026-01-01',
        'effective_until' => null,
        'created_by' => $user->id,
    ]);

    expect($user->can('viewAny', User::class))->toBeFalse()
        ->and($user->can('publish', $tariffVersion))->toBeFalse()
        ->and($user->can('viewAny', AppointmentRequest::class))->toBeTrue();
});

test('operations admin can publish tariffs but not manage users', function () {
    $user = createAdminUser('operations_admin');
    $tariffVersion = TariffVersion::query()->create([
        'label' => '2026-Q4',
        'status' => 'draft',
        'effective_from' => '2026-01-01',
        'effective_until' => null,
        'created_by' => $user->id,
    ]);

    expect($user->can('publish', $tariffVersion))->toBeTrue()
        ->and($user->can('viewAny', User::class))->toBeFalse()
        ->and($user->can('viewAny', ContentBlock::class))->toBeFalse();
});
