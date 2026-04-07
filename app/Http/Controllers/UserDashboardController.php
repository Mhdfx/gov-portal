<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\FileUpload;
use App\Models\Notification;
use App\Models\UserProfile;
use App\Constants\AppConstants;
use Illuminate\Validation\Rule;
use Artesaos\SEOTools\Facades\SEOTools;

class UserDashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Set SEO meta tags
        SEOTools::setTitle('Tableau de Bord - ' . $user->username);
        SEOTools::setDescription('Gérez vos soumissions, projets et documents sur la plateforme Boiema.');
        SEOTools::setCanonical(url()->current());
        
        // Get user's submissions
        $submissions = [
            'investment' => InvestmentSubmission::where('user_id', $user->id)->latest()->get(),
            'project_carrier' => ProjectCarrierSubmission::where('user_id', $user->id)->latest()->get(),
            'idea_carrier' => IdeaCarrierSubmission::where('user_id', $user->id)->latest()->get(),
            'auto_entrepreneur' => AutoEntrepreneurSubmission::where('user_id', $user->id)->latest()->get(),
            'indh' => INDHSubmission::where('user_id', $user->id)->latest()->get(),
            'training' => TrainingSubmission::where('user_id', $user->id)->latest()->get(),
        ];
        
        // Get user's files
        $files = FileUpload::where('user_id', $user->id)->latest()->get();
        
        // Get recent notifications
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_submissions' => collect($submissions)->flatten()->count(),
            'pending_submissions' => collect($submissions)->flatten()->where('status', AppConstants::STATUS_PENDING)->count(),
            'approved_submissions' => collect($submissions)->flatten()->where('status', AppConstants::STATUS_APPROVED)->count(),
            'rejected_submissions' => collect($submissions)->flatten()->where('status', AppConstants::STATUS_REJECTED)->count(),
            'total_files' => $files->count(),
            'unread_notifications' => $notifications->where('is_read', false)->count(),
        ];
        
        return view('dashboard.user.index', compact('user', 'submissions', 'files', 'notifications', 'stats'));
    }
    
    /**
     * Show user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            $profile = $user->profile()->create([
                'first_name' => $user->username,
                'last_name' => '',
                'phone' => '',
                'address' => '',
                'city' => $user->city,
                'region' => $user->region,
                'country' => 'Morocco',
            ]);
        }
        
        SEOTools::setTitle('Mon Profil - ' . $user->username);
        SEOTools::setDescription('Gérez vos informations personnelles et paramètres de compte.');
        
        return view('dashboard.user.profile', compact('user', 'profile'));
    }
    
    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? $user->profile()->create([
            'first_name' => $user->username,
        ]);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $data = $request->only([
            'first_name',
            'last_name',
            'phone',
            'address',
            'city',
            'region',
            'postal_code',
            'country',
            'bio',
        ]);
        
        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) {
                Storage::disk('public')->delete($profile->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars/users', 'public');
        }
        
        $profile->update($data);
        
        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show user submissions.
     */
    public function submissions(Request $request)
    {
        $user = Auth::user();
        
        SEOTools::setTitle('My Submissions - ' . $user->username);
        SEOTools::setDescription('View and track all your submitted forms and applications.');
        
        // Collect all submission types
        $allSubmissions = collect([
            InvestmentSubmission::where('user_id', $user->id)->get()->map(function($item) {
                $item->submission_type = 'investment';
                $item->submission_type_label = 'Investment';
                return $item;
            }),
            ProjectCarrierSubmission::where('user_id', $user->id)->get()->map(function($item) {
                $item->submission_type = 'project_carrier';
                $item->submission_type_label = 'Project Carrier';
                return $item;
            }),
            IdeaCarrierSubmission::where('user_id', $user->id)->get()->map(function($item) {
                $item->submission_type = 'idea_carrier';
                $item->submission_type_label = 'Idea Carrier';
                return $item;
            }),
            AutoEntrepreneurSubmission::where('user_id', $user->id)->get()->map(function($item) {
                $item->submission_type = 'auto_entrepreneur';
                $item->submission_type_label = 'Auto-Entrepreneur';
                return $item;
            }),
            INDHSubmission::where('user_id', $user->id)->get()->map(function($item) {
                $item->submission_type = 'indh';
                $item->submission_type_label = 'INDH';
                return $item;
            }),
            TrainingSubmission::where('user_id', $user->id)->get()->map(function($item) {
                $item->submission_type = 'training';
                $item->submission_type_label = 'Training';
                return $item;
            }),
        ])->flatten();
        
        // Apply filters
        if ($request->filled('type')) {
            $allSubmissions = $allSubmissions->where('submission_type', $request->type);
        }
        
        if ($request->filled('status')) {
            $allSubmissions = $allSubmissions->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $allSubmissions = $allSubmissions->filter(function($item) use ($request) {
                return $item->created_at >= $request->date_from;
            });
        }
        
        if ($request->filled('date_to')) {
            $allSubmissions = $allSubmissions->filter(function($item) use ($request) {
                return $item->created_at <= $request->date_to . ' 23:59:59';
            });
        }
        
        // Sort by created_at descending
        $allSubmissions = $allSubmissions->sortByDesc('created_at');
        
        // Paginate manually
        $currentPage = $request->get('page', 1);
        $perPage = 20;
        $currentItems = $allSubmissions->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $submissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allSubmissions->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Calculate statistics
        $allSubmissionsForStats = collect([
            InvestmentSubmission::where('user_id', $user->id)->get(),
            ProjectCarrierSubmission::where('user_id', $user->id)->get(),
            IdeaCarrierSubmission::where('user_id', $user->id)->get(),
            AutoEntrepreneurSubmission::where('user_id', $user->id)->get(),
            INDHSubmission::where('user_id', $user->id)->get(),
            TrainingSubmission::where('user_id', $user->id)->get(),
        ])->flatten();
        
        $stats = [
            'total_submissions' => $allSubmissionsForStats->count(),
            'pending_submissions' => $allSubmissionsForStats->where('status', AppConstants::STATUS_PENDING)->count(),
            'approved_submissions' => $allSubmissionsForStats->where('status', AppConstants::STATUS_APPROVED)->count(),
            'rejected_submissions' => $allSubmissionsForStats->where('status', AppConstants::STATUS_REJECTED)->count(),
            'in_review_submissions' => $allSubmissionsForStats->where('status', 'in_review')->count(),
        ];
        
        return view('dashboard.user.submissions', compact('user', 'submissions', 'stats'));
    }
    
    /**
     * Show user files.
     */
    public function files(Request $request)
    {
        $user = Auth::user();
        
        SEOTools::setTitle('My Files - ' . $user->username);
        SEOTools::setDescription('Manage your uploaded documents and files.');
        
        $files = FileUpload::where('user_id', $user->id)
            ->latest()
            ->get();
        
        return view('dashboard.user.files', compact('user', 'files'));
    }

    /**
     * Display account settings.
     */
    public function settings()
    {
        $user = Auth::user();

        SEOTools::setTitle('Account Settings - ' . $user->username);
        SEOTools::setDescription('Configure your account preferences and security settings.');

        return view('dashboard.user.settings', compact('user'));
    }

    /**
     * Update account settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'region' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'current_password' => ['nullable', 'required_with:password'],
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
            $user->password = $request->password;
        }

        $user->username = $request->username;
        $user->email = $request->email;
        $user->region = $request->region;
        $user->city = $request->city;
        $user->save();

        return redirect()->route('user.settings')->with('success', 'Settings updated successfully.');
    }
    
    /**
     * Show user notifications.
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();

        SEOTools::setTitle('Notifications - ' . $user->username);
        SEOTools::setDescription('Stay updated on your submissions, files, and account changes.');

        $notificationsQuery = Notification::where('user_id', $user->id);

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $notificationsQuery->where('is_read', false);
            } elseif ($request->status === 'read') {
                $notificationsQuery->where('is_read', true);
            }
        }

        if ($request->filled('search')) {
            $notificationsQuery->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $notifications = $notificationsQuery->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ];

        return view('dashboard.user.notifications', compact('user', 'notifications', 'stats'));
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead(Notification $notification)
    {
        $user = Auth::user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }
}






















