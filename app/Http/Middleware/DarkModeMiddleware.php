<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DarkModeMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Detects dark mode preference from cookie or user preference
     */
    public function handle(Request $request, Closure $next)
    {
        // Get dark mode preference from cookie or default to false
        $darkMode = $request->cookie('dark_mode', 'false') === 'true';
        
        // Share with all views
        View::share('darkMode', $darkMode);
        
        $response = $next($request);
        
        return $response;
    }
}














