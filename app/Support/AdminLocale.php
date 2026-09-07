<?php

namespace App\Support;

use App\Domain\Enums\Locale;

final class AdminLocale
{
    public const SESSION_KEY = 'admin_locale';

    /**
     * @return list<string>
     */
    public static function supported(): array
    {
        return config('admin.locales', config('locale.supported', ['fr', 'en']));
    }

    public static function default(): string
    {
        return (string) config('admin.default_locale', config('locale.default', 'fr'));
    }

    public static function current(): Locale
    {
        return Locale::fromString(app()->getLocale());
    }

    public static function resolveFromSession(): string
    {
        $locale = session(self::SESSION_KEY);

        if (is_string($locale) && in_array($locale, self::supported(), true)) {
            return $locale;
        }

        return self::default();
    }

    public static function set(string $locale): void
    {
        if (! in_array($locale, self::supported(), true)) {
            $locale = self::default();
        }

        session([self::SESSION_KEY => $locale]);
        app()->setLocale($locale);
    }

    public static function isActive(string $locale): bool
    {
        return app()->getLocale() === $locale;
    }
}
