<?php

use App\Actions\Appointment\Data\TransitionAppointmentStatusData;
use App\Actions\Appointment\TransitionAppointmentStatus;
use App\Domain\Enums\AppointmentStatus;
use App\Models\Appointment\AppointmentRequest;
use App\Models\Identity\AdminUserScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

test('a public request can be confirmed and then tracked without private details', function () {
    seedBaselineCentres();
    freezeDisplayTime('2026-09-07 10:00:00');

    $centreId = centreId('ecole-de-police');
    $catalogue = seedBookableCatalogue($centreId);

    $this->post('/fr/rendez-vous', [
        'centre_id' => $centreId,
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'registration' => 'CE 4419 KD',
        'preferred_date' => '2026-09-09',
        'preferred_period' => 'morning',
        'full_name' => 'Amina Nkolo',
        'phone' => '655443322',
        'email' => 'amina.nkolo@client.test',
    ])->assertRedirect()->assertSessionHas('appointment_received.reference');

    $appointment = AppointmentRequest::query()->firstOrFail();

    expect($appointment->status)->toBe(AppointmentStatus::Received);

    $officer = createAdminUser('reception_officer');
    AdminUserScope::query()->create([
        'user_id' => $officer->id,
        'centre_id' => $centreId,
        'created_at' => now(),
    ]);

    app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $appointment->id,
        toStatus: AppointmentStatus::UnderReview,
        actor: $officer,
    ));
    app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $appointment->id,
        toStatus: AppointmentStatus::Confirmed,
        actor: $officer,
    ));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::Confirmed)
        ->and($appointment->statusHistories()->count())->toBe(3);

    RateLimiter::clear('tracking-lookup:127.0.0.1');

    $tracked = $this->post('/fr/rendez-vous/track', [
        'request_reference' => (string) $appointment->public_reference,
        'tracking_phone' => '655443322',
    ]);

    $tracked->assertRedirect()
        ->assertSessionHas('tracking_result.reference', (string) $appointment->public_reference)
        ->assertSessionHas(
            'tracking_result.status',
            __('public.security.tracking_statuses.confirmed', [], 'fr'),
        );

    $this->get('/fr/rendez-vous')
        ->assertOk()
        ->assertSeeText((string) $appointment->public_reference)
        ->assertSee(
            '— '.__('public.security.tracking_statuses.confirmed', [], 'fr').' — École de Police',
            false,
        )
        ->assertDontSee('Amina Nkolo')
        ->assertDontSee('amina.nkolo@client.test')
        ->assertDontSee('CE 4419 KD')
        ->assertDontSee('CE4419KD')
        ->assertDontSee('655443322')
        ->assertDontSee('+237655443322');
});
