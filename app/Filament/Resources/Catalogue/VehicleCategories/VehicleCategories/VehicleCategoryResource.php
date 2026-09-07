<?php

namespace App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories;

use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Pages\CreateVehicleCategory;
use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Pages\EditVehicleCategory;
use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Pages\ListVehicleCategories;
use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Schemas\VehicleCategoryForm;
use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Tables\VehicleCategoriesTable;
use App\Models\Catalogue\VehicleCategory;
use App\Support\AdminLocale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VehicleCategoryResource extends Resource
{
    protected static ?string $model = VehicleCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.catalogue');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.catalogue.categories.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.catalogue.categories.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.catalogue.categories.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof VehicleCategory) {
            return null;
        }

        return $record->translatedLabel(AdminLocale::current());
    }

    public static function form(Schema $schema): Schema
    {
        return VehicleCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleCategories::route('/'),
            'create' => CreateVehicleCategory::route('/create'),
            'edit' => EditVehicleCategory::route('/{record}/edit'),
        ];
    }
}
