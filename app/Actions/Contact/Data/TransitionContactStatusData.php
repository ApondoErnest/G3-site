<?php

namespace App\Actions\Contact\Data;

use App\Domain\Enums\ContactStatus;
use App\Models\User;

final readonly class TransitionContactStatusData
{
    public function __construct(
        public int $contactMessageId,
        public ContactStatus $toStatus,
        public User $actor,
    ) {}
}
