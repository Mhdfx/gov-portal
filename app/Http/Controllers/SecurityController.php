<?php

namespace App\Http\Controllers;

use App\Services\SecurityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    protected SecurityService $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Get security audit log
     */
    public function auditLog(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 100);
        $log = $this->securityService->getSecurityAuditLog($limit);
        
        return response()->json([
            'audit_log' => $log,
            'total' => $log->count(),
        ]);
    }

    /**
     * Check password strength
     */
    public function checkPasswordStrength(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->securityService->checkPasswordStrength($request->password);
        
        return response()->json($result);
    }

    /**
     * Generate secure password
     */
    public function generatePassword(Request $request): JsonResponse
    {
        $length = $request->get('length', 12);
        
        if ($length < 8 || $length > 32) {
            return response()->json(['error' => 'Password length must be between 8 and 32 characters'], 422);
        }

        $password = $this->securityService->generateSecurePassword($length);
        $strength = $this->securityService->checkPasswordStrength($password);
        
        return response()->json([
            'password' => $password,
            'strength' => $strength,
        ]);
    }

    /**
     * Block IP address
     */
    public function blockIp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
            'duration' => 'integer|min:1|max:1440', // max 24 hours
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $duration = $request->get('duration', 60);
        $this->securityService->blockIp($request->ip, $duration);
        
        return response()->json([
            'message' => 'IP address blocked successfully',
            'ip' => $request->ip,
            'duration' => $duration,
        ]);
    }

    /**
     * Unblock IP address
     */
    public function unblockIp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->securityService->unblockIp($request->ip);
        
        return response()->json([
            'message' => 'IP address unblocked successfully',
            'ip' => $request->ip,
        ]);
    }

    /**
     * Check if IP is blocked
     */
    public function checkIpStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $isBlocked = $this->securityService->isIpBlocked($request->ip);
        
        return response()->json([
            'ip' => $request->ip,
            'blocked' => $isBlocked,
        ]);
    }

    /**
     * Clean up security logs
     */
    public function cleanupLogs(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        
        if ($days < 1 || $days > 365) {
            return response()->json(['error' => 'Days must be between 1 and 365'], 422);
        }

        $deleted = $this->securityService->cleanupSecurityLogs($days);
        
        return response()->json([
            'message' => 'Security logs cleaned up successfully',
            'deleted_records' => $deleted,
            'days' => $days,
        ]);
    }

    /**
     * Get security statistics
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_login_attempts' => \App\Models\LoginAttempt::count(),
            'failed_login_attempts' => \App\Models\LoginAttempt::failed()->count(),
            'successful_login_attempts' => \App\Models\LoginAttempt::successful()->count(),
            'recent_failed_attempts' => \App\Models\LoginAttempt::failed()->recent(60)->count(),
            'unique_ips' => \App\Models\LoginAttempt::distinct('ip_address')->count(),
            'blocked_ips' => $this->getBlockedIpsCount(),
        ];
        
        return response()->json($stats);
    }

    /**
     * Get blocked IPs count
     */
    private function getBlockedIpsCount(): int
    {
        // This is a simplified implementation
        // In a real application, you might want to store blocked IPs in a database
        return 0;
    }

    /**
     * Validate file upload security
     */
    public function validateFileUpload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->securityService->validateFileUpload($request->file('file'));
        
        return response()->json($result);
    }

    /**
     * Generate CSRF token
     */
    public function generateCsrfToken(): JsonResponse
    {
        $token = $this->securityService->generateCsrfToken();
        
        return response()->json([
            'csrf_token' => $token,
        ]);
    }

    /**
     * Encrypt data
     */
    public function encryptData(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $encrypted = $this->securityService->encryptData($request->data);
        
        return response()->json([
            'encrypted_data' => $encrypted,
        ]);
    }

    /**
     * Decrypt data
     */
    public function decryptData(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'encrypted_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $decrypted = $this->securityService->decryptData($request->encrypted_data);
            
            return response()->json([
                'decrypted_data' => $decrypted,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to decrypt data'], 400);
        }
    }
}






























