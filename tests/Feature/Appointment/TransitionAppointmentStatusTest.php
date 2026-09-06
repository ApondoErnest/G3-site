<?php

use App\Actions\Appointment\Data\TransitionAppointmentStatusData;
use App\Actions\Appointment\TransitionAppointmentStatus;
use App\Domain\Appointment\AppointmentStateMachine;
use App\Domain\Appointment\InvalidAppointmentTransitionException;
use App\Domain\Enums\AppointmentStatus;
use App\Events\AppointmentStatusChanged;
use App\Models\Appointment\AppointmentRequest;
use App\Models\Identity\AdminUserScope;
use App\Models\User;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Event::fake();
    (new BaselineCentresSeeder)->run();
    Role::create(['name' => 'reception_officer', 'guard_name' => 'web']);
    Role::create(['name' => 'content_editor', 'guard_name' => 'web']);
});

function receptionUserForCentre(int $centreId): User
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

test('received to completed is rejected BR-APPT-003', function () {
    $centreId = centreId('ecole-de-police');
    $requestId = insertAppointmentRequest(['centre_id' => $centreId]);
    $user = receptionUserForCentre($centreId);

    expect(fn () => app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::Completed,
        actor: $user,
    )))->toThrow(InvalidAppointmentTransitionException::class);
});

test('received to cancelled is allowed', function () {
    $requestId = insertAppointmentRequest(['centre_id' => centreId('ecole-de-police')]);
    $user = receptionUserForCentre(centreId('ecole-de-police'));

    $appointment = app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::Cancelled,
        actor: $user,
    ));

    expect($appointment->status)->toBe(AppointmentStatus::Cancelled)
        ->and($appointment->finalized_at)->not->toBeNull()
        ->and(DB::table('appointment_status_histories')->where('appointment_request_id', $requestId)->count())
        ->toBe(1);
});

test('happy path to completed records four history rows FR-AP-07', function () {
    $requestId = insertAppointmentRequest(['centre_id' => centreId('ecole-de-police')]);
    insertAppointmentStatusHistory($requestId, ['status' => 'received', 'actor_type' => 'system']);
    $user = receptionUserForCentre(centreId('ecole-de-police'));

    app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::UnderReview,
        actor: $user,
    ));
    app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::Confirmed,
        actor: $user,
    ));
    app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::Completed,
        actor: $user,
    ));

    $appointment = AppointmentRequest::query()->findOrFail($requestId);

    expect($appointment->status)->toBe(AppointmentStatus::Completed)
        ->and(DB::table('appointment_status_histories')->where('appointment_request_id', $requestId)->count())
        ->toBe(4);
});

test('final states have no allowed transitions', function () {
    $machine = app(AppointmentStateMachine::class);

    expect($machine->allowedTransitions(AppointmentStatus::Completed))->toBe([])
        ->and($machine->allowedTransitions(AppointmentStatus::Cancelled))->toBe([]);
});

test('content editor cannot transition appointments FR-AD-03', function () {
    $requestId = insertAppointmentRequest(['centre_id' => centreId('ecole-de-police')]);
    $user = User::factory()->create();
    $user->assignRole('content_editor');

    expect(fn () => app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::UnderReview,
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});

test('transition dispatches AppointmentStatusChanged event', function () {
    $requestId = insertAppointmentRequest(['centre_id' => centreId('ecole-de-police')]);
    $user = receptionUserForCentre(centreId('ecole-de-police'));

    app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
        appointmentId: $requestId,
        toStatus: AppointmentStatus::UnderReview,
        actor: $user,
    ));

    Event::assertDispatched(AppointmentStatusChanged::class);
});
