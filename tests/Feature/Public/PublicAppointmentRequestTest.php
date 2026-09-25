<?php

use App\Actions\Schedule\CreateScheduleException;
use App\Actions\Schedule\Data\CreateScheduleExceptionData;
use App\Models\Appointment\AppointmentRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    seedBaselineCentres();
});

test('a public request is received once and the confirmation does not book a slot', function () {
    $payload = appointmentRequestPayload(centreId('ecole-de-police'));

    $this->from('/fr/rendez-vous')
        ->withSession(['appointment_idempotency' => 'request-flow-1'])
        ->post('/fr/rendez-vous', $payload)
        ->assertRedirect('/fr/rendez-vous')
        ->assertSessionHas('appointment_received.message', __('public.security.appointment_received'));

    $this->get('/fr/rendez-vous')
        ->assertOk()
        ->assertSeeText(__('public.security.appointment_received'));

    $request = AppointmentRequest::query()->first();

    expect(AppointmentRequest::query()->count())->toBe(1)
        ->and($request?->status->value)->toBe('received')
        ->and($request?->statusHistories()->count())->toBe(1)
        ->and((string) $request?->public_reference)->toMatch('/^G3-\d{2}-[A-Z0-9]{5}$/');
});

test('a french appointment form keeps french validation', function () {
    $this->from('/fr/rendez-vous')
        ->post('/fr/rendez-vous', [])
        ->assertRedirect('/fr/rendez-vous')
        ->assertSessionHasErrors([
            'full_name' => __('public.security.appointment_errors.full_name', [], 'fr'),
        ]);

    $this->from('/en/appointment')
        ->post('/en/appointment', [])
        ->assertRedirect('/en/appointment')
        ->assertSessionHasErrors([
            'full_name' => __('public.security.appointment_errors.full_name', [], 'en'),
        ]);

    $this->get('/fr/rendez-vous')->assertSee('Indiquez votre nom et prénom.', false);
    $this->get('/en/appointment')->assertSee('Enter your full name.', false);

    $this->postJson('/fr/rendez-vous', [])
        ->assertUnprocessable()
        ->assertJsonPath('errors.full_name.0', 'Indiquez votre nom et prénom.');

    $this->postJson('/en/appointment', [])
        ->assertUnprocessable()
        ->assertJsonPath('errors.full_name.0', 'Enter your full name.');
});

test('a public request confirms in place', function () {
    $response = $this->postJson('/fr/rendez-vous', appointmentRequestPayload(centreId('ecole-de-police')));

    $response->assertOk()
        ->assertJsonPath('message', __('public.security.appointment_received'));

    expect($response->json('reference'))->toMatch('/^G3-\d{2}-[A-Z0-9]{5}$/')
        ->and(AppointmentRequest::query()->count())->toBe(1);
});

test('an exceptional closure cannot be chosen on the public request form', function () {
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
        reason: ['fr' => 'Fermeture exceptionnelle', 'en' => 'Exceptional closure'],
        actor: $user,
    ));

    $this->from('/fr/rendez-vous')
        ->post('/fr/rendez-vous', appointmentRequestPayload(centreId('ecole-de-police')))
        ->assertRedirect('/fr/rendez-vous')
        ->assertSessionHasErrors([
            'preferred_date' => __('public.security.appointment_outside_hours', [], 'fr'),
        ]);

    expect(AppointmentRequest::query()->count())->toBe(0);
});

test('a service that is not offered at the centre is rejected', function () {
    $catalogue = seedBookableCatalogue(centreId('nomayos'));

    $this->from('/fr/rendez-vous')
        ->post('/fr/rendez-vous', appointmentRequestPayload(centreId('ecole-de-police'), [
            'service_id' => $catalogue['serviceId'],
            'vehicle_category_id' => $catalogue['categoryId'],
        ]))
        ->assertRedirect('/fr/rendez-vous')
        ->assertSessionHasErrors([
            'service_id' => __('public.security.appointment_unavailable', [], 'fr'),
        ]);

    expect(AppointmentRequest::query()->count())->toBe(0);
});

test('an invalid phone number stays on the form', function () {
    $this->from('/fr/rendez-vous')
        ->post('/fr/rendez-vous', appointmentRequestPayload(centreId('ecole-de-police'), [
            'phone' => '12345',
        ]))
        ->assertRedirect('/fr/rendez-vous')
        ->assertSessionHasErrors([
            'phone' => __('public.security.appointment_phone', [], 'fr'),
        ]);

    expect(AppointmentRequest::query()->count())->toBe(0);
});

function appointmentRequestPayload(int $centreId, array $overrides = []): array
{
    $catalogue = seedBookableCatalogue($centreId);

    return array_merge([
        'centre_id' => $centreId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'registration' => 'LT 123 AB',
        'preferred_date' => '2026-09-09',
        'preferred_period' => 'morning',
        'full_name' => 'Jean Dupont',
        'phone' => '687187516',
    ], $overrides);
}
