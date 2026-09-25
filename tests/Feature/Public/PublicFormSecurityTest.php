<?php

use App\Models\Appointment\AppointmentRequest;
use App\Models\Contact\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    seedBaselineCentres();
    RateLimiter::clear('contact-submit:127.0.0.1');
    RateLimiter::clear('tracking-lookup:127.0.0.1');
    RateLimiter::clear('appointment-submit:127.0.0.1');
});

test('contact form posts with csrf protection and a honeypot that stores nothing', function () {
    $this->get('/fr/contact')
        ->assertOk()
        ->assertSee('name="_token"', false)
        ->assertSee('name="website"', false);

    $this->post('/fr/contact', contactSecurityPayload([
        'website' => 'https://spam.example',
    ]))
        ->assertRedirect()
        ->assertSessionHas('contact_received', __('public.security.contact_received'));

    expect(ContactMessage::query()->count())->toBe(0);

    $this->post('/fr/contact', contactSecurityPayload())
        ->assertRedirect()
        ->assertSessionHas('contact_received');

    expect(ContactMessage::query()->count())->toBe(1);
});

test('contact json submission confirms in place and localizes required fields', function () {
    $this->get('/fr/contact')->assertSee('Indiquez votre nom et prénom.', false);
    $this->get('/en/contact')->assertSee('Enter your full name.', false);

    $this->postJson('/fr/contact', contactSecurityPayload())
        ->assertOk()
        ->assertJsonPath('message', 'Votre message a été reçu. L’équipe G3 Control vous répondra.');

    expect(ContactMessage::query()->count())->toBe(1);

    $this->postJson('/fr/contact', contactSecurityPayload(['name' => '']))
        ->assertUnprocessable()
        ->assertJsonPath('errors.name.0', 'Indiquez votre nom et prénom.');

    $this->postJson('/en/contact', contactSecurityPayload(['name' => '']))
        ->assertUnprocessable()
        ->assertJsonPath('errors.name.0', 'Enter your full name.');
});

test('repeated contact posts return a generic limit message', function () {
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post('/fr/contact', contactSecurityPayload([
            'email' => "marie{$attempt}@example.com",
        ]))->assertSessionHas('contact_received');
    }

    $this->post('/fr/contact', contactSecurityPayload([
        'email' => 'blocked@example.com',
    ]))
        ->assertRedirect()
        ->assertSessionHasErrors([
            'form' => __('public.security.too_many'),
        ]);

    expect(ContactMessage::query()->count())->toBe(5);
});

test('tracking failure stays generic and omits internal notes', function () {
    $requestId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'public_reference' => 'G3-26-A0001',
        'contact_phone_e164' => '+237687187516',
    ]);
    insertAppointmentStatusHistory($requestId);

    DB::table('appointment_internal_notes')->insert([
        'appointment_request_id' => $requestId,
        'author_id' => User::factory()->create()->id,
        'body' => 'SECRET-STAFF-NOTE',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->post('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-A0001',
        'tracking_phone' => '600000000',
    ])->assertRedirect()->assertSessionHasErrors([
        'tracking' => __('public.security.tracking_failed'),
    ]);

    $mismatch = session('errors')->first('tracking');

    $this->post('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-ZZZZZ',
        'tracking_phone' => '687187516',
    ])->assertRedirect()->assertSessionHasErrors('tracking');

    expect(session('errors')->first('tracking'))->toBe($mismatch);

    $this->post('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-A0001',
        'tracking_phone' => '687187516',
    ])->assertRedirect()->assertSessionHas('tracking_result');

    $this->get('/fr/rendez-vous')
        ->assertOk()
        ->assertSee('G3-26-A0001')
        ->assertDontSee('SECRET-STAFF-NOTE');

    $this->postJson('/fr/rendez-vous/track', [])
        ->assertUnprocessable()
        ->assertJsonPath('errors.request_reference.0', 'Indiquez la référence de demande.');

    $this->postJson('/en/appointment/track', [])
        ->assertUnprocessable()
        ->assertJsonPath('errors.request_reference.0', 'Enter the request reference.');

    $this->postJson('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-A0001',
        'tracking_phone' => '687187516',
    ])->assertOk()
        ->assertJsonPath('reference', 'G3-26-A0001')
        ->assertJsonPath('message', __('public.security.tracking_found'));
});

test('appointment post is idempotent for the same session token', function () {
    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));

    $payload = [
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'registration' => 'LT 123 AB',
        'preferred_date' => '2026-09-09',
        'preferred_period' => 'morning',
        'full_name' => 'Jean Dupont',
        'phone' => '687187516',
    ];

    $this->withSession(['appointment_idempotency' => 'session-token-1'])
        ->post('/fr/rendez-vous', $payload)
        ->assertRedirect()
        ->assertSessionHas('appointment_received');

    $this->withSession(['appointment_idempotency' => 'session-token-1'])
        ->post('/fr/rendez-vous', $payload)
        ->assertRedirect()
        ->assertSessionHas('appointment_received');

    expect(AppointmentRequest::query()->count())->toBe(1);
});

test('repeated appointment posts return a generic limit message', function () {
    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));

    $payload = [
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
        'registration' => 'LT 123 AB',
        'preferred_date' => '2026-09-09',
        'preferred_period' => 'morning',
        'full_name' => 'Jean Dupont',
        'phone' => '687187516',
    ];

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->withSession(['appointment_idempotency' => 'session-token-'.$attempt])
            ->post('/fr/rendez-vous', $payload)
            ->assertSessionHas('appointment_received');
    }

    $this->withSession(['appointment_idempotency' => 'session-token-6'])
        ->post('/fr/rendez-vous', $payload)
        ->assertRedirect()
        ->assertSessionHasErrors([
            'form' => __('public.security.too_many'),
        ]);

    expect(AppointmentRequest::query()->count())->toBe(5);
});

function contactSecurityPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Marie N.',
        'phone' => '653100801',
        'email' => 'marie@example.com',
        'subject' => 'Horaires',
        'message' => 'Quels sont vos horaires le dimanche ?',
        'centre' => 'ecole-de-police',
        'website' => '',
    ], $overrides);
}
