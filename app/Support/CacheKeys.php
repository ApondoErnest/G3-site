<?php

namespace App\Support;

final class CacheKeys
{
    private const CATALOGUE_TTL_SECONDS = 3600;

    private const TARIFF_TTL_SECONDS = 3600;

    private const CONTENT_TTL_SECONDS = 3600;

    public static function scheduleCentre(int $centreId): string
    {
        return "schedule:centre:{$centreId}";
    }

    public static function alertsActive(): string
    {
        return 'alerts:active';
    }

    public static function catalogueServices(): string
    {
        return 'catalogue:services:published:v2';
    }

    public static function catalogueCategories(): string
    {
        return 'catalogue:categories:published';
    }

    public static function catalogueTtlSeconds(): int
    {
        return self::CATALOGUE_TTL_SECONDS;
    }

    public static function tariffEffective(string $date): string
    {
        return "tariff:effective:{$date}";
    }

    public static function tariffMatrix(int $versionId): string
    {
        return "tariff:matrix:{$versionId}";
    }

    public static function tariffTtlSeconds(): int
    {
        return self::TARIFF_TTL_SECONDS;
    }

    public static function contentBlock(string $key): string
    {
        return "content:block:{$key}";
    }

    public static function contentBlocksForPage(string $page): string
    {
        return "content:page:{$page}:published";
    }

    public static function faqPublished(?string $categoryCode = null): string
    {
        return $categoryCode === null
            ? 'content:faq:published'
            : "content:faq:published:{$categoryCode}";
    }

    public static function teamPublic(): string
    {
        return 'content:team:public';
    }

    public static function roadSafetyPublished(): string
    {
        return 'content:road_safety:published';
    }

    public static function equipmentForCentre(int $centreId): string
    {
        return "content:equipment:centre:{$centreId}";
    }

    public static function pageSeo(string $page): string
    {
        return "content:seo:{$page}";
    }

    public static function publicCentres(string $locale): string
    {
        return "public:centres:v3:{$locale}";
    }

    public static function publicPhone(): string
    {
        return 'public:phone:primary';
    }

    public static function publicTariff(string $locale, string $date): string
    {
        return "public:tariff:{$locale}:{$date}";
    }

    public static function availabilityAll(string $minute): string
    {
        return "availability:all:v2:{$minute}";
    }

    public static function contentTtlSeconds(): int
    {
        return self::CONTENT_TTL_SECONDS;
    }
}
