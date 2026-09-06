<?php

use App\Actions\Appointment\AddAppointmentInternalNote;
use App\Actions\Appointment\Data\AddAppointmentInternalNoteData;
use App\Actions\Appointment\Data\UpdateAppointmentPreferredTimeData;
use App\Actions\Appointment\UpdateAppointmentPreferredTime;
use App\Domain\Enums\PreferredPeriod;
use App\Models\Identity\AdminUserScope;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    (new BaselineCentresSeeder)->run();
    Role::create(['name' => 'reception_officer', 'guard_name' => 'web']);
});

function scopedReceptionUser(int $centreId): User
{
    $user = User::factory()->create();
    $user->assignRole('reception_officer');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $centreId,
        'created_at' => now(),
    ]);

    return $user;
}

test('update preferred time changes appointment fields FR-AP-09', function () {
    $centreId = centreId('ecole-de-police');
    $requestId = insertAppointmentRequest(['centre_id' => $centreId, 'status' => 'under_review']);
    $user = scopedReceptionUser($centreId);

    $appointment = app(UpdateAppointmentPreferredTime::class)(new UpdateAppointmentPreferredTimeData(
        appointmentId: $requestId,
        preferredDate: CarbonImmutable::parse('2026-09-10', 'Africa/Douala'),
        preferredPeriod: PreferredPeriod::Afternoon,
        actor: $user,
    ));

    expect($appointment->preferred_date?->toDateString())->toBe('2026-09-10')
        ->and($appointment->preferred_period)->toBe(PreferredPeriod::Afternoon);
});

test('completed appointments cannot be rescheduled', function () {
    $centreId = centreId('ecole-de-police');
    $requestId = insertAppointmentRequest([
        'centre_id' => $centreId,
        'status' => 'completed',
        'finalized_at' => now(),
    ]);
    $user = scopedReceptionUser($centreId);

    expect(fn () => app(UpdateAppointmentPreferredTime::class)(new UpdateAppointmentPreferredTimeData(
        appointmentId: $requestId,
        preferredDate: CarbonImmutable::parse('2026-09-10', 'Africa/Douala'),
        preferredPeriod: PreferredPeriod::Morning,
        actor: $user,
    )))->toThrow(ValidationException::class);
});

test('internal note is stored separately from status history BR-APPT-006', function () {
    $centreId = centreId('ecole-de-police');
    $requestId = insertAppointmentRequest(['centre_id' => $centreId]);
    $user = scopedReceptionUser($centreId);

    app(AddAppointmentInternalNote::class)(new AddAppointmentInternalNoteData(
        appointmentId: $requestId,
        body: 'Client prefers morning slot.',
        author: $user,
    ));

    expect(DB::table('appointment_internal_notes')->count())->toBe(1)
        ->and(DB::table('appointment_status_histories')->where('appointment_request_id', $requestId)->count())
        ->toBe(0);
});
