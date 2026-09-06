<?php

namespace App\Domain\Appointment;

use App\Domain\Enums\AppointmentStatus;
use RuntimeException;

final class InvalidAppointmentTransitionException extends RuntimeException
{
    public static function fromStatuses(AppointmentStatus $from, AppointmentStatus $to): self
    {
        return new self("Cannot transition appointment from {$from->value} to {$to->value}.");
    }
}
