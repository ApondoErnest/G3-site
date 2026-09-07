<?php

namespace App\Filament\Resources\Catalogue\Services\Services;

use App\Filament\Resources\Catalogue\Services\Services\Pages\CreateService;
use App\Filament\Resources\Catalogue\Services\Services\Pages\EditService;
use App\Filament\Resources\Catalogue\Services\Services\Pages\ListServices;
use App\Filament\Resources\Catalogue\Services\Services\Schemas\ServiceForm;
use App\Filament\Resources\Catalogue\Services\Services\Tables\ServicesTable;
use App\Models\Catalogue\Service;
use App\Support\AdminLocale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.catalogue');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.catalogue.services.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.catalogue.services.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.catalogue.services.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof Service) {
            return null;
        }

        return $record->translatedTitle(AdminLocale::current());
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
