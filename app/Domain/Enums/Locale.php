<?php

namespace App\Domain\Enums;

enum Locale: string
{
    case Fr = 'fr';
    case En = 'en';

    public static function fromString(string $value): self
    {
        return self::tryFrom($value) ?? self::Fr;
    }

    public function fallback(): self
    {
        return self::Fr;
    }
}
