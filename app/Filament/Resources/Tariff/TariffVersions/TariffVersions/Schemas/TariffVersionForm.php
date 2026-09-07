<?php

namespace App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Schemas;

use App\Domain\Enums\TariffVersionStatus;
use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;
use App\Models\Tariff\TariffVersion;
use App\Support\AdminLocale;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TariffVersionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.tariffs.sections.identity'))
                    ->schema([
                        TextInput::make('label')
                            ->label(__('admin.tariffs.fields.label'))
                            ->required()
                            ->maxLength(64)
                            ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                        DatePicker::make('effective_from')
                            ->label(__('admin.tariffs.fields.effective_from'))
                            ->required()
                            ->native(false)
                            ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                        DatePicker::make('effective_until')
                            ->label(__('admin.tariffs.fields.effective_until'))
                            ->native(false)
                            ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                    ])
                    ->columns(3),
                Section::make(__('admin.tariffs.sections.items'))
                    ->schema([
                        Repeater::make('tariff_items')
                            ->label(__('admin.tariffs.sections.items'))
                            ->schema([
                                Select::make('vehicle_category_id')
                                    ->label(__('admin.tariffs.fields.vehicle_category'))
                                    ->options(fn (): array => self::vehicleCategoryOptions())
                                    ->required()
                                    ->searchable()
                                    ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                                Select::make('service_id')
                                    ->label(__('admin.tariffs.fields.service'))
                                    ->options(fn (): array => self::serviceOptions())
                                    ->nullable()
                                    ->searchable()
                                    ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                                TextInput::make('amount_xaf')
                                    ->label(__('admin.tariffs.fields.amount_xaf'))
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                                Select::make('centres')
                                    ->label(__('admin.tariffs.fields.centres'))
                                    ->options(fn (): array => self::centreOptions())
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                                AdminForm::bilingualTextarea(
                                    'validity_notes',
                                    __('admin.tariffs.fields.validity_notes'),
                                )
                                    ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                                TextInput::make('sort_order')
                                    ->label(__('admin.tariffs.fields.sort_order'))
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record)),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel(__('admin.tariffs.actions.add_item'))
                            ->disabled(fn (?TariffVersion $record): bool => self::isLocked($record))
                            ->hiddenOn('create'),
                    ])
                    ->hiddenOn('create'),
            ]);
    }

    private static function isLocked(?TariffVersion $record): bool
    {
        if ($record === null) {
            return false;
        }

        return in_array($record->status, [TariffVersionStatus::Published, TariffVersionStatus::Archived], true);
    }

    /**
     * @return array<int, string>
     */
    private static function vehicleCategoryOptions(): array
    {
        return VehicleCategory::query()
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (VehicleCategory $category): array => [
                $category->id => $category->translatedLabel(AdminLocale::current()),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private static function centreOptions(): array
    {
        return Centre::query()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (Centre $centre): array => [
                $centre->id => $centre->translatedName(AdminLocale::current()),
            ])
            ->all();
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
}
