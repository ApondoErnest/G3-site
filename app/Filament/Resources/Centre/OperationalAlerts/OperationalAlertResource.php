<?php

namespace App\Filament\Resources\Centre\OperationalAlerts;

use App\Filament\Resources\Centre\OperationalAlerts\Pages\CreateOperationalAlert;
use App\Filament\Resources\Centre\OperationalAlerts\Pages\EditOperationalAlert;
use App\Filament\Resources\Centre\OperationalAlerts\Pages\ListOperationalAlerts;
use App\Filament\Resources\Centre\OperationalAlerts\Schemas\OperationalAlertForm;
use App\Filament\Resources\Centre\OperationalAlerts\Tables\OperationalAlertsTable;
use App\Models\Centre\OperationalAlert;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OperationalAlertResource extends Resource
{
    protected static ?string $model = OperationalAlert::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?int $navigationSort = 40;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.centres');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.alerts.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.alerts.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.alerts.navigation');
    }

    public static function form(Schema $schema): Schema
    {
        return OperationalAlertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperationalAlertsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperationalAlerts::route('/'),
            'create' => CreateOperationalAlert::route('/create'),
            'edit' => EditOperationalAlert::route('/{record}/edit'),
        ];
    }
}
