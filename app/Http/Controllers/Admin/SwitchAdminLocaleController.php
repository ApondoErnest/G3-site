<?php

namespace App\Http\Controllers\Admin;

use App\Support\AdminLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchAdminLocaleController
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        AdminLocale::set($locale);

        $redirect = $request->query('redirect');

        if (is_string($redirect) && str_starts_with($redirect, url('/admin'))) {
            return redirect()->to($redirect);
        }

        return redirect()->back(fallback: url('/admin'));
    }
}
