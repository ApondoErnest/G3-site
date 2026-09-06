<?php

namespace App\Domain\ValueObjects;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final readonly class TimeWindow
{
    public function __construct(
        public CarbonImmutable $opens,
        public CarbonImmutable $closes,
    ) {
        if (! $this->opens->lessThan($this->closes)) {
            throw new InvalidArgumentException('Open time must be before close time.');
        }
    }

    public static function forDate(string $date, string $opensAt, string $closesAt, string $timezone): self
    {
        return new self(
            CarbonImmutable::parse("{$date} {$opensAt}", $timezone),
            CarbonImmutable::parse("{$date} {$closesAt}", $timezone),
        );
    }

    public function contains(CarbonImmutable $instant): bool
    {
        return $instant->greaterThanOrEqualTo($this->opens) && $instant->lessThan($this->closes);
    }

    public function intersects(self $other): bool
    {
        return $this->opens->lessThan($other->closes) && $other->opens->lessThan($this->closes);
    }
}
