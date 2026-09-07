<?php

namespace App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\Schemas;

use App\Support\Filament\AdminForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoadSafetySectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.content.road_safety.sections.identity'))
                    ->schema([
                        TextInput::make('anchor')
                            ->label(__('admin.content.road_safety.fields.anchor'))
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                        TextInput::make('sort_order')
                            ->label(__('admin.content.road_safety.fields.sort_order'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        Toggle::make('is_published')
                            ->label(__('admin.content.road_safety.fields.is_published')),
                    ]),
                Section::make(__('admin.content.road_safety.sections.content'))
                    ->schema([
                        AdminForm::bilingualText('title', __('admin.content.road_safety.fields.title')),
                        AdminForm::bilingualTextarea('body', __('admin.content.road_safety.fields.body')),
                    ]),
            ]);
    }
}
