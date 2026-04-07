<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationService
{
    /**
     * Send a notification to a user
     */
    public function sendNotification(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = [],
        string $priority = 'medium',
        ?\DateTime $expiresAt = null
    ): Notification {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $priority,
            'expires_at' => $expiresAt,
        ]);

        // Send email notification for high priority notifications
        if ($priority === 'high') {
            $this->sendEmailNotification($userId, $notification);
        }

        // Log notification
        Log::info('Notification sent', [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
        ]);

        return $notification;
    }

    /**
     * Send notification to multiple users
     */
    public function sendBulkNotification(
        array $userIds,
        string $type,
        string $title,
        string $message,
        array $data = [],
        string $priority = 'medium',
        ?\DateTime $expiresAt = null
    ): array {
        $notifications = [];

        foreach ($userIds as $userId) {
            $notifications[] = $this->sendNotification(
                $userId,
                $type,
                $title,
                $message,
                $data,
                $priority,
                $expiresAt
            );
        }

        return $notifications;
    }

    /**
     * Send notification to users by role
     */
    public function sendNotificationToRole(
        string $role,
        string $type,
        string $title,
        string $message,
        array $data = [],
        string $priority = 'medium',
        ?\DateTime $expiresAt = null
    ): array {
        $userIds = User::where('role', $role)->pluck('id')->toArray();
        
        return $this->sendBulkNotification(
            $userIds,
            $type,
            $title,
            $message,
            $data,
            $priority,
            $expiresAt
        );
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(int $userId, Notification $notification): void
    {
        try {
            $user = User::find($userId);
            if ($user && $user->email) {
                Mail::to($user->email)->send(new NotificationMail($notification));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Delete notification
     */
    public function deleteNotification(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->delete();
            return true;
        }

        return false;
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(int $userId, int $limit = 20, bool $unreadOnly = false)
    {
        $query = Notification::where('user_id', $userId)
            ->notExpired()
            ->orderBy('created_at', 'desc');

        if ($unreadOnly) {
            $query->unread();
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get unread notification count for user
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->notExpired()
            ->count();
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpiredNotifications(): int
    {
        return Notification::where('expires_at', '<', now())->delete();
    }

    /**
     * Send submission status notification
     */
    public function sendSubmissionStatusNotification(
        int $userId,
        string $submissionType,
        string $status,
        array $submissionData = []
    ): Notification {
        $title = match($status) {
            'approved' => 'Submission Approved',
            'rejected' => 'Submission Rejected',
            'in_review' => 'Submission Under Review',
            'pending' => 'Submission Received',
            default => 'Submission Status Update',
        };

        $message = match($status) {
            'approved' => "Your {$submissionType} submission has been approved.",
            'rejected' => "Your {$submissionType} submission has been rejected.",
            'in_review' => "Your {$submissionType} submission is currently under review.",
            'pending' => "Your {$submissionType} submission has been received and is pending review.",
            default => "Your {$submissionType} submission status has been updated.",
        };

        $priority = match($status) {
            'approved' => 'high',
            'rejected' => 'high',
            default => 'medium',
        };

        return $this->sendNotification(
            $userId,
            "submission_{$status}",
            $title,
            $message,
            array_merge($submissionData, ['submission_type' => $submissionType]),
            $priority
        );
    }

    /**
     * Send company approval notification
     */
    public function sendCompanyApprovalNotification(
        int $userId,
        string $status,
        string $companyName
    ): Notification {
        $title = match($status) {
            'approved' => 'Company Approved',
            'rejected' => 'Company Rejected',
            default => 'Company Status Update',
        };

        $message = match($status) {
            'approved' => "Your company '{$companyName}' has been approved.",
            'rejected' => "Your company '{$companyName}' has been rejected.",
            default => "Your company '{$companyName}' status has been updated.",
        };

        $priority = match($status) {
            'approved' => 'high',
            'rejected' => 'high',
            default => 'medium',
        };

        return $this->sendNotification(
            $userId,
            "company_{$status}",
            $title,
            $message,
            ['company_name' => $companyName],
            $priority
        );
    }

    /**
     * Send system maintenance notification
     */
    public function sendMaintenanceNotification(
        string $message,
        ?\DateTime $scheduledAt = null
    ): array {
        $title = 'System Maintenance';
        $priority = 'high';
        $expiresAt = $scheduledAt ? $scheduledAt->add(new \DateInterval('P1D')) : null;

        return $this->sendNotificationToRole(
            'main_admin',
            'system_maintenance',
            $title,
            $message,
            ['scheduled_at' => $scheduledAt],
            $priority,
            $expiresAt
        );
    }

    /**
     * Send job application notification
     */
    public function sendJobApplicationNotification(
        int $companyUserId,
        string $candidateName,
        string $jobTitle
    ): Notification {
        return $this->sendNotification(
            $companyUserId,
            'job_application',
            'New Job Application',
            "{$candidateName} has applied for the position: {$jobTitle}",
            [
                'candidate_name' => $candidateName,
                'job_title' => $jobTitle,
            ],
            'medium'
        );
    }

    /**
     * Send newsletter notification
     */
    public function sendNewsletterNotification(
        string $title,
        string $content
    ): array {
        $userIds = User::where('role', '!=', 'main_admin')->pluck('id')->toArray();
        
        return $this->sendBulkNotification(
            $userIds,
            'newsletter',
            $title,
            $content,
            ['newsletter_title' => $title],
            'low'
        );
    }
}






























