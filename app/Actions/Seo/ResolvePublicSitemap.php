<?php

namespace App\Actions\Seo;

use App\Actions\Seo\Data\PublicSitemapEntry;
use App\Domain\Enums\CentreCode;
use App\Domain\Enums\ContentPage;
use App\Models\Centre\Centre;
use App\Support\PublicNavigation;

final class ResolvePublicSitemap
{
    /**
     * @return list<PublicSitemapEntry>
     */
    public function __invoke(): array
    {
        $activeCentreCodes = Centre::query()
            ->active()
            ->pluck('code')
            ->map(fn (mixed $code): string => $code instanceof CentreCode ? $code->value : (string) $code)
            ->all();

        $entries = [];

        foreach (array_keys(config('locale.pages', [])) as $page) {
            if (! $this->isPublished((string) $page, $activeCentreCodes)) {
                continue;
            }

            foreach (config('locale.supported', []) as $locale) {
                $entries[] = new PublicSitemapEntry(
                    location: PublicNavigation::pageUrl((string) $page, (string) $locale),
                );
            }
        }

        return $entries;
    }

    /**
     * @param  list<string>  $activeCentreCodes
     */
    private function isPublished(string $page, array $activeCentreCodes): bool
    {
        $code = match ($page) {
            ContentPage::CentreEcoleDePolice->value => CentreCode::EcoleDePolice->value,
            ContentPage::CentreNomayos->value => CentreCode::Nomayos->value,
            default => null,
        };

        if ($code === null) {
            return true;
        }

        return in_array($code, $activeCentreCodes, true);
    }
}
