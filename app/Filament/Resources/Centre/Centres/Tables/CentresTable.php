<?php

namespace App\Filament\Resources\Centre\Centres\Tables;

use App\Domain\Enums\CentreStatus;
use App\Support\AdminLocale;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CentresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('admin.centres.fields.code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('admin.centres.fields.name'))
                    ->formatStateUsing(fn ($record): string => $record->translatedName(AdminLocale::current()))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('admin.centres.fields.email')),
                TextColumn::make('secondary_email')
                    ->label(__('admin.centres.fields.secondary_email')),
                TextColumn::make('status')
                    ->label(__('admin.centres.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (CentreStatus|string $state): string => __(
                        'admin.centres.status.'.($state instanceof CentreStatus ? $state->value : $state),
                    )),
                IconColumn::make('holiday_default_open')
                    ->label(__('admin.centres.fields.holiday_default_open'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.centres.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
