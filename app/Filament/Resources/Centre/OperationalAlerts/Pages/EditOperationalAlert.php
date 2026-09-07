<?php

namespace App\Filament\Resources\Centre\OperationalAlerts\Pages;

use App\Filament\Resources\Centre\OperationalAlerts\OperationalAlertResource;
use App\Support\CacheKeys;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditOperationalAlert extends EditRecord
{
    protected static string $resource = OperationalAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn (): bool => Cache::forget(CacheKeys::alertsActive()) ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['message'] = self::messageState($data['message'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['message'] = self::normalizeMessage($data['message'] ?? null);

        return $data;
    }

    protected function afterSave(): void
    {
        Cache::forget(CacheKeys::alertsActive());
    }

    /**
     * @param  array<string, mixed>|string|null  $message
     * @return array<string, string>
     */
    private static function messageState(array|string|null $message): array
    {
        if (is_string($message)) {
            $message = json_decode($message, true) ?? [];
        }

        if (! is_array($message)) {
            return ['fr' => '', 'en' => ''];
        }

        return [
            'fr' => (string) ($message['fr'] ?? ''),
            'en' => (string) ($message['en'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>|null  $message
     * @return array<string, string>
     */
    private static function normalizeMessage(?array $message): array
    {
        return [
            'fr' => (string) ($message['fr'] ?? ''),
            'en' => (string) ($message['en'] ?? ''),
        ];
    }
}
