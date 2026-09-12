@php
    use App\Support\PublicNavigation;

    $pageTitle = PublicNavigation::pageTitle($page);
    $metaTitle = $pageTitle.' · '.$company->display_name;
    $metaDescription = $company->defaultSeoDescriptionFor($locale);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription }}">

    <title>{{ $metaTitle }}</title>

    <link rel="icon" href="{{ asset('images/reusable/favicon.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body id="page-top" class="flex min-h-screen flex-col bg-g3-white">
    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-g3-royal focus:px-4 focus:py-2 focus:text-white"
    >
        {{ $locale === 'fr' ? 'Aller au contenu principal' : 'Skip to main content' }}
    </a>

    <x-public.utility-strip
        :company="$company"
        :current-page="$page"
        :locale="$locale"
        :phone-display="$publicPhoneDisplay"
        :phone-e164="$publicPhone"
    />

    <x-public.header
        :company="$company"
        :current-page="$page"
        :locale="$locale"
    />

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    <x-public.footer
        :company="$company"
        :current-page="$page"
        :locale="$locale"
        :phone-display="$publicPhoneDisplay"
        :phone-e164="$publicPhone"
    />
</body>
</html>
