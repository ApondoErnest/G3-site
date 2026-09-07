<?php

namespace App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Tables;

use App\Support\AdminLocale;
use App\Support\CatalogueCache;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('admin.catalogue.categories.fields.code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('label')
                    ->label(__('admin.catalogue.categories.fields.label'))
                    ->state(fn ($record): string => $record->translatedLabel(AdminLocale::current()))
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label(__('admin.catalogue.categories.fields.is_published'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.catalogue.categories.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(fn (): bool => CatalogueCache::forgetPublished() ?? true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (): bool => CatalogueCache::forgetPublished() ?? true),
                ]),
            ]);
    }
}
