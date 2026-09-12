@php
    use App\Support\PublicNavigation;
@endphp

@props([
    'company',
    'currentPage',
    'locale',
    'phoneDisplay' => null,
    'phoneE164' => null,
])

<div {{ $attributes->merge(['class' => 'g3-utility-strip']) }}>
    <div class="g3-utility-strip__content">
        <div class="g3-utility-strip__items">
            <div class="g3-utility-strip__item">
                <span class="g3-utility-strip__copy">
                    <span class="g3-utility-strip__title">{{ __('public.utility.location') }}</span>
                </span>
            </div>

            <div class="g3-utility-strip__item">
                <span class="g3-utility-strip__copy">
                    <span class="g3-utility-strip__title">{{ __('public.utility.centres_title') }}</span>
                    <span class="g3-utility-strip__detail">{{ __('public.utility.centres_detail') }}</span>
                </span>
            </div>

            <div class="g3-utility-strip__item">
                <span class="g3-utility-strip__copy">
                    <span class="g3-utility-strip__title">{{ __('public.utility.open_title') }}</span>
                    <span class="g3-utility-strip__detail">{{ __('public.utility.open_detail') }}</span>
                </span>
            </div>

            <div class="g3-utility-strip__item">
                <span class="g3-utility-strip__copy">
                    <span class="g3-utility-strip__title">{{ __('public.utility.approval_title', ['number' => $company->agrementLabel()]) }}</span>
                    <span class="g3-utility-strip__detail">{{ __('public.utility.approval_detail', ['year' => $company->agrement_year]) }}</span>
                </span>
            </div>

            @if ($phoneDisplay && $phoneE164)
                <a href="tel:{{ $phoneE164 }}" class="g3-utility-strip__item g3-utility-strip__item--link">
                    <span class="g3-utility-strip__copy">
                        <span class="g3-utility-strip__title">{{ $phoneDisplay }}</span>
                    </span>
                </a>
            @endif
        </div>

        <nav class="g3-utility-strip__locale" aria-label="{{ __('public.locale.switch') }}">
            @foreach (config('locale.supported', ['fr', 'en']) as $code)
                @php
                    $isActive = $code === $locale;
                @endphp

                <a
                    href="{{ PublicNavigation::switchLocaleUrl($code, $currentPage) }}"
                    hreflang="{{ $code }}"
                    lang="{{ $code }}"
                    @class([
                        'g3-utility-strip__locale-link',
                        'g3-utility-strip__locale-link--active' => $isActive,
                    ])
                    @if ($isActive) aria-current="page" @endif
                >
                    {{ __('public.locale.'.$code) }}
                </a>

                @if (! $loop->last)
                    <span class="g3-utility-strip__locale-divider" aria-hidden="true"></span>
                @endif
            @endforeach
        </nav>
    </div>
</div>
