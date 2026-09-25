<?php

namespace App\Filament\Resources\Operations\ContactMessages\ContactMessages\Schemas;

use App\Domain\Enums\ContactIntent;
use App\Domain\Enums\ContactStatus;
use App\Domain\Enums\Locale;
use App\Support\AdminLabels;
use App\Support\AdminLocale;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.contacts.sections.summary'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label(__('admin.contacts.fields.status'))
                            ->badge()
                            ->formatStateUsing(fn (ContactStatus|string $state): string => AdminLabels::contactStatus(
                                $state instanceof ContactStatus ? $state : ContactStatus::from($state),
                            )),
                        TextEntry::make('intent')
                            ->label(__('admin.contacts.fields.intent'))
                            ->formatStateUsing(fn (ContactIntent|string|null $state): string => match (true) {
                                $state instanceof ContactIntent => __('admin.contacts.intents.'.$state->value),
                                is_string($state) => __('admin.contacts.intents.'.$state),
                                default => '—',
                            }),
                        TextEntry::make('subject')
                            ->label(__('admin.contacts.fields.subject'))
                            ->columnSpanFull(),
                        TextEntry::make('centre.name')
                            ->label(__('admin.contacts.fields.centre'))
                            ->state(fn ($record): string => $record->centre?->translatedName(AdminLocale::current())
                                ?? __('admin.contacts.empty.centre')),
                        TextEntry::make('locale')
                            ->label(__('admin.contacts.fields.locale'))
                            ->formatStateUsing(fn (Locale|string|null $state): string => match (true) {
                                $state instanceof Locale => strtoupper($state->value),
                                is_string($state) => strtoupper($state),
                                default => '—',
                            }),
                        TextEntry::make('created_at')
                            ->label(__('admin.contacts.fields.created_at'))
                            ->dateTime(),
                        TextEntry::make('resolved_at')
                            ->label(__('admin.contacts.fields.resolved_at'))
                            ->dateTime()
                            ->placeholder('—'),
                    ]),
                Section::make(__('admin.contacts.sections.sender'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('admin.contacts.fields.name')),
                        TextEntry::make('phone_e164')
                            ->label(__('admin.contacts.fields.phone')),
                        TextEntry::make('email')
                            ->label(__('admin.contacts.fields.email')),
                    ]),
                Section::make(__('admin.contacts.sections.message'))
                    ->schema([
                        TextEntry::make('message')
                            ->label(__('admin.contacts.fields.message'))
                            ->columnSpanFull(),
                    ]),
                Section::make(__('admin.contacts.sections.notes'))
                    ->schema([
                        RepeatableEntry::make('internalNotes')
                            ->label('')
                            ->contained(false)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('admin.contacts.fields.note_at'))
                                    ->dateTime(),
                                TextEntry::make('author.name')
                                    ->label(__('admin.contacts.fields.note_author')),
                                TextEntry::make('body')
                                    ->label(__('admin.contacts.fields.note_body'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->placeholder(__('admin.contacts.empty.notes')),
                    ]),
            ]);
    }
}
