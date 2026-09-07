<?php

namespace App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Tables;

use App\Domain\Enums\TariffVersionStatus;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TariffVersionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label(__('admin.tariffs.fields.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('admin.tariffs.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (TariffVersionStatus|string $state): string => __(
                        'admin.tariffs.status.'.($state instanceof TariffVersionStatus ? $state->value : $state),
                    )),
                TextColumn::make('effective_from')
                    ->label(__('admin.tariffs.fields.effective_from'))
                    ->date()
                    ->sortable(),
                TextColumn::make('items_count')
                    ->label(__('admin.tariffs.fields.items_count'))
                    ->counts('items'),
            ])
            ->defaultSort('effective_from', 'desc')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
