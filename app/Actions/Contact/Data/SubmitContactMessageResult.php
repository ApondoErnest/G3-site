<?php

namespace App\Actions\Contact\Data;

final readonly class SubmitContactMessageResult
{
    public function __construct(
        public string $messageKey,
        public bool $wasStored = true,
    ) {}
}
