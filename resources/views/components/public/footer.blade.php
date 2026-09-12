@php
    use App\Support\PublicNavigation;

    $footerNavPages = [
        'home',
        'about',
        'centres',
        'services',
        'fees',
        'contact',
    ];

    $socialLinks = [
        'linkedin' => $company->social_links['linkedin'] ?? '#',
        'facebook' => $company->social_links['facebook'] ?? '#',
        'youtube' => $company->social_links['youtube'] ?? '#',
    ];
@endphp

@props([
    'company',
    'currentPage',
    'locale',
    'phoneDisplay' => null,
    'phoneE164' => null,
])

<footer class="g3-site-footer">
    <div class="g3-site-footer__shell">
        <img
            src="{{ asset('images/reusable/site-logo.png') }}"
            alt=""
            class="g3-site-footer__watermark"
            width="2172"
            height="724"
            aria-hidden="true"
        >

        <div class="g3-site-footer__main">
            <section class="g3-site-footer__brand" aria-labelledby="g3-footer-brand">
                <img
                    src="{{ asset('images/reusable/site-logo.png') }}"
                    alt="{{ $company->display_name ?? __('public.brand') }}"
                    class="g3-site-footer__logo"
                    width="2172"
                    height="724"
                >
                <p id="g3-footer-brand" class="g3-site-footer__tagline">
                    {{ __('public.footer.tagline') }}
                </p>
            </section>

            <nav class="g3-site-footer__nav" aria-label="{{ $locale === 'fr' ? 'Navigation pied de page' : 'Footer navigation' }}">
                @foreach ($footerNavPages as $page)
                    <a href="{{ PublicNavigation::pageUrl($page, $locale) }}">
                        {{ PublicNavigation::navLabel($page) }}
                    </a>
                @endforeach
            </nav>

            <section class="g3-site-footer__contact" aria-label="{{ __('public.footer.contact_heading') }}">
                <div class="g3-site-footer__centres" aria-label="{{ __('public.footer.centres_heading') }}">
                    <a href="{{ PublicNavigation::pageUrl('centre_ecole_de_police', $locale) }}" class="g3-site-footer__chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.4 7 9.8 4.1-1.4 7-5.3 7-9.8V6l-7-3Z"/>
                        </svg>
                        <span>{{ __('public.centres.ecole_de_police') }}</span>
                    </a>
                    <a href="{{ PublicNavigation::pageUrl('centre_nomayos', $locale) }}" class="g3-site-footer__chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 8.5 9-4 9 4-9 4-9-4Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 11v4.5c2.6 2 7.4 2 10 0V11"/>
                        </svg>
                        <span>{{ __('public.centres.nomayos') }}</span>
                    </a>
                </div>

                <div class="g3-site-footer__contact-row">
                    @if ($phoneDisplay && $phoneE164)
                        <a href="tel:{{ $phoneE164 }}" class="g3-site-footer__contact-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v2.25A2 2 0 0 1 19.82 21 19.8 19.8 0 0 1 3 4.18 2 2 0 0 1 4.83 2H7.1a2 2 0 0 1 2 1.72c.12.9.32 1.77.6 2.61a2 2 0 0 1-.45 2.05L8.3 9.33a16 16 0 0 0 6.37 6.37l.95-.95a2 2 0 0 1 2.05-.45c.84.28 1.71.48 2.61.6A2 2 0 0 1 22 16.92Z"/>
                            </svg>
                            <span>{{ $phoneDisplay }}</span>
                        </a>
                    @endif

                    <a href="mailto:{{ $company->email }}" class="g3-site-footer__contact-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
                        </svg>
                        <span>{{ $company->email }}</span>
                    </a>
                </div>

                <a href="{{ PublicNavigation::pageUrl('appointment', $locale) }}" class="g3-site-footer__cta">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                        <path stroke-linecap="round" d="M8 13h2M8 17h2M14 13h2M14 17h2"/>
                    </svg>
                    <span>{{ __('public.cta.appointment') }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/>
                    </svg>
                </a>
            </section>

            <a href="#page-top" class="g3-site-footer__to-top">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 14 6-6 6 6"/>
                </svg>
                <span>{{ __('public.footer.back_to_top') }}</span>
            </a>
        </div>

        <div class="g3-site-footer__bottom">
            <p class="g3-site-footer__legal">
                © {{ date('Y') }} G3 CONTROL <span aria-hidden="true">—</span> {{ __('public.footer.legal_business') }}
            </p>

            <div class="g3-site-footer__meta">
                <span>{{ __('public.footer.approval_since', ['number' => $company->agrementLabel(), 'year' => $company->agrement_year]) }}</span>
                <span aria-hidden="true">|</span>
                <nav class="g3-site-footer__locale" aria-label="{{ __('public.locale.switch') }}">
                    @foreach (config('locale.supported', ['fr', 'en']) as $code)
                        @php
                            $isActive = $code === $locale;
                        @endphp

                        <a
                            href="{{ PublicNavigation::switchLocaleUrl($code, $currentPage) }}"
                            hreflang="{{ $code }}"
                            lang="{{ $code }}"
                            @class(['is-active' => $isActive])
                            @if ($isActive) aria-current="page" @endif
                        >
                            {{ __('public.locale.'.$code) }}
                        </a>
                        @if (! $loop->last)
                            <span aria-hidden="true">/</span>
                        @endif
                    @endforeach
                </nav>
            </div>

            <div class="g3-site-footer__social">
                <a href="{{ $socialLinks['linkedin'] }}" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M6.9 8.8H3.6v11h3.3v-11Zm.2-3.4A1.9 1.9 0 1 0 3.3 5.4a1.9 1.9 0 0 0 3.8 0Zm13.3 8.1c0-3-1.6-4.9-4.1-4.9-1.9 0-2.8 1-3.2 1.8V8.8H9.9v11h3.3v-5.7c0-1.5.7-2.6 2.1-2.6s1.8 1 1.8 2.6v5.7h3.3v-6.3Z"/>
                    </svg>
                </a>
                <a href="{{ $socialLinks['facebook'] }}" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M14.1 8.3V6.7c0-.8.5-1 1.1-1h1.6V2.9c-.8-.1-1.6-.2-2.4-.2-2.4 0-4 1.5-4 4.1v1.5H7.8v3.2h2.6v8.1h3.7v-8.1h2.7l.4-3.2h-3.1Z"/>
                    </svg>
                </a>
                <a href="{{ $socialLinks['youtube'] }}" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M21.6 7.2a3 3 0 0 0-2.1-2.1C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.5.5a3 3 0 0 0-2.1 2.1C2 9 2 12 2 12s0 3 .4 4.8a3 3 0 0 0 2.1 2.1c1.8.5 7.5.5 7.5.5s5.7 0 7.5-.5a3 3 0 0 0 2.1-2.1C22 15 22 12 22 12s0-3-.4-4.8ZM10 15.4V8.6l5.8 3.4-5.8 3.4Z"/>
                    </svg>
                </a>
            </div>

            <p class="g3-site-footer__signature">{{ __('public.footer.signature') }}</p>
        </div>
    </div>
</footer>
