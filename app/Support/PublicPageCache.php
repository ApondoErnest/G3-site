<?php

namespace App\Support;

use App\Domain\Enums\Locale;
use Illuminate\Support\Facades\Cache;

final class PublicPageCache
{
    public static function forgetCentres(): void
    {
        foreach (Locale::cases() as $locale) {
            Cache::forget(CacheKeys::publicCentres($locale->value));
        }

        Cache::forget(CacheKeys::publicPhone());
        self::forgetLiveStatus();
    }

    public static function forgetTariffsForDate(string $date): void
    {
        foreach (Locale::cases() as $locale) {
            Cache::forget(CacheKeys::publicTariff($locale->value, $date));
        }
    }

    public static function forgetLiveStatus(): void
    {
        Cache::forget(CacheKeys::availabilityAll(Clock::nowDisplay()->format('Y-m-d-H-i')));
    }
}
