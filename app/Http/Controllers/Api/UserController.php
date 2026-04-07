<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user()),
        ]);
    }

    /**
     * Update authenticated user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'current_password' => 'required_with:password|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 422);
            }
        }

        $updateData = [];

        if ($request->filled('username')) {
            $updateData['username'] = $request->username;
        }

        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Get user's submissions.
     */
    public function submissions(Request $request): JsonResponse
    {
        $user = $request->user();
        $submissions = collect();

        // Get all submission types for the user
        $autoEntrepreneur = $user->autoEntrepreneurSubmissions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->submission_type = 'auto_entrepreneur';
                return $item;
            });

        $porteurIdee = $user->porteurIdeeSubmissions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->submission_type = 'porteur_idee';
                return $item;
            });

        $porteurProjet = $user->porteurProjetSubmissions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->submission_type = 'porteur_projet';
                return $item;
            });

        $investment = $user->investmentSubmissions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->submission_type = 'investment';
                return $item;
            });

        $indh = $user->indhSubmissions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->submission_type = 'indh';
                return $item;
            });

        $training = $user->trainingSubmissions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->submission_type = 'training';
                return $item;
            });

        $submissions = $submissions
            ->merge($autoEntrepreneur)
            ->merge($porteurIdee)
            ->merge($porteurProjet)
            ->merge($investment)
            ->merge($indh)
            ->merge($training)
            ->sortByDesc('created_at');

        return response()->json([
            'success' => true,
            'data' => $submissions->values(),
        ]);
    }

    /**
     * Get user's files.
     */
    public function files(Request $request): JsonResponse
    {
        $user = $request->user();
        $files = $user->fileUploads()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }

    /**
     * Get user's notifications.
     */
    public function notifications(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        
        $notification->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Get user dashboard data.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $dashboardData = [
            'user' => new UserResource($user),
            'submissions_count' => $user->autoEntrepreneurSubmissions()->count() +
                                 $user->porteurIdeeSubmissions()->count() +
                                 $user->porteurProjetSubmissions()->count() +
                                 $user->investmentSubmissions()->count() +
                                 $user->indhSubmissions()->count() +
                                 $user->trainingSubmissions()->count(),
            'files_count' => $user->fileUploads()->count(),
            'unread_notifications' => $user->notifications()->whereNull('read_at')->count(),
        ];

        // Add role-specific data
        if ($user->role === 'company') {
            $company = $user->company;
            if ($company) {
                $dashboardData['company'] = $company;
                $dashboardData['job_listings_count'] = $company->jobListings()->count();
                $dashboardData['products_count'] = $company->products()->count();
            }
        } elseif ($user->role === 'user') {
            $candidate = $user->candidate;
            if ($candidate) {
                $dashboardData['candidate'] = $candidate;
                $dashboardData['job_applications_count'] = $candidate->jobApplications()->count();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $dashboardData,
        ]);
    }
}