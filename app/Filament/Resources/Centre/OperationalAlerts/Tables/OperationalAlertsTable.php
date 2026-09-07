<?php

namespace App\Filament\Resources\Centre\OperationalAlerts\Tables;

use App\Domain\Enums\AlertSeverity;
use App\Support\AdminLocale;
use App\Support\CacheKeys;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class OperationalAlertsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('severity')
                    ->label(__('admin.alerts.fields.severity'))
                    ->badge()
                    ->formatStateUsing(fn (AlertSeverity|string $state): string => __(
                        'admin.alerts.severity.'.($state instanceof AlertSeverity ? $state->value : $state),
                    )),
                TextColumn::make('message')
                    ->label(__('admin.alerts.fields.message'))
                    ->limit(60)
                    ->state(fn ($record): string => self::translatedMessage($record->message)),
                TextColumn::make('scope')
                    ->label(__('admin.alerts.fields.scope'))
                    ->state(fn ($record): string => $record->centre_id === null
                        ? __('admin.alerts.fields.site_wide')
                        : ($record->centre?->translatedName(AdminLocale::current())
                            ?? __('admin.dashboard.centres.centre_fallback'))),
                TextColumn::make('starts_at')
                    ->label(__('admin.alerts.fields.starts_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label(__('admin.alerts.fields.expires_at'))
                    ->dateTime()
                    ->placeholder('—'),
                IconColumn::make('is_active')
                    ->label(__('admin.alerts.fields.is_active'))
                    ->boolean(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(fn (): bool => Cache::forget(CacheKeys::alertsActive()) ?? true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (): bool => Cache::forget(CacheKeys::alertsActive()) ?? true),
                ]),
            ]);
    }

    /**
     * @param  array<string, string>|string|null  $message
     */
    private static function translatedMessage(array|string|null $message): string
    {
        if (is_string($message)) {
            $message = json_decode($message, true) ?? [];
        }

        if (! is_array($message)) {
            return '';
        }

        $locale = AdminLocale::current();

        return $message[$locale] ?? $message['fr'] ?? $message['en'] ?? '';
    }
}
