<?php

namespace App\Http\Controllers;

use App\Actions\Schedule\ResolveAllCentresAvailability;
use App\Domain\Enums\Locale;
use App\Models\Centre\Centre;
use App\Support\PublicNavigation;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        private ResolveAllCentresAvailability $resolveAllCentresAvailability,
    ) {}

    public function __invoke(string $page): View
    {
        $locale = app()->getLocale();
        $view = view()->exists("pages.{$page}") ? "pages.{$page}" : 'pages.shell';

        return view($view, [
            'page' => $page,
            'locale' => $locale,
            'pageTitle' => PublicNavigation::pageTitle($page),
            'homeHeroCentres' => $page === 'home'
                ? $this->homeHeroCentres(Locale::from($locale))
                : [],
        ]);
    }

    /**
     * @return list<array{name: string, status: string, isOpen: bool}>
     */
    private function homeHeroCentres(Locale $locale): array
    {
        $centres = Centre::query()
            ->active()
            ->with('weeklyHours')
            ->orderBy('sort_order')
            ->limit(2)
            ->get();

        if ($centres->isEmpty()) {
            return [
                [
                    'name' => __('public.centres.ecole_de_police'),
                    'status' => __('public.home.hero.live.open_until', ['time' => '20h00']),
                    'isOpen' => true,
                ],
                [
                    'name' => __('public.centres.nomayos'),
                    'status' => __('public.home.hero.live.open_until', ['time' => '19h00']),
                    'isOpen' => true,
                ],
            ];
        }

        $snapshots = ($this->resolveAllCentresAvailability)();

        return $centres
            ->map(function (Centre $centre) use ($locale, $snapshots): array {
                $snapshot = $snapshots[$centre->id] ?? null;
                $isOpen = $snapshot?->isOpenNow ?? false;
                $statusTime = $isOpen ? $snapshot?->nextCloseAt : $snapshot?->nextOpenAt;

                return [
                    'name' => $centre->translatedName($locale),
                    'status' => $this->homeHeroCentreStatus($isOpen, $statusTime),
                    'isOpen' => $isOpen,
                ];
            })
            ->all();
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
        return $time->format('H\hi');
    }
}
