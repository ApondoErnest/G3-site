<?php

namespace App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Tables;

use App\Support\AdminLocale;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequiredDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label(__('admin.catalogue.documents.fields.label'))
                    ->state(fn ($record): string => $record->translatedLabel(AdminLocale::current()))
                    ->searchable(),
                TextColumn::make('service.title')
                    ->label(__('admin.catalogue.documents.fields.service'))
                    ->state(fn ($record): string => $record->service_id === null
                        ? '—'
                        : ($record->service?->translatedTitle(AdminLocale::current()) ?? '—')),
                TextColumn::make('vehicleCategory.label')
                    ->label(__('admin.catalogue.documents.fields.category'))
                    ->state(fn ($record): string => $record->vehicle_category_id === null
                        ? '—'
                        : ($record->vehicleCategory?->translatedLabel(AdminLocale::current()) ?? '—')),
                TextColumn::make('sort_order')
                    ->label(__('admin.catalogue.documents.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
