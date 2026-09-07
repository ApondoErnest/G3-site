<?php

namespace App\Filament\Resources\Catalogue\Services\Services\Schemas;

use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;
use App\Support\AdminLocale;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.catalogue.services.sections.identity'))
                    ->schema([
                        TextInput::make('code')
                            ->label(__('admin.catalogue.services.fields.code'))
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                        AdminForm::bilingualText('title', __('admin.catalogue.services.fields.title')),
                        TextInput::make('icon')
                            ->label(__('admin.catalogue.services.fields.icon'))
                            ->maxLength(64),
                        TextInput::make('sort_order')
                            ->label(__('admin.catalogue.services.fields.sort_order'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        Toggle::make('is_published')
                            ->label(__('admin.catalogue.services.fields.is_published')),
                    ]),
                Section::make(__('admin.catalogue.services.sections.content'))
                    ->schema([
                        AdminForm::bilingualTextarea('summary', __('admin.catalogue.services.fields.summary')),
                        AdminForm::bilingualTextarea('body', __('admin.catalogue.services.fields.body')),
                    ]),
                Section::make(__('admin.catalogue.services.sections.availability'))
                    ->schema([
                        Select::make('centres')
                            ->label(__('admin.catalogue.services.fields.centres'))
                            ->relationship(
                                name: 'centres',
                                titleAttribute: 'code',
                                modifyQueryUsing: fn ($query) => $query->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Centre $centre): string => $centre->translatedName(AdminLocale::current()),
                            )
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        Select::make('vehicleCategories')
                            ->label(__('admin.catalogue.services.fields.categories'))
                            ->relationship(
                                name: 'vehicleCategories',
                                titleAttribute: 'code',
                                modifyQueryUsing: fn ($query) => $query->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (VehicleCategory $category): string => $category->translatedLabel(AdminLocale::current()),
                            )
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }
}
