<?php

use App\Actions\Appointment\CreateAppointmentRequest;
use App\Actions\Appointment\TrackAppointment;
use App\Actions\Contact\SubmitContactMessage;
use App\Actions\Schedule\ResolveAllCentresAvailability;
use App\Actions\Schedule\ResolveCentreAvailability;
use App\Actions\Tariff\PublishTariffVersion;
use App\Contracts\NotificationPort;
use App\Infrastructure\Notifications\MailNotificationAdapter;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

test('use case catalogue is fully implemented in app Actions FR-AD-03', function (): void {
    $expected = [
        'CreateAppointmentRequest',
        'TransitionAppointmentStatus',
        'UpdateAppointmentPreferredTime',
        'AddAppointmentInternalNote',
        'TrackAppointment',
        'ResolveCentreAvailability',
        'ResolveAllCentresAvailability',
        'ChangeCentreWeeklyHours',
        'CreateScheduleException',
        'UpdateScheduleException',
        'DeleteScheduleException',
        'ResolveEffectiveTariff',
        'CreateTariffVersionDraft',
        'UpdateTariffVersionItems',
        'MarkTariffVersionReviewed',
        'PublishTariffVersion',
        'SubmitContactMessage',
        'TransitionContactStatus',
        'AddContactInternalNote',
    ];

    foreach ($expected as $class) {
        $module = resolveModule($class);

        expect(class_exists("App\\Actions\\{$module}\\{$class}"))
            ->toBeTrue("Missing use case class {$class}");
    }
});

test('catalogue use cases are invokable single-purpose classes', function (): void {
    $invokable = [
        CreateAppointmentRequest::class,
        TrackAppointment::class,
        ResolveAllCentresAvailability::class,
        PublishTariffVersion::class,
        SubmitContactMessage::class,
    ];

    foreach ($invokable as $class) {
        expect(method_exists($class, '__invoke'))->toBeTrue("{$class} must be invokable");
        expect((new ReflectionClass($class))->isFinal())->toBeTrue("{$class} must be final");
    }

    $availability = new ReflectionClass(ResolveCentreAvailability::class);

    expect($availability->isFinal())->toBeTrue();
    expect($availability->hasMethod('snapshot'))->toBeTrue();
    expect($availability->hasMethod('isBookableOnDate'))->toBeTrue();
});

test('mail facade is only used inside notification infrastructure FR-NT-02', function (): void {
    $allowed = [
        realpath(app_path('Infrastructure/Notifications')),
    ];

    $finder = Finder::create()
        ->files()
        ->in(app_path())
        ->name('*.php')
        ->notPath('Infrastructure/Notifications');

    foreach ($finder as $file) {
        $contents = File::get($file->getRealPath());

        expect($contents)->not->toContain(
            'Illuminate\\Support\\Facades\\Mail',
            "Mail facade found in {$file->getRelativePathname()} — use NotificationPort instead",
        );
    }
});

test('notification port binds to mail adapter FR-NT-01', function (): void {
    expect(app(NotificationPort::class))->toBeInstanceOf(MailNotificationAdapter::class);
});

test('track appointment never caches lookup results', function (): void {
    $source = File::get(app_path('Actions/Appointment/TrackAppointment.php'));

    expect($source)->not->toContain('Cache::remember');
    expect($source)->not->toContain('Cache::get');
});

/**
 * @return non-empty-string
 */
function resolveModule(string $class): string
{
    return match ($class) {
        'CreateAppointmentRequest',
        'TransitionAppointmentStatus',
        'UpdateAppointmentPreferredTime',
        'AddAppointmentInternalNote',
        'TrackAppointment' => 'Appointment',
        'ResolveCentreAvailability',
        'ResolveAllCentresAvailability',
        'ChangeCentreWeeklyHours',
        'CreateScheduleException',
        'UpdateScheduleException',
        'DeleteScheduleException' => 'Schedule',
        'ResolveEffectiveTariff',
        'CreateTariffVersionDraft',
        'UpdateTariffVersionItems',
        'MarkTariffVersionReviewed',
        'PublishTariffVersion' => 'Tariff',
        'SubmitContactMessage',
        'TransitionContactStatus',
        'AddContactInternalNote' => 'Contact',
    };
}
