<?php

use App\Domain\Appointment\AppointmentStateMachine;
use App\Domain\Appointment\InvalidAppointmentTransitionException;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\ValueObjects\PhoneNumber;
use App\Domain\ValueObjects\PublicReference;
use App\Domain\ValueObjects\RegistrationPlate;

test('appointment status final states are completed and cancelled', function () {
    expect(AppointmentStatus::Completed->isFinal())->toBeTrue()
        ->and(AppointmentStatus::Cancelled->isFinal())->toBeTrue()
        ->and(AppointmentStatus::Received->isFinal())->toBeFalse();
});

test('state machine allows received to under review and cancelled', function () {
    $machine = new AppointmentStateMachine;

    expect($machine->allowedTransitions(AppointmentStatus::Received))
        ->toContain(AppointmentStatus::UnderReview, AppointmentStatus::Cancelled);
});

test('state machine rejects received to completed', function () {
    $machine = new AppointmentStateMachine;

    expect(fn () => $machine->assertCanTransition(
        AppointmentStatus::Received,
        AppointmentStatus::Completed,
    ))->toThrow(InvalidAppointmentTransitionException::class);
});

test('public reference validates G3 format BR-APPT-004', function () {
    $reference = PublicReference::fromString('G3-26-A8FD2');

    expect((string) $reference)->toBe('G3-26-A8FD2');

    expect(fn () => PublicReference::fromString('bad-reference'))
        ->toThrow(InvalidArgumentException::class);
});

test('phone number normalizes cameroon mobile to e164 BR-CENT-004', function () {
    $phone = PhoneNumber::fromInput('687187516');

    expect($phone->e164)->toBe('+237687187516');
});

test('registration plate normalizes for tracking search', function () {
    $plate = RegistrationPlate::fromInput('lt 123 ab');

    expect($plate->normalized)->toBe('LT123AB')
        ->and($plate->display)->toBe('lt 123 ab');
});
