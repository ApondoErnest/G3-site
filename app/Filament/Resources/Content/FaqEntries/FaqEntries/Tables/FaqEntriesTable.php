<?php

namespace App\Filament\Resources\Content\FaqEntries\FaqEntries\Tables;

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

class FaqEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_code')
                    ->label(__('admin.content.faq.fields.category_code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('question')
                    ->label(__('admin.content.faq.fields.question'))
                    ->state(fn ($record): string => (string) ($record->question[AdminLocale::current()->value] ?? $record->question['fr'] ?? ''))
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label(__('admin.content.faq.fields.is_published'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.content.faq.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(fn ($record): bool => self::forgetCache($record->category_code) ?? true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (): bool => self::forgetCache() ?? true),
                ]),
            ]);
    }

    private static function forgetCache(?string $categoryCode = null): void
    {
        Cache::forget(CacheKeys::faqPublished());

        if ($categoryCode !== null) {
            Cache::forget(CacheKeys::faqPublished($categoryCode));
        }
    }
}
