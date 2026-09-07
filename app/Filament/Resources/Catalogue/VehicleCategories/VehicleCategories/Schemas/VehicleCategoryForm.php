<?php

namespace App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\Schemas;

use App\Support\Filament\AdminForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.catalogue.categories.sections.identity'))
                    ->schema([
                        TextInput::make('code')
                            ->label(__('admin.catalogue.categories.fields.code'))
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                        AdminForm::bilingualText('label', __('admin.catalogue.categories.fields.label')),
                        TextInput::make('sort_order')
                            ->label(__('admin.catalogue.categories.fields.sort_order'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        Toggle::make('is_published')
                            ->label(__('admin.catalogue.categories.fields.is_published')),
                    ]),
                Section::make(__('admin.catalogue.categories.sections.details'))
                    ->schema([
                        AdminForm::bilingualTextarea('examples', __('admin.catalogue.categories.fields.examples')),
                        AdminForm::bilingualTextarea('description', __('admin.catalogue.categories.fields.description')),
                    ]),
            ]);
    }
}
