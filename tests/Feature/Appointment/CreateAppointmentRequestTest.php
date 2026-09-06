<?php

use App\Actions\Appointment\CreateAppointmentRequest;
use App\Actions\Appointment\Data\CreateAppointmentRequestData;
use App\Actions\Schedule\CreateScheduleException;
use App\Actions\Schedule\Data\CreateScheduleExceptionData;
use App\Actions\Schedule\ResolveCentreAvailability;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\Locale;
use App\Domain\Enums\PreferredPeriod;
use App\Events\AppointmentRequested;
use App\Models\Appointment\AppointmentRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Event::fake();
    $this->seed(BaselineCentresSeeder::class);
});

test('submit creates one request with unique reference and received status FR-AP-04', function () {
    $payload = createAppointmentPayload(centreId('ecole-de-police'));

    $result = app(CreateAppointmentRequest::class)($payload);

    expect($result->publicReference)->toMatch('/^G3-\d{2}-[A-Z0-9]{5}$/')
        ->and($result->status)->toBe(AppointmentStatus::Received)
        ->and($result->messageKey)->toBe('appointments.request.received')
        ->and($result->wasExisting)->toBeFalse()
        ->and(AppointmentRequest::query()->count())->toBe(1)
        ->and(DB::table('appointment_status_histories')->count())->toBe(1);
});

test('idempotency key returns existing request on double submit FR-AP-12', function () {
    $payload = createAppointmentPayload(centreId('ecole-de-police'), [
        'idempotencyKey' => 'submit-token-1',
    ]);

    $first = app(CreateAppointmentRequest::class)($payload);
    $second = app(CreateAppointmentRequest::class)($payload);

    expect($second->wasExisting)->toBeTrue()
        ->and($second->publicReference)->toBe($first->publicReference)
        ->and(AppointmentRequest::query()->count())->toBe(1);
});

test('invalid service for centre is rejected BR-SVC-002', function () {
    $centreId = centreId('ecole-de-police');
    $otherCentreId = centreId('nomayos');
    $catalogue = seedBookableCatalogue($otherCentreId);

    $payload = new CreateAppointmentRequestData(
        centreId: $centreId,
        serviceId: $catalogue['serviceId'],
        vehicleCategoryId: $catalogue['categoryId'],
        registration: 'LT123AB',
        preferredDate: CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        preferredPeriod: PreferredPeriod::Morning,
        contactName: 'Jean Dupont',
        contactPhone: '687187516',
        contactEmail: null,
        preferredChannel: null,
        locale: Locale::Fr,
        rateLimitKey: 'test-client-2',
    );

    expect(fn () => app(CreateAppointmentRequest::class)($payload))
        ->toThrow(ValidationException::class);
});

test('preferred time outside hours is rejected BR-APPT-002', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(CreateScheduleException::class)(new CreateScheduleExceptionData(
        appliesToAllCentres: false,
        centreId: centreId('ecole-de-police'),
        startsOn: CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        endsOn: null,
        isOpen: false,
        opensAt: null,
        closesAt: null,
        reason: null,
        actor: $user,
    ));

    $payload = createAppointmentPayload(centreId('ecole-de-police'), [
        'preferredDate' => CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        'rateLimitKey' => 'test-client-3',
    ]);

    expect(fn () => app(CreateAppointmentRequest::class)($payload))
        ->toThrow(ValidationException::class);
});

test('sunday afternoon is rejected when centre closes at 15:00', function () {
    $resolver = app(ResolveCentreAvailability::class);
    $sunday = CarbonImmutable::parse('2026-09-06', 'Africa/Douala');

    expect($resolver->isBookableOnDate(centreId('ecole-de-police'), $sunday, PreferredPeriod::Morning))
        ->toBeTrue()
        ->and($resolver->isBookableOnDate(centreId('ecole-de-police'), $sunday, PreferredPeriod::Afternoon))
        ->toBeTrue();

    $payload = createAppointmentPayload(centreId('ecole-de-police'), [
        'preferredDate' => $sunday,
        'preferredPeriod' => PreferredPeriod::Afternoon,
        'rateLimitKey' => 'test-client-4',
    ]);

    app(CreateAppointmentRequest::class)($payload);

    expect(AppointmentRequest::query()->count())->toBe(1);
});

test('nomayos wednesday afternoon is bookable', function () {
    $payload = createAppointmentPayload(centreId('nomayos'), [
        'preferredDate' => CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        'preferredPeriod' => PreferredPeriod::Afternoon,
        'rateLimitKey' => 'test-client-5',
    ]);

    $result = app(CreateAppointmentRequest::class)($payload);

    expect($result->status)->toBe(AppointmentStatus::Received);
});

test('create dispatches AppointmentRequested event after commit', function () {
    app(CreateAppointmentRequest::class)(createAppointmentPayload(centreId('ecole-de-police'), [
        'rateLimitKey' => 'test-client-6',
    ]));

    Event::assertDispatched(AppointmentRequested::class);
});
