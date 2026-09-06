<?php

namespace App\Listeners;

use App\Contracts\NotificationPort;
use App\Events\ContactMessageReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendContactNotification implements ShouldQueue
{
    public function __construct(private NotificationPort $notifications) {}

    public function handle(ContactMessageReceived $event): void
    {
        $this->notifications->contactReceived($event);
    }
}
