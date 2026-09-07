<?php

namespace App\Filament\Resources\Centre\ScheduleExceptions;

use App\Filament\Resources\Centre\ScheduleExceptions\Pages\CreateScheduleException;
use App\Filament\Resources\Centre\ScheduleExceptions\Pages\EditScheduleException;
use App\Filament\Resources\Centre\ScheduleExceptions\Pages\ListScheduleExceptions;
use App\Filament\Resources\Centre\ScheduleExceptions\Schemas\ScheduleExceptionForm;
use App\Filament\Resources\Centre\ScheduleExceptions\Tables\ScheduleExceptionsTable;
use App\Models\Centre\ScheduleException;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScheduleExceptionResource extends Resource
{
    protected static ?string $model = ScheduleException::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.centres');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.exceptions.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.exceptions.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.exceptions.navigation');
    }

    public static function form(Schema $schema): Schema
    {
        return ScheduleExceptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScheduleExceptionsTable::configure($table);
    }

    /**
     * @return Builder<ScheduleException>
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return $query;
        }

        if ($user->hasRole('centre_manager')) {
            $centreIds = $user->centreScopes()->pluck('centre_id')->all();

            return $query->whereIn('centre_id', $centreIds === [] ? [-1] : $centreIds);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScheduleExceptions::route('/'),
            'create' => CreateScheduleException::route('/create'),
            'edit' => EditScheduleException::route('/{record}/edit'),
        ];
    }
}
