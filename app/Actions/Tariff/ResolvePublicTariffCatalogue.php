<?php

namespace App\Actions\Tariff;

use App\Actions\Tariff\Data\PublicTariffCatalogue;
use App\Actions\Tariff\Data\PublicTariffLine;
use App\Domain\Enums\Locale;
use App\Domain\Enums\TariffVersionStatus;
use App\Models\Tariff\TariffItem;
use App\Models\Tariff\TariffVersion;
use App\Support\CacheKeys;
use App\Support\Clock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class ResolvePublicTariffCatalogue
{
    public function __invoke(Locale $locale): PublicTariffCatalogue
    {
        $day = Clock::nowDisplay()->toDateString();
        $key = CacheKeys::publicTariff($locale->value, $day);
        /** @var array<string, mixed>|null $cached */
        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $this->catalogueFromCache($cached);
        }

        $catalogue = $this->resolve($locale);

        if ($catalogue->versionLabel !== null && ! in_array($catalogue->versionLabel, ['Tarifs publics', 'Public fees'], true)) {
            Cache::put($key, [
                'versionLabel' => $catalogue->versionLabel,
                'effectiveLine' => $catalogue->effectiveLine,
                'lines' => array_map(
                    fn (PublicTariffLine $line): array => get_object_vars($line),
                    $catalogue->lines,
                ),
            ], CacheKeys::tariffTtlSeconds());
        }

        return $catalogue;
    }

    /**
     * @param  array<string, mixed>  $cached
     */
    private function catalogueFromCache(array $cached): PublicTariffCatalogue
    {
        /** @var list<array<string, mixed>> $lines */
        $lines = $cached['lines'] ?? [];

        return new PublicTariffCatalogue(
            versionLabel: $cached['versionLabel'] ?? null,
            effectiveLine: $cached['effectiveLine'] ?? null,
            lines: array_map(
                fn (array $line): PublicTariffLine => new PublicTariffLine(...$line),
                $lines,
            ),
        );
    }

    private function resolve(Locale $locale): PublicTariffCatalogue
    {
        $version = $this->effectiveVersion();

        if ($version === null) {
            return new PublicTariffCatalogue(
                versionLabel: null,
                effectiveLine: null,
                lines: [],
            );
        }

        $version->load([
            'items.centres',
            'items.service',
            'items.vehicleCategory',
        ]);

        return new PublicTariffCatalogue(
            versionLabel: $version->label,
            effectiveLine: $this->effectiveLine($version, $locale),
            lines: $version->items
                ->map(fn (TariffItem $item): PublicTariffLine => $this->toLine($item, $locale))
                ->values()
                ->all(),
        );
    }

    private function effectiveVersion(): ?TariffVersion
    {
        $day = Clock::nowDisplay()->toDateString();

        return TariffVersion::query()
            ->where('status', TariffVersionStatus::Published)
            ->where('effective_from', '<=', $day)
            ->where(function ($query) use ($day): void {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', $day);
            })
            ->orderByDesc('effective_from')
            ->first();
    }

    private function toLine(TariffItem $item, Locale $locale): PublicTariffLine
    {
        $category = $item->vehicleCategory;
        $categoryCode = $category?->code ?? (string) $item->vehicle_category_id;
        $categoryLabel = $this->translated($category?->label, $locale, $categoryCode);
        $categoryExamples = $this->translated($category?->examples, $locale, $categoryLabel);
        $categoryDescription = $this->translated($category?->description, $locale, $categoryExamples);
        $validity = $this->translated(
            $item->validity_notes,
            $locale,
            $locale === Locale::Fr ? 'Selon la catégorie officielle.' : 'According to the official category.',
        );

        return new PublicTariffLine(
            id: $item->id,
            profileId: Str::slug($categoryCode.'-'.$item->id),
            categoryCode: $categoryCode,
            selector: $categoryLabel,
            title: ($locale === Locale::Fr ? 'Catégorie ' : 'Category ').$categoryCode,
            label: $categoryLabel,
            examples: $categoryExamples,
            plain: $categoryDescription,
            amount: number_format((int) $item->amount_xaf, 0, ',', ' ').' FCFA',
            validity: $validity,
            image: $this->imageFor($categoryCode),
            centreNames: $item->centres
                ->map(fn ($centre): string => $centre->translatedName($locale))
                ->values()
                ->all(),
        );
    }

    /**
     * @param  array<string, string>|null  $value
     */
    private function translated(?array $value, Locale $locale, string $fallback): string
    {
        if (! is_array($value)) {
            return $fallback;
        }

        foreach ([$locale->value, Locale::Fr->value, Locale::En->value] as $key) {
            $candidate = trim((string) ($value[$key] ?? ''));

            if ($candidate !== '') {
                return $candidate;
            }
        }

        return $fallback;
    }

    private function effectiveLine(TariffVersion $version, Locale $locale): string
    {
        $date = $version->effective_from->format('d/m/Y');

        return $locale === Locale::Fr
            ? "En vigueur depuis le {$date}"
            : "Effective since {$date}";
    }

    private function imageFor(string $categoryCode): string
    {
        return match (Str::lower($categoryCode)) {
            'a' => 'taxi.png',
            'b' => 'light-vehicle.png',
            'b1' => 'pickup.png',
            'c', 'c-bus' => 'large bus.png',
            'c-mini', 'c < 3,5t', 'c < 3.5t' => 'mini bus.png',
            'd', 'd-heavy' => 'heavy vehicle.png',
            default => 'other-machinery.png',
        };
    }
}
