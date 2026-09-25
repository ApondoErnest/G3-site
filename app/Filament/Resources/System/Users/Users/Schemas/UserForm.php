<?php

namespace App\Filament\Resources\System\Users\Users\Schemas;

use App\Domain\Enums\AdminRole;
use App\Models\Centre\Centre;
use App\Models\User;
use App\Support\AdminLocale;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.users.sections.account'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('admin.users.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => __('admin.users.errors.required'),
                            ]),
                        TextInput::make('email')
                            ->label(__('admin.users.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => __('admin.users.errors.required'),
                                'email' => __('admin.users.errors.email'),
                                'unique' => __('admin.users.errors.unique'),
                            ]),
                        TextInput::make('password')
                            ->label(__('admin.users.fields.password'))
                            ->password()
                            ->revealable()
                            ->nullable()
                            ->rule(Password::min(12)->mixedCase()->numbers())
                            ->same('password_confirmation')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (?User $record): bool => $record === null)
                            ->validationMessages([
                                'required' => __('admin.users.errors.required'),
                                'same' => __('admin.users.errors.password_confirmation'),
                                'password.min' => __('admin.users.errors.password'),
                                'password.mixed' => __('admin.users.errors.password'),
                                'password.numbers' => __('admin.users.errors.password'),
                            ]),
                        TextInput::make('password_confirmation')
                            ->label(__('admin.users.fields.password_confirmation'))
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->required(fn (?User $record): bool => $record === null)
                            ->validationMessages([
                                'required' => __('admin.users.errors.required'),
                            ]),
                        Toggle::make('is_active')
                            ->label(__('admin.users.fields.is_active'))
                            ->default(true),
                    ]),
                Section::make(__('admin.users.sections.access'))
                    ->schema([
                        Select::make('roles')
                            ->label(__('admin.users.fields.roles'))
                            ->options(self::roleOptions())
                            ->multiple()
                            ->required()
                            ->live()
                            ->native(false)
                            ->validationMessages([
                                'required' => __('admin.users.errors.required'),
                            ]),
                        Select::make('centre_ids')
                            ->label(__('admin.users.fields.centres'))
                            ->options(fn (): array => self::centreOptions())
                            ->multiple()
                            ->native(false)
                            ->visible(fn (Get $get): bool => self::needsCentre($get('roles')))
                            ->required(fn (Get $get): bool => self::needsCentre($get('roles')))
                            ->validationMessages([
                                'required' => __('admin.users.errors.required'),
                            ]),
                    ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public static function roleOptions(): array
    {
        return collect(AdminRole::cases())
            ->mapWithKeys(fn (AdminRole $role): array => [
                $role->value => __('admin.users.roles.'.$role->value),
            ])
            ->all();
    }

    public static function needsCentre(mixed $roles): bool
    {
        $selected = is_array($roles) ? $roles : [];

        return array_intersect($selected, [
            AdminRole::CentreManager->value,
            AdminRole::ReceptionOfficer->value,
        ]) !== [];
    }

    /**
     * @return array<int, string>
     */
    private static function centreOptions(): array
    {
        return Centre::query()
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (Centre $centre): array => [
                $centre->id => $centre->translatedName(AdminLocale::current()),
            ])
            ->all();
    }
}
