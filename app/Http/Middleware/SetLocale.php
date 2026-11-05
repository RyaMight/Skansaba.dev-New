<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale')
            ?? $request->cookie('locale')
            ?? config('app.locale', 'id');

        if (!in_array($locale, ['id', 'en'], true)) {
            $locale = 'id';
        }

        App::setLocale($locale);
        
        // Synchronize Carbon date formatting language
        \Carbon\Carbon::setLocale($locale === 'en' ? 'en' : 'id');

        return $next($request);
    }
}
