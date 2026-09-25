<?php

namespace App\Http\Controllers;

use App\Actions\Catalogue\ResolvePublishedServices;
use App\Actions\Catalogue\ResolvePublishedVehicleCategories;
use App\Actions\Centre\ResolvePublicCentres;
use App\Actions\Company\ResolvePublicCompanyProfile;
use App\Actions\Schedule\ResolveAllCentresAvailability;
use App\Actions\Seo\ResolvePublicPageMeta;
use App\Actions\Tariff\ResolvePublicTariffCatalogue;
use App\Domain\Enums\Locale;
use App\Models\Centre\Centre;
use App\Support\DisplayTime;
use App\Support\PublicNavigation;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        private ResolveAllCentresAvailability $resolveAllCentresAvailability,
        private ResolvePublishedServices $resolvePublishedServices,
        private ResolvePublishedVehicleCategories $resolvePublishedVehicleCategories,
        private ResolvePublicCentres $resolvePublicCentres,
        private ResolvePublicCompanyProfile $resolvePublicCompanyProfile,
        private ResolvePublicPageMeta $resolvePublicPageMeta,
        private ResolvePublicTariffCatalogue $resolvePublicTariffCatalogue,
    ) {}

    public function __invoke(string $page): View
    {
        $locale = app()->getLocale();
        $publicLocale = Locale::fromString($locale);
        $view = view()->exists("pages.{$page}") ? "pages.{$page}" : 'pages.shell';
        $liveStatus = $this->needsLiveStatus($page)
            ? $this->liveStatus($publicLocale)
            : [];

        return view($view, [
            'page' => $page,
            'locale' => $locale,
            'pageTitle' => PublicNavigation::pageTitle($page),
            'pageMeta' => ($this->resolvePublicPageMeta)($page, $publicLocale),
            'publicCentres' => $this->needsCentres($page)
                ? ($this->resolvePublicCentres)($publicLocale)
                : [],
            'publicCompany' => $page === 'contact'
                ? ($this->resolvePublicCompanyProfile)($publicLocale)
                : null,
            'publishedServices' => in_array($page, ['services', 'appointment'], true)
                ? ($this->resolvePublishedServices)()
                : [],
            'publishedVehicleCategories' => $page === 'appointment'
                ? ($this->resolvePublishedVehicleCategories)()
                : [],
            'publicTariffCatalogue' => $this->needsTariff($page)
                ? ($this->resolvePublicTariffCatalogue)($publicLocale)
                : null,
            'homeHeroCentres' => $page === 'home' ? array_values($liveStatus) : [],
            'publicLiveStatus' => $liveStatus,
            'appointmentHandoff' => $page === 'appointment' ? [
                'centre' => request()->string('centre')->toString(),
                'category' => request()->string('category')->toString(),
            ] : null,
        ]);
    }

    private function needsLiveStatus(string $page): bool
    {
        return in_array($page, [
            'home',
            'centres',
            'centre_ecole_de_police',
            'centre_nomayos',
            'fees',
            'appointment',
            'contact',
        ], true);
    }

    private function needsCentres(string $page): bool
    {
        return in_array($page, [
            'home',
            'centres',
            'centre_ecole_de_police',
            'centre_nomayos',
            'services',
            'appointment',
            'contact',
        ], true);
    }

    private function needsTariff(string $page): bool
    {
        return in_array($page, ['fees', 'appointment'], true);
    }

    /**
     * @return array<string, array{name: string, status: string, isOpen: bool}>
     */
    private function liveStatus(Locale $locale): array
    {
        $centres = Centre::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $snapshots = ($this->resolveAllCentresAvailability)();
        $statuses = [];

        foreach ($centres as $centre) {
            $snapshot = $snapshots[$centre->id] ?? null;
            $isOpen = $snapshot?->isOpenNow ?? false;
            $reason = $snapshot?->reason[$locale->value] ?? $snapshot?->reason['fr'] ?? null;
            $statusTime = $isOpen ? $snapshot?->nextCloseAt : $snapshot?->nextOpenAt;

            $statuses[(string) $centre->code] = [
                'name' => $centre->translatedName($locale),
                'status' => filled($reason) && ! $isOpen
                    ? (string) $reason
                    : $this->homeHeroCentreStatus($isOpen, $statusTime),
                'isOpen' => $isOpen,
            ];
        }

        return $statuses;
    }

    private function homeHeroCentreStatus(bool $isOpen, ?CarbonImmutable $statusTime): string
    {
        if ($isOpen && $statusTime !== null) {
            return __('public.home.hero.live.open_until', [
                'time' => $this->formatHeroTime($statusTime),
            ]);
        }

        if (! $isOpen && $statusTime !== null) {
            return __('public.home.hero.live.opens_at', [
                'time' => $this->formatHeroTime($statusTime),
            ]);
        }

        return __('public.home.hero.live.closed');
    }

    private function formatHeroTime(CarbonImmutable $time): string
    {
        return DisplayTime::format($time);
    }
}
