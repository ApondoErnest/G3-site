<?php

namespace App\Filament\Resources\Content\FaqEntries\FaqEntries\Schemas;

use App\Support\Filament\AdminForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.content.faq.sections.identity'))
                    ->schema([
                        TextInput::make('category_code')
                            ->label(__('admin.content.faq.fields.category_code'))
                            ->required()
                            ->maxLength(64),
                        TextInput::make('sort_order')
                            ->label(__('admin.content.faq.fields.sort_order'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        Toggle::make('is_published')
                            ->label(__('admin.content.faq.fields.is_published')),
                    ]),
                Section::make(__('admin.content.faq.sections.content'))
                    ->schema([
                        AdminForm::bilingualText('question', __('admin.content.faq.fields.question')),
                        AdminForm::bilingualTextarea('answer', __('admin.content.faq.fields.answer')),
                    ]),
            ]);
    }
}
