<?php

namespace App\Domain\Enums;

use Carbon\CarbonInterface;

enum Weekday: int
{
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;
    case Sunday = 7;

    public static function fromCarbon(CarbonInterface $date): self
    {
        return self::from($date->isoWeekday());
    }
}
