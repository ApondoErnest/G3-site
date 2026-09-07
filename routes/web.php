<?php

use App\Http\Controllers\Admin\SwitchAdminLocaleController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

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
        });
}
