<?php

namespace App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Tables;

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

class RoadSafetySectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anchor')
                    ->label(__('admin.content.road_safety.fields.anchor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('admin.content.road_safety.fields.title'))
                    ->state(fn ($record): string => (string) ($record->title[AdminLocale::current()->value] ?? $record->title['fr'] ?? '')),
                IconColumn::make('is_published')
                    ->label(__('admin.content.road_safety.fields.is_published'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.content.road_safety.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(fn (): bool => Cache::forget(CacheKeys::roadSafetyPublished()) ?? true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (): bool => Cache::forget(CacheKeys::roadSafetyPublished()) ?? true),
                ]),
            ]);
    }
}
