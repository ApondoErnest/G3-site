<?php

namespace App\Domain\Enums;

enum LiveCentreState: string
{
    case OpenNormalHours = 'open_normal_hours';
    case Closed = 'closed';
}
