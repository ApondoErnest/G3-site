<?php

namespace App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\Schemas;

use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Support\AdminLocale;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RequiredDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.catalogue.documents.sections.target'))
                    ->schema([
                        Select::make('service_id')
                            ->label(__('admin.catalogue.documents.fields.service'))
                            ->options(fn (): array => self::serviceOptions())
                            ->searchable()
                            ->nullable()
                            ->requiredWithout('vehicle_category_id'),
                        Select::make('vehicle_category_id')
                            ->label(__('admin.catalogue.documents.fields.category'))
                            ->options(fn (): array => self::categoryOptions())
                            ->searchable()
                            ->nullable()
                            ->requiredWithout('service_id'),
                    ])
                    ->columns(2),
                Section::make(__('admin.catalogue.documents.sections.content'))
                    ->schema([
                        AdminForm::bilingualText('label', __('admin.catalogue.documents.fields.label')),
                        TextInput::make('sort_order')
                            ->label(__('admin.catalogue.documents.fields.sort_order'))
                            ->numeric()
                            ->required()
                            ->default(1),
                    ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function serviceOptions(): array
    {
        return Service::query()
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (Service $service): array => [
                $service->id => $service->translatedTitle(AdminLocale::current()),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private static function categoryOptions(): array
    {
        return VehicleCategory::query()
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (VehicleCategory $category): array => [
                $category->id => $category->translatedLabel(AdminLocale::current()),
            ])
            ->all();
    }
}
