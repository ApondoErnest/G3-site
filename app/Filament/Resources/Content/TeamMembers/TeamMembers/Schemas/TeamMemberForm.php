<?php

namespace App\Filament\Resources\Content\TeamMembers\TeamMembers\Schemas;

use App\Support\Filament\AdminForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.content.team.sections.identity'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('admin.content.team.fields.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sort_order')
                            ->label(__('admin.content.team.fields.sort_order'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        Toggle::make('display_publicly')
                            ->label(__('admin.content.team.fields.display_publicly')),
                    ]),
                Section::make(__('admin.content.team.sections.content'))
                    ->schema([
                        AdminForm::bilingualText('role_title', __('admin.content.team.fields.role_title')),
                        AdminForm::bilingualTextarea('bio', __('admin.content.team.fields.bio')),
                    ]),
            ]);
    }
}
