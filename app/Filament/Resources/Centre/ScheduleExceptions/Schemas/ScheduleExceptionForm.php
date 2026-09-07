<?php

namespace App\Filament\Resources\Centre\ScheduleExceptions\Schemas;

use App\Models\Centre\Centre;
use App\Models\User;
use App\Support\AdminLocale;
use App\Support\CentreAccess;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ScheduleExceptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.exceptions.sections.scope'))
                    ->schema([
                        Toggle::make('applies_to_all_centres')
                            ->label(__('admin.exceptions.fields.all_centres'))
                            ->live()
                            ->visible(fn (): bool => auth()->user() instanceof User
                                && auth()->user()->hasRole(['super_admin', 'operations_admin'])),
                        Select::make('centre_id')
                            ->label(__('admin.exceptions.fields.centre'))
                            ->options(fn (): array => self::centreOptions())
                            ->required(fn (Get $get): bool => ! $get('applies_to_all_centres'))
                            ->visible(fn (Get $get): bool => ! $get('applies_to_all_centres'))
                            ->searchable(),
                    ]),
                Section::make(__('admin.exceptions.sections.schedule'))
                    ->schema([
                        DatePicker::make('starts_on')
                            ->label(__('admin.exceptions.fields.starts_on'))
                            ->required()
                            ->native(false),
                        DatePicker::make('ends_on')
                            ->label(__('admin.exceptions.fields.ends_on'))
                            ->native(false),
                        Toggle::make('is_open')
                            ->label(__('admin.exceptions.fields.is_open'))
                            ->live(),
                        TimePicker::make('opens_at')
                            ->label(__('admin.exceptions.fields.opens_at'))
                            ->seconds(false)
                            ->visible(fn (Get $get): bool => (bool) $get('is_open'))
                            ->required(fn (Get $get): bool => (bool) $get('is_open')),
                        TimePicker::make('closes_at')
                            ->label(__('admin.exceptions.fields.closes_at'))
                            ->seconds(false)
                            ->visible(fn (Get $get): bool => (bool) $get('is_open'))
                            ->required(fn (Get $get): bool => (bool) $get('is_open')),
                    ])
                    ->columns(2),
                Section::make(__('admin.exceptions.sections.reason'))
                    ->schema([
                        AdminForm::bilingualTextarea('reason', __('admin.exceptions.fields.reason')),
                    ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function centreOptions(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        return Centre::query()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (Centre $centre): bool => CentreAccess::userCanAccessCentre($user, $centre))
            ->mapWithKeys(fn (Centre $centre): array => [
                $centre->id => $centre->translatedName(AdminLocale::current()),
            ])
            ->all();
    }
}
