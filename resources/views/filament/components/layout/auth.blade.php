@props([
    'livewire' => null,
])

@php
    use App\Settings\CompanySettings;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;

    $livewire ??= null;
    $renderHookScopes = $livewire?->getRenderHookScopes();
    $company = app(CompanySettings::class);
    $locale = app()->getLocale();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="g3-login-screen">
        <div class="g3-login-screen__pattern" aria-hidden="true"></div>

        <div class="g3-login-locale">
            @include('filament.components.locale-switcher-wrapper')
        </div>

        <div class="g3-login-card">
            <header class="g3-login-card__header">
                <div class="g3-login-card__logo">
                    G3 <span>Control</span>
                </div>
                <div class="g3-login-card__line" aria-hidden="true"></div>
                <p class="g3-login-card__tagline">{{ $company->sloganFor($locale) }}</p>
            </header>

            <div class="g3-login-card__safety-line" aria-hidden="true"></div>

            {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

            <main id="fi-main-content" tabindex="-1" class="g3-login-card__body">
                {{ $slot }}
            </main>

            {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}

            <footer class="g3-login-card__footer">
                <span>{{ $company->agrementLabel() }}</span>
                <span class="g3-login-card__footer-sep" aria-hidden="true">·</span>
                <span>{{ __('admin.auth.footer_access') }}</span>
                <span class="g3-login-card__footer-sep" aria-hidden="true">·</span>
                <span>{{ __('admin.auth.footer_mfa') }}</span>
            </footer>
        </div>
    </div>
</x-filament-panels::layout.base>
