<?php

namespace App\Actions\Schedule\Data;

use App\Domain\Enums\Weekday;

final readonly class WeeklyHoursData
{
    public function __construct(
        public Weekday $weekday,
        public bool $isOpen,
        public ?string $opensAt,
        public ?string $closesAt,
    ) {}
}
