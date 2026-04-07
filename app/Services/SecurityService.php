<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class SecurityService
{
    /**
     * Maximum login attempts before lockout
     */
    const MAX_LOGIN_ATTEMPTS = 5;
    
    /**
     * Lockout duration in minutes
     */
    const LOCKOUT_DURATION = 15;
    
    /**
     * Session timeout in minutes
     */
    const SESSION_TIMEOUT = 120;

    /**
     * Check if user is locked out
     */
    public function isUserLockedOut(string $identifier): bool
    {
        $key = 'login_attempts:' . $identifier;
        $attempts = Cache::get($key, 0);
        
        return $attempts >= self::MAX_LOGIN_ATTEMPTS;
    }

    /**
     * Record failed login attempt
     */
    public function recordFailedLogin(string $identifier, string $ip, string $userAgent): void
    {
        $key = 'login_attempts:' . $identifier;
        $attempts = Cache::get($key, 0) + 1;
        
        Cache::put($key, $attempts, now()->addMinutes(self::LOCKOUT_DURATION));
        
        // Log the attempt
        LoginAttempt::create([
            'identifier' => $identifier,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'success' => false,
            'attempted_at' => now(),
        ]);
        
        Log::warning('Failed login attempt', [
            'identifier' => $identifier,
            'ip' => $ip,
            'attempts' => $attempts,
        ]);
    }

    /**
     * Clear failed login attempts
     */
    public function clearFailedLogins(string $identifier): void
    {
        $key = 'login_attempts:' . $identifier;
        Cache::forget($key);
    }

    /**
     * Check password strength
     */
    public function checkPasswordStrength(string $password): array
    {
        $strength = 0;
        $feedback = [];
        
        // Length check
        if (strlen($password) >= 8) {
            $strength += 1;
        } else {
            $feedback[] = 'Password should be at least 8 characters long';
        }
        
        // Uppercase check
        if (preg_match('/[A-Z]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Password should contain at least one uppercase letter';
        }
        
        // Lowercase check
        if (preg_match('/[a-z]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Password should contain at least one lowercase letter';
        }
        
        // Number check
        if (preg_match('/[0-9]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Password should contain at least one number';
        }
        
        // Special character check
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $strength += 1;
        } else {
            $feedback[] = 'Password should contain at least one special character';
        }
        
        $level = match($strength) {
            0, 1 => 'very_weak',
            2 => 'weak',
            3 => 'fair',
            4 => 'good',
            5 => 'strong',
            default => 'very_weak',
        };
        
        return [
            'strength' => $strength,
            'level' => $level,
            'feedback' => $feedback,
            'is_acceptable' => $strength >= 3,
        ];
    }

    /**
     * Generate secure password
     */
    public function generateSecurePassword(int $length = 12): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        $all = $uppercase . $lowercase . $numbers . $special;
        
        for ($i = 4; $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }
        
        return str_shuffle($password);
    }

    /**
     * Check if IP is blocked
     */
    public function isIpBlocked(string $ip): bool
    {
        return Cache::has('blocked_ip:' . $ip);
    }

    /**
     * Block IP address
     */
    public function blockIp(string $ip, int $duration = 60): void
    {
        Cache::put('blocked_ip:' . $ip, true, now()->addMinutes($duration));
        
        Log::warning('IP address blocked', [
            'ip' => $ip,
            'duration' => $duration,
        ]);
    }

    /**
     * Unblock IP address
     */
    public function unblockIp(string $ip): void
    {
        Cache::forget('blocked_ip:' . $ip);
        
        Log::info('IP address unblocked', ['ip' => $ip]);
    }

    /**
     * Check for suspicious activity
     */
    public function checkSuspiciousActivity(string $ip, string $userAgent): bool
    {
        $key = 'suspicious_activity:' . $ip;
        $activity = Cache::get($key, []);
        
        // Check for multiple user agents from same IP
        if (!in_array($userAgent, $activity)) {
            $activity[] = $userAgent;
            Cache::put($key, $activity, now()->addHours(1));
        }
        
        // If more than 5 different user agents from same IP, it's suspicious
        return count($activity) > 5;
    }

    /**
     * Rate limit requests
     */
    public function rateLimit(string $key, int $maxAttempts = 60, int $decayMinutes = 1): bool
    {
        return RateLimiter::attempt($key, $maxAttempts, function () {
            // Request allowed
        }, $decayMinutes * 60);
    }

    /**
     * Generate CSRF token
     */
    public function generateCsrfToken(): string
    {
        return Str::random(40);
    }

    /**
     * Validate CSRF token
     */
    public function validateCsrfToken(string $token, string $sessionToken): bool
    {
        return hash_equals($sessionToken, $token);
    }

    /**
     * Encrypt sensitive data
     */
    public function encryptData(string $data): string
    {
        return encrypt($data);
    }

    /**
     * Decrypt sensitive data
     */
    public function decryptData(string $encryptedData): string
    {
        return decrypt($encryptedData);
    }

    /**
     * Hash sensitive data
     */
    public function hashData(string $data): string
    {
        return Hash::make($data);
    }

    /**
     * Verify hashed data
     */
    public function verifyHash(string $data, string $hash): bool
    {
        return Hash::check($data, $hash);
    }

    /**
     * Sanitize input data
     */
    public function sanitizeInput(string $input): string
    {
        // Remove HTML tags
        $input = strip_tags($input);
        
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }

    /**
     * Validate file upload
     */
    public function validateFileUpload(\Illuminate\Http\UploadedFile $file): array
    {
        $errors = [];
        
        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            $errors[] = 'File size exceeds 10MB limit';
        }
        
        // Check file type
        $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            $errors[] = 'File type not allowed';
        }
        
        // Check MIME type
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'image/gif',
        ];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'File MIME type not allowed';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get security audit log
     */
    public function getSecurityAuditLog(int $limit = 100): \Illuminate\Database\Eloquent\Collection
    {
        return LoginAttempt::orderBy('attempted_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up old security logs
     */
    public function cleanupSecurityLogs(int $days = 30): int
    {
        return LoginAttempt::where('attempted_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Check session security
     */
    public function checkSessionSecurity(Request $request): array
    {
        $issues = [];
        
        // Check if session is secure
        if (!$request->secure() && config('app.env') === 'production') {
            $issues[] = 'Session not using HTTPS';
        }
        
        // Check session timeout
        $lastActivity = $request->session()->get('last_activity');
        if ($lastActivity && now()->diffInMinutes($lastActivity) > self::SESSION_TIMEOUT) {
            $issues[] = 'Session timeout exceeded';
        }
        
        // Update last activity
        $request->session()->put('last_activity', now());
        
        return [
            'secure' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Generate secure random string
     */
    public function generateSecureRandomString(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Check for SQL injection patterns
     */
    public function checkSqlInjection(string $input): bool
    {
        $patterns = [
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b)/i',
            '/(\binsert\b.*\binto\b)/i',
            '/(\bupdate\b.*\bset\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\balter\b.*\btable\b)/i',
            '/(\bcreate\b.*\btable\b)/i',
            '/(\bexec\b|\bexecute\b)/i',
            '/(\bscript\b)/i',
            '/(\bjavascript\b)/i',
            '/(\bonload\b)/i',
            '/(\bonerror\b)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check for XSS patterns
     */
    public function checkXss(string $input): bool
    {
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
            '/<iframe\b[^>]*>/i',
            '/<object\b[^>]*>/i',
            '/<embed\b[^>]*>/i',
            '/<link\b[^>]*>/i',
            '/<meta\b[^>]*>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}






























