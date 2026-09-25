<?php

use App\Actions\Appointment\Data\TrackAppointmentData;
use App\Actions\Appointment\TrackAppointment;
use App\Domain\Appointment\TrackingLookupFailedException;
use App\Domain\Enums\AppointmentStatus;
use App\Models\Identity\AdminUserScope;
use App\Models\User;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    RateLimiter::clear('tracking-lookup:test-tracker');
    $this->seed(BaselineCentresSeeder::class);
});

function trackAppointment(string $reference, string $secondFactor, string $rateLimitKey = 'test-tracker'): void
{
    app(TrackAppointment::class)(new TrackAppointmentData(
        publicReference: $reference,
        phoneOrRegistration: $secondFactor,
        rateLimitKey: $rateLimitKey,
    ));
}

test('matching phone with reference returns tracking result FR-TR-02', function () {
    $requestId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'public_reference' => 'G3-26-A0001',
        'contact_phone_e164' => '+237687187516',
        'registration_normalized' => 'LT123AB',
        'status' => 'under_review',
    ]);
    insertAppointmentStatusHistory($requestId, [
        'status' => 'received',
        'created_at' => now()->subHour(),
    ]);
    insertAppointmentStatusHistory($requestId, [
        'status' => 'under_review',
        'actor_type' => 'user',
        'created_at' => now(),
    ]);

    $result = app(TrackAppointment::class)(new TrackAppointmentData(
        publicReference: 'G3-26-A0001',
        phoneOrRegistration: '687187516',
        rateLimitKey: 'test-tracker',
    ));

    expect($result->publicReference)->toBe('G3-26-A0001')
        ->and($result->currentStatus)->toBe(AppointmentStatus::UnderReview)
        ->and($result->centreName)->toBe('École de Police')
        ->and($result->timeline)->toHaveCount(2)
        ->and($result->timeline[0]->status)->toBe(AppointmentStatus::Received)
        ->and($result->timeline[0]->labelKey)->toBe('appointments.status.received')
        ->and($result->timeline[1]->status)->toBe(AppointmentStatus::UnderReview);
});

test('matching registration with reference returns tracking result BR-TRACK-001', function () {
    insertAppointmentRequest([
        'centre_id' => centreId('nomayos'),
        'public_reference' => 'G3-26-B0002',
        'registration_normalized' => 'LT456CD',
        'locale' => 'en',
    ]);

    $result = app(TrackAppointment::class)(new TrackAppointmentData(
        publicReference: 'G3-26-B0002',
        phoneOrRegistration: 'lt 456 cd',
        rateLimitKey: 'test-tracker',
    ));

    expect($result->publicReference)->toBe('G3-26-B0002')
        ->and($result->centreName)->toBe('Nomayos');
});

test('wrong second factor returns generic failure BR-TRACK-003', function () {
    insertAppointmentRequest([
        'public_reference' => 'G3-26-C0003',
        'contact_phone_e164' => '+237687187516',
    ]);

    expect(fn () => trackAppointment('G3-26-C0003', '653100801'))
        ->toThrow(TrackingLookupFailedException::class, TrackingLookupFailedException::MESSAGE_KEY);
});

test('unknown reference returns same generic failure as mismatch FR-TR-03', function () {
    insertAppointmentRequest([
        'public_reference' => 'G3-26-D0004',
        'contact_phone_e164' => '+237687187516',
    ]);

    try {
        trackAppointment('G3-26-N0000', '687187516');
        expect(false)->toBeTrue('Expected tracking failure.');
    } catch (TrackingLookupFailedException $unknownReference) {
        try {
            trackAppointment('G3-26-D0004', '000000000');
        } catch (TrackingLookupFailedException $wrongFactor) {
            expect($unknownReference->messageKey())->toBe($wrongFactor->messageKey());
        }
    }
});

test('reference alone without phone or registration is rejected', function () {
    insertAppointmentRequest(['public_reference' => 'G3-26-E0005']);

    expect(fn () => trackAppointment('G3-26-E0005', ''))
        ->toThrow(TrackingLookupFailedException::class);
});

test('internal notes are never included in tracking result BR-TRACK-002', function () {
    Role::create(['name' => 'reception_officer', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('reception_officer');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => centreId('ecole-de-police'),
        'created_at' => now(),
    ]);

    $requestId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'public_reference' => 'G3-26-F0006',
    ]);

    DB::table('appointment_internal_notes')->insert([
        'appointment_request_id' => $requestId,
        'author_id' => $user->id,
        'body' => 'Staff-only note about the client.',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $result = app(TrackAppointment::class)(new TrackAppointmentData(
        publicReference: 'G3-26-F0006',
        phoneOrRegistration: '687187516',
        rateLimitKey: 'test-tracker',
    ));

    $encoded = json_encode($result, JSON_THROW_ON_ERROR);

    expect($encoded)->not->toContain('Staff-only note')
        ->and($encoded)->not->toContain('author')
        ->and($encoded)->not->toContain('"id"');
});

test('repeated failed lookups are rate limited FR-TR-05', function () {
    for ($attempt = 0; $attempt < 10; $attempt++) {
        try {
            trackAppointment('G3-26-R'.str_pad((string) $attempt, 4, '0', STR_PAD_LEFT), '000000000', 'rate-limit-client');
        } catch (TrackingLookupFailedException) {
            // expected
        }
    }

    expect(fn () => trackAppointment('G3-26-R9999', '000000000', 'rate-limit-client'))
        ->toThrow(TrackingLookupFailedException::class, TrackingLookupFailedException::MESSAGE_KEY);

    RateLimiter::clear('tracking-lookup:rate-limit-client');
});

test('public tracking page shows status without contact details or notes BR-TRACK-002', function () {
    $requestId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'public_reference' => 'G3-26-P0001',
        'contact_phone_e164' => '+237699000111',
        'contact_email' => 'secret@client.test',
        'registration_display' => 'LT 999 ZZ',
        'registration_normalized' => 'LT999ZZ',
    ]);
    insertAppointmentStatusHistory($requestId, [
        'public_note' => 'PUBLIC-NOTE-SHOULD-STAY-OFF-TRACKING',
        'actor_id' => User::factory()->create(['name' => 'Agent Secret'])->id,
        'actor_type' => 'user',
    ]);
    insertAppointmentRequest([
        'centre_id' => centreId('nomayos'),
        'public_reference' => 'G3-26-OTHER',
    ]);
    DB::table('appointment_internal_notes')->insert([
        'appointment_request_id' => $requestId,
        'author_id' => User::factory()->create()->id,
        'body' => 'PRIVATE-NOTE-140',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->post('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-P0001',
        'tracking_phone' => '699000111',
    ])->assertRedirect();

    $this->get('/fr/rendez-vous')
        ->assertOk()
        ->assertSeeText('G3-26-P0001')
        ->assertSeeText(__('public.security.tracking_statuses.received'))
        ->assertSeeText('École de Police')
        ->assertDontSee('PRIVATE-NOTE-140')
        ->assertDontSee('PUBLIC-NOTE-SHOULD-STAY-OFF-TRACKING')
        ->assertDontSee('secret@client.test')
        ->assertDontSee('LT 999 ZZ')
        ->assertDontSee('Agent Secret')
        ->assertDontSee('G3-26-OTHER');
});

test('public tracking accepts the registration plate as the second factor BR-TRACK-001', function () {
    insertAppointmentRequest([
        'centre_id' => centreId('nomayos'),
        'public_reference' => 'G3-26-P0002',
        'registration_normalized' => 'CE1122',
        'contact_phone_e164' => '+237699000222',
    ]);

    $this->post('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-P0002',
        'tracking_phone' => 'CE 1122',
    ])->assertRedirect()->assertSessionHas('tracking_result.reference', 'G3-26-P0002');
});

test('repeated public failures keep the same generic message FR-TR-05', function () {
    insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'public_reference' => 'G3-26-P0003',
        'contact_phone_e164' => '+237699000333',
    ]);

    for ($attempt = 0; $attempt < 10; $attempt++) {
        $this->post('/fr/rendez-vous/track', [
            'request_reference' => 'G3-26-P0003',
            'tracking_phone' => '600000000',
        ])->assertRedirect()->assertSessionHasErrors([
            'tracking' => __('public.security.tracking_failed'),
        ]);
    }

    $this->post('/fr/rendez-vous/track', [
        'request_reference' => 'G3-26-P0003',
        'tracking_phone' => '699000333',
    ])->assertRedirect()
        ->assertSessionHasErrors(['tracking' => __('public.security.tracking_failed')])
        ->assertSessionMissing('tracking_result');

    RateLimiter::clear('tracking-lookup:127.0.0.1');
});

test('invalid reference format returns generic failure without existence leak', function () {
    expect(fn () => trackAppointment('not-a-reference', '687187516'))
        ->toThrow(TrackingLookupFailedException::class, TrackingLookupFailedException::MESSAGE_KEY);
});
