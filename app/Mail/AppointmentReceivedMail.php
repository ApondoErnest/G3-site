<?php

namespace App\Mail;

use App\Models\Appointment\AppointmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class AppointmentReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public AppointmentRequest $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[G3] New appointment request '.$this->appointment->public_reference,
        );
    }

    public function content(): Content
    {
        $locale = $this->appointment->locale->value;

        return new Content(
            text: 'mail.appointment-received',
            with: [
                'reference' => (string) $this->appointment->public_reference,
                'centreName' => $this->appointment->centre->translatedName($this->appointment->locale),
                'contactName' => $this->appointment->contact_name,
                'contactPhone' => $this->appointment->contact_phone_e164,
                'preferredDate' => $this->appointment->preferred_date->format('Y-m-d'),
                'preferredPeriod' => $this->appointment->preferred_period->value,
                'registration' => $this->appointment->registration_display,
                'locale' => $locale,
            ],
        );
    }
}
