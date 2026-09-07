<?php

namespace App\Filament\Resources\Content\TeamMembers\TeamMembers\Tables;

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

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.content.team.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role_title')
                    ->label(__('admin.content.team.fields.role_title'))
                    ->state(fn ($record): string => (string) ($record->role_title[AdminLocale::current()->value] ?? $record->role_title['fr'] ?? '')),
                IconColumn::make('display_publicly')
                    ->label(__('admin.content.team.fields.display_publicly'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.content.team.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(fn (): bool => Cache::forget(CacheKeys::teamPublic()) ?? true),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (): bool => Cache::forget(CacheKeys::teamPublic()) ?? true),
                ]),
            ]);
    }
}
