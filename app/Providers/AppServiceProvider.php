<?php

namespace App\Providers;

use App\Contracts\NotificationPort;
use App\Events\AppointmentRequested;
use App\Events\ContactMessageReceived;
use App\Infrastructure\Notifications\MailNotificationAdapter;
use App\Listeners\SendAppointmentNotification;
use App\Listeners\SendContactNotification;
use App\Models\Centre\CentrePhone;
use App\Settings\CompanySettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const FALLBACK_PUBLIC_PHONE = '+237687187516';

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

    private function publicPhone(): ?string
    {
        return CentrePhone::query()
            ->whereHas('centre', function ($query): void {
                $query->where('code', 'ecole-de-police');
            })
            ->orderBy('sort_order')
            ->value('e164') ?? self::FALLBACK_PUBLIC_PHONE;
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
