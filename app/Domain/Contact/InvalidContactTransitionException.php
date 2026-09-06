<?php

namespace App\Domain\Contact;

use App\Domain\Enums\ContactStatus;
use RuntimeException;

final class InvalidContactTransitionException extends RuntimeException
{
    public static function fromStatuses(ContactStatus $from, ContactStatus $to): self
    {
        return new self("Cannot transition contact message from {$from->value} to {$to->value}.");
    }
}
