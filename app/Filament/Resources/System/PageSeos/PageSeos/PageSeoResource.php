<?php

namespace App\Filament\Resources\System\PageSeos\PageSeos;

use App\Filament\Resources\System\PageSeos\PageSeos\Pages\EditPageSeo;
use App\Filament\Resources\System\PageSeos\PageSeos\Pages\ListPageSeos;
use App\Filament\Resources\System\PageSeos\PageSeos\Schemas\PageSeoForm;
use App\Filament\Resources\System\PageSeos\PageSeos\Tables\PageSeosTable;
use App\Models\Content\PageSeo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PageSeoResource extends Resource
{
    protected static ?string $model = PageSeo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.content.page_seo.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.content.page_seo.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.content.page_seo.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof PageSeo) {
            return null;
        }

        return __('admin.content.pages.'.$record->page->value);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PageSeoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageSeosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageSeos::route('/'),
            'edit' => EditPageSeo::route('/{record}/edit'),
        ];
    }
}
