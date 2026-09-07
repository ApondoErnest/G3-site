<?php

namespace App\Filament\Resources\Centre\OperationalAlerts\Pages;

use App\Filament\Resources\Centre\OperationalAlerts\OperationalAlertResource;
use App\Support\CacheKeys;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateOperationalAlert extends CreateRecord
{
    protected static string $resource = OperationalAlertResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['message'] = self::normalizeMessage($data['message'] ?? null);

        return $data;
    }

    protected function afterCreate(): void
    {
        Cache::forget(CacheKeys::alertsActive());
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
