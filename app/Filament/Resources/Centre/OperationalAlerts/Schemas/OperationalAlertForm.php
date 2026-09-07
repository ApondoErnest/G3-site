<?php

namespace App\Filament\Resources\Centre\OperationalAlerts\Schemas;

use App\Domain\Enums\AlertSeverity;
use App\Models\Centre\Centre;
use App\Support\AdminLocale;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperationalAlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.alerts.sections.scope'))
                    ->schema([
                        Select::make('centre_id')
                            ->label(__('admin.alerts.fields.centre'))
                            ->options(fn (): array => self::centreOptions())
                            ->placeholder(__('admin.alerts.fields.site_wide'))
                            ->searchable(),
                    ]),
                Section::make(__('admin.alerts.sections.content'))
                    ->schema([
                        Select::make('severity')
                            ->label(__('admin.alerts.fields.severity'))
                            ->options(collect(AlertSeverity::cases())->mapWithKeys(
                                fn (AlertSeverity $severity): array => [
                                    $severity->value => __('admin.alerts.severity.'.$severity->value),
                                ],
                            )->all())
                            ->required(),
                        AdminForm::bilingualTextarea('message', __('admin.alerts.fields.message')),
                    ]),
                Section::make(__('admin.alerts.sections.window'))
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label(__('admin.alerts.fields.starts_at'))
                            ->required()
                            ->native(false),
                        DateTimePicker::make('expires_at')
                            ->label(__('admin.alerts.fields.expires_at'))
                            ->native(false),
                        Toggle::make('is_active')
                            ->label(__('admin.alerts.fields.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
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
}
