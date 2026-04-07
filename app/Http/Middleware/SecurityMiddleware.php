<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityService;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    protected SecurityService $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Check if IP is blocked
        if ($this->securityService->isIpBlocked($ip)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Check for suspicious activity
        if ($this->securityService->checkSuspiciousActivity($ip, $userAgent)) {
            $this->securityService->blockIp($ip, 60); // Block for 1 hour
            return response()->json(['error' => 'Suspicious activity detected'], 403);
        }

        // Rate limiting
        $rateLimitKey = 'rate_limit:' . $ip;
        if (!$this->securityService->rateLimit($rateLimitKey, 100, 1)) {
            return response()->json(['error' => 'Too many requests'], 429);
        }

        // Check session security
        $sessionSecurity = $this->securityService->checkSessionSecurity($request);
        if (!$sessionSecurity['secure']) {
            // Log security issues but don't block the request
            \Log::warning('Session security issues detected', [
                'ip' => $ip,
                'issues' => $sessionSecurity['issues'],
            ]);
        }

        // Sanitize input data
        $this->sanitizeRequestData($request);

        return $next($request);
    }

    /**
     * Sanitize request data
     */
    private function sanitizeRequestData(Request $request): void
    {
        $input = $request->all();
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Check for SQL injection
                if ($this->securityService->checkSqlInjection($value)) {
                    \Log::warning('SQL injection attempt detected', [
                        'ip' => $request->ip(),
                        'input' => $key,
                        'value' => $value,
                    ]);
                    continue;
                }
                
                // Check for XSS
                if ($this->securityService->checkXss($value)) {
                    \Log::warning('XSS attempt detected', [
                        'ip' => $request->ip(),
                        'input' => $key,
                        'value' => $value,
                    ]);
                    continue;
                }
                
                // Sanitize the input
                $sanitized = $this->securityService->sanitizeInput($value);
                $request->merge([$key => $sanitized]);
            }
        }
    }
}






























