<?php

namespace App\Support;

final class PublicNavigation
{
    /**
     * Primary header navigation page keys (internal IDs from config/locale.php).
     *
     * @return list<string>
     */
    public static function primaryPages(): array
    {
        return [
            'home',
            'about',
            'centres',
            'services',
            'technical_inspection',
            'fees',
            'road_safety',
            'contact',
        ];
    }

    /**
     * Footer quick links (high-intent handoffs).
     *
     * @return list<string>
     */
    public static function footerUtilityPages(): array
    {
        return [
            'appointment',
            'fees',
            'contact',
        ];
    }

    /**
     * Both centre detail pages for footer listing.
     *
     * @return list<string>
     */
    public static function centrePages(): array
    {
        return [
            'centre_ecole_de_police',
            'centre_nomayos',
        ];
    }

    public static function pageUrl(string $page, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route("{$locale}.{$page}");
    }

    public static function switchLocaleUrl(string $targetLocale, string $currentPage): string
    {
        if (! array_key_exists($currentPage, config('locale.pages', []))) {
            return self::pageUrl('home', $targetLocale);
        }

        return self::pageUrl($currentPage, $targetLocale);
    }

    public static function isNavActive(string $navPage, string $currentPage): bool
    {
        if ($navPage === 'centres') {
            return $currentPage === 'centres'
                || str_starts_with($currentPage, 'centre_');
        }

        return $navPage === $currentPage;
    }

    public static function navLabel(string $page): string
    {
        return __("public.nav.{$page}");
    }

    public static function pageTitle(string $page): string
    {
        $navKey = "public.nav.{$page}";

        if (__($navKey) !== $navKey) {
            return __($navKey);
        }

        $centreSlug = match ($page) {
            'centre_ecole_de_police' => 'ecole_de_police',
            'centre_nomayos' => 'nomayos',
            default => null,
        };

        if ($centreSlug !== null) {
            return __('public.centres.'.$centreSlug);
        }

        $pageKey = "public.pages.{$page}";

        if (__($pageKey) !== $pageKey) {
            return __($pageKey);
        }

        return str_replace('_', ' ', ucfirst($page));
    }
}
