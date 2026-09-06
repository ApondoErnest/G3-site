<?php

namespace App\Domain\Enums;

enum ContactStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';

    public function isFinal(): bool
    {
        return $this === self::Resolved;
    }
}
