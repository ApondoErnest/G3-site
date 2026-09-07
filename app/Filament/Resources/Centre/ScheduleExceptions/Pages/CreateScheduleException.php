<?php

namespace App\Filament\Resources\Centre\ScheduleExceptions\Pages;

use App\Actions\Schedule\CreateScheduleException as CreateScheduleExceptionAction;
use App\Actions\Schedule\Data\CreateScheduleExceptionData;
use App\Filament\Resources\Centre\ScheduleExceptions\ScheduleExceptionResource;
use Carbon\CarbonImmutable;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateScheduleException extends CreateRecord
{
    protected static string $resource = ScheduleExceptionResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateScheduleExceptionAction::class)(new CreateScheduleExceptionData(
            appliesToAllCentres: (bool) ($data['applies_to_all_centres'] ?? false),
            centreId: ($data['applies_to_all_centres'] ?? false) ? null : (int) $data['centre_id'],
            startsOn: CarbonImmutable::parse($data['starts_on']),
            endsOn: filled($data['ends_on'] ?? null) ? CarbonImmutable::parse($data['ends_on']) : null,
            isOpen: (bool) ($data['is_open'] ?? false),
            opensAt: self::formatTime($data['opens_at'] ?? null),
            closesAt: self::formatTime($data['closes_at'] ?? null),
            reason: self::normalizeReason($data['reason'] ?? null),
            actor: auth()->user(),
        ));
    }

    /**
     * @param  array<string, mixed>|null  $reason
     * @return array<string, string>|null
     */
    private static function normalizeReason(?array $reason): ?array
    {
        if ($reason === null || ($reason['fr'] ?? '') === '' && ($reason['en'] ?? '') === '') {
            return null;
        }

        return [
            'fr' => (string) ($reason['fr'] ?? ''),
            'en' => (string) ($reason['en'] ?? ''),
        ];
    }

    private static function formatTime(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        return strlen((string) $value) === 5
            ? (string) $value.':00'
            : (string) $value;
    }
}
