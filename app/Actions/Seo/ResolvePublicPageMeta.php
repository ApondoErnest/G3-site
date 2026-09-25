<?php

namespace App\Actions\Seo;

use App\Actions\Content\ResolvePageSeo;
use App\Actions\Seo\Data\PublicPageMeta;
use App\Domain\Enums\CentreCode;
use App\Domain\Enums\ContentPage;
use App\Domain\Enums\Locale;
use App\Models\Centre\Centre;
use App\Settings\CompanySettings;
use App\Support\PublicNavigation;

final class ResolvePublicPageMeta
{
    public function __construct(
        private ResolvePageSeo $resolvePageSeo,
        private CompanySettings $company,
    ) {}

    public function __invoke(string $page, Locale $locale): PublicPageMeta
    {
        $contentPage = ContentPage::tryFrom($page);
        $pageSeo = $contentPage === null ? null : ($this->resolvePageSeo)($contentPage);
        $centre = $this->centreForPage($page);

        $storedTitle = $this->preferredLocalized(
            $centre?->seo_title,
            $pageSeo?->seoTitle,
            $locale,
        );
        $storedDescription = $this->preferredLocalized(
            $centre?->seo_description,
            $pageSeo?->seoDescription,
            $locale,
        );

        $alternates = [];

        foreach (config('locale.supported', []) as $code) {
            $alternates[$code] = PublicNavigation::pageUrl($page, $code);
        }

        $defaultLocale = (string) config('locale.default', Locale::Fr->value);

        return new PublicPageMeta(
            title: $storedTitle !== ''
                ? $storedTitle
                : PublicNavigation::pageTitle($page).' · '.$this->company->display_name,
            description: $storedDescription !== ''
                ? $storedDescription
                : $this->company->defaultSeoDescriptionFor($locale->value),
            canonical: PublicNavigation::pageUrl($page, $locale->value),
            alternates: $alternates,
            defaultLocaleUrl: $alternates[$defaultLocale] ?? PublicNavigation::pageUrl($page, $defaultLocale),
        );
    }

    private function centreForPage(string $page): ?Centre
    {
        $code = match ($page) {
            ContentPage::CentreEcoleDePolice->value => CentreCode::EcoleDePolice,
            ContentPage::CentreNomayos->value => CentreCode::Nomayos,
            default => null,
        };

        if ($code === null) {
            return null;
        }

        return Centre::query()->where('code', $code->value)->first();
    }

    /**
     * Centre SEO overrides page SEO when the centre value is present.
     *
     * @param  array<string, string>|null  $centreValues
     * @param  array<string, string>|null  $pageValues
     */
    private function preferredLocalized(?array $centreValues, ?array $pageValues, Locale $locale): string
    {
        $centreValue = $this->localized($centreValues, $locale);

        if ($centreValue !== '') {
            return $centreValue;
        }

        return $this->localized($pageValues, $locale);
    }

    /**
     * @param  array<string, string>|null  $values
     */
    private function localized(?array $values, Locale $locale): string
    {
        if ($values === null) {
            return '';
        }

        return trim((string) ($values[$locale->value] ?? $values[Locale::Fr->value] ?? ''));
    }
}
