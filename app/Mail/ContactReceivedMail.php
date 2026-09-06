<?php

namespace App\Mail;

use App\Models\Contact\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ContactReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public ContactMessage $message) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[G3] New contact message — '.$this->message->intent->value,
        );
    }

    public function content(): Content
    {
        $centreName = $this->message->centre !== null
            ? $this->message->centre->translatedName($this->message->locale)
            : null;

        return new Content(
            text: 'mail.contact-received',
            with: [
                'intent' => $this->message->intent->value,
                'name' => $this->message->name,
                'phone' => $this->message->phone_e164,
                'email' => $this->message->email,
                'subject' => $this->message->subject,
                'body' => $this->message->message,
                'centreName' => $centreName,
                'locale' => $this->message->locale->value,
            ],
        );
    }
}
