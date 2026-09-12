@php
    use App\Support\PublicNavigation;

    $supported = config('locale.supported', ['fr', 'en']);
@endphp

@props([
    'currentPage',
    'locale',
    'variant' => 'pill',
])

<nav
    aria-label="{{ __('public.locale.switch') }}"
    {{ $attributes->class([
        'g3-locale-pill' => $variant === 'pill',
        'flex items-center gap-2' => $variant === 'inline',
    ]) }}
>
    @foreach ($supported as $code)
        @php
            $isActive = $code === $locale;
            $href = PublicNavigation::switchLocaleUrl($code, $currentPage);
        @endphp

        <a
            href="{{ $href }}"
            hreflang="{{ $code }}"
            lang="{{ $code }}"
            @class([
                'g3-locale-pill__link' => $variant === 'pill',
                'g3-locale-pill__link--active' => $variant === 'pill' && $isActive,
                'rounded px-2 py-1 text-xs font-semibold uppercase tracking-wide transition' => $variant === 'inline',
                'bg-g3-royal text-white' => $variant === 'inline' && $isActive,
                'text-g3-muted hover:text-g3-royal' => $variant === 'inline' && ! $isActive,
            ])
            @if ($isActive) aria-current="page" @endif
        >
            {{ __('public.locale.'.$code) }}
        </a>

        @if ($variant === 'inline' && ! $loop->last)
            <span class="text-g3-border" aria-hidden="true">|</span>
        @endif
    @endforeach
</nav>
