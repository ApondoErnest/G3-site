<?php

namespace App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Schemas;

use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\PreferredChannel;
use App\Domain\Enums\PreferredPeriod;
use App\Domain\ValueObjects\PublicReference;
use App\Support\AdminLabels;
use App\Support\AdminLocale;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.appointments.sections.summary'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('public_reference')
                            ->label(__('admin.appointments.fields.public_reference'))
                            ->formatStateUsing(fn (PublicReference|string|null $state): string => match (true) {
                                $state instanceof PublicReference => $state->value,
                                is_string($state) => $state,
                                default => '',
                            })
                            ->fontFamily('mono'),
                        TextEntry::make('status')
                            ->label(__('admin.appointments.fields.status'))
                            ->badge()
                            ->formatStateUsing(fn (AppointmentStatus|string $state): string => AdminLabels::appointmentStatus(
                                $state instanceof AppointmentStatus ? $state : AppointmentStatus::from($state),
                            )),
                        TextEntry::make('centre.name')
                            ->label(__('admin.appointments.fields.centre'))
                            ->state(fn ($record): string => $record->centre?->translatedName(AdminLocale::current())
                                ?? __('admin.dashboard.centres.centre_fallback')),
                        TextEntry::make('service.title')
                            ->label(__('admin.appointments.fields.service'))
                            ->state(fn ($record): string => $record->service?->translatedTitle(AdminLocale::current()) ?? '—'),
                        TextEntry::make('vehicleCategory.label')
                            ->label(__('admin.appointments.fields.vehicle_category'))
                            ->state(fn ($record): string => self::translatedLabel($record->vehicleCategory?->label)),
                        TextEntry::make('created_at')
                            ->label(__('admin.appointments.fields.created_at'))
                            ->dateTime(),
                    ]),
                Section::make(__('admin.appointments.sections.schedule'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('preferred_date')
                            ->label(__('admin.appointments.fields.preferred_date'))
                            ->date(),
                        TextEntry::make('preferred_period')
                            ->label(__('admin.appointments.fields.preferred_period'))
                            ->formatStateUsing(fn (PreferredPeriod|string|null $state): string => match (true) {
                                $state instanceof PreferredPeriod => __('admin.appointments.periods.'.$state->value),
                                is_string($state) => __('admin.appointments.periods.'.$state),
                                default => '—',
                            }),
                    ]),
                Section::make(__('admin.appointments.sections.contact'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('contact_name')
                            ->label(__('admin.appointments.fields.contact_name')),
                        TextEntry::make('contact_phone_e164')
                            ->label(__('admin.appointments.fields.contact_phone')),
                        TextEntry::make('contact_email')
                            ->label(__('admin.appointments.fields.contact_email'))
                            ->placeholder('—'),
                        TextEntry::make('preferred_channel')
                            ->label(__('admin.appointments.fields.preferred_channel'))
                            ->formatStateUsing(fn (PreferredChannel|string|null $state): string => match (true) {
                                $state instanceof PreferredChannel => __('admin.appointments.channels.'.$state->value),
                                is_string($state) => __('admin.appointments.channels.'.$state),
                                default => '—',
                            }),
                    ]),
                Section::make(__('admin.appointments.sections.vehicle'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('registration_display')
                            ->label(__('admin.appointments.fields.registration')),
                    ]),
                Section::make(__('admin.appointments.sections.timeline'))
                    ->schema([
                        RepeatableEntry::make('statusHistories')
                            ->label('')
                            ->contained(false)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('admin.appointments.fields.timeline_at'))
                                    ->dateTime(),
                                TextEntry::make('status')
                                    ->label(__('admin.appointments.fields.status'))
                                    ->badge()
                                    ->formatStateUsing(fn (AppointmentStatus|string $state): string => AdminLabels::appointmentStatus(
                                        $state instanceof AppointmentStatus ? $state : AppointmentStatus::from($state),
                                    )),
                                TextEntry::make('public_note')
                                    ->label(__('admin.appointments.fields.public_note'))
                                    ->formatStateUsing(fn (?array $state): string => self::translatedPublicNote($state))
                                    ->placeholder('—')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
                Section::make(__('admin.appointments.sections.notes'))
                    ->schema([
                        RepeatableEntry::make('internalNotes')
                            ->label('')
                            ->contained(false)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('admin.appointments.fields.note_at'))
                                    ->dateTime(),
                                TextEntry::make('author.name')
                                    ->label(__('admin.appointments.fields.note_author')),
                                TextEntry::make('body')
                                    ->label(__('admin.appointments.fields.note_body'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->placeholder(__('admin.appointments.empty.notes')),
                    ]),
            ]);
    }

    /**
     * @param  array<string, string>|null  $label
     */
    private static function translatedLabel(?array $label): string
    {
        if ($label === null) {
            return '—';
        }

        $locale = AdminLocale::current()->value;

        return $label[$locale] ?? $label['fr'] ?? $label['en'] ?? '—';
    }

    /**
     * @param  array<string, string>|null  $note
     */
    private static function translatedPublicNote(?array $note): string
    {
        if ($note === null || $note === []) {
            return '';
        }

        $locale = AdminLocale::current()->value;

        return $note[$locale] ?? $note['fr'] ?? $note['en'] ?? '';
    }
}
