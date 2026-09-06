<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class RegistrationPlate
{
    private function __construct(
        public string $normalized,
        public string $display,
    ) {}

    public static function fromInput(string $input): self
    {
        $trimmed = trim($input);

        if ($trimmed === '') {
            throw new InvalidArgumentException('Registration plate is required.');
        }

        $normalized = strtoupper(preg_replace('/\s+/', '', $trimmed) ?? '');

        if ($normalized === '') {
            throw new InvalidArgumentException('Registration plate is required.');
        }

        return new self($normalized, $trimmed);
    }
}
