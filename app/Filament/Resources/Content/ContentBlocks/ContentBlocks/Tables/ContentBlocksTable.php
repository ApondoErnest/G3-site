<?php

namespace App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentBlocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(__('admin.content.blocks.fields.key'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('page')
                    ->label(__('admin.content.blocks.fields.page'))
                    ->formatStateUsing(fn ($state): string => $state ? __('admin.content.pages.'.$state->value) : '')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label(__('admin.content.blocks.fields.is_published'))
                    ->boolean(),
                TextColumn::make('locale_status.fr')
                    ->label(__('admin.content.blocks.fields.locale_fr')),
                TextColumn::make('locale_status.en')
                    ->label(__('admin.content.blocks.fields.locale_en')),
                TextColumn::make('updated_at')
                    ->label(__('admin.content.blocks.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('key')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
