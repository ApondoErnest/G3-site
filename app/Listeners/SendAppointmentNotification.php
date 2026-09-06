<?php

namespace App\Listeners;

use App\Contracts\NotificationPort;
use App\Events\AppointmentRequested;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendAppointmentNotification implements ShouldQueue
{
    public function __construct(private NotificationPort $notifications) {}

    public function handle(AppointmentRequested $event): void
    {
        $this->notifications->appointmentReceived($event);
    }
}
