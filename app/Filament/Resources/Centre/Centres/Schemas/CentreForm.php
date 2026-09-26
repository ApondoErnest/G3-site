<?php

namespace App\Filament\Resources\Centre\Centres\Schemas;

use App\Domain\Enums\CentreStatus;
use App\Models\Centre\Centre;
use App\Support\AdminLocale;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CentreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.centres.sections.identity'))
                    ->schema([
                        TextInput::make('code')
                            ->label(__('admin.centres.fields.code'))
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->dehydrated(fn (string $operation): bool => $operation === 'create')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->unique(Centre::class, 'code', ignoreRecord: true)
                            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                            ->maxLength(64),
                        AdminForm::bilingualText('name', __('admin.centres.fields.name')),
                        AdminForm::bilingualText('address', __('admin.centres.fields.address')),
                        AdminForm::bilingualText('landmark', __('admin.centres.fields.landmark')),
                        TextInput::make('email')
                            ->label(__('admin.centres.fields.email'))
                            ->email()
                            ->required(),
                        TextInput::make('secondary_email')
                            ->label(__('admin.centres.fields.secondary_email'))
                            ->email(),
                        TextInput::make('postal_code')
                            ->label(__('admin.centres.fields.postal_code')),
                    ])
                    ->columns(1),
                Section::make(__('admin.centres.sections.location'))
                    ->schema([
                        TextInput::make('latitude')
                            ->label(__('admin.centres.fields.latitude'))
                            ->numeric()
                            ->required(),
                        TextInput::make('longitude')
                            ->label(__('admin.centres.fields.longitude'))
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),
                Section::make(__('admin.centres.sections.operations'))
                    ->schema([
                        Select::make('status')
                            ->label(__('admin.centres.fields.status'))
                            ->options(collect(CentreStatus::cases())->mapWithKeys(
                                fn (CentreStatus $status): array => [
                                    $status->value => __('admin.centres.status.'.$status->value),
                                ],
                            )->all())
                            ->required(),
                        TextInput::make('sort_order')
                            ->label(__('admin.centres.fields.sort_order'))
                            ->numeric()
                            ->required(),
                        Toggle::make('holiday_default_open')
                            ->label(__('admin.centres.fields.holiday_default_open')),
                    ])
                    ->columns(2),
                Section::make(__('admin.centres.sections.seo'))
                    ->schema([
                        AdminForm::bilingualText('seo_title', __('admin.centres.fields.seo_title')),
                        AdminForm::bilingualTextarea('seo_description', __('admin.centres.fields.seo_description')),
                    ]),
            ]);
    }

    public static function recordTitleLabel(mixed $record): string
    {
        if ($record === null) {
            return '';
        }

        return $record->translatedName(AdminLocale::current());
    }
}
