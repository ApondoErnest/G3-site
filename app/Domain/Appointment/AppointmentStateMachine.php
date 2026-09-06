<?php

namespace App\Domain\Appointment;

use App\Domain\Enums\AppointmentStatus;

final class AppointmentStateMachine
{
    /**
     * @var array<string, list<AppointmentStatus>>
     */
    private const TRANSITIONS = [
        'received' => [
            AppointmentStatus::UnderReview,
            AppointmentStatus::Cancelled,
        ],
        'under_review' => [
            AppointmentStatus::Confirmed,
            AppointmentStatus::ModificationRequested,
            AppointmentStatus::Cancelled,
        ],
        'modification_requested' => [
            AppointmentStatus::UnderReview,
            AppointmentStatus::Confirmed,
            AppointmentStatus::Cancelled,
        ],
        'confirmed' => [
            AppointmentStatus::Completed,
            AppointmentStatus::Cancelled,
        ],
        'completed' => [],
        'cancelled' => [],
    ];

    /**
     * @return list<AppointmentStatus>
     */
    public function allowedTransitions(AppointmentStatus $from): array
    {
        return self::TRANSITIONS[$from->value] ?? [];
    }

    public function assertCanTransition(AppointmentStatus $from, AppointmentStatus $to): void
    {
        if (! in_array($to, $this->allowedTransitions($from), true)) {
            throw InvalidAppointmentTransitionException::fromStatuses($from, $to);
        }
    }

    public function isFinal(AppointmentStatus $status): bool
    {
        return $status->isFinal();
    }
}
