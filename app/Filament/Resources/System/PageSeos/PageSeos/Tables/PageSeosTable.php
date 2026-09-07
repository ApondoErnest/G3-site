<?php

namespace App\Filament\Resources\System\PageSeos\PageSeos\Tables;

use App\Support\AdminLocale;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageSeosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page')
                    ->label(__('admin.content.page_seo.fields.page'))
                    ->formatStateUsing(fn ($state): string => $state ? __('admin.content.pages.'.$state->value) : '')
                    ->sortable(),
                TextColumn::make('seo_title')
                    ->label(__('admin.content.page_seo.fields.seo_title'))
                    ->state(fn ($record): string => (string) ($record->seo_title[AdminLocale::current()->value] ?? $record->seo_title['fr'] ?? '')),
                TextColumn::make('updated_at')
                    ->label(__('admin.content.page_seo.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('page')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
