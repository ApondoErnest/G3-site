<?php

namespace App\Domain\Enums;

enum ContactIntent: string
{
    case Appointment = 'appointment';
    case Centre = 'centre';
    case Tariffs = 'tariffs';
    case Assistance = 'assistance';
}
