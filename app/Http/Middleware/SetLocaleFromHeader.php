<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('services.tmdb.locales');

        $locale = $request->getPreferredLanguage($supported)
            ?? $supported[0];

        app()->setLocale($locale);

        return $next($request);
    }
}
