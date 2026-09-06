<?php

namespace App\Domain\Enums;

enum PreferredChannel: string
{
    case Phone = 'phone';
    case Email = 'email';
    case Whatsapp = 'whatsapp';
}
