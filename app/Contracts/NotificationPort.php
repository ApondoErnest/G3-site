<?php

namespace App\Contracts;

use App\Events\AppointmentRequested;
use App\Events\ContactMessageReceived;

interface NotificationPort
{
    public function appointmentReceived(AppointmentRequested $event): void;

    public function contactReceived(ContactMessageReceived $event): void;
}
