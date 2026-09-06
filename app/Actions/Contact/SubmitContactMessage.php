<?php

namespace App\Actions\Contact;

use App\Actions\Contact\Data\SubmitContactMessageData;
use App\Actions\Contact\Data\SubmitContactMessageResult;
use App\Domain\Enums\CentreStatus;
use App\Domain\Enums\ContactStatus;
use App\Domain\ValueObjects\PhoneNumber;
use App\Events\ContactMessageReceived;
use App\Models\Centre\Centre;
use App\Models\Contact\ContactMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

final class SubmitContactMessage
{
    private const MESSAGE_KEY = 'contact.submit.received';

    public function __invoke(SubmitContactMessageData $data): SubmitContactMessageResult
    {
        if ($data->honeypot !== '') {
            return new SubmitContactMessageResult(
                messageKey: self::MESSAGE_KEY,
                wasStored: false,
            );
        }

        $this->assertWithinRateLimit($data);

        if ($data->centreId !== null) {
            $centre = Centre::query()->find($data->centreId);

            if ($centre === null || $centre->status !== CentreStatus::Active) {
                throw ValidationException::withMessages([
                    'centreId' => ['The selected centre is not available.'],
                ]);
            }
        }

        if (! filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email' => ['The email address is invalid.'],
            ]);
        }

        $phone = PhoneNumber::fromInput($data->phone);

        $message = ContactMessage::query()->create([
            'intent' => $data->intent,
            'status' => ContactStatus::New,
            'name' => $data->name,
            'phone_e164' => $phone->e164,
            'email' => $data->email,
            'subject' => $data->subject,
            'centre_id' => $data->centreId,
            'message' => $data->message,
            'locale' => $data->locale,
        ]);

        Event::dispatch(new ContactMessageReceived(
            contactMessageId: $message->id,
            intent: $message->intent,
            locale: $message->locale->value,
        ));

        $this->hitRateLimit($data);

        return new SubmitContactMessageResult(messageKey: self::MESSAGE_KEY);
    }

    private function assertWithinRateLimit(SubmitContactMessageData $data): void
    {
        $key = $this->rateLimitKey($data);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new TooManyRequestsHttpException(
                RateLimiter::availableIn($key),
                'Too many contact submissions.',
            );
        }
    }

    private function hitRateLimit(SubmitContactMessageData $data): void
    {
        RateLimiter::hit($this->rateLimitKey($data), 3600);
    }

    private function rateLimitKey(SubmitContactMessageData $data): string
    {
        $identifier = $data->rateLimitKey ?? request()->ip() ?? '127.0.0.1';

        return 'contact-submit:'.$identifier;
    }
}
