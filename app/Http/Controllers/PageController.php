<?php

namespace App\Http\Controllers;

use App\Support\PublicNavigation;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __invoke(string $page): View
    {
        return view('pages.shell', [
            'page' => $page,
            'locale' => app()->getLocale(),
            'pageTitle' => PublicNavigation::pageTitle($page),
        ]);
    }
}
