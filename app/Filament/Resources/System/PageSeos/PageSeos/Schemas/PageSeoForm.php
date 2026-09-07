<?php

namespace App\Filament\Resources\System\PageSeos\PageSeos\Schemas;

use App\Domain\Enums\ContentPage;
use App\Support\Filament\AdminForm;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageSeoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.content.page_seo.sections.identity'))
                    ->schema([
                        TextInput::make('page')
                            ->label(__('admin.content.page_seo.fields.page'))
                            ->formatStateUsing(fn ($state): string => self::pageLabel($state))
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                Section::make(__('admin.content.page_seo.sections.seo'))
                    ->schema([
                        AdminForm::bilingualText('seo_title', __('admin.content.page_seo.fields.seo_title')),
                        AdminForm::bilingualTextarea('seo_description', __('admin.content.page_seo.fields.seo_description')),
                    ]),
            ]);
    }

    private static function pageLabel(mixed $state): string
    {
        if ($state instanceof ContentPage) {
            return __('admin.content.pages.'.$state->value);
        }

        if (is_string($state) && $state !== '') {
            return __('admin.content.pages.'.$state);
        }

        return '';
    }
}
