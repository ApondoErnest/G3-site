<?php

namespace App\Providers;

use App\Contracts\NotificationPort;
use App\Events\AppointmentRequested;
use App\Events\ContactMessageReceived;
use App\Infrastructure\Notifications\MailNotificationAdapter;
use App\Listeners\SendAppointmentNotification;
use App\Listeners\SendContactNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
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

        RateLimiter::for('appointment-submit', function (Request $request) {
            return Limit::perHour(5)->by($request->ip() ?? '127.0.0.1');
        });

        RateLimiter::for('tracking-lookup', function (Request $request) {
            return Limit::perHour(10)->by($request->ip() ?? '127.0.0.1');
        });

        RateLimiter::for('contact-submit', function (Request $request) {
            return Limit::perHour(5)->by($request->ip() ?? '127.0.0.1');
        });
    }
}
