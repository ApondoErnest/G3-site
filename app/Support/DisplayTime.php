<?php

namespace App\Support;

use App\Domain\Enums\Locale;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class DisplayTime
{
    public static function format(CarbonInterface|string $time, ?Locale $locale = null): string
    {
        $locale ??= Locale::fromString(app()->getLocale());
        $instant = $time instanceof CarbonInterface
            ? CarbonImmutable::instance($time)->timezone(Clock::displayTimezone())
            : self::fromClock($time);

        return $locale === Locale::En
            ? $instant->format('g:i A')
            : $instant->format('H\hi');
    }

    private static function fromClock(string $time): CarbonImmutable
    {
        $parts = explode(':', $time);

        return CarbonImmutable::parse(
            sprintf('%02d:%02d:00', (int) $parts[0], (int) ($parts[1] ?? 0)),
            Clock::displayTimezone(),
        );
    }
}
