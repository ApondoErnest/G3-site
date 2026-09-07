<?php

namespace App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Tables;

use App\Domain\Enums\AppointmentStatus;
use App\Domain\ValueObjects\PublicReference;
use App\Support\AdminLabels;
use App\Support\AdminLocale;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('public_reference')
                    ->label(__('admin.appointments.fields.public_reference'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (PublicReference|string|null $state): string => match (true) {
                        $state instanceof PublicReference => $state->value,
                        is_string($state) => $state,
                        default => '',
                    }),
                TextColumn::make('contact_name')
                    ->label(__('admin.appointments.fields.contact_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('centre.name')
                    ->label(__('admin.appointments.fields.centre'))
                    ->state(fn ($record): string => $record->centre?->translatedName(AdminLocale::current())
                        ?? __('admin.dashboard.centres.centre_fallback')),
                TextColumn::make('status')
                    ->label(__('admin.appointments.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (AppointmentStatus|string $state): string => AdminLabels::appointmentStatus(
                        $state instanceof AppointmentStatus ? $state : AppointmentStatus::from($state),
                    ))
                    ->sortable(),
                TextColumn::make('preferred_date')
                    ->label(__('admin.appointments.fields.preferred_date'))
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.appointments.fields.status'))
                    ->options(collect(AppointmentStatus::cases())
                        ->mapWithKeys(fn (AppointmentStatus $status): array => [
                            $status->value => AdminLabels::appointmentStatus($status),
                        ])
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
