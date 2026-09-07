<?php

namespace App\Filament\Resources\Catalogue\Services\Services\Tables;

use App\Support\AdminLocale;
use App\Support\CatalogueCache;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('admin.catalogue.services.fields.code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('admin.catalogue.services.fields.title'))
                    ->state(fn ($record): string => $record->translatedTitle(AdminLocale::current()))
                    ->searchable(),
                TextColumn::make('centres_count')
                    ->label(__('admin.catalogue.services.fields.centres'))
                    ->counts('centres'),
                TextColumn::make('vehicle_categories_count')
                    ->label(__('admin.catalogue.services.fields.categories'))
                    ->counts('vehicleCategories'),
                IconColumn::make('is_published')
                    ->label(__('admin.catalogue.services.fields.is_published'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.catalogue.services.fields.sort_order'))
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
