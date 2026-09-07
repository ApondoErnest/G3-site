<?php

namespace App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Schemas;

use App\Domain\Enums\ContentPage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ContentBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.content.blocks.sections.identity'))
                    ->schema([
                        TextInput::make('key')
                            ->label(__('admin.content.blocks.fields.key'))
                            ->required()
                            ->maxLength(128)
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit'),
                        Select::make('page')
                            ->label(__('admin.content.blocks.fields.page'))
                            ->options(self::pageOptions())
                            ->required()
                            ->disabledOn('edit'),
                    ]),
                Section::make(__('admin.content.blocks.sections.content'))
                    ->schema([
                        Tabs::make('content')
                            ->tabs([
                                Tab::make(__('admin.forms.tab_fr'))
                                    ->schema([
                                        TextInput::make('content.fr.headline')
                                            ->label(__('admin.content.blocks.fields.headline'))
                                            ->required(),
                                        Textarea::make('content.fr.body')
                                            ->label(__('admin.content.blocks.fields.body'))
                                            ->rows(4),
                                    ]),
                                Tab::make(__('admin.forms.tab_en'))
                                    ->schema([
                                        TextInput::make('content.en.headline')
                                            ->label(__('admin.content.blocks.fields.headline'))
                                            ->required(),
                                        Textarea::make('content.en.body')
                                            ->label(__('admin.content.blocks.fields.body'))
                                            ->rows(4),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function pageOptions(): array
    {
        return collect(ContentPage::cases())
            ->mapWithKeys(fn (ContentPage $page): array => [
                $page->value => __('admin.content.pages.'.$page->value),
            ])
            ->all();
    }
}
