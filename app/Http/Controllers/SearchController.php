<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Company;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\InvestmentSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\Candidate;
use App\Models\JobListing;
use App\Models\BlogArticle;
use App\Models\NewsletterSubscription;
use SEO;

class SearchController extends Controller
{
    /**
     * Global search functionality.
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
            ], 400);
        }

        $results = collect();
        $totalResults = 0;

        // Search users
        if ($type === 'all' || $type === 'users') {
            $users = User::where(function($q) use ($query) {
                $q->where('username', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->where('verification_status', 'verified')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user',
                    'id' => $user->id,
                    'title' => $user->username,
                    'description' => $user->email,
                    'url' => route('admin.users'),
                    'created_at' => $user->created_at,
                ];
            });

            $results = $results->merge($users);
        }

        // Search companies
        if ($type === 'all' || $type === 'companies') {
            $companies = Company::where(function($q) use ($query) {
                $q->where('company_name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->limit(5)
            ->get()
            ->map(function($company) {
                return [
                    'type' => 'company',
                    'id' => $company->id,
                    'title' => $company->company_name,
                    'description' => $company->description,
                    'url' => route('admin.companies'),
                    'created_at' => $company->created_at,
                ];
            });

            $results = $results->merge($companies);
        }

        // Search submissions
        if ($type === 'all' || $type === 'submissions') {
            // Auto Entrepreneur submissions
            $autoEntrepreneur = AutoEntrepreneurSubmission::where(function($q) use ($query) {
                $q->where('business_name', 'like', '%' . $query . '%')
                  ->orWhere('business_description', 'like', '%' . $query . '%')
                  ->orWhere('sector', 'like', '%' . $query . '%');
            })
            ->limit(3)
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'auto_entrepreneur',
                    'id' => $submission->id,
                    'title' => $submission->business_name,
                    'description' => $submission->business_description,
                    'url' => route('admin.submissions'),
                    'created_at' => $submission->created_at,
                ];
            });

            // Porteur Idee submissions
            $porteurIdee = IdeaCarrierSubmission::where(function($q) use ($query) {
                $q->where('idea_title', 'like', '%' . $query . '%')
                  ->orWhere('idea_description', 'like', '%' . $query . '%')
                  ->orWhere('sector', 'like', '%' . $query . '%');
            })
            ->limit(3)
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'idea_carrier',
                    'id' => $submission->id,
                    'title' => $submission->idea_title,
                    'description' => $submission->idea_description,
                    'url' => route('admin.submissions'),
                    'created_at' => $submission->created_at,
                ];
            });

            // Project Carrier submissions
            $porteurProjet = ProjectCarrierSubmission::where(function($q) use ($query) {
                $q->where('project_name', 'like', '%' . $query . '%')
                  ->orWhere('project_description', 'like', '%' . $query . '%')
                  ->orWhere('sector', 'like', '%' . $query . '%');
            })
            ->limit(3)
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'project_carrier',
                    'id' => $submission->id,
                    'title' => $submission->project_name,
                    'description' => $submission->project_description,
                    'url' => route('admin.submissions'),
                    'created_at' => $submission->created_at,
                ];
            });

            // Investment submissions
            $investment = InvestmentSubmission::where(function($q) use ($query) {
                $q->where('project_name', 'like', '%' . $query . '%')
                  ->orWhere('project_description', 'like', '%' . $query . '%')
                  ->orWhere('sector', 'like', '%' . $query . '%');
            })
            ->limit(3)
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'investment',
                    'id' => $submission->id,
                    'title' => $submission->project_name,
                    'description' => $submission->project_description,
                    'url' => route('admin.submissions'),
                    'created_at' => $submission->created_at,
                ];
            });

            // INDH submissions
            $indh = INDHSubmission::where(function($q) use ($query) {
                $q->where('project_title', 'like', '%' . $query . '%')
                  ->orWhere('project_description', 'like', '%' . $query . '%')
                  ->orWhere('project_sector', 'like', '%' . $query . '%');
            })
            ->limit(3)
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'indh',
                    'id' => $submission->id,
                    'title' => $submission->project_title,
                    'description' => $submission->project_description,
                    'url' => route('admin.submissions'),
                    'created_at' => $submission->created_at,
                ];
            });

            // Training submissions
            $training = TrainingSubmission::where(function($q) use ($query) {
                $q->where('training_title', 'like', '%' . $query . '%')
                  ->orWhere('training_description', 'like', '%' . $query . '%')
                  ->orWhere('training_sector', 'like', '%' . $query . '%');
            })
            ->limit(3)
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'training',
                    'id' => $submission->id,
                    'title' => $submission->training_title,
                    'description' => $submission->training_description,
                    'url' => route('admin.submissions'),
                    'created_at' => $submission->created_at,
                ];
            });

            $results = $results->merge($autoEntrepreneur)
                             ->merge($porteurIdee)
                             ->merge($porteurProjet)
                             ->merge($investment)
                             ->merge($indh)
                             ->merge($training);
        }

        // Search candidates
        if ($type === 'all' || $type === 'candidates') {
            $candidates = Candidate::where(function($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('professional_summary', 'like', '%' . $query . '%');
            })
            ->limit(5)
            ->get()
            ->map(function($candidate) {
                return [
                    'type' => 'candidate',
                    'id' => $candidate->id,
                    'title' => $candidate->full_name,
                    'description' => $candidate->professional_summary,
                    'url' => route('admin.candidates'),
                    'created_at' => $candidate->created_at,
                ];
            });

            $results = $results->merge($candidates);
        }

        // Search job listings
        if ($type === 'all' || $type === 'jobs') {
            $jobs = JobListing::where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('requirements', 'like', '%' . $query . '%');
            })
            ->where('status', 'active')
            ->limit(5)
            ->get()
            ->map(function($job) {
                return [
                    'type' => 'job',
                    'id' => $job->id,
                    'title' => $job->title,
                    'description' => $job->description,
                    'url' => route('jobs.show', $job->id),
                    'created_at' => $job->created_at,
                ];
            });

            $results = $results->merge($jobs);
        }

        // Search blog articles
        if ($type === 'all' || $type === 'blog') {
            $articles = BlogArticle::where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('excerpt', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%');
            })
            ->where('status', 'published')
            ->limit(5)
            ->get()
            ->map(function($article) {
                return [
                    'type' => 'blog',
                    'id' => $article->id,
                    'title' => $article->title,
                    'description' => $article->excerpt,
                    'url' => route('blog.show', $article->id),
                    'created_at' => $article->created_at,
                ];
            });

            $results = $results->merge($articles);
        }

        // Sort results by relevance and date
        $results = $results->sortByDesc('created_at')->values();

        $totalResults = $results->count();
        $offset = ($page - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return response()->json([
            'success' => true,
            'data' => $paginatedResults,
            'pagination' => [
                'current_page' => (int) $page,
                'last_page' => ceil($totalResults / $perPage),
                'per_page' => $perPage,
                'total' => $totalResults,
            ],
            'query' => $query,
            'type' => $type,
        ]);
    }

    /**
     * Search results page.
     */
    public function searchResults(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        SEO::setTitle('Search Results | Boiema Platform');
        SEO::setDescription('Search results for: ' . $query);

        if (empty($query)) {
            return view('search.results', [
                'query' => $query,
                'type' => $type,
                'results' => collect(),
                'totalResults' => 0,
            ]);
        }

        // Get search results
        $searchRequest = new Request([
            'q' => $query,
            'type' => $type,
            'per_page' => 20,
        ]);

        $searchResponse = $this->globalSearch($searchRequest);
        $searchData = $searchResponse->getData(true);

        return view('search.results', [
            'query' => $query,
            'type' => $type,
            'results' => collect($searchData['data']),
            'totalResults' => $searchData['pagination']['total'],
            'pagination' => $searchData['pagination'],
        ]);
    }

    /**
     * Advanced search with filters.
     */
    public function advancedSearch(Request $request)
    {
        $filters = $request->all();
        $results = collect();

        // Date range filter
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Status filter
        $status = $request->get('status');

        // Region filter
        $region = $request->get('region');

        // Sector filter
        $sector = $request->get('sector');

        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
            ], 400);
        }

        // Apply filters to submissions
        if ($request->get('include_submissions', true)) {
            $submissionQuery = AutoEntrepreneurSubmission::query();

            if ($dateFrom) {
                $submissionQuery->where('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $submissionQuery->where('created_at', '<=', $dateTo);
            }

            if ($status) {
                $submissionQuery->where('status', $status);
            }

            if ($region) {
                $submissionQuery->where('location_region', $region);
            }

            if ($sector) {
                $submissionQuery->where('sector', $sector);
            }

            $submissions = $submissionQuery->where(function($q) use ($query) {
                $q->where('business_name', 'like', '%' . $query . '%')
                  ->orWhere('business_description', 'like', '%' . $query . '%');
            })
            ->get()
            ->map(function($submission) {
                return [
                    'type' => 'submission',
                    'submission_type' => 'auto_entrepreneur',
                    'id' => $submission->id,
                    'title' => $submission->business_name,
                    'description' => $submission->business_description,
                    'status' => $submission->status,
                    'region' => $submission->location_region,
                    'sector' => $submission->sector,
                    'created_at' => $submission->created_at,
                ];
            });

            $results = $results->merge($submissions);
        }

        return response()->json([
            'success' => true,
            'data' => $results->values(),
            'filters' => $filters,
            'total' => $results->count(),
        ]);
    }

    /**
     * Get search suggestions.
     */
    public function getSuggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $suggestions = collect();

        // Get suggestions from business names
        $businessNames = AutoEntrepreneurSubmission::where('business_name', 'like', '%' . $query . '%')
            ->distinct()
            ->pluck('business_name')
            ->take(5);

        foreach ($businessNames as $name) {
            $suggestions->push([
                'text' => $name,
                'type' => 'business_name',
            ]);
        }

        // Get suggestions from sectors
        $sectors = AutoEntrepreneurSubmission::where('sector', 'like', '%' . $query . '%')
            ->distinct()
            ->pluck('sector')
            ->take(5);

        foreach ($sectors as $sector) {
            $suggestions->push([
                'text' => $sector,
                'type' => 'sector',
            ]);
        }

        // Get suggestions from regions
        $regions = AutoEntrepreneurSubmission::where('location_region', 'like', '%' . $query . '%')
            ->distinct()
            ->pluck('location_region')
            ->take(5);

        foreach ($regions as $region) {
            $suggestions->push([
                'text' => $region,
                'type' => 'region',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $suggestions->unique('text')->values(),
        ]);
    }
}