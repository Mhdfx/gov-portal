<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role !== $role) {
            // Redirect to their own dashboard
            $route = match($user->role) {
                'main_admin' => 'admin.dashboard',
                'institutional_admin' => 'institutional.dashboard',
                'sectoral_admin' => 'sectoral.dashboard',
                'company' => 'company.dashboard',
                'candidate' => 'candidate.dashboard',
                default => 'user.dashboard',
            };
            
            return redirect()->route($route);
        }

        return $next($request);
    }
}
