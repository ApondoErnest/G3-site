<?php

namespace App\Filament\Resources\Operations\ContactMessages\ContactMessages\Tables;

use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\ContactStatus;
use App\Support\AdminLabels;
use App\Support\AdminLocale;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.contacts.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label(__('admin.contacts.fields.subject'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('centre.name')
                    ->label(__('admin.contacts.fields.centre'))
                    ->state(fn ($record): string => $record->centre?->translatedName(AdminLocale::current())
                        ?? __('admin.contacts.empty.centre')),
                TextColumn::make('status')
                    ->label(__('admin.contacts.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (ContactStatus|string $state): string => AdminLabels::contactStatus(
                        $state instanceof ContactStatus ? $state : ContactStatus::from($state),
                    ))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin.contacts.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.contacts.fields.status'))
                    ->options(collect(ContactStatus::cases())
                        ->mapWithKeys(fn (ContactStatus $status): array => [
                            $status->value => AdminLabels::contactStatus($status),
                        ])
                        ->all()),
                SelectFilter::make('intent')
                    ->label(__('admin.contacts.fields.intent'))
                    ->options(collect(ContactIntent::cases())
                        ->mapWithKeys(fn (ContactIntent $intent): array => [
                            $intent->value => __('admin.contacts.intents.'.$intent->value),
                        ])
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
