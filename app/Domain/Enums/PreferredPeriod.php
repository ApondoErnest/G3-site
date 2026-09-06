<?php

namespace App\Domain\Enums;

enum PreferredPeriod: string
{
    case Morning = 'morning';
    case Afternoon = 'afternoon';
    case Any = 'any';
}
