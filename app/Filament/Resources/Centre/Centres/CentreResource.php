<?php

namespace App\Filament\Resources\Centre\Centres;

use App\Filament\Resources\Centre\Centres\Pages\EditCentre;
use App\Filament\Resources\Centre\Centres\Pages\ListCentres;
use App\Filament\Resources\Centre\Centres\RelationManagers\PhonesRelationManager;
use App\Filament\Resources\Centre\Centres\Schemas\CentreForm;
use App\Filament\Resources\Centre\Centres\Tables\CentresTable;
use App\Models\Centre\Centre;
use App\Models\User;
use App\Support\AdminLocale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CentreResource extends Resource
{
    protected static ?string $model = Centre::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.centres');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.centres.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.centres.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.centres.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof Centre) {
            return null;
        }

        return $record->translatedName(AdminLocale::current());
    }

    public static function form(Schema $schema): Schema
    {
        return CentreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CentresTable::configure($table);
    }

    /**
     * @return Builder<Centre>
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

            return $query->whereIn('id', $centreIds === [] ? [-1] : $centreIds);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            PhonesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCentres::route('/'),
            'edit' => EditCentre::route('/{record}/edit'),
        ];
    }
}
