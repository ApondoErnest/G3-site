<?php

namespace App\Filament\Livewire;

use App\Support\AdminLocale;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AdminLocaleSwitcher extends Component
{
    public function switchLocale(string $locale): void
    {
        AdminLocale::set($locale);

        $target = url()->current();

        if (! str_starts_with($target, url('/admin'))) {
            $target = url('/admin');
        }

        $this->redirect($target, navigate: false);
    }

    public function render(): View
    {
        return view('filament.livewire.admin-locale-switcher');
    }
}
