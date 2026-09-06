<?php

namespace App\Actions\Contact\Data;

use App\Models\User;

final readonly class AddContactInternalNoteData
{
    public function __construct(
        public int $contactMessageId,
        public string $body,
        public User $author,
    ) {}
}
