<?php

use App\Contracts\NotificationPort;
use App\Domain\Enums\ContactIntent;
use App\Events\AppointmentRequested;
use App\Events\ContactMessageReceived;
use App\Infrastructure\Notifications\MailNotificationAdapter;
use App\Mail\AppointmentReceivedMail;
use App\Mail\ContactReceivedMail;
use App\Models\Appointment\AppointmentRequest;
use App\Settings\CompanySettings;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Mail::fake();
    $this->seed(BaselineCentresSeeder::class);
});

test('notification port binding resolves to mail adapter FR-NT-01', function () {
    expect(app(NotificationPort::class))->toBeInstanceOf(MailNotificationAdapter::class);
});

test('appointment received sends ops email FR-AP-11', function () {
    config(['notifications.ops_email' => 'ops@example.com']);

    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    $appointmentId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
    ]);

    app(NotificationPort::class)->appointmentReceived(new AppointmentRequested(
        appointmentId: $appointmentId,
        publicReference: 'G3-26-AB12C',
        centreId: centreId('ecole-de-police'),
        locale: 'fr',
    ));

    Mail::assertSent(AppointmentReceivedMail::class, function (AppointmentReceivedMail $mail): bool {
        return $mail->hasTo('ops@example.com');
    });
});

test('contact received sends ops email FR-CT-04', function () {
    config(['notifications.ops_email' => 'ops@example.com']);

    $messageId = insertContactMessage();

    app(NotificationPort::class)->contactReceived(new ContactMessageReceived(
        contactMessageId: $messageId,
        intent: ContactIntent::Assistance,
        locale: 'fr',
    ));

    Mail::assertSent(ContactReceivedMail::class, function (ContactReceivedMail $mail): bool {
        return $mail->hasTo('ops@example.com');
    });
});

test('notifications fall back to company settings email when ops email unset', function () {
    config(['notifications.ops_email' => null]);

    $messageId = insertContactMessage();

    app(NotificationPort::class)->contactReceived(new ContactMessageReceived(
        contactMessageId: $messageId,
        intent: ContactIntent::Assistance,
        locale: 'fr',
    ));

    Mail::assertSent(ContactReceivedMail::class, function (ContactReceivedMail $mail): bool {
        return $mail->hasTo(app(CompanySettings::class)->email);
    });
});

test('disabled notifications skip mail FR-NT-02', function () {
    config(['notifications.enabled' => false]);

    $messageId = insertContactMessage();

    app(NotificationPort::class)->contactReceived(new ContactMessageReceived(
        contactMessageId: $messageId,
        intent: ContactIntent::Assistance,
        locale: 'fr',
    ));

    Mail::assertNothingSent();
});

test('failed mail does not remove stored appointment NFR-R-02', function () {
    $mailer = Mockery::mock(Mailer::class);
    $mailer->shouldReceive('send')->andThrow(new RuntimeException('SMTP unavailable'));
    Mail::swap($mailer);

    $catalogue = seedBookableCatalogue(centreId('ecole-de-police'));
    $appointmentId = insertAppointmentRequest([
        'centre_id' => centreId('ecole-de-police'),
        'service_id' => $catalogue['serviceId'],
        'vehicle_category_id' => $catalogue['categoryId'],
    ]);

    app(NotificationPort::class)->appointmentReceived(new AppointmentRequested(
        appointmentId: $appointmentId,
        publicReference: 'G3-26-AB12C',
        centreId: centreId('ecole-de-police'),
        locale: 'fr',
    ));

    expect(AppointmentRequest::query()->whereKey($appointmentId)->exists())->toBeTrue();
});
