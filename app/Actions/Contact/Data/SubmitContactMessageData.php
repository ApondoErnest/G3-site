<?php

namespace App\Actions\Contact\Data;

use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\Locale;

final readonly class SubmitContactMessageData
{
    public function __construct(
        public ContactIntent $intent,
        public string $name,
        public string $phone,
        public string $email,
        public string $subject,
        public string $message,
        public Locale $locale,
        public ?int $centreId = null,
        public string $honeypot = '',
        public ?string $rateLimitKey = null,
    ) {}
}
