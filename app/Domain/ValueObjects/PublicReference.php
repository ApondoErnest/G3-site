<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class PublicReference
{
    private const PATTERN = '/^G3-\d{2}-[A-Z0-9]{5}$/';

    private function __construct(public string $value) {}

    public static function generate(): self
    {
        $year = now()->format('y');
        $suffix = strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));

        return new self("G3-{$year}-{$suffix}");
    }

    public static function fromString(string $value): self
    {
        $normalized = strtoupper(trim($value));

        if (! preg_match(self::PATTERN, $normalized)) {
            throw new InvalidArgumentException('Public reference must match G3-YY-XXXXX format.');
        }

        return new self($normalized);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
