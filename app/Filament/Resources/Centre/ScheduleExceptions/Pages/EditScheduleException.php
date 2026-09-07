<?php

namespace App\Filament\Resources\Centre\ScheduleExceptions\Pages;

use App\Actions\Schedule\Data\DeleteScheduleExceptionData;
use App\Actions\Schedule\Data\UpdateScheduleExceptionData;
use App\Actions\Schedule\DeleteScheduleException;
use App\Actions\Schedule\UpdateScheduleException;
use App\Filament\Resources\Centre\ScheduleExceptions\ScheduleExceptionResource;
use Carbon\CarbonImmutable;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditScheduleException extends EditRecord
{
    protected static string $resource = ScheduleExceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->action(function (): void {
                    app(DeleteScheduleException::class)(new DeleteScheduleExceptionData(
                        exceptionId: $this->getRecord()->id,
                        actor: auth()->user(),
                    ));

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['reason'] = $data['reason'] ?? ['fr' => '', 'en' => ''];

        if (is_string($data['reason'])) {
            $data['reason'] = json_decode($data['reason'], true) ?? ['fr' => '', 'en' => ''];
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(UpdateScheduleException::class)(new UpdateScheduleExceptionData(
            exceptionId: $record->id,
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
