<?php

namespace App\Filament\Resources\Tariff\TariffVersions\TariffVersions;

use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages\CreateTariffVersion;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages\EditTariffVersion;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages\ListTariffVersions;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Schemas\TariffVersionForm;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Tables\TariffVersionsTable;
use App\Models\Tariff\TariffVersion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TariffVersionResource extends Resource
{
    protected static ?string $model = TariffVersion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 50;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.catalogue');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.tariffs.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.tariffs.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.tariffs.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof TariffVersion) {
            return null;
        }

        return $record->label;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TariffVersionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TariffVersionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTariffVersions::route('/'),
            'create' => CreateTariffVersion::route('/create'),
            'edit' => EditTariffVersion::route('/{record}/edit'),
        ];
    }
}
