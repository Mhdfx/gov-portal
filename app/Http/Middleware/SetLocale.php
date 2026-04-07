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
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session or default to French
        $locale = Session::get('locale', config('app.locale', 'fr'));
        
        // Validate locale
        $supportedLocales = ['fr', 'ar', 'en'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'fr';
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        return $next($request);
    }
}
