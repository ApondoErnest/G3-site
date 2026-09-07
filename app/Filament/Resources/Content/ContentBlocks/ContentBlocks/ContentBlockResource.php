<?php

namespace App\Filament\Resources\Content\ContentBlocks\ContentBlocks;

use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Pages\CreateContentBlock;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Pages\EditContentBlock;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Pages\ListContentBlocks;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Schemas\ContentBlockForm;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Tables\ContentBlocksTable;
use App\Models\Content\ContentBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ContentBlockResource extends Resource
{
    protected static ?string $model = ContentBlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.content.blocks.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.content.blocks.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.content.blocks.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof ContentBlock) {
            return null;
        }

        return $record->key;
    }

    public static function form(Schema $schema): Schema
    {
        return ContentBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentBlocksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentBlocks::route('/'),
            'create' => CreateContentBlock::route('/create'),
            'edit' => EditContentBlock::route('/{record}/edit'),
        ];
    }
}
