<?php

namespace App\Providers;

use App\Actions\Centre\ResolvePublicCentres;
use App\Contracts\NotificationPort;
use App\Domain\Enums\Locale;
use App\Events\AppointmentRequested;
use App\Events\ContactMessageReceived;
use App\Infrastructure\Notifications\MailNotificationAdapter;
use App\Listeners\SendAppointmentNotification;
use App\Listeners\SendContactNotification;
use App\Models\Centre\CentrePhone;
use App\Settings\CompanySettings;
use App\Support\AdminLocale;
use App\Support\CacheKeys;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationPort::class, MailNotificationAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(AppointmentRequested::class, SendAppointmentNotification::class);
        Event::listen(ContactMessageReceived::class, SendContactNotification::class);
        $this->configureAdminTimeDisplay();

        RateLimiter::for('appointment-submit', function (Request $request) {
            return Limit::perHour(5)->by($request->ip() ?? '127.0.0.1');
        });

        RateLimiter::for('tracking-lookup', function (Request $request) {
            return Limit::perHour(10)->by($request->ip() ?? '127.0.0.1');
        });

        RateLimiter::for('contact-submit', function (Request $request) {
            return Limit::perHour(5)->by($request->ip() ?? '127.0.0.1');
        });

        View::composer('layouts.public', function ($view): void {
            $publicPhone = $this->publicPhone();

            $view->with([
                'company' => app(CompanySettings::class),
                'publicPhone' => $publicPhone,
                'publicPhoneDisplay' => $this->formatPublicPhone($publicPhone),
            ]);
        });
    }

    private function configureAdminTimeDisplay(): void
    {
        $dateTimeFormat = fn (): string => AdminLocale::current() === Locale::En
            ? 'M j, Y g:i:s A'
            : 'M j, Y H:i:s';
        $timeFormat = fn (): string => AdminLocale::current() === Locale::En
            ? 'g:i:s A'
            : 'H:i:s';

        Table::configureUsing(function (Table $table) use ($dateTimeFormat, $timeFormat): void {
            $table
                ->defaultDateTimeDisplayFormat($dateTimeFormat)
                ->defaultTimeDisplayFormat($timeFormat);
        });

        Schema::configureUsing(function (Schema $schema) use ($dateTimeFormat, $timeFormat): void {
            $schema
                ->defaultDateTimeDisplayFormat($dateTimeFormat)
                ->defaultTimeDisplayFormat($timeFormat);
        });

        DateTimePicker::configureUsing(function (DateTimePicker $picker): void {
            $picker->displayFormat(function (DateTimePicker $picker): ?string {
                if (AdminLocale::current() !== Locale::En || ! $picker->hasTime()) {
                    return null;
                }

                if (! $picker->hasDate()) {
                    return $picker->hasSeconds() ? 'g:i:s A' : 'g:i A';
                }

                return $picker->hasSeconds() ? 'M j, Y g:i:s A' : 'M j, Y g:i A';
            });

            $picker->native(function (DateTimePicker $picker): bool {
                return ! (AdminLocale::current() === Locale::En && $picker->hasTime());
            });
        });
    }

    private function publicPhone(): ?string
    {
        return Cache::remember(
            CacheKeys::publicPhone(),
            CacheKeys::catalogueTtlSeconds(),
            fn (): ?string => CentrePhone::query()
                ->whereHas('centre', function ($query): void {
                    $query->where('code', 'ecole-de-police');
                })
                ->orderBy('sort_order')
                ->value('e164') ?? $this->fallbackPublicPhone(),
        );
    }

    private function fallbackPublicPhone(): ?string
    {
        return app(ResolvePublicCentres::class)(Locale::fromString(app()->getLocale()))['ecole-de-police']->primaryPhoneE164 ?? null;
    }

    private function formatPublicPhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        if (preg_match('/^\+237(\d{3})(\d{3})(\d{3})$/', $phone, $matches) === 1) {
            return "+237 {$matches[1]} {$matches[2]} {$matches[3]}";
        }

        return $phone;
    }
}
