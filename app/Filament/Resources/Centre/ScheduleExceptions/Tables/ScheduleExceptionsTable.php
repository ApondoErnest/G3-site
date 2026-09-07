<?php

namespace App\Filament\Resources\Centre\ScheduleExceptions\Tables;

use App\Support\AdminLocale;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScheduleExceptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('starts_on')
                    ->label(__('admin.exceptions.fields.starts_on'))
                    ->date()
                    ->sortable(),
                TextColumn::make('ends_on')
                    ->label(__('admin.exceptions.fields.ends_on'))
                    ->date()
                    ->placeholder('—'),
                TextColumn::make('scope')
                    ->label(__('admin.exceptions.fields.scope'))
                    ->state(function ($record): string {
                        if ($record->applies_to_all_centres) {
                            return __('admin.dashboard.centres.all_centres');
                        }

                        return $record->centre?->translatedName(AdminLocale::current())
                            ?? __('admin.dashboard.centres.centre_fallback');
                    }),
                IconColumn::make('is_open')
                    ->label(__('admin.exceptions.fields.is_open'))
                    ->boolean(),
                TextColumn::make('status')
                    ->label(__('admin.exceptions.fields.status'))
                    ->badge()
                    ->state(fn ($record): string => $record->is_open
                        ? __('admin.status.open')
                        : __('admin.status.closed')),
            ])
            ->defaultSort('starts_on', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
