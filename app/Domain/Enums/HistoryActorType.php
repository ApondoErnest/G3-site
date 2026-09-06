<?php

namespace App\Domain\Enums;

enum HistoryActorType: string
{
    case System = 'system';
    case User = 'user';
}
