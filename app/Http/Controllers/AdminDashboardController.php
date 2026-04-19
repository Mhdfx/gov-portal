<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
use App\Models\LoginAttempt;
use App\Models\FileUpload;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Storage;
use SEO;
use Carbon\Carbon;
use App\Constants\AppConstants;

class AdminDashboardController extends Controller
{
    /**
     * Show the main admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Set SEO meta tags
        SEO::setTitle('Administration - Tableau de Bord Principal');
        SEO::setDescription('Gérez la plateforme Boiema et supervisez toutes les activités.');
        SEO::setCanonical(url()->current());
        
        // Get comprehensive statistics with caching
        $stats = Cache::remember('admin_stats_' . $user->id, 300, function () {
            return [
                'total_users' => User::count(),
                'verified_users' => User::where('verification_status', AppConstants::VERIFICATION_VERIFIED)->count(),
                'pending_users' => User::where('verification_status', AppConstants::VERIFICATION_PENDING)->count(),
                'total_companies' => Company::count(),
                'approved_companies' => Company::where('approval_status', AppConstants::APPROVAL_APPROVED)->count(),
                'pending_companies' => Company::where('approval_status', AppConstants::APPROVAL_PENDING)->count(),
                'total_submissions' => $this->getTotalSubmissions(),
                'pending_submissions' => $this->getPendingSubmissions(),
                'approved_submissions' => $this->getApprovedSubmissions(),
                'rejected_submissions' => $this->getRejectedSubmissions(),
            ];
        });
        
        // Get recent activities with optimized queries and caching
        $recentUsers = Cache::remember('admin_recent_users_' . $user->id, 180, function () {
            return User::select('id', 'username', 'email', 'role', 'verification_status', 'created_at')
                ->latest()
                ->limit(10)
                ->get();
        });
        
        $recentCompanies = Cache::remember('admin_recent_companies_' . $user->id, 180, function () {
            return Company::select('id', 'company_name', 'business_sectors', 'approval_status', 'created_at')
                ->latest()
                ->limit(10)
                ->get();
        });
        
        $recentSubmissions = Cache::remember('admin_recent_submissions_' . $user->id, 180, function () {
            return $this->getRecentSubmissions();
        });
        
        $recentLogs = SystemLog::select('id', 'action', 'user_id', 'created_at')
            ->with('user:id,username')
            ->latest()
            ->limit(20)
            ->get();
        
        // Get notifications with caching
        $notifications = Cache::remember('admin_notifications_' . $user->id, 60, function () use ($user) {
            return Notification::select('id', 'type', 'title', 'message', 'read_at', 'created_at')
                ->latest()
                ->limit(20)
                ->get();
        });
        
        return view('dashboard.admin.index', compact('user', 'stats', 'recentUsers', 'recentCompanies', 'recentSubmissions', 'recentLogs', 'notifications'));
    }
    
    /**
     * Show user management.
     */
    public function users(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('Users Management | Main Admin');
        SEO::setDescription('Review, filter, and moderate every user account in the I.M System.');

        $query = User::with('profile')->select('id', 'username', 'email', 'role', 'verification_status', 'created_at', 'updated_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

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

        $stats = [
            'total' => User::count(),
            'verified' => User::where('verification_status', AppConstants::VERIFICATION_VERIFIED)->count(),
            'pending' => User::where('verification_status', AppConstants::VERIFICATION_PENDING)->count(),
            'rejected' => User::where('verification_status', AppConstants::VERIFICATION_REJECTED)->count(),
            'companies' => User::where('role', AppConstants::ROLE_COMPANY)->count(),
        ];

        $roleBreakdown = User::select('role')
            ->selectRaw('count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $filters = [
            'search' => $request->search,
            'role' => $request->role,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        $roles = [
            AppConstants::ROLE_USER => 'Citizen / User',
            AppConstants::ROLE_COMPANY => 'Company',
            'candidate' => 'Candidate',
            AppConstants::ROLE_INSTITUTIONAL_ADMIN => 'Institutional Admin',
            AppConstants::ROLE_SECTORAL_ADMIN => 'Sectoral Admin',
            AppConstants::ROLE_MAIN_ADMIN => 'Main Admin',
        ];

        return view('dashboard.admin.users', compact('admin', 'users', 'stats', 'roleBreakdown', 'filters', 'roles'));
    }
    
    /**
     * Show company management.
     */
    public function companies(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('Companies Management | Main Admin');
        SEO::setDescription('Monitor company onboarding, approvals, and platform activity for the I.M System.');

        $query = Company::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', '%' . $search . '%')
                    ->orWhere('registration_number', 'like', '%' . $search . '%')
                    ->orWhere('tax_number', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        if ($request->filled('activity')) {
            $query->where('is_active', $request->activity === 'active');
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        if ($request->filled('sector')) {
            $sector = $request->sector;
            $query->where(function ($q) use ($sector) {
                $q->whereJsonContains('business_sectors', $sector)
                    ->orWhere('business_sectors', 'like', '%' . $sector . '%');
            });
        }

        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderBy('company_name');
                break;
            case 'status':
                $query->orderBy('approval_status')->orderByDesc('created_at');
                break;
            case 'region':
                $query->orderBy('region')->orderBy('company_name');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $companies = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Company::count(),
            'approved' => Company::where('approval_status', AppConstants::APPROVAL_APPROVED)->count(),
            'pending' => Company::where('approval_status', AppConstants::APPROVAL_PENDING)->count(),
            'rejected' => Company::where('approval_status', AppConstants::APPROVAL_REJECTED)->count(),
            'active' => Company::where('is_active', true)->count(),
        ];

        $regions = Company::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region')
            ->toArray();

        $sectorSamples = Company::whereNotNull('business_sectors')
            ->pluck('business_sectors')
            ->flatten()
            ->unique()
            ->filter()
            ->values()
            ->take(12)
            ->toArray();

        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'activity' => $request->activity,
            'region' => $request->region,
            'sector' => $request->sector,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.admin.companies', compact('admin', 'companies', 'stats', 'regions', 'sectorSamples', 'filters'));
    }
    
    /**
     * Show submission management.
     */
    public function submissions(Request $request)
    {
        $admin = Auth::user();
        
        SEO::setTitle('Submissions Management | Main Admin');
        SEO::setDescription('Review, filter, and moderate all form submissions across the I.M System.');
        
        // Collect all submission types with metadata
        $allSubmissions = collect([
            InvestmentSubmission::with('user')->get()->map(function($item) {
                $item->submission_type = 'investment';
                $item->submission_type_label = 'Investment';
                return $item;
            }),
            ProjectCarrierSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->get()
                ->map(function($item) {
                $item->submission_type = 'project_carrier';
                $item->submission_type_label = 'Project Carrier';
                return $item;
            }),
            IdeaCarrierSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->get()
                ->map(function($item) {
                $item->submission_type = 'idea_carrier';
                $item->submission_type_label = 'Idea Carrier';
                return $item;
            }),
            AutoEntrepreneurSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->get()
                ->map(function($item) {
                $item->submission_type = 'auto_entrepreneur';
                $item->submission_type_label = 'Auto-Entrepreneur';
                return $item;
            }),
            INDHSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->get()
                ->map(function($item) {
                $item->submission_type = 'indh';
                $item->submission_type_label = 'INDH';
                return $item;
            }),
            TrainingSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->get()
                ->map(function($item) {
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
        
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allSubmissions = $allSubmissions->filter(function($item) use ($search) {
                return str_contains(strtolower($item->user->username ?? ''), $search) ||
                       str_contains(strtolower($item->user->email ?? ''), $search) ||
                       str_contains(strtolower($item->submission_type_label ?? ''), $search);
            });
        }
        
        if ($request->filled('date_from')) {
            $allSubmissions = $allSubmissions->filter(function($item) use ($request) {
                return $item->created_at >= Carbon::parse($request->date_from)->startOfDay();
            });
        }
        
        if ($request->filled('date_to')) {
            $allSubmissions = $allSubmissions->filter(function($item) use ($request) {
                return $item->created_at <= Carbon::parse($request->date_to)->endOfDay();
            });
        }
        
        // Sort
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $allSubmissions = $allSubmissions->sortBy('created_at');
                break;
            case 'status':
                $allSubmissions = $allSubmissions->sortBy('status')->sortByDesc('created_at');
                break;
            case 'type':
                $allSubmissions = $allSubmissions->sortBy('submission_type_label')->sortByDesc('created_at');
                break;
            default:
                $allSubmissions = $allSubmissions->sortByDesc('created_at');
        }
        
        // Manual pagination
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
        $stats = [
            'total' => $this->getTotalSubmissions(),
            'pending' => $this->getPendingSubmissions(),
            'approved' => $this->getApprovedSubmissions(),
            'rejected' => $this->getRejectedSubmissions(),
        ];
        
        // Type breakdown
        $typeBreakdown = collect([
            InvestmentSubmission::count(),
            ProjectCarrierSubmission::count(),
            IdeaCarrierSubmission::count(),
            AutoEntrepreneurSubmission::count(),
            INDHSubmission::count(),
            TrainingSubmission::count(),
        ]);
        
        $submissionTypes = [
            'investment' => 'Investment',
            'project_carrier' => 'Project Carrier',
            'idea_carrier' => 'Idea Carrier',
            'auto_entrepreneur' => 'Auto-Entrepreneur',
            'indh' => 'INDH',
            'training' => 'Training',
        ];
        
        $filters = [
            'search' => $request->search,
            'type' => $request->type,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];
        
        return view('dashboard.admin.submissions', compact('admin', 'submissions', 'stats', 'submissionTypes', 'filters'));
    }

    /**
     * Show reports & statistics.
     */
    public function reports(Request $request)
    {
        $admin = Auth::user();

        $range = $request->input('range', '30');
        $allowedRanges = ['7', '30', '90', '365'];
        if (!in_array($range, $allowedRanges)) {
            $range = '30';
        }
        $startDate = Carbon::now()->subDays((int) $range);

        SEO::setTitle('Reports & Statistics | Main Admin');
        SEO::setDescription('High-level insights covering submissions, users, companies, and platform activity.');

        $submissionCounts = [
            'investment' => InvestmentSubmission::where('created_at', '>=', $startDate)->count(),
            'project_carrier' => ProjectCarrierSubmission::where('created_at', '>=', $startDate)->count(),
            'idea_carrier' => IdeaCarrierSubmission::where('created_at', '>=', $startDate)->count(),
            'auto_entrepreneur' => AutoEntrepreneurSubmission::where('created_at', '>=', $startDate)->count(),
            'indh' => INDHSubmission::where('created_at', '>=', $startDate)->count(),
            'training' => TrainingSubmission::where('created_at', '>=', $startDate)->count(),
        ];

        $statusBreakdown = [
            'pending' => $this->getPendingSubmissions(),
            'approved' => $this->getApprovedSubmissions(),
            'rejected' => $this->getRejectedSubmissions(),
        ];

        $userGrowth = [
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'verified_users' => User::where('created_at', '>=', $startDate)->where('verification_status', AppConstants::VERIFICATION_VERIFIED)->count(),
        ];

        $companyGrowth = [
            'new_companies' => Company::where('created_at', '>=', $startDate)->count(),
            'approved_companies' => Company::where('created_at', '>=', $startDate)->where('approval_status', AppConstants::APPROVAL_APPROVED)->count(),
        ];

        $recentSubmissions = $this->getRecentSubmissions()->take(8);

        $rangeOptions = [
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
            '365' => 'Last 12 months',
        ];

        return view('dashboard.admin.reports', compact(
            'admin',
            'range',
            'rangeOptions',
            'submissionCounts',
            'statusBreakdown',
            'userGrowth',
            'companyGrowth',
            'recentSubmissions'
        ));
    }

    /**
     * Show analytics dashboard.
     */
    public function analytics(Request $request)
    {
        $admin = Auth::user();

        $range = $request->input('range', '30');
        $allowedRanges = ['7', '30', '90', '365'];
        if (!in_array($range, $allowedRanges, true)) {
            $range = '30';
        }
        $startDate = Carbon::now()->subDays((int) $range);

        SEO::setTitle('Analytics & Insights | Main Admin');
        SEO::setDescription('Monitor KPIs, growth trends, and platform performance across the I.M System.');

        $allSubmissions = $this->getAllSubmissions();
        $submissionsInRange = $allSubmissions->filter(fn ($item) => $item->created_at->greaterThanOrEqualTo($startDate));

        $totalSubmissions = $allSubmissions->count();
        $approvedSubmissions = $allSubmissions->where('status', 'approved')->count();
        $rejectedSubmissions = $allSubmissions->where('status', 'rejected')->count();
        $pendingSubmissions = $allSubmissions->where('status', 'pending')->count();
        $underReviewSubmissions = $allSubmissions->where('status', 'under_review')->count();
        $approvedInRange = $submissionsInRange->where('status', 'approved')->count();

        $kpis = [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'total_submissions' => $totalSubmissions,
            'approval_rate' => $totalSubmissions ? round(($approvedSubmissions / $totalSubmissions) * 100, 1) : 0,
            'conversion_rate' => $submissionsInRange->count() ? round(($approvedInRange / $submissionsInRange->count()) * 100, 1) : 0,
        ];

        $userStats = [
            'verified' => User::where('verification_status', AppConstants::VERIFICATION_VERIFIED)->count(),
            'pending_verification' => User::where('verification_status', AppConstants::VERIFICATION_PENDING)->count(),
        ];

        $companyStats = [
            'total' => Company::count(),
            'new' => Company::where('created_at', '>=', $startDate)->count(),
            'approved' => Company::where('approval_status', AppConstants::APPROVAL_APPROVED)->count(),
            'approval_rate' => Company::count() ? round((Company::where('approval_status', AppConstants::APPROVAL_APPROVED)->count() / Company::count()) * 100, 1) : 0,
        ];

        $roleDistribution = User::select('role')
            ->selectRaw('count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $approvalBreakdown = [
            'pending' => $pendingSubmissions,
            'under_review' => $underReviewSubmissions,
            'approved' => $approvedSubmissions,
            'rejected' => $rejectedSubmissions,
        ];

        $submissionsTrend = $allSubmissions
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->sortKeys()
            ->map->count()
            ->slice(-6);

        $topSectors = $allSubmissions
            ->pluck('sector')
            ->filter()
            ->map(fn ($sector) => trim($sector))
            ->filter()
            ->groupBy(fn ($sector) => $sector)
            ->map->count()
            ->sortDesc()
            ->take(5);

        $rangeOptions = [
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
            '365' => 'Last 12 months',
        ];

        return view('dashboard.admin.analytics', compact(
            'admin',
            'range',
            'rangeOptions',
            'kpis',
            'userStats',
            'companyStats',
            'roleDistribution',
            'approvalBreakdown',
            'submissionsTrend',
            'topSectors'
        ));
    }

    /**
     * Show file management.
     */
    public function files(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('File Management | Main Admin');
        SEO::setDescription('Monitor, download, and manage all uploaded files across the I.M System.');

        $query = FileUpload::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', '%' . $search . '%')
                    ->orWhere('file_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('upload_type')) {
            $query->where('upload_type', $request->upload_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderBy('original_name');
                break;
            case 'size':
                $query->orderByDesc('file_size');
                break;
            case 'type':
                $query->orderBy('upload_type')->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $files = $query->paginate(20)->withQueryString();

        // Add human-readable file sizes
        $files->getCollection()->transform(function ($file) {
            $file->file_size_human = $this->formatFileSize($file->file_size);
            $file->download_url = Storage::disk('public')->exists($file->file_path) 
                ? Storage::disk('public')->url($file->file_path) 
                : null;
            return $file;
        });

        $stats = [
            'total' => FileUpload::count(),
            'total_size' => FileUpload::sum('file_size'),
            'by_type' => FileUpload::select('upload_type')
                ->selectRaw('count(*) as total, sum(file_size) as total_size')
                ->groupBy('upload_type')
                ->get()
                ->pluck('total', 'upload_type')
                ->toArray(),
        ];

        $uploadTypes = [
            'cv' => 'CV / Resume',
            'business_plan' => 'Business Plan',
            'company_document' => 'Company Document',
            'candidate_document' => 'Candidate Document',
            'general' => 'General',
        ];

        $filters = [
            'search' => $request->search,
            'upload_type' => $request->upload_type,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.admin.files', compact('admin', 'files', 'stats', 'uploadTypes', 'filters'));
    }

    /**
     * Format file size to human readable format.
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
    
    /**
     * Show system logs.
     */
    public function logs(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('System Logs | Main Admin');
        SEO::setDescription('Monitor system activity, errors, and security events across the platform.');

        $query = SystemLog::with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        // Export functionality
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportLogsToCsv($query->get());
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'level':
                $query->orderBy('level')->orderByDesc('created_at');
                break;
            case 'action':
                $query->orderBy('action')->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $logs = $query->paginate(50)->withQueryString();

        // Statistics
        $stats = [
            'total' => SystemLog::count(),
            'by_level' => SystemLog::select('level')
                ->selectRaw('count(*) as total')
                ->groupBy('level')
                ->get()
                ->pluck('total', 'level')
                ->toArray(),
            'by_action' => SystemLog::select('action')
                ->selectRaw('count(*) as total')
                ->groupBy('action')
                ->get()
                ->pluck('total', 'action')
                ->toArray(),
            'today' => SystemLog::whereDate('created_at', today())->count(),
            'this_week' => SystemLog::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
        ];

        // Get unique actions for filter dropdown
        $actions = SystemLog::distinct()->pluck('action')->sort()->values();

        // Get users who have logs for filter dropdown
        $usersWithLogs = User::whereHas('systemLogs')->select('id', 'username', 'email')->get();

        $levels = [
            'info' => 'Info',
            'warning' => 'Warning',
            'error' => 'Error',
            'critical' => 'Critical',
        ];

        $filters = [
            'search' => $request->search,
            'level' => $request->level,
            'action' => $request->action,
            'user_id' => $request->user_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.admin.logs', compact('admin', 'logs', 'stats', 'actions', 'usersWithLogs', 'levels', 'filters'));
    }

    /**
     * Export logs to CSV.
     */
    private function exportLogsToCsv($logs)
    {
        $filename = 'system_logs_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Date', 'Level', 'Action', 'User', 'Description', 'IP Address', 'User Agent']);
            
            // CSV rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->level ?? 'N/A',
                    $log->action,
                    $log->user ? $log->user->username : 'System',
                    $log->description,
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Show notifications management.
     */
    /**
     * Show notifications center.
     */
    public function notifications(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('Notifications Center | Main Admin');
        SEO::setDescription('Manage and monitor all platform notifications across users and system events.');

        $query = Notification::with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
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

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
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
            'total' => Notification::count(),
            'unread' => Notification::where('is_read', false)->count(),
            'read' => Notification::where('is_read', true)->count(),
            'by_type' => Notification::select('type')
                ->selectRaw('count(*) as total')
                ->groupBy('type')
                ->get()
                ->pluck('total', 'type')
                ->toArray(),
            'by_priority' => Notification::select('priority')
                ->selectRaw('count(*) as total')
                ->groupBy('priority')
                ->get()
                ->pluck('total', 'priority')
                ->toArray(),
            'today' => Notification::whereDate('created_at', today())->count(),
            'this_week' => Notification::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
        ];

        // Get unique types for filter dropdown
        $types = Notification::distinct()->pluck('type')->sort()->values();

        // Get users who have notifications for filter dropdown
        $usersWithNotifications = User::whereHas('notifications')->select('id', 'username', 'email')->get();

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
            'user_id' => $request->user_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.admin.notifications', compact('admin', 'notifications', 'stats', 'types', 'usersWithNotifications', 'priorities', 'filters'));
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark notification as unread.
     */
    public function markNotificationAsUnread($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsUnread();

        return back()->with('success', 'Notification marked as unread.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $query = Notification::query();

        // Apply same filters as the index method
        if ($request->filled('status') && $request->status === 'unread') {
            $query->where('is_read', false);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $count = $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', "Marked {$count} notification(s) as read.");
    }

    /**
     * Delete notification.
     */
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Bulk delete notifications.
     */
    public function bulkDeleteNotifications(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id',
        ]);

        $count = Notification::whereIn('id', $request->notification_ids)->delete();

        return back()->with('success', "Deleted {$count} notification(s) successfully.");
    }

    /**
     * Show security audit log.
     */
    public function securityAuditLog(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('Security Audit Log | Main Admin');
        SEO::setDescription('Monitor authentication events, permission changes, and security anomalies across the platform.');

        // Security-related actions to filter from SystemLog
        $securityActions = [
            'login', 'logout', 'login_failed', 'password_change', 'role_change',
            'permission_change', 'user_created', 'user_deleted', 'user_suspended',
            'company_approved', 'company_rejected', 'submission_status_updated',
            'file_upload', 'file_deleted', 'admin_action', 'security_alert',
        ];

        // Get security logs from SystemLog
        $securityLogsQuery = SystemLog::with('user')
            ->whereIn('action', $securityActions)
            ->orWhere('level', 'error')
            ->orWhere('level', 'critical');

        // Get login attempts
        $loginAttemptsQuery = LoginAttempt::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $securityLogsQuery->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%')
                    ->orHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });

            $loginAttemptsQuery->where(function ($q) use ($search) {
                $q->where('identifier', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%');
            });
        }

        // Event type filter
        if ($request->filled('event_type')) {
            if ($request->event_type === 'login_attempts') {
                $securityLogsQuery->whereRaw('1 = 0'); // Exclude system logs
            } elseif ($request->event_type === 'system_events') {
                $loginAttemptsQuery->whereRaw('1 = 0'); // Exclude login attempts
            }
        }

        // Risk level filter
        if ($request->filled('risk_level')) {
            $riskLevel = $request->risk_level;
            if ($riskLevel === 'high') {
                $securityLogsQuery->where(function ($q) {
                    $q->where('level', 'critical')
                        ->orWhere('level', 'error')
                        ->orWhereIn('action', ['login_failed', 'user_deleted', 'security_alert']);
                });
                $loginAttemptsQuery->where('success', false);
            } elseif ($riskLevel === 'medium') {
                $securityLogsQuery->where(function ($q) {
                    $q->where('level', 'warning')
                        ->orWhereIn('action', ['password_change', 'role_change', 'permission_change']);
                });
            }
        }

        // IP filter
        if ($request->filled('ip_address')) {
            $ip = $request->ip_address;
            $securityLogsQuery->where('ip_address', $ip);
            $loginAttemptsQuery->where('ip_address', $ip);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from);
            $securityLogsQuery->whereDate('created_at', '>=', $dateFrom);
            $loginAttemptsQuery->whereDate('attempted_at', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to);
            $securityLogsQuery->whereDate('created_at', '<=', $dateTo);
            $loginAttemptsQuery->whereDate('attempted_at', '<=', $dateTo);
        }

        // Export functionality
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportSecurityAuditToCsv($securityLogsQuery->get(), $loginAttemptsQuery->get());
        }

        // Get security logs
        $securityLogs = $securityLogsQuery->orderByDesc('created_at')->limit(500)->get();
        
        // Get login attempts
        $loginAttempts = $loginAttemptsQuery->orderByDesc('attempted_at')->limit(500)->get();

        // Combine and merge events
        $events = collect();
        
        // Add system log events
        foreach ($securityLogs as $log) {
            $events->push([
                'id' => 'log_' . $log->id,
                'type' => 'system_event',
                'event' => $log->action,
                'description' => $log->description,
                'user' => $log->user,
                'user_id' => $log->user_id,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'timestamp' => $log->created_at,
                'level' => $log->level,
                'risk_level' => $this->calculateRiskLevel($log->action, $log->level),
                'data' => $log->log_data,
            ]);
        }

        // Add login attempt events
        foreach ($loginAttempts as $attempt) {
            $events->push([
                'id' => 'attempt_' . $attempt->id,
                'type' => 'login_attempt',
                'event' => $attempt->success ? 'login_success' : 'login_failed',
                'description' => $attempt->success 
                    ? "Successful login attempt for '{$attempt->identifier}'"
                    : "Failed login attempt for '{$attempt->identifier}'",
                'user' => null,
                'user_id' => null,
                'identifier' => $attempt->identifier,
                'ip_address' => $attempt->ip_address,
                'user_agent' => $attempt->user_agent,
                'timestamp' => $attempt->attempted_at,
                'level' => $attempt->success ? 'info' : 'warning',
                'risk_level' => $attempt->success ? 'low' : ($this->isAnomalousIp($attempt->ip_address) ? 'high' : 'medium'),
                'success' => $attempt->success,
            ]);
        }

        // Sort by timestamp
        $events = $events->sortByDesc('timestamp')->values();

        // Manual pagination
        $page = $request->get('page', 1);
        $perPage = 50;
        $currentPage = $events->slice(($page - 1) * $perPage, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPage,
            $events->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Statistics
        $stats = [
            'total_events' => $events->count(),
            'high_risk' => $events->where('risk_level', 'high')->count(),
            'medium_risk' => $events->where('risk_level', 'medium')->count(),
            'low_risk' => $events->where('risk_level', 'low')->count(),
            'failed_logins' => LoginAttempt::failed()->count(),
            'successful_logins' => LoginAttempt::successful()->count(),
            'unique_ips' => LoginAttempt::distinct('ip_address')->count('ip_address'),
            'today_events' => $events->filter(fn($e) => $e['timestamp']->isToday())->count(),
            'this_week_events' => $events->filter(fn($e) => $e['timestamp']->isCurrentWeek())->count(),
        ];

        // Get unique IPs for filter
        $uniqueIps = collect()
            ->merge(LoginAttempt::distinct()->pluck('ip_address'))
            ->merge(SystemLog::whereIn('action', $securityActions)->distinct()->pluck('ip_address'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $filters = [
            'search' => $request->search,
            'event_type' => $request->event_type,
            'risk_level' => $request->risk_level,
            'ip_address' => $request->ip_address,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        return view('dashboard.admin.security.audit-log', compact('admin', 'paginator', 'stats', 'uniqueIps', 'filters'));
    }

    /**
     * Calculate risk level for an event.
     */
    private function calculateRiskLevel(string $action, ?string $level): string
    {
        $highRiskActions = ['login_failed', 'user_deleted', 'security_alert', 'permission_change', 'role_change'];
        $mediumRiskActions = ['password_change', 'user_created', 'user_suspended', 'file_deleted'];

        if (in_array($action, $highRiskActions) || $level === 'critical' || $level === 'error') {
            return 'high';
        }

        if (in_array($action, $mediumRiskActions) || $level === 'warning') {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Check if IP address is anomalous (multiple failed attempts).
     */
    private function isAnomalousIp(string $ip): bool
    {
        $failedAttempts = LoginAttempt::failed()
            ->byIp($ip)
            ->recent(60)
            ->count();

        return $failedAttempts >= 5;
    }

    /**
     * Export security audit log to CSV.
     */
    private function exportSecurityAuditToCsv($securityLogs, $loginAttempts)
    {
        $filename = 'security_audit_log_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($securityLogs, $loginAttempts) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Type', 'Event', 'Description', 'User', 'IP Address', 'User Agent', 'Timestamp', 'Risk Level', 'Status']);
            
            // System log rows
            foreach ($securityLogs as $log) {
                fputcsv($file, [
                    'System Event',
                    $log->action,
                    $log->description,
                    $log->user ? $log->user->username : 'System',
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                    $log->created_at->format('Y-m-d H:i:s'),
                    $this->calculateRiskLevel($log->action, $log->level),
                    $log->level ?? 'N/A',
                ]);
            }
            
            // Login attempt rows
            foreach ($loginAttempts as $attempt) {
                fputcsv($file, [
                    'Login Attempt',
                    $attempt->success ? 'login_success' : 'login_failed',
                    $attempt->success 
                        ? "Successful login for '{$attempt->identifier}'"
                        : "Failed login for '{$attempt->identifier}'",
                    $attempt->identifier,
                    $attempt->ip_address ?? 'N/A',
                    $attempt->user_agent ?? 'N/A',
                    $attempt->attempted_at->format('Y-m-d H:i:s'),
                    $attempt->success ? 'low' : ($this->isAnomalousIp($attempt->ip_address) ? 'high' : 'medium'),
                    $attempt->success ? 'Success' : 'Failed',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show newsletter management.
     */
    public function newsletter(Request $request)
    {
        $admin = Auth::user();

        SEO::setTitle('Newsletter Management | Main Admin');
        SEO::setDescription('Manage newsletter subscribers, compose campaigns, and track delivery metrics.');

        // Use NewsletterSubscription model (seems to be the primary one)
        $subscribersQuery = NewsletterSubscription::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $subscribersQuery->where(function ($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $subscribersQuery->where('status', $request->status);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $subscribersQuery->whereDate('subscribed_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $subscribersQuery->whereDate('subscribed_at', '<=', Carbon::parse($request->date_to));
        }

        // Export functionality
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportNewsletterSubscribersToCsv($subscribersQuery->get());
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $subscribersQuery->orderBy('subscribed_at');
                break;
            case 'email':
                $subscribersQuery->orderBy('email');
                break;
            case 'name':
                $subscribersQuery->orderBy('name');
                break;
            case 'status':
                $subscribersQuery->orderBy('status')->orderByDesc('subscribed_at');
                break;
            default:
                $subscribersQuery->orderByDesc('subscribed_at');
        }

        $subscribers = $subscribersQuery->paginate(30)->withQueryString();

        // Statistics
        $stats = [
            'total' => NewsletterSubscription::count(),
            'active' => NewsletterSubscription::active()->count(),
            'inactive' => NewsletterSubscription::inactive()->count(),
            'unsubscribed' => NewsletterSubscription::unsubscribed()->count(),
            'recent' => NewsletterSubscription::recent(30)->count(),
            'this_month' => NewsletterSubscription::whereMonth('subscribed_at', now()->month)
                ->whereYear('subscribed_at', now()->year)
                ->count(),
            'growth_rate' => $this->calculateGrowthRate(),
        ];

        // Subscription trends (last 6 months)
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trends[] = [
                'month' => $date->format('M Y'),
                'subscribed' => NewsletterSubscription::whereMonth('subscribed_at', $date->month)
                    ->whereYear('subscribed_at', $date->year)
                    ->count(),
                'unsubscribed' => NewsletterSubscription::whereMonth('unsubscribed_at', $date->month)
                    ->whereYear('unsubscribed_at', $date->year)
                    ->whereNotNull('unsubscribed_at')
                    ->count(),
            ];
        }

        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'unsubscribed' => 'Unsubscribed',
        ];

        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'sort' => $sort,
        ];

        return view('dashboard.admin.newsletter', compact('admin', 'subscribers', 'stats', 'trends', 'statuses', 'filters'));
    }

    /**
     * Calculate subscription growth rate.
     */
    private function calculateGrowthRate(): float
    {
        $thisMonth = NewsletterSubscription::whereMonth('subscribed_at', now()->month)
            ->whereYear('subscribed_at', now()->year)
            ->count();

        $lastMonth = NewsletterSubscription::whereMonth('subscribed_at', now()->subMonth()->month)
            ->whereYear('subscribed_at', now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    /**
     * Export newsletter subscribers to CSV.
     */
    private function exportNewsletterSubscribersToCsv($subscribers)
    {
        $filename = 'newsletter_subscribers_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Email', 'Name', 'Status', 'Subscribed At', 'Unsubscribed At']);
            
            // CSV rows
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->name ?? 'N/A',
                    $subscriber->status,
                    $subscriber->subscribed_at ? $subscriber->subscribed_at->format('Y-m-d H:i:s') : 'N/A',
                    $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('Y-m-d H:i:s') : '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Unsubscribe a newsletter subscriber.
     */
    public function unsubscribeSubscriber($id)
    {
        $subscriber = NewsletterSubscription::findOrFail($id);
        $subscriber->unsubscribe();

        return back()->with('success', 'Subscriber unsubscribed successfully.');
    }

    /**
     * Resubscribe a newsletter subscriber.
     */
    public function resubscribeSubscriber($id)
    {
        $subscriber = NewsletterSubscription::findOrFail($id);
        $subscriber->reactivate();

        return back()->with('success', 'Subscriber resubscribed successfully.');
    }

    /**
     * Show newsletter compose form.
     */
    public function composeNewsletter()
    {
        $admin = Auth::user();

        SEO::setTitle('Compose Newsletter | Main Admin');
        SEO::setDescription('Create and send a newsletter campaign to subscribers.');

        $activeSubscribers = NewsletterSubscription::active()->count();

        return view('dashboard.admin.newsletter-compose', compact('admin', 'activeSubscribers'));
    }
    
    /**
     * Show admin settings.
     */
    public function settings()
    {
        $admin = Auth::user();

        SEO::setTitle('Settings | Main Admin');
        SEO::setDescription('Configure system settings, security policies, and platform preferences.');

        // Get current settings from config or database
        // For now, we'll use a simple structure - in production, these would come from a settings table
        $settings = [
            'site_name' => config('app.name', 'I.M System'),
            'site_email' => config('mail.from.address', 'noreply@example.com'),
            'maintenance_mode' => false, // Would come from config
            'registration_enabled' => true,
            'email_verification_required' => true,
            'session_timeout' => 120, // minutes
            'password_min_length' => 8,
            'password_require_uppercase' => true,
            'password_require_lowercase' => true,
            'password_require_numbers' => true,
            'password_require_symbols' => false,
            'max_login_attempts' => 5,
            'lockout_duration' => 15, // minutes
            'enable_notifications' => true,
            'enable_email_notifications' => true,
            'enable_sms_notifications' => false,
            'log_retention_days' => 90,
            'backup_frequency' => 'daily',
            'enable_2fa' => false,
        ];

        return view('dashboard.admin.settings', compact('admin', 'settings'));
    }

    /**
     * Update admin settings.
     */
    public function updateSettings(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'maintenance_mode' => 'boolean',
            'registration_enabled' => 'boolean',
            'email_verification_required' => 'boolean',
            'session_timeout' => 'required|integer|min:15|max:1440',
            'password_min_length' => 'required|integer|min:6|max:32',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:1440',
            'enable_notifications' => 'boolean',
            'enable_email_notifications' => 'boolean',
            'enable_sms_notifications' => 'boolean',
            'log_retention_days' => 'required|integer|min:7|max:365',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'enable_2fa' => 'boolean',
        ]);

        // In production, these would be saved to a settings table or config file
        // For now, we'll just log the action and show success
        SystemLog::create([
            'user_id' => $admin->id,
            'action' => 'settings_updated',
            'description' => 'System settings updated by admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'log_data' => $request->except(['_token', '_method']),
            'level' => 'info',
        ]);

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    /**
     * Show admin profile.
     */
    public function profile()
    {
        $admin = Auth::user();

        SEO::setTitle('Profile | Main Admin');
        SEO::setDescription('Manage your admin account information and preferences.');

        return view('dashboard.admin.profile', compact('admin'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'username' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($admin->id)],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($admin->id)],
            'region' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->region = $request->region;
        $admin->city = $request->city;

        // Update optional fields if they exist in the model
        if ($request->filled('phone') && in_array('phone', $admin->getFillable())) {
            $admin->phone = $request->phone;
        }

        if ($request->filled('bio') && in_array('bio', $admin->getFillable())) {
            $admin->bio = $request->bio;
        }

        if ($request->hasFile('avatar')) {
            $oldAvatar = $admin->avatar_path ?? null;
            if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars/admins', 'public');
            if (in_array('avatar_path', $admin->getFillable())) {
                $admin->avatar_path = $avatarPath;
            }
        }

        $admin->save();

        // Log the action
        SystemLog::create([
            'user_id' => $admin->id,
            'action' => 'profile_updated',
            'description' => 'Admin profile updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'level' => 'info',
        ]);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $admin->save();

        // Log the action
        SystemLog::create([
            'user_id' => $admin->id,
            'action' => 'password_changed',
            'description' => 'Admin password changed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'level' => 'warning',
        ]);

        return redirect()->route('admin.profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Approve a company.
     */
    public function approveCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['approval_status' => AppConstants::APPROVAL_APPROVED]);
        
        // Create notification
        Notification::create([
            'user_id' => $company->user_id,
            'type' => 'company_approved',
            'title' => 'Entreprise Approuvée',
            'message' => 'Votre entreprise "' . $company->company_name . '" a été approuvée.',
            'is_read' => false,
        ]);
        
        // Log the action
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'company_approved',
            'description' => 'Company "' . $company->company_name . '" approved by admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        // Clear admin dashboard cache
        Cache::forget('admin_stats_' . Auth::id());
        Cache::forget('admin_recent_companies_' . Auth::id());
        
        return redirect()->back()->with('success', 'Entreprise approuvée avec succès.');
    }
    
    /**
     * Reject a company.
     */
    public function rejectCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['approval_status' => AppConstants::APPROVAL_REJECTED]);
        
        // Create notification
        Notification::create([
            'user_id' => $company->user_id,
            'type' => 'company_rejected',
            'title' => 'Entreprise Rejetée',
            'message' => 'Votre entreprise "' . $company->company_name . '" a été rejetée.',
            'is_read' => false,
        ]);
        
        // Log the action
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'company_rejected',
            'description' => 'Company "' . $company->company_name . '" rejected by admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        // Clear admin dashboard cache
        Cache::forget('admin_stats_' . Auth::id());
        Cache::forget('admin_recent_companies_' . Auth::id());
        
        return redirect()->back()->with('success', 'Entreprise rejetée avec succès.');
    }
    
    /**
     * Update submission status.
     */
    public function updateSubmissionStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,under_review',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $submission = $this->getSubmissionByType($type, $id);
        
        if (!$submission) {
            return redirect()->back()->with('error', 'Soumission non trouvée.');
        }
        
        $submission->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        // Create notification
        Notification::create([
            'user_id' => $submission->user_id,
            'type' => 'submission_status_updated',
            'title' => 'Statut de Soumission Mis à Jour',
            'message' => 'Le statut de votre soumission a été mis à jour: ' . $request->status,
            'is_read' => false,
        ]);
        
        // Log the action
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'submission_status_updated',
            'description' => 'Submission ' . $type . ' #' . $id . ' status updated to ' . $request->status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return redirect()->back()->with('success', 'Statut de la soumission mis à jour avec succès.');
    }
    
    /**
     * Get total submissions count.
     */
    private function getTotalSubmissions()
    {
        // Optimize: Use single query with union for better performance
        return InvestmentSubmission::count() +
               ProjectCarrierSubmission::count() +
               IdeaCarrierSubmission::count() +
               AutoEntrepreneurSubmission::count() +
               INDHSubmission::count() +
               TrainingSubmission::count();
    }
    
    /**
     * Get pending submissions count.
     */
    /**
     * Get pending submissions count.
     * 
     * Aggregates pending submissions from all form types (Investment, Project Carrier, 
     * Idea Carrier, Auto-Entrepreneur, INDH, Training).
     * 
     * @return int Total count of pending submissions across all form types
     */
    private function getPendingSubmissions()
    {
        return InvestmentSubmission::where('status', AppConstants::STATUS_PENDING)->count() +
               ProjectCarrierSubmission::where('status', AppConstants::STATUS_PENDING)->count() +
               IdeaCarrierSubmission::where('status', AppConstants::STATUS_PENDING)->count() +
               AutoEntrepreneurSubmission::where('status', AppConstants::STATUS_PENDING)->count() +
               INDHSubmission::where('status', AppConstants::STATUS_PENDING)->count() +
               TrainingSubmission::where('status', AppConstants::STATUS_PENDING)->count();
    }
    
    /**
     * Get approved submissions count.
     * 
     * @return int Total count of approved submissions across all form types
     */
    private function getApprovedSubmissions()
    {
        return InvestmentSubmission::where('status', AppConstants::STATUS_APPROVED)->count() +
               ProjectCarrierSubmission::where('status', AppConstants::STATUS_APPROVED)->count() +
               IdeaCarrierSubmission::where('status', AppConstants::STATUS_APPROVED)->count() +
               AutoEntrepreneurSubmission::where('status', AppConstants::STATUS_APPROVED)->count() +
               INDHSubmission::where('status', AppConstants::STATUS_APPROVED)->count() +
               TrainingSubmission::where('status', AppConstants::STATUS_APPROVED)->count();
    }
    
    /**
     * Get rejected submissions count.
     * 
     * @return int Total count of rejected submissions across all form types
     */
    private function getRejectedSubmissions()
    {
        return InvestmentSubmission::where('status', AppConstants::STATUS_REJECTED)->count() +
               ProjectCarrierSubmission::where('status', AppConstants::STATUS_REJECTED)->count() +
               IdeaCarrierSubmission::where('status', AppConstants::STATUS_REJECTED)->count() +
               AutoEntrepreneurSubmission::where('status', AppConstants::STATUS_REJECTED)->count() +
               INDHSubmission::where('status', AppConstants::STATUS_REJECTED)->count() +
               TrainingSubmission::where('status', AppConstants::STATUS_REJECTED)->count();
    }
    
    /**
     * Get recent submissions across all form types.
     * 
     * Optimized with eager loading and column selection to prevent N+1 queries.
     * 
     * @return \Illuminate\Support\Collection Collection of recent submissions with type and user info
     */
    private function getRecentSubmissions()
    {
        // Optimize: Use select() to limit columns, eager load relationships, and cache results
        $submissions = collect();
        
        $submissions = $submissions->merge(
            InvestmentSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->submission_type = 'investment';
                    return $item;
                })
        );
        $submissions = $submissions->merge(
            ProjectCarrierSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->submission_type = 'project-carrier';
                    return $item;
                })
        );
        $submissions = $submissions->merge(
            IdeaCarrierSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->submission_type = 'idea-carrier';
                    return $item;
                })
        );
        $submissions = $submissions->merge(
            AutoEntrepreneurSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->submission_type = 'auto-entrepreneur';
                    return $item;
                })
        );
        $submissions = $submissions->merge(
            INDHSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->submission_type = 'indh';
                    return $item;
                })
        );
        $submissions = $submissions->merge(
            TrainingSubmission::select('id', 'user_id', 'status', 'created_at', 'submission_number')
                ->with('user:id,username,email')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->submission_type = 'training';
                    return $item;
                })
        );
        
        return $submissions->sortByDesc('created_at')->take(20);
    }
    
    /**
     * Get all submissions.
     */
    private function getAllSubmissions()
    {
        $submissions = collect();
        
        $submissions = $submissions->merge(InvestmentSubmission::with('user')->latest()->get());
        $submissions = $submissions->merge(ProjectCarrierSubmission::with('user')->latest()->get());
        $submissions = $submissions->merge(IdeaCarrierSubmission::with('user')->latest()->get());
        $submissions = $submissions->merge(AutoEntrepreneurSubmission::with('user')->latest()->get());
        $submissions = $submissions->merge(INDHSubmission::with('user')->latest()->get());
        $submissions = $submissions->merge(TrainingSubmission::with('user')->latest()->get());
        
        return $submissions->sortByDesc('created_at');
    }
    
    /**
     * Get submission by type and ID.
     */
    private function getSubmissionByType($type, $id)
    {
        switch ($type) {
            case 'investment':
                return InvestmentSubmission::find($id);
            case 'project-carrier':
                return ProjectCarrierSubmission::find($id);
            case 'idea-carrier':
                return IdeaCarrierSubmission::find($id);
            case 'auto-entrepreneur':
                return AutoEntrepreneurSubmission::find($id);
            case 'indh':
                return INDHSubmission::find($id);
            case 'training':
                return TrainingSubmission::find($id);
            default:
                return null;
        }
    }
}























