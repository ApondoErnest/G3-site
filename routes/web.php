<?php

use App\Http\Controllers\Admin\SwitchAdminLocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StorePublicAppointmentRequestController;
use App\Http\Controllers\StorePublicContactMessageController;
use App\Http\Controllers\TrackPublicAppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::redirect('/', '/fr/accueil');

Route::get('/admin/locale/{locale}', SwitchAdminLocaleController::class)
    ->whereIn('locale', config('admin.locales', config('locale.supported')))
    ->name('admin.locale.switch');

foreach (config('locale.supported') as $locale) {
    Route::prefix($locale)
        ->middleware('locale')
        ->group(function () use ($locale): void {
            $homeSlug = config("locale.pages.home.{$locale}");

            Route::redirect('/', "/{$locale}/{$homeSlug}");

            foreach (config('locale.pages') as $page => $slugs) {
                Route::get($slugs[$locale], PageController::class)
                    ->defaults('page', $page)
                    ->name("{$locale}.{$page}");
            }

            Route::post(config("locale.pages.contact.{$locale}"), StorePublicContactMessageController::class)
                ->name("{$locale}.contact.submit");

            Route::post(config("locale.pages.appointment.{$locale}"), StorePublicAppointmentRequestController::class)
                ->name("{$locale}.appointment.store");

            Route::post(config("locale.pages.appointment.{$locale}").'/track', TrackPublicAppointmentController::class)
                ->name("{$locale}.appointment.track");
        });
}
