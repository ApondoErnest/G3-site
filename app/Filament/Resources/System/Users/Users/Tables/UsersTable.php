<?php

namespace App\Filament\Resources\System\Users\Users\Tables;

use App\Filament\Resources\System\Users\Users\Schemas\UserForm;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.users.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('admin.users.fields.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label(__('admin.users.fields.roles'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => UserForm::roleOptions()[$state] ?? $state),
                IconColumn::make('is_active')
                    ->label(__('admin.users.fields.is_active'))
                    ->boolean(),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
