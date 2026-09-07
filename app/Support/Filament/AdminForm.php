<?php

namespace App\Support\Filament;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

final class AdminForm
{
    public static function bilingualText(string $field, string $label): Tabs
    {
        return Tabs::make($field)
            ->tabs([
                Tab::make(__('admin.forms.tab_fr'))
                    ->schema([
                        TextInput::make("{$field}.fr")
                            ->label($label)
                            ->required(),
                    ]),
                Tab::make(__('admin.forms.tab_en'))
                    ->schema([
                        TextInput::make("{$field}.en")
                            ->label($label)
                            ->required(),
                    ]),
            ]);
    }

    public static function bilingualTextarea(string $field, string $label): Tabs
    {
        return Tabs::make($field)
            ->tabs([
                Tab::make(__('admin.forms.tab_fr'))
                    ->schema([
                        Textarea::make("{$field}.fr")
                            ->label($label)
                            ->rows(3),
                    ]),
                Tab::make(__('admin.forms.tab_en'))
                    ->schema([
                        Textarea::make("{$field}.en")
                            ->label($label)
                            ->rows(3),
                    ]),
            ]);
    }

    /**
     * @param  array<string, mixed>|string|null  $value
     * @return array<string, string>
     */
    public static function bilingualState(array|string|null $value): array
    {
        if (is_string($value)) {
            $value = json_decode($value, true) ?? [];
        }

        if (! is_array($value)) {
            return ['fr' => '', 'en' => ''];
        }

        return [
            'fr' => (string) ($value['fr'] ?? ''),
            'en' => (string) ($value['en'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>|null  $value
     * @return array<string, string>
     */
    public static function normalizeBilingual(?array $value): array
    {
        return [
            'fr' => (string) ($value['fr'] ?? ''),
            'en' => (string) ($value['en'] ?? ''),
        ];
    }
}
