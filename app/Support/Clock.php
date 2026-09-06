<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class Clock
{
    public static function storageTimezone(): string
    {
        return (string) config('time.storage_timezone');
    }

    public static function displayTimezone(): string
    {
        return (string) config('time.display_timezone');
    }

    public static function nowUtc(): CarbonImmutable
    {
        return CarbonImmutable::now(self::storageTimezone());
    }

    public static function nowDisplay(): CarbonImmutable
    {
        return self::nowUtc()->timezone(self::displayTimezone());
    }

    public static function toDisplay(CarbonInterface $instant): CarbonImmutable
    {
        return CarbonImmutable::instance($instant)->timezone(self::displayTimezone());
    }

    public static function freeze(CarbonImmutable $instant): void
    {
        CarbonImmutable::setTestNow($instant);
    }

    public static function unfreeze(): void
    {
        CarbonImmutable::setTestNow();
    }
}
