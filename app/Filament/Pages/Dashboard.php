<?php

namespace App\Filament\Pages;

use App\Actions\Admin\ResolveDashboardData;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.dashboard');
    }

    public function getTitle(): string
    {
        return __('admin.nav.dashboard');
    }

    public function getSubheading(): ?string
    {
        return app(ResolveDashboardData::class)(auth()->user())->displayDateLine;
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('filament.pages.dashboard')
                    ->viewData(fn (): array => [
                        'dashboard' => app(ResolveDashboardData::class)(auth()->user()),
                    ]),
            ]);
    }
}
