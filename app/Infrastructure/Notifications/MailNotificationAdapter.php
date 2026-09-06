<?php

namespace App\Infrastructure\Notifications;

use App\Contracts\NotificationPort;
use App\Events\AppointmentRequested;
use App\Events\ContactMessageReceived;
use App\Mail\AppointmentReceivedMail;
use App\Mail\ContactReceivedMail;
use App\Models\Appointment\AppointmentRequest;
use App\Models\Contact\ContactMessage;
use App\Settings\CompanySettings;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class MailNotificationAdapter implements NotificationPort
{
    public function __construct(private CompanySettings $companySettings) {}

    public function appointmentReceived(AppointmentRequested $event): void
    {
        if (! config('notifications.enabled')) {
            return;
        }

        $appointment = AppointmentRequest::query()
            ->with(['centre', 'service', 'vehicleCategory'])
            ->find($event->appointmentId);

        if ($appointment === null) {
            return;
        }

        $this->sendSafely(fn () => Mail::to($this->opsRecipient())->send(
            new AppointmentReceivedMail($appointment),
        ));
    }

    public function contactReceived(ContactMessageReceived $event): void
    {
        if (! config('notifications.enabled')) {
            return;
        }

        $message = ContactMessage::query()
            ->with('centre')
            ->find($event->contactMessageId);

        if ($message === null) {
            return;
        }

        $this->sendSafely(fn () => Mail::to($this->opsRecipient())->send(
            new ContactReceivedMail($message),
        ));
    }

    private function opsRecipient(): string
    {
        return config('notifications.ops_email') ?? $this->companySettings->email;
    }

    private function sendSafely(callable $send): void
    {
        try {
            $send();
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
