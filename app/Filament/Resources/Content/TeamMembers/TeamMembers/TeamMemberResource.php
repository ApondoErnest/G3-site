<?php

namespace App\Filament\Resources\Content\TeamMembers\TeamMembers;

use App\Filament\Resources\Content\TeamMembers\TeamMembers\Pages\CreateTeamMember;
use App\Filament\Resources\Content\TeamMembers\TeamMembers\Pages\EditTeamMember;
use App\Filament\Resources\Content\TeamMembers\TeamMembers\Pages\ListTeamMembers;
use App\Filament\Resources\Content\TeamMembers\TeamMembers\Schemas\TeamMemberForm;
use App\Filament\Resources\Content\TeamMembers\TeamMembers\Tables\TeamMembersTable;
use App\Models\Content\TeamMember;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.content.team.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.content.team.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.content.team.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof TeamMember) {
            return null;
        }

        return $record->name;
    }

    public static function form(Schema $schema): Schema
    {
        return TeamMemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamMembersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeamMembers::route('/'),
            'create' => CreateTeamMember::route('/create'),
            'edit' => EditTeamMember::route('/{record}/edit'),
        ];
    }
}
