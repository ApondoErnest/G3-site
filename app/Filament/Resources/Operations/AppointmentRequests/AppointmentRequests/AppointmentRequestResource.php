<?php

namespace App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests;

use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Pages\ListAppointmentRequests;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Pages\ViewAppointmentRequest;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Schemas\AppointmentRequestInfolist;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Tables\AppointmentRequestsTable;
use App\Models\Appointment\AppointmentRequest;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequestResource extends Resource
{
    protected static ?string $model = AppointmentRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.appointments.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.appointments.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.appointments.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof AppointmentRequest) {
            return null;
        }

        return (string) $record->public_reference;
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppointmentRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppointmentRequestsTable::configure($table);
    }

    /**
     * @return Builder<AppointmentRequest>
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['centre']);
        $user = auth()->user();

        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return $query;
        }

        if ($user->hasRole(['centre_manager', 'reception_officer'])) {
            $centreIds = $user->centreScopes()->pluck('centre_id')->all();

            return $query->whereIn('centre_id', $centreIds === [] ? [-1] : $centreIds);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppointmentRequests::route('/'),
            'view' => ViewAppointmentRequest::route('/{record}'),
        ];
    }
}
