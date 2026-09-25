<?php

namespace App\Filament\Resources\System\Users\Users;

use App\Domain\Enums\AdminRole;
use App\Filament\Resources\System\Users\Users\Pages\CreateUser;
use App\Filament\Resources\System\Users\Users\Pages\EditUser;
use App\Filament\Resources\System\Users\Users\Pages\ListUsers;
use App\Filament\Resources\System\Users\Users\Schemas\UserForm;
use App\Filament\Resources\System\Users\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.users.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.users.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.users.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof User) {
            return null;
        }

        return $record->name;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function canDelete(Model $record): bool
    {
        if (! $record instanceof User || ! parent::canDelete($record)) {
            return false;
        }

        if (! $record->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        return User::query()
            ->role(AdminRole::SuperAdmin->value)
            ->where('is_active', true)
            ->whereKeyNot($record->id)
            ->exists();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
