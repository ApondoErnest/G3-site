<?php

namespace App\Domain\Enums;

enum AppointmentStatus: string
{
    case Received = 'received';
    case UnderReview = 'under_review';
    case Confirmed = 'confirmed';
    case ModificationRequested = 'modification_requested';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function isFinal(): bool
    {
        return match ($this) {
            self::Completed, self::Cancelled => true,
            default => false,
        };
    }

    public function isPublicTimelineVisible(): bool
    {
        return true;
    }
}
