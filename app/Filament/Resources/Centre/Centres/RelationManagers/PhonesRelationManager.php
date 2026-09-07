<?php

namespace App\Filament\Resources\Centre\Centres\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PhonesRelationManager extends RelationManager
{
    protected static string $relationship = 'phones';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin.centres.phones.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label(__('admin.centres.phones.label'))
                    ->required()
                    ->maxLength(64),
                TextInput::make('e164')
                    ->label(__('admin.centres.phones.e164'))
                    ->required()
                    ->maxLength(20),
                Toggle::make('is_whatsapp')
                    ->label(__('admin.centres.phones.is_whatsapp')),
                TextInput::make('sort_order')
                    ->label(__('admin.centres.fields.sort_order'))
                    ->numeric()
                    ->required()
                    ->default(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label(__('admin.centres.phones.label')),
                TextColumn::make('e164')
                    ->label(__('admin.centres.phones.e164')),
                IconColumn::make('is_whatsapp')
                    ->label(__('admin.centres.phones.is_whatsapp'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('admin.centres.fields.sort_order')),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
