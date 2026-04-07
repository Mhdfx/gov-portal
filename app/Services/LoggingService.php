<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoggingService
{
    /**
     * Log an action to the system logs
     */
    public function log(string $action, string $description, array $context = [], string $level = AppConstants::LOG_LEVEL_INFO): void
    {
        try {
            SystemLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
                'level' => $level,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => json_encode($context),
            ]);

            // Also log to Laravel's log system
            Log::log($level, $description, array_merge([
                'action' => $action,
                'user_id' => Auth::id(),
            ], $context));
        } catch (\Exception $e) {
            // Fallback to Laravel log if database logging fails
            Log::error('Failed to log to database', [
                'error' => $e->getMessage(),
                'action' => $action,
                'description' => $description,
            ]);
        }
    }

    /**
     * Log a form submission
     */
    public function logFormSubmission(string $formType, int $submissionId, array $context = []): void
    {
        $this->log(
            AppConstants::LOG_ACTION_SUBMISSION,
            "Form submission: {$formType} (ID: {$submissionId})",
            array_merge(['form_type' => $formType, 'submission_id' => $submissionId], $context),
            AppConstants::LOG_LEVEL_INFO
        );
    }

    /**
     * Log an authentication attempt
     */
    public function logAuthAttempt(string $action, bool $success, string $username = null, array $context = []): void
    {
        $level = $success ? AppConstants::LOG_LEVEL_INFO : AppConstants::LOG_LEVEL_WARNING;
        $description = $success 
            ? "Successful {$action}" 
            : "Failed {$action}" . ($username ? " for user: {$username}" : '');

        $this->log(
            $action === 'login' ? AppConstants::LOG_ACTION_LOGIN : AppConstants::LOG_ACTION_LOGOUT,
            $description,
            array_merge(['success' => $success, 'username' => $username], $context),
            $level
        );
    }

    /**
     * Log a file upload
     */
    public function logFileUpload(string $fileName, string $fileType, int $fileSize, array $context = []): void
    {
        $this->log(
            AppConstants::LOG_ACTION_FILE_UPLOAD,
            "File uploaded: {$fileName} ({$fileType}, {$fileSize} bytes)",
            array_merge([
                'file_name' => $fileName,
                'file_type' => $fileType,
                'file_size' => $fileSize,
            ], $context),
            AppConstants::LOG_LEVEL_INFO
        );
    }

    /**
     * Log an approval action
     */
    public function logApproval(string $type, int $id, string $status, array $context = []): void
    {
        $action = $status === AppConstants::STATUS_APPROVED 
            ? AppConstants::LOG_ACTION_APPROVAL 
            : AppConstants::LOG_ACTION_REJECTION;

        $this->log(
            $action,
            "{$type} {$status}: ID {$id}",
            array_merge(['type' => $type, 'id' => $id, 'status' => $status], $context),
            AppConstants::LOG_LEVEL_INFO
        );
    }

    /**
     * Log a profile update
     */
    public function logProfileUpdate(array $changes, array $context = []): void
    {
        $this->log(
            AppConstants::LOG_ACTION_PROFILE_UPDATE,
            'Profile updated',
            array_merge(['changes' => $changes], $context),
            AppConstants::LOG_LEVEL_INFO
        );
    }

    /**
     * Log an error
     */
    public function logError(string $message, \Throwable $exception = null, array $context = []): void
    {
        $context = array_merge([
            'error_message' => $exception?->getMessage(),
            'error_trace' => $exception?->getTraceAsString(),
        ], $context);

        $this->log(
            'error',
            $message,
            $context,
            AppConstants::LOG_LEVEL_ERROR
        );
    }

    /**
     * Log a security event
     */
    public function logSecurityEvent(string $event, string $description, string $riskLevel = 'medium', array $context = []): void
    {
        $level = match($riskLevel) {
            'high', 'critical' => AppConstants::LOG_LEVEL_CRITICAL,
            'medium' => AppConstants::LOG_LEVEL_WARNING,
            default => AppConstants::LOG_LEVEL_INFO,
        };

        $this->log(
            'security_event',
            "[SECURITY] {$event}: {$description}",
            array_merge(['event' => $event, 'risk_level' => $riskLevel], $context),
            $level
        );
    }
}














