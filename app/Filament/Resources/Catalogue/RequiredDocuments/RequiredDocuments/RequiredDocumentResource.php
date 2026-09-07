<?php

namespace App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments;

use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Pages\CreateRequiredDocument;
use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Pages\EditRequiredDocument;
use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Pages\ListRequiredDocuments;
use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Schemas\RequiredDocumentForm;
use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Tables\RequiredDocumentsTable;
use App\Models\Catalogue\RequiredDocument;
use App\Support\AdminLocale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RequiredDocumentResource extends Resource
{
    protected static ?string $model = RequiredDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.catalogue');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.catalogue.documents.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.catalogue.documents.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.catalogue.documents.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof RequiredDocument) {
            return null;
        }

        return $record->translatedLabel(AdminLocale::current());
    }

    public static function form(Schema $schema): Schema
    {
        return RequiredDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequiredDocumentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRequiredDocuments::route('/'),
            'create' => CreateRequiredDocument::route('/create'),
            'edit' => EditRequiredDocument::route('/{record}/edit'),
        ];
    }
}
