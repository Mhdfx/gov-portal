<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\Notification;
use App\Models\SystemLog;
use SEO;
use Carbon\Carbon;

class InstitutionalDashboardController extends Controller
{
    /**
     * Show the institutional admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Set SEO meta tags
        SEO::setTitle('Administration Institutionnelle - Tableau de Bord');
        SEO::setDescription('Gérez les soumissions et projets de votre institution.');
        SEO::setCanonical(url()->current());
        
        // Get institution-specific statistics
        $stats = [
            'total_submissions' => $this->getInstitutionSubmissions()->count(),
            'pending_submissions' => $this->getInstitutionSubmissions()->where('status', 'pending')->count(),
            'approved_submissions' => $this->getInstitutionSubmissions()->where('status', 'approved')->count(),
            'rejected_submissions' => $this->getInstitutionSubmissions()->where('status', 'rejected')->count(),
            'under_review_submissions' => $this->getInstitutionSubmissions()->where('status', 'under_review')->count(),
        ];
        
        // Get recent submissions for this institution
        $recentSubmissions = $this->getInstitutionSubmissions()->sortByDesc('created_at')->take(20);
        
        // Get notifications
        $notifications = Notification::where('type', 'like', 'institutional_%')
            ->latest()
            ->limit(20)
            ->get();
        
        return view('dashboard.institutional.index', compact('user', 'stats', 'recentSubmissions', 'notifications'));
    }
    
    /**
     * Show submission management for institution.
     */
    public function submissions()
    {
        $user = Auth::user();
        
        SEO::setTitle('Gestion des Soumissions - Institution');
        SEO::setDescription('Gérez les soumissions de votre institution.');
        
        $submissions = $this->getInstitutionSubmissions()->sortByDesc('created_at');
        
        return view('dashboard.institutional.submissions', compact('user', 'submissions'));
    }
    
    /**
     * Show specific submission details.
     */
    public function showSubmission($type, $id)
    {
        $user = Auth::user();
        $submission = $this->getSubmissionByType($type, $id);
        
        if (!$submission) {
            return redirect()->back()->with('error', 'Soumission non trouvée.');
        }
        
        // Load reviewer if exists
        if ($submission->reviewed_by) {
            $submission->load('reviewer');
        }
        
        SEO::setTitle('Submission Details - ' . ($submission->submission_number ?? '#' . $submission->id));
        SEO::setDescription('Review and manage this submission.');
        
        return view('dashboard.institutional.submission-details', compact('user', 'submission', 'type'));
    }
    
    /**
     * Update submission status.
     */
    public function updateSubmissionStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,under_review',
            'institutional_notes' => 'nullable|string|max:1000',
            'next_steps' => 'nullable|string|max:1000',
        ]);
        
        $submission = $this->getSubmissionByType($type, $id);
        
        if (!$submission) {
            return redirect()->back()->with('error', 'Soumission non trouvée.');
        }
        
        $submission->update([
            'status' => $request->status,
            'institutional_notes' => $request->institutional_notes,
            'next_steps' => $request->next_steps,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        // Create notification
        Notification::create([
            'user_id' => $submission->user_id,
            'type' => 'submission_status_updated',
            'title' => 'Statut de Soumission Mis à Jour',
            'message' => 'Le statut de votre soumission a été mis à jour par l\'institution: ' . $request->status,
            'is_read' => false,
        ]);
        
        // Log the action
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'institutional_submission_review',
            'description' => 'Institutional admin reviewed submission ' . $type . ' #' . $id . ' with status: ' . $request->status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'Statut de la soumission mis à jour avec succès.');
    }
    
    /**
     * Show reports and analytics.
     */
    public function reports()
    {
        $user = Auth::user();
        
        SEO::setTitle('Rapports et Analyses - Institution');
        SEO::setDescription('Consultez les rapports et analyses de votre institution.');
        
        $submissions = $this->getInstitutionSubmissions();
        
        // Generate reports
        $reports = [
            'submissions_by_type' => $this->getSubmissionsByType($submissions),
            'submissions_by_status' => $this->getSubmissionsByStatus($submissions),
            'submissions_by_month' => $this->getSubmissionsByMonth($submissions),
            'submissions_by_region' => $this->getSubmissionsByRegion($submissions),
        ];
        
        return view('dashboard.institutional.reports', compact('user', 'reports'));
    }
    
    /**
     * Show notifications.
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        
        SEO::setTitle('Notifications | Institutional Admin');
        SEO::setDescription('Manage and monitor institutional notifications and alerts.');
        
        $query = Notification::where('type', 'like', 'institutional_%')
            ->orWhere('user_id', $user->id)
            ->with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'priority':
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")->orderByDesc('created_at');
                break;
            case 'type':
                $query->orderBy('type')->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $notifications = $query->paginate(30)->withQueryString();

        // Statistics
        $stats = [
            'total' => Notification::where('type', 'like', 'institutional_%')
                ->orWhere('user_id', $user->id)
                ->count(),
            'unread' => Notification::where(function($q) use ($user) {
                $q->where('type', 'like', 'institutional_%')
                  ->orWhere('user_id', $user->id);
            })->where('is_read', false)->count(),
            'read' => Notification::where(function($q) use ($user) {
                $q->where('type', 'like', 'institutional_%')
                  ->orWhere('user_id', $user->id);
            })->where('is_read', true)->count(),
            'by_type' => Notification::where(function($q) use ($user) {
                $q->where('type', 'like', 'institutional_%')
                  ->orWhere('user_id', $user->id);
            })->select('type')
                ->selectRaw('count(*) as total')
                ->groupBy('type')
                ->get()
                ->pluck('total', 'type')
                ->toArray(),
            'today' => Notification::where(function($q) use ($user) {
                $q->where('type', 'like', 'institutional_%')
                  ->orWhere('user_id', $user->id);
            })->whereDate('created_at', today())->count(),
        ];

        // Get unique types for filter dropdown
        $types = Notification::where('type', 'like', 'institutional_%')
            ->orWhere('user_id', $user->id)
            ->distinct()
            ->pluck('type')
            ->sort()
            ->values();

        $priorities = [
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low',
        ];

        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'type' => $request->type,
            'priority' => $request->priority,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.institutional.notifications', compact('user', 'notifications', 'stats', 'types', 'priorities', 'filters'));
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::findOrFail($id);

        // Verify the notification belongs to the user or is institutional
        if ($notification->user_id !== $user->id && !str_starts_with($notification->type, 'institutional_')) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark notification as unread.
     */
    public function markNotificationAsUnread($id)
    {
        $user = Auth::user();
        $notification = Notification::findOrFail($id);

        // Verify the notification belongs to the user or is institutional
        if ($notification->user_id !== $user->id && !str_starts_with($notification->type, 'institutional_')) {
            abort(403);
        }

        $notification->markAsUnread();

        return back()->with('success', 'Notification marked as unread.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        $query = Notification::where(function($q) use ($user) {
            $q->where('type', 'like', 'institutional_%')
              ->orWhere('user_id', $user->id);
        })->where('is_read', false);

        // Apply same filters as the index method
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $count = $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', "Marked {$count} notification(s) as read.");
    }

    /**
     * Show users directory.
     */
    public function users(Request $request)
    {
        $user = Auth::user();
        
        SEO::setTitle('Users Directory | Institutional Admin');
        SEO::setDescription('View and manage users affiliated with your institution.');
        
        // Get users based on institution region or submissions
        $query = User::with('profile');
        
        // Filter by institution region if available
        $institutionRegion = $user->region;
        if ($institutionRegion) {
            $query->where('region', $institutionRegion);
        }
        
        // Exclude admins
        $query->whereNotIn('role', ['main_admin', 'institutional_admin', 'sectoral_admin']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderBy('username');
                break;
            case 'role':
                $query->orderBy('role')->orderByDesc('created_at');
                break;
            case 'status':
                $query->orderBy('verification_status')->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $users = $query->paginate(20)->withQueryString();

        // Statistics
        $baseQuery = User::whereNotIn('role', ['main_admin', 'institutional_admin', 'sectoral_admin']);
        if ($institutionRegion) {
            $baseQuery->where('region', $institutionRegion);
        }

        $stats = [
            'total' => $baseQuery->count(),
            'verified' => (clone $baseQuery)->where('verification_status', 'verified')->count(),
            'pending' => (clone $baseQuery)->where('verification_status', 'pending')->count(),
            'by_role' => (clone $baseQuery)->select('role')
                ->selectRaw('count(*) as total')
                ->groupBy('role')
                ->get()
                ->pluck('total', 'role')
                ->toArray(),
        ];

        $roles = [
            'user' => 'Citizen / User',
            'company' => 'Company',
            'candidate' => 'Candidate',
        ];

        $filters = [
            'search' => $request->search,
            'role' => $request->role,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.institutional.users', compact('user', 'users', 'stats', 'roles', 'filters'));
    }

    /**
     * Show companies oversight.
     */
    public function companies(Request $request)
    {
        $user = Auth::user();
        
        SEO::setTitle('Companies Oversight | Institutional Admin');
        SEO::setDescription('Monitor and manage companies affiliated with your institution.');
        
        $query = Company::with('user');
        
        // Filter by institution region if available
        $institutionRegion = $user->region;
        if ($institutionRegion) {
            $query->where('region', $institutionRegion);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', '%' . $search . '%')
                    ->orWhere('sector', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        // Sector filter
        if ($request->filled('sector')) {
            $query->where('sector', $request->sector);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderBy('company_name');
                break;
            case 'status':
                $query->orderBy('approval_status')->orderByDesc('created_at');
                break;
            case 'region':
                $query->orderBy('region')->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $companies = $query->paginate(20)->withQueryString();

        // Statistics
        $baseQuery = Company::query();
        if ($institutionRegion) {
            $baseQuery->where('region', $institutionRegion);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'approved' => (clone $baseQuery)->where('approval_status', 'approved')->count(),
            'pending' => (clone $baseQuery)->where('approval_status', 'pending')->count(),
            'rejected' => (clone $baseQuery)->where('approval_status', 'rejected')->count(),
            'by_sector' => (clone $baseQuery)->select('sector')
                ->selectRaw('count(*) as total')
                ->groupBy('sector')
                ->get()
                ->pluck('total', 'sector')
                ->toArray(),
        ];

        // Get unique sectors for filter
        $sectors = Company::when($institutionRegion, function($q) use ($institutionRegion) {
            $q->where('region', $institutionRegion);
        })->distinct()->pluck('sector')->filter()->sort()->values();

        $statuses = [
            'approved' => 'Approved',
            'pending' => 'Pending',
            'rejected' => 'Rejected',
        ];

        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'sector' => $request->sector,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.institutional.companies', compact('user', 'companies', 'stats', 'sectors', 'statuses', 'filters'));
    }

    /**
     * Show institutional admin profile.
     */
    public function profile()
    {
        $user = Auth::user();
        
        SEO::setTitle('Profile | Institutional Admin');
        SEO::setDescription('Manage your institutional admin account information and preferences.');
        
        return view('dashboard.institutional.profile', compact('user'));
    }

    /**
     * Update institutional admin profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'username' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'region' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $user->username = $request->username;
        $user->email = $request->email;
        $user->region = $request->region;
        $user->city = $request->city;
        
        if ($request->filled('phone') && in_array('phone', $user->getFillable())) {
            $user->phone = $request->phone;
        }
        
        if ($request->filled('bio') && in_array('bio', $user->getFillable())) {
            $user->bio = $request->bio;
        }
        
        if ($request->hasFile('avatar')) {
            $oldAvatar = $user->avatar_path ?? null;
            if ($oldAvatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldAvatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldAvatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars/institutional', 'public');
            if (in_array('avatar_path', $user->getFillable())) {
                $user->avatar_path = $avatarPath;
            }
        }
        
        $user->save();
        
        // Log the action
        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'profile_updated',
            'description' => 'Institutional admin profile updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'level' => 'info',
        ]);
        
        return redirect()->route('institutional.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update institutional admin password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }
        
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();
        
        // Log the action
        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'password_changed',
            'description' => 'Institutional admin password changed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'level' => 'warning',
        ]);
        
        return redirect()->route('institutional.profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Show institutional admin settings.
     */
    public function settings()
    {
        $user = Auth::user();
        
        SEO::setTitle('Settings | Institutional Admin');
        SEO::setDescription('Configure institutional preferences and notification settings.');
        
        // Get current settings - in production, these would come from a settings table
        $settings = [
            'institution_name' => $user->username ?? 'Institution',
            'email' => $user->email,
            'region' => $user->region,
            'city' => $user->city,
            'phone' => $user->phone ?? '',
            'enable_notifications' => true,
            'enable_email_notifications' => true,
            'enable_sms_notifications' => false,
            'notification_frequency' => 'immediate',
            'auto_approve_submissions' => false,
            'require_verification' => true,
        ];
        
        return view('dashboard.institutional.settings', compact('user', 'settings'));
    }

    /**
     * Update institutional admin settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'region' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'enable_notifications' => 'boolean',
            'enable_email_notifications' => 'boolean',
            'enable_sms_notifications' => 'boolean',
            'notification_frequency' => 'required|in:immediate,daily,weekly',
            'auto_approve_submissions' => 'boolean',
            'require_verification' => 'boolean',
        ]);
        
        // Update user fields
        $user->username = $request->institution_name;
        $user->email = $request->email;
        $user->region = $request->region;
        $user->city = $request->city;
        
        if ($request->filled('phone') && in_array('phone', $user->getFillable())) {
            $user->phone = $request->phone;
        }
        
        $user->save();
        
        // In production, other settings would be saved to a settings table
        // For now, we'll just log the action
        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'settings_updated',
            'description' => 'Institutional admin settings updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'log_data' => $request->except(['_token', '_method']),
            'level' => 'info',
        ]);
        
        return redirect()->route('institutional.settings')->with('success', 'Settings updated successfully.');
    }
    
    /**
     * Get submissions for this institution.
     */
    private function getInstitutionSubmissions()
    {
        $submissions = collect();
        
        // Get submissions based on institution type and region
        $user = Auth::user();
        $institutionRegion = $user->region ?? 'all';
        
        // For now, we'll get all submissions. In a real implementation,
        // you would filter based on institution type and region
        $submissions = $submissions->merge(InvestmentSubmission::with('user')->get());
        $submissions = $submissions->merge(ProjectCarrierSubmission::with('user')->get());
        $submissions = $submissions->merge(IdeaCarrierSubmission::with('user')->get());
        $submissions = $submissions->merge(AutoEntrepreneurSubmission::with('user')->get());
        $submissions = $submissions->merge(INDHSubmission::with('user')->get());
        $submissions = $submissions->merge(TrainingSubmission::with('user')->get());
        
        return $submissions;
    }
    
    /**
     * Get submission by type and ID.
     */
    private function getSubmissionByType($type, $id)
    {
        $submission = null;
        switch ($type) {
            case 'investment':
                $submission = InvestmentSubmission::with('user')->find($id);
                break;
            case 'project-carrier':
                $submission = ProjectCarrierSubmission::with('user')->find($id);
                break;
            case 'idea-carrier':
                $submission = IdeaCarrierSubmission::with('user')->find($id);
                break;
            case 'auto-entrepreneur':
                $submission = AutoEntrepreneurSubmission::with('user')->find($id);
                break;
            case 'indh':
                $submission = INDHSubmission::with('user')->find($id);
                break;
            case 'training':
                $submission = TrainingSubmission::with('user')->find($id);
                break;
        }
        
        // Load reviewer if exists
        if ($submission && $submission->reviewed_by) {
            $submission->reviewer = User::find($submission->reviewed_by);
        }
        
        return $submission;
    }
    
    /**
     * Get submissions grouped by type.
     */
    private function getSubmissionsByType($submissions)
    {
        return $submissions->groupBy(function ($submission) {
            return class_basename($submission);
        })->map(function ($group) {
            return $group->count();
        });
    }
    
    /**
     * Get submissions grouped by status.
     */
    private function getSubmissionsByStatus($submissions)
    {
        return $submissions->groupBy('status')->map(function ($group) {
            return $group->count();
        });
    }
    
    /**
     * Get submissions grouped by month.
     */
    private function getSubmissionsByMonth($submissions)
    {
        return $submissions->groupBy(function ($submission) {
            return $submission->created_at->format('Y-m');
        })->map(function ($group) {
            return $group->count();
        });
    }
    
    /**
     * Get submissions grouped by region.
     */
    private function getSubmissionsByRegion($submissions)
    {
        return $submissions->groupBy(function ($submission) {
            return $submission->region ?? 'Non spécifié';
        })->map(function ($group) {
            return $group->count();
        });
    }
}























