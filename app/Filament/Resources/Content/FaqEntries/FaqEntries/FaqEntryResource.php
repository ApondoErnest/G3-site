<?php

namespace App\Filament\Resources\Content\FaqEntries\FaqEntries;

use App\Filament\Resources\Content\FaqEntries\FaqEntries\Pages\CreateFaqEntry;
use App\Filament\Resources\Content\FaqEntries\FaqEntries\Pages\EditFaqEntry;
use App\Filament\Resources\Content\FaqEntries\FaqEntries\Pages\ListFaqEntries;
use App\Filament\Resources\Content\FaqEntries\FaqEntries\Schemas\FaqEntryForm;
use App\Filament\Resources\Content\FaqEntries\FaqEntries\Tables\FaqEntriesTable;
use App\Models\Content\FaqEntry;
use App\Support\AdminLocale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FaqEntryResource extends Resource
{
    protected static ?string $model = FaqEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.content.faq.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.content.faq.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.content.faq.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof FaqEntry) {
            return null;
        }

        $question = $record->question;

        return (string) ($question[AdminLocale::current()->value] ?? $question['fr'] ?? '');
    }

    public static function form(Schema $schema): Schema
    {
        return FaqEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqEntriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFaqEntries::route('/'),
            'create' => CreateFaqEntry::route('/create'),
            'edit' => EditFaqEntry::route('/{record}/edit'),
        ];
    }
}
