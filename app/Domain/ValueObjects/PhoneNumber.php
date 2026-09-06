<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class PhoneNumber
{
    private const DEFAULT_COUNTRY = '237';

    public function __construct(public string $e164)
    {
        if (! preg_match('/^\+\d{8,15}$/', $this->e164)) {
            throw new InvalidArgumentException('Phone number must be stored in E.164 format.');
        }
    }

    public static function fromInput(string $input, string $defaultCountry = self::DEFAULT_COUNTRY): self
    {
        $digits = preg_replace('/[^\d+]/', '', trim($input)) ?? '';

        if ($digits === '') {
            throw new InvalidArgumentException('Phone number is required.');
        }

        if (str_starts_with($digits, '+')) {
            return new self($digits);
        }

        if (str_starts_with($digits, $defaultCountry)) {
            return new self('+'.$digits);
        }

        if (strlen($digits) === 9 && in_array($digits[0], ['6', '2'], true)) {
            return new self('+'.$defaultCountry.$digits);
        }

        throw new InvalidArgumentException('Phone number could not be normalized to E.164.');
    }

    public function displayNational(): string
    {
        $national = preg_replace('/^\+237/', '0', $this->e164) ?? $this->e164;

        return chunk_split($national, 3, ' ');
    }
}
