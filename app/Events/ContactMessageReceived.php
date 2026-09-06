<?php

namespace App\Events;

use App\Domain\Enums\ContactIntent;

final readonly class ContactMessageReceived
{
    public function __construct(
        public int $contactMessageId,
        public ContactIntent $intent,
        public string $locale,
    ) {}
}
