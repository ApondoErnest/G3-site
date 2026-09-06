<?php

namespace App\Domain\Content;

final class BilingualFields
{
    /**
     * @param  array<string, mixed>|null  $value
     */
    public static function isComplete(?array $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (! isset($value['fr'], $value['en']) || ! is_string($value['fr']) || ! is_string($value['en'])) {
            return false;
        }

        return $value['fr'] !== '' && $value['en'] !== '';
    }
}
