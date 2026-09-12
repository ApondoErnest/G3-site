@php
    use App\Support\PublicNavigation;
@endphp

@props([
    'company',
    'currentPage',
    'locale',
])

<header
    data-g3-header
    class="g3-public-header"
>
    <div class="g3-public-header__inner">
        <a
            href="{{ PublicNavigation::pageUrl('home', $locale) }}"
            class="g3-public-header__logo"
        >
            <img
                src="{{ asset('images/reusable/site-logo.png') }}"
                alt="{{ $company->display_name ?? __('public.brand') }}"
                class="g3-public-header__logo-img"
                width="2172"
                height="724"
            >
        </a>

        <div class="g3-public-header__desktop">
            <nav
                class="g3-public-header__nav"
                aria-label="{{ $locale === 'fr' ? 'Navigation principale' : 'Main navigation' }}"
            >
                @foreach (PublicNavigation::primaryPages() as $navPage)
                    <a
                        href="{{ PublicNavigation::pageUrl($navPage, $locale) }}"
                        @class([
                            'g3-nav-link whitespace-nowrap',
                            'g3-nav-link--active' => PublicNavigation::isNavActive($navPage, $currentPage),
                        ])
                        @if (PublicNavigation::isNavActive($navPage, $currentPage)) aria-current="page" @endif
                    >
                        {{ PublicNavigation::navLabel($navPage) }}
                    </a>
                @endforeach
            </nav>

            <div class="g3-public-header__actions">
                <a
                    href="{{ PublicNavigation::pageUrl('appointment', $locale) }}"
                    class="g3-header-appointment"
                >
                    <svg class="g3-header-appointment__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                        <path stroke-linecap="round" d="M8 13h2M8 17h2M14 13h2M14 17h2"/>
                    </svg>
                    <span>{{ __('public.cta.appointment') }}</span>
                    <svg class="g3-header-appointment__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="g3-public-header__mobile-actions">
            <a
                href="{{ PublicNavigation::pageUrl('appointment', $locale) }}"
                class="g3-public-header__mobile-booking"
            >
                <span class="sr-only">{{ __('public.cta.appointment') }}</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                    <path stroke-linecap="round" d="M8 13h2M8 17h2M14 13h2M14 17h2"/>
                </svg>
            </a>

            <button
                type="button"
                data-mobile-nav-toggle
                class="g3-public-header__menu"
                aria-controls="g3-mobile-nav"
                aria-expanded="false"
            >
                <span class="sr-only">{{ __('public.utility.menu') }}</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </div>

        <span class="g3-public-header__accent g3-public-header__accent--orange" aria-hidden="true"></span>
        <span class="g3-public-header__accent g3-public-header__accent--blue" aria-hidden="true"></span>
    </div>

    <div
        data-mobile-nav-backdrop
        data-open="false"
        class="fixed inset-0 z-40 bg-g3-blue-deep/40 opacity-0 transition-opacity data-[open=true]:opacity-100 xl:hidden pointer-events-none data-[open=true]:pointer-events-auto"
        aria-hidden="true"
    ></div>

    <div
        id="g3-mobile-nav"
        data-mobile-nav-panel
        data-open="false"
        class="fixed inset-y-0 right-0 z-50 flex w-64 translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 data-[open=true]:translate-x-0 xl:hidden"
    >
        <div class="g3-safety-band g3-safety-band--micro" aria-hidden="true"></div>

        <div class="flex items-center justify-between border-b border-g3-border px-4 py-4">
            <img
                src="{{ asset('images/reusable/site-logo.png') }}"
                alt="{{ $company->display_name ?? __('public.brand') }}"
                class="h-10 w-auto"
                width="2172"
                height="724"
            >
            <button
                type="button"
                data-mobile-nav-toggle
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-g3-border text-g3-blue-deep"
            >
                <span class="sr-only">{{ __('public.utility.menu_close') }}</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-6" aria-label="{{ $locale === 'fr' ? 'Navigation mobile' : 'Mobile navigation' }}">
            <ul class="space-y-1">
                @foreach (PublicNavigation::primaryPages() as $navPage)
                    <li>
                        <a
                            href="{{ PublicNavigation::pageUrl($navPage, $locale) }}"
                            @class([
                                'block rounded-lg px-3 py-3 text-base font-bold transition',
                                'bg-g3-orange-tint text-g3-orange' => PublicNavigation::isNavActive($navPage, $currentPage),
                                'text-g3-blue-deep hover:bg-g3-wall' => ! PublicNavigation::isNavActive($navPage, $currentPage),
                            ])
                        >
                            {{ PublicNavigation::navLabel($navPage) }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-8 space-y-4 border-t border-g3-border pt-6">
                <a href="{{ PublicNavigation::pageUrl('appointment', $locale) }}" class="g3-btn-primary w-full">
                    {{ __('public.cta.appointment') }}
                </a>
            </div>
        </nav>
    </div>
</header>
