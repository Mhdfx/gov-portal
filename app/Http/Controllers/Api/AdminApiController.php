<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Company;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\PorteurIdeeSubmission;
use App\Models\PorteurProjetSubmission;
use App\Models\InvestmentSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\Candidate;
use App\Models\JobListing;
use App\Models\BlogArticle;
use App\Models\NewsletterSubscription;
use App\Http\Resources\UserResource;
use App\Http\Resources\AutoEntrepreneurResource;
use App\Http\Resources\PorteurIdeeResource;
use App\Http\Resources\PorteurProjetResource;
use App\Http\Resources\InvestmentResource;
use App\Http\Resources\INDHResource;
use App\Http\Resources\TrainingResource;
use App\Http\Resources\CandidateResource;

class AdminApiController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function dashboard(): JsonResponse
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'by_role' => User::selectRaw('role, count(*) as count')
                    ->groupBy('role')
                    ->get()
                    ->pluck('count', 'role'),
            ],
            'companies' => [
                'total' => Company::count(),
                'approved' => Company::where('status', 'approved')->count(),
                'pending' => Company::where('status', 'pending')->count(),
            ],
            'submissions' => [
                'auto_entrepreneur' => AutoEntrepreneurSubmission::count(),
                'porteur_idee' => PorteurIdeeSubmission::count(),
                'porteur_projet' => PorteurProjetSubmission::count(),
                'investment' => InvestmentSubmission::count(),
                'indh' => INDHSubmission::count(),
                'training' => TrainingSubmission::count(),
                'total' => AutoEntrepreneurSubmission::count() + 
                          PorteurIdeeSubmission::count() + 
                          PorteurProjetSubmission::count() + 
                          InvestmentSubmission::count() + 
                          INDHSubmission::count() + 
                          TrainingSubmission::count(),
            ],
            'candidates' => [
                'total' => Candidate::count(),
                'active' => Candidate::where('is_available', true)->count(),
                'verified' => Candidate::where('is_verified', true)->count(),
            ],
            'jobs' => [
                'total' => JobListing::count(),
                'active' => JobListing::where('status', 'active')->count(),
                'featured' => JobListing::where('is_featured', true)->count(),
            ],
            'blog' => [
                'total' => BlogArticle::count(),
                'published' => BlogArticle::where('status', 'published')->count(),
                'featured' => BlogArticle::where('is_featured', true)->count(),
            ],
            'newsletter' => [
                'total' => NewsletterSubscription::count(),
                'active' => NewsletterSubscription::where('status', 'active')->count(),
                'recent' => NewsletterSubscription::recent(30)->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get all users with pagination.
     */
    public function users(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users->items()),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Get all companies with pagination.
     */
    public function companies(Request $request): JsonResponse
    {
        $query = Company::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $companies = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $companies,
            'pagination' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total' => $companies->total(),
            ],
        ]);
    }

    /**
     * Get all submissions with pagination.
     */
    public function submissions(Request $request): JsonResponse
    {
        $type = $request->get('type', 'all');
        $perPage = $request->get('per_page', 15);

        $submissions = collect();

        if ($type === 'all' || $type === 'auto_entrepreneur') {
            $autoEntrepreneur = AutoEntrepreneurSubmission::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'auto_entrepreneur';
                    return $item;
                });
            $submissions = $submissions->merge($autoEntrepreneur);
        }

        if ($type === 'all' || $type === 'porteur_idee') {
            $porteurIdee = PorteurIdeeSubmission::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'porteur_idee';
                    return $item;
                });
            $submissions = $submissions->merge($porteurIdee);
        }

        if ($type === 'all' || $type === 'porteur_projet') {
            $porteurProjet = PorteurProjetSubmission::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'porteur_projet';
                    return $item;
                });
            $submissions = $submissions->merge($porteurProjet);
        }

        if ($type === 'all' || $type === 'investment') {
            $investment = InvestmentSubmission::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'investment';
                    return $item;
                });
            $submissions = $submissions->merge($investment);
        }

        if ($type === 'all' || $type === 'indh') {
            $indh = INDHSubmission::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'indh';
                    return $item;
                });
            $submissions = $submissions->merge($indh);
        }

        if ($type === 'all' || $type === 'training') {
            $training = TrainingSubmission::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    $item->submission_type = 'training';
                    return $item;
                });
            $submissions = $submissions->merge($training);
        }

        $submissions = $submissions->sortByDesc('created_at');
        $total = $submissions->count();
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginatedSubmissions = $submissions->slice($offset, $perPage)->values();

        return response()->json([
            'success' => true,
            'data' => $paginatedSubmissions,
            'pagination' => [
                'current_page' => (int) $page,
                'last_page' => ceil($total / $perPage),
                'per_page' => $perPage,
                'total' => $total,
            ],
        ]);
    }

    /**
     * Get all candidates with pagination.
     */
    public function candidates(Request $request): JsonResponse
    {
        $query = Candidate::with('user');

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_available', true);
            } elseif ($request->status === 'verified') {
                $query->where('is_verified', true);
            }
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $candidates = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => CandidateResource::collection($candidates->items()),
            'pagination' => [
                'current_page' => $candidates->currentPage(),
                'last_page' => $candidates->lastPage(),
                'per_page' => $candidates->perPage(),
                'total' => $candidates->total(),
            ],
        ]);
    }

    /**
     * Get all job listings with pagination.
     */
    public function jobListings(Request $request): JsonResponse
    {
        $query = JobListing::with('company');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $jobListings = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $jobListings,
            'pagination' => [
                'current_page' => $jobListings->currentPage(),
                'last_page' => $jobListings->lastPage(),
                'per_page' => $jobListings->perPage(),
                'total' => $jobListings->total(),
            ],
        ]);
    }

    /**
     * Get all blog articles with pagination.
     */
    public function blogArticles(Request $request): JsonResponse
    {
        $query = BlogArticle::with('author');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $articles,
            'pagination' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    /**
     * Get all newsletter subscriptions with pagination.
     */
    public function newsletterSubscriptions(Request $request): JsonResponse
    {
        $query = NewsletterSubscription::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $subscriptions = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
            'pagination' => [
                'current_page' => $subscriptions->currentPage(),
                'last_page' => $subscriptions->lastPage(),
                'per_page' => $subscriptions->perPage(),
                'total' => $subscriptions->total(),
            ],
        ]);
    }

    /**
     * Update user status.
     */
    public function updateUserStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update company status.
     */
    public function updateCompanyStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $company = Company::findOrFail($id);
        $company->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Company status updated successfully.',
            'data' => $company,
        ]);
    }

    /**
     * Get system statistics for charts.
     */
    public function statistics(Request $request): JsonResponse
    {
        $period = $request->get('period', '30'); // days

        $stats = [
            'users_registration' => User::where('created_at', '>=', now()->subDays($period))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'submissions_by_type' => [
                'auto_entrepreneur' => AutoEntrepreneurSubmission::count(),
                'porteur_idee' => PorteurIdeeSubmission::count(),
                'porteur_projet' => PorteurProjetSubmission::count(),
                'investment' => InvestmentSubmission::count(),
                'indh' => INDHSubmission::count(),
                'training' => TrainingSubmission::count(),
            ],
            'submissions_by_status' => [
                'pending' => AutoEntrepreneurSubmission::where('status', 'pending')->count() +
                           PorteurIdeeSubmission::where('status', 'pending')->count() +
                           PorteurProjetSubmission::where('status', 'pending')->count() +
                           InvestmentSubmission::where('status', 'pending')->count() +
                           INDHSubmission::where('status', 'pending')->count() +
                           TrainingSubmission::where('status', 'pending')->count(),
                'approved' => AutoEntrepreneurSubmission::where('status', 'approved')->count() +
                            PorteurIdeeSubmission::where('status', 'approved')->count() +
                            PorteurProjetSubmission::where('status', 'approved')->count() +
                            InvestmentSubmission::where('status', 'approved')->count() +
                            INDHSubmission::where('status', 'approved')->count() +
                            TrainingSubmission::where('status', 'approved')->count(),
                'rejected' => AutoEntrepreneurSubmission::where('status', 'rejected')->count() +
                            PorteurIdeeSubmission::where('status', 'rejected')->count() +
                            PorteurProjetSubmission::where('status', 'rejected')->count() +
                            InvestmentSubmission::where('status', 'rejected')->count() +
                            INDHSubmission::where('status', 'rejected')->count() +
                            TrainingSubmission::where('status', 'rejected')->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}