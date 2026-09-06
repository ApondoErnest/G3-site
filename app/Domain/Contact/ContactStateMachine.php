<?php

namespace App\Domain\Contact;

use App\Domain\Enums\ContactStatus;

final class ContactStateMachine
{
    /**
     * @var array<string, list<ContactStatus>>
     */
    private const TRANSITIONS = [
        'new' => [
            ContactStatus::InProgress,
            ContactStatus::Resolved,
        ],
        'in_progress' => [
            ContactStatus::Resolved,
        ],
        'resolved' => [],
    ];

    /**
     * @return list<ContactStatus>
     */
    public function allowedTransitions(ContactStatus $from): array
    {
        return self::TRANSITIONS[$from->value] ?? [];
    }

    public function assertCanTransition(ContactStatus $from, ContactStatus $to): void
    {
        if (! in_array($to, $this->allowedTransitions($from), true)) {
            throw InvalidContactTransitionException::fromStatuses($from, $to);
        }
    }
}
