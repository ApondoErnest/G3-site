<?php

namespace App\Http\Middleware;

use App\Support\AdminLocale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(AdminLocale::resolveFromSession());

        return $next($request);
    }
}
