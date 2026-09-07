<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Http\Middleware\SetAdminLocale;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile(isSimple: false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('G3 Control')
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::hex('#F47A20'),
                'gray' => Color::Slate,
                'info' => Color::hex('#1769B0'),
                'success' => Color::hex('#168653'),
                'warning' => Color::hex('#B45309'),
                'danger' => Color::hex('#C62828'),
            ])
            ->font('Inter')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17.5rem')
            ->maxContentWidth(Width::Full)
            ->spa(hasPrefetching: true)
            ->spaUrlExceptions(['/admin/locale/*'])
            ->strictAuthorization()
            ->navigationGroups([
                NavigationGroup::make(fn (): string => __('admin.nav.groups.operations')),
                NavigationGroup::make(fn (): string => __('admin.nav.groups.centres')),
                NavigationGroup::make(fn (): string => __('admin.nav.groups.catalogue')),
                NavigationGroup::make(fn (): string => __('admin.nav.groups.content')),
                NavigationGroup::make(fn (): string => __('admin.nav.groups.system')),
            ])
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): string => view('filament.components.locale-switcher-wrapper')->render(),
            )
            ->multiFactorAuthentication(
                providers: [
                    AppAuthentication::make()
                        ->brandName('G3 Control')
                        ->recoverable(),
                ],
                isRequired: fn (): bool => (bool) config('admin.mfa_required'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetAdminLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
