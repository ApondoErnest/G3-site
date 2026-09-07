<?php

namespace App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections;

use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Pages\CreateRoadSafetySection;
use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Pages\EditRoadSafetySection;
use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Pages\ListRoadSafetySections;
use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Schemas\RoadSafetySectionForm;
use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Tables\RoadSafetySectionsTable;
use App\Models\Content\RoadSafetySection;
use App\Support\AdminLocale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RoadSafetySectionResource extends Resource
{
    protected static ?string $model = RoadSafetySection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 40;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.content.road_safety.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.content.road_safety.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.content.road_safety.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof RoadSafetySection) {
            return null;
        }

        $title = $record->title;

        return (string) ($title[AdminLocale::current()->value] ?? $title['fr'] ?? $record->anchor);
    }

    public static function form(Schema $schema): Schema
    {
        return RoadSafetySectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoadSafetySectionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoadSafetySections::route('/'),
            'create' => CreateRoadSafetySection::route('/create'),
            'edit' => EditRoadSafetySection::route('/{record}/edit'),
        ];
    }
}
