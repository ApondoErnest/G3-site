<?php

use App\Actions\Appointment\CreateAppointmentRequest;
use App\Actions\Contact\SubmitContactMessage;
use App\Listeners\SendAppointmentNotification;
use App\Listeners\SendContactNotification;
use App\Mail\AppointmentReceivedMail;
use App\Mail\ContactReceivedMail;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Mail::fake();
    $this->seed(BaselineCentresSeeder::class);
});

test('appointment requested listener is queued FR-NT-01', function () {
    Queue::fake();

    app(CreateAppointmentRequest::class)(createAppointmentPayload(centreId('ecole-de-police'), [
        'rateLimitKey' => 'notification-listener-appointment',
    ]));

    Queue::assertPushed(CallQueuedListener::class, function (CallQueuedListener $job): bool {
        return $job->class === SendAppointmentNotification::class;
    });
});

test('contact message received listener is queued FR-NT-01', function () {
    Queue::fake();

    app(SubmitContactMessage::class)(submitContactPayload([
        'rateLimitKey' => 'notification-listener-contact',
    ]));

    Queue::assertPushed(CallQueuedListener::class, function (CallQueuedListener $job): bool {
        return $job->class === SendContactNotification::class;
    });
});

test('create appointment delivers ops notification email end to end FR-AP-11', function () {
    config(['notifications.ops_email' => 'ops@example.com']);

    app(CreateAppointmentRequest::class)(createAppointmentPayload(centreId('ecole-de-police'), [
        'rateLimitKey' => 'notification-e2e-appointment',
    ]));

    Mail::assertSent(AppointmentReceivedMail::class, fn (AppointmentReceivedMail $mail): bool => $mail->hasTo('ops@example.com'));
});

test('submit contact delivers ops notification email end to end FR-CT-04', function () {
    config(['notifications.ops_email' => 'ops@example.com']);

    app(SubmitContactMessage::class)(submitContactPayload([
        'rateLimitKey' => 'notification-e2e-contact',
    ]));

    Mail::assertSent(ContactReceivedMail::class, fn (ContactReceivedMail $mail): bool => $mail->hasTo('ops@example.com'));
});
