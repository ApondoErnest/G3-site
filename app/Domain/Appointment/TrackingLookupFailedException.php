<?php

namespace App\Domain\Appointment;

use RuntimeException;

final class TrackingLookupFailedException extends RuntimeException
{
    public const MESSAGE_KEY = 'appointments.tracking.generic_failure';

    public static function generic(): self
    {
        return new self(self::MESSAGE_KEY);
    }

    public function messageKey(): string
    {
        return $this->getMessage();
    }
}
