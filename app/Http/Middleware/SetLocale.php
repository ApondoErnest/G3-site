<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if (! is_string($locale) || ! in_array($locale, config('locale.supported'), true)) {
            abort(404);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
