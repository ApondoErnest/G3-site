<?php

use App\Actions\Contact\Data\SubmitContactMessageData;
use App\Actions\Contact\SubmitContactMessage;
use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\ContactStatus;
use App\Domain\Enums\Locale;
use App\Events\ContactMessageReceived;
use App\Models\Contact\ContactMessage;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Event::fake();
    RateLimiter::clear('contact-submit:test-contact-client');
    $this->seed(BaselineCentresSeeder::class);
});

test('valid contact submission stores new message FR-CT-02', function () {
    $result = app(SubmitContactMessage::class)(submitContactPayload());

    expect($result->messageKey)->toBe('contact.submit.received')
        ->and($result->wasStored)->toBeTrue()
        ->and(ContactMessage::query()->count())->toBe(1);

    $message = ContactMessage::query()->first();

    expect($message->status)->toBe(ContactStatus::New)
        ->and($message->intent)->toBe(ContactIntent::Assistance)
        ->and($message->phone_e164)->toBe('+237653100801');
});

test('contact submission dispatches ContactMessageReceived event FR-CT-04', function () {
    app(SubmitContactMessage::class)(submitContactPayload([
        'rateLimitKey' => 'test-contact-event',
    ]));

    Event::assertDispatched(ContactMessageReceived::class);
});

test('filled honeypot stores nothing FR-CT-05', function () {
    $result = app(SubmitContactMessage::class)(submitContactPayload([
        'honeypot' => 'bot@spam.example',
        'rateLimitKey' => 'test-contact-honeypot',
    ]));

    expect($result->wasStored)->toBeFalse()
        ->and(ContactMessage::query()->count())->toBe(0);

    Event::assertNotDispatched(ContactMessageReceived::class);
});

test('repeated contact submissions are rate limited FR-CT-05', function () {
    for ($attempt = 0; $attempt < 5; $attempt++) {
        app(SubmitContactMessage::class)(submitContactPayload([
            'email' => "marie{$attempt}@example.com",
            'rateLimitKey' => 'contact-rate-limit',
        ]));
    }

    expect(fn () => app(SubmitContactMessage::class)(submitContactPayload([
        'email' => 'marie6@example.com',
        'rateLimitKey' => 'contact-rate-limit',
    ])))->toThrow(TooManyRequestsHttpException::class);

    RateLimiter::clear('contact-submit:contact-rate-limit');
});

test('invalid centre is rejected', function () {
    $payload = new SubmitContactMessageData(
        intent: ContactIntent::Centre,
        name: 'Marie N.',
        phone: '653100801',
        email: 'marie@example.com',
        subject: 'Question',
        message: 'Bonjour',
        locale: Locale::Fr,
        centreId: 99999,
        rateLimitKey: 'test-contact-centre',
    );

    expect(fn () => app(SubmitContactMessage::class)($payload))
        ->toThrow(ValidationException::class);
});

test('invalid email is rejected', function () {
    expect(fn () => app(SubmitContactMessage::class)(submitContactPayload([
        'email' => 'not-an-email',
        'rateLimitKey' => 'test-contact-email',
    ])))->toThrow(ValidationException::class);
});

test('contact submission accepts optional centre FR-CT-02', function () {
    app(SubmitContactMessage::class)(submitContactPayload([
        'intent' => ContactIntent::Centre,
        'centreId' => centreId('nomayos'),
        'rateLimitKey' => 'test-contact-centre-valid',
    ]));

    expect(ContactMessage::query()->value('centre_id'))->toBe(centreId('nomayos'));
});
