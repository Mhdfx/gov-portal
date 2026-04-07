<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get user notifications
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = $request->get('limit', 20);
        $unreadOnly = $request->boolean('unread_only', false);

        $notifications = $this->notificationService->getUserNotifications(
            $user->id,
            $limit,
            $unreadOnly
        );

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user->id),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        
        $success = $this->notificationService->markAsRead($id, $user->id);
        
        if ($success) {
            return response()->json(['message' => 'Notification marked as read']);
        }
        
        return response()->json(['error' => 'Notification not found'], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $count = $this->notificationService->markAllAsRead($user->id);
        
        return response()->json([
            'message' => "Marked {$count} notifications as read"
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        
        $success = $this->notificationService->deleteNotification($id, $user->id);
        
        if ($success) {
            return response()->json(['message' => 'Notification deleted']);
        }
        
        return response()->json(['error' => 'Notification not found'], 404);
    }

    /**
     * Get unread notification count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $count = $this->notificationService->getUnreadCount($user->id);
        
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Send notification (admin only)
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'in:low,medium,high',
            'expires_at' => 'nullable|date',
        ]);

        $notification = $this->notificationService->sendNotification(
            $request->user_id,
            $request->type,
            $request->title,
            $request->message,
            $request->get('data', []),
            $request->get('priority', 'medium'),
            $request->expires_at ? new \DateTime($request->expires_at) : null
        );

        return response()->json([
            'message' => 'Notification sent successfully',
            'notification' => $notification,
        ]);
    }

    /**
     * Send bulk notification (admin only)
     */
    public function sendBulk(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'in:low,medium,high',
            'expires_at' => 'nullable|date',
        ]);

        $notifications = $this->notificationService->sendBulkNotification(
            $request->user_ids,
            $request->type,
            $request->title,
            $request->message,
            $request->get('data', []),
            $request->get('priority', 'medium'),
            $request->expires_at ? new \DateTime($request->expires_at) : null
        );

        return response()->json([
            'message' => 'Notifications sent successfully',
            'count' => count($notifications),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Send notification to role (admin only)
     */
    public function sendToRole(Request $request): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|in:user,company,institutional_admin,sectoral_admin,main_admin',
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'in:low,medium,high',
            'expires_at' => 'nullable|date',
        ]);

        $notifications = $this->notificationService->sendNotificationToRole(
            $request->role,
            $request->type,
            $request->title,
            $request->message,
            $request->get('data', []),
            $request->get('priority', 'medium'),
            $request->expires_at ? new \DateTime($request->expires_at) : null
        );

        return response()->json([
            'message' => 'Notifications sent successfully',
            'count' => count($notifications),
            'notifications' => $notifications,
        ]);
    }
}






























