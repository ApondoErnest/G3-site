<?php

namespace App\Actions\Centre;

use App\Actions\Centre\Data\PublicCentreEntry;
use App\Domain\Enums\Locale;
use App\Domain\Enums\Weekday;
use App\Models\Centre\Centre;
use App\Models\Centre\CentreWeeklyHours;
use App\Support\CacheKeys;
use App\Support\DisplayTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class ResolvePublicCentres
{
    /**
     * @return array<string, PublicCentreEntry>
     */
    public function __invoke(Locale $locale): array
    {
        /** @var array<string, array<string, mixed>>|null $cached */
        $cached = Cache::get(CacheKeys::publicCentres($locale->value));

        if (is_array($cached)) {
            return collect($cached)
                ->map(fn (array $entry): PublicCentreEntry => new PublicCentreEntry(...$entry))
                ->all();
        }

        $entries = $this->load($locale);

        if ($entries !== [] && ($entries[array_key_first($entries)]->id ?? 0) !== 0) {
            Cache::put(
                CacheKeys::publicCentres($locale->value),
                collect($entries)
                    ->map(fn (PublicCentreEntry $entry): array => get_object_vars($entry))
                    ->all(),
                CacheKeys::catalogueTtlSeconds(),
            );
        }

        return $entries;
    }

    /**
     * @return array<string, PublicCentreEntry>
     */
    private function load(Locale $locale): array
    {
        /** @var Collection<int, Centre> $centres */
        $centres = Centre::query()
            ->active()
            ->with([
                'phones' => fn ($query) => $query->orderBy('sort_order'),
                'weeklyHours',
            ])
            ->orderBy('sort_order')
            ->get();

        if ($centres->isEmpty()) {
            return $this->fallbackCentres($locale);
        }

        return $centres
            ->mapWithKeys(fn (Centre $centre): array => [
                (string) $centre->code => $this->toEntry($centre, $locale),
            ])
            ->all();
    }

    private function toEntry(Centre $centre, Locale $locale): PublicCentreEntry
    {
        $weekdayHours = $this->hoursFor($centre->weeklyHours, Weekday::Monday, $locale);
        $sundayHours = $this->hoursFor($centre->weeklyHours, Weekday::Sunday, $locale);
        $phonesDisplay = $centre->phones
            ->map(fn ($phone): string => $this->formatPhone($phone->e164))
            ->values()
            ->all();
        $phonesE164 = $centre->phones
            ->pluck('e164')
            ->values()
            ->all();
        $landmark = $this->translated($centre->landmark, $locale);
        $address = $this->translated($centre->address, $locale);

        return new PublicCentreEntry(
            id: $centre->id,
            key: (string) $centre->code,
            name: $centre->translatedName($locale),
            shortName: $centre->translatedName($locale),
            address: $address,
            landmark: $landmark,
            displayAddress: trim($landmark.', '.$address, ', '),
            primaryPhoneE164: $phonesE164[0] ?? null,
            primaryPhoneDisplay: $phonesDisplay[0] ?? null,
            phonesDisplayLine: implode(' / ', $phonesDisplay),
            phonesDisplay: $phonesDisplay,
            phonesE164: $phonesE164,
            weekdayHours: $weekdayHours,
            sundayHours: $sundayHours,
            closeTime: $this->closeTimeFor($centre->weeklyHours, Weekday::Monday, $locale),
            holidayHours: $centre->holiday_default_open
                ? ($locale === Locale::Fr ? 'Ouvert' : 'Open')
                : ($locale === Locale::Fr ? 'Sur confirmation' : 'By confirmation'),
            latitude: (float) $centre->latitude,
            longitude: (float) $centre->longitude,
            coordinates: (float) $centre->latitude.', '.(float) $centre->longitude,
            directionsUrl: 'https://www.google.com/maps/dir/?api=1&destination='.(float) $centre->latitude.','.(float) $centre->longitude.'&travelmode=driving',
        );
    }

    /**
     * @param  array<string, string>|null  $value
     */
    private function translated(?array $value, Locale $locale): string
    {
        return $value[$locale->value] ?? $value['fr'] ?? $value['en'] ?? '';
    }

    /**
     * @param  Collection<int, CentreWeeklyHours>  $weeklyHours
     */
    private function hoursFor(Collection $weeklyHours, Weekday $weekday, Locale $locale): string
    {
        $hours = $weeklyHours->first(fn (CentreWeeklyHours $hours): bool => $hours->weekday === $weekday);

        if (! $hours?->is_open || $hours->opens_at === null || $hours->closes_at === null) {
            return $locale === Locale::Fr ? 'Fermé' : 'Closed';
        }

        return $this->formatTime($hours->opens_at, $locale).' - '.$this->formatTime($hours->closes_at, $locale);
    }

    /**
     * @param  Collection<int, CentreWeeklyHours>  $weeklyHours
     */
    private function closeTimeFor(Collection $weeklyHours, Weekday $weekday, Locale $locale): ?string
    {
        $hours = $weeklyHours->first(fn (CentreWeeklyHours $hours): bool => $hours->weekday === $weekday);

        if (! $hours?->is_open || $hours->closes_at === null) {
            return null;
        }

        return $this->formatTime($hours->closes_at, $locale);
    }

    private function formatTime(string $time, Locale $locale): string
    {
        return DisplayTime::format($time, $locale);
    }

    private function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '237')) {
            return trim(chunk_split(substr($digits, 3), 3, ' '));
        }

        return $phone;
    }

    /**
     * @return array<string, PublicCentreEntry>
     */
    private function fallbackCentres(Locale $locale): array
    {
        $isFrench = $locale === Locale::Fr;
        $entries = [
            new PublicCentreEntry(
                id: 0,
                key: 'ecole-de-police',
                name: 'École de Police',
                shortName: 'École de Police',
                address: 'Yaoundé',
                landmark: $isFrench ? 'Descente ancien Texaco, École de Police' : 'Former Texaco descent, École de Police',
                displayAddress: $isFrench ? 'Descente ancien Texaco, École de Police, Yaoundé' : 'Former Texaco descent, École de Police, Yaoundé',
                primaryPhoneE164: '+237687187516',
                primaryPhoneDisplay: '687 187 516',
                phonesDisplayLine: '687 187 516',
                phonesDisplay: ['687 187 516'],
                phonesE164: ['+237687187516'],
                weekdayHours: DisplayTime::format('07:00', $locale).' - '.DisplayTime::format('20:00', $locale),
                sundayHours: DisplayTime::format('07:00', $locale).' - '.DisplayTime::format('15:00', $locale),
                closeTime: DisplayTime::format('20:00', $locale),
                holidayHours: $isFrench ? 'Ouvert' : 'Open',
                latitude: 3.8786152,
                longitude: 11.5116814,
                coordinates: '3.8786152, 11.5116814',
                directionsUrl: 'https://www.google.com/maps/dir/?api=1&destination=3.8786152,11.5116814&travelmode=driving',
            ),
            new PublicCentreEntry(
                id: 0,
                key: 'nomayos',
                name: 'Nomayos',
                shortName: 'Nomayos',
                address: 'Yaoundé',
                landmark: $isFrench ? 'Carrefour Nomayos' : 'Nomayos junction',
                displayAddress: $isFrench ? 'Carrefour Nomayos, Yaoundé' : 'Nomayos junction, Yaoundé',
                primaryPhoneE164: '+237653100801',
                primaryPhoneDisplay: '653 100 801',
                phonesDisplayLine: '653 100 801 / 692 242 143',
                phonesDisplay: ['653 100 801', '692 242 143'],
                phonesE164: ['+237653100801', '+237692242143'],
                weekdayHours: DisplayTime::format('07:00', $locale).' - '.DisplayTime::format('19:00', $locale),
                sundayHours: DisplayTime::format('07:00', $locale).' - '.DisplayTime::format('15:00', $locale),
                closeTime: DisplayTime::format('19:00', $locale),
                holidayHours: $isFrench ? 'Ouvert' : 'Open',
                latitude: 3.7902275,
                longitude: 11.4439448,
                coordinates: '3.7902275, 11.4439448',
                directionsUrl: 'https://www.google.com/maps/dir/?api=1&destination=3.7902275,11.4439448&travelmode=driving',
            ),
        ];

        return collect($entries)
            ->mapWithKeys(fn (PublicCentreEntry $entry): array => [$entry->key => $entry])
            ->all();
    }
}
