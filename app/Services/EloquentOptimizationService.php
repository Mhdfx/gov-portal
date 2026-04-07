<?php

namespace App\Services;

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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentOptimizationService
{
    /**
     * Optimize user queries with eager loading
     */
    public function getUsersWithRelations(array $relations = ['profile', 'company'])
    {
        return User::with($relations)
            ->select(['id', 'username', 'email', 'role', 'verification_status', 'created_at', 'last_login_at'])
            ->get();
    }

    /**
     * Optimize company queries with eager loading
     */
    public function getCompaniesWithRelations(array $relations = ['user', 'jobListings'])
    {
        return Company::with($relations)
            ->select(['id', 'user_id', 'company_name', 'business_sectors', 'region', 'approval_status', 'created_at'])
            ->get();
    }

    /**
     * Optimize submission queries with eager loading
     */
    public function getSubmissionsWithRelations(string $type, array $relations = ['user'])
    {
        $model = $this->getSubmissionModel($type);
        
        return $model::with($relations)
            ->select(['id', 'user_id', 'status', 'created_at', 'updated_at'])
            ->get();
    }

    /**
     * Get paginated results with optimized queries
     */
    public function getPaginatedResults(string $model, int $perPage = 15, array $relations = [])
    {
        $query = $model::query();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Optimize dashboard queries with chunking
     */
    public function getDashboardDataOptimized()
    {
        $data = [];
        
        // Use chunking for large datasets
        User::select(['id', 'role', 'created_at'])
            ->chunk(1000, function ($users) use (&$data) {
                foreach ($users as $user) {
                    $data['users_by_role'][$user->role] = ($data['users_by_role'][$user->role] ?? 0) + 1;
                }
            });
        
        Company::select(['id', 'approval_status', 'created_at'])
            ->chunk(1000, function ($companies) use (&$data) {
                foreach ($companies as $company) {
                    $data['companies_by_status'][$company->approval_status] = ($data['companies_by_status'][$company->approval_status] ?? 0) + 1;
                }
            });
        
        return $data;
    }

    /**
     * Optimize search queries with full-text search
     */
    public function searchUsersOptimized(string $query, int $limit = 20)
    {
        return User::whereRaw("MATCH(username, email) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->select(['id', 'username', 'email', 'role'])
            ->limit($limit)
            ->get();
    }

    /**
     * Optimize search queries for companies
     */
    public function searchCompaniesOptimized(string $query, int $limit = 20)
    {
        return Company::whereRaw("MATCH(company_name, business_sectors) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->select(['id', 'company_name', 'business_sectors', 'region'])
            ->limit($limit)
            ->get();
    }

    /**
     * Optimize submission statistics queries
     */
    public function getSubmissionStatisticsOptimized()
    {
        $statistics = [];
        
        $submissionTypes = [
            'auto_entrepreneur' => AutoEntrepreneurSubmission::class,
            'idea_carrier' => IdeaCarrierSubmission::class,
            'project_carrier' => ProjectCarrierSubmission::class,
            'investment' => InvestmentSubmission::class,
            'indh' => INDHSubmission::class,
            'training' => TrainingSubmission::class,
        ];
        
        foreach ($submissionTypes as $type => $model) {
            $statistics[$type] = $model::selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending,
                COUNT(CASE WHEN status = "approved" THEN 1 END) as approved,
                COUNT(CASE WHEN status = "rejected" THEN 1 END) as rejected,
                COUNT(CASE WHEN status = "in_review" THEN 1 END) as in_review
            ')->first();
        }
        
        return $statistics;
    }

    /**
     * Optimize user activity queries
     */
    public function getUserActivityOptimized(int $days = 30)
    {
        return User::selectRaw('
            DATE(last_login_at) as date,
            COUNT(*) as active_users
        ')
        ->where('last_login_at', '>=', now()->subDays($days))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    /**
     * Optimize company approval queries
     */
    public function getCompanyApprovalStatsOptimized()
    {
        return Company::selectRaw('
            approval_status,
            COUNT(*) as count,
            AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_approval_time_hours
        ')
        ->groupBy('approval_status')
        ->get();
    }

    /**
     * Optimize job listing queries
     */
    public function getJobListingsOptimized(array $filters = [])
    {
        $query = JobListing::with(['company'])
            ->select(['id', 'company_id', 'title', 'location', 'employment_type', 'status', 'created_at']);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }
        
        if (isset($filters['employment_type'])) {
            $query->where('employment_type', $filters['employment_type']);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Optimize candidate queries
     */
    public function getCandidatesOptimized(array $filters = [])
    {
        $query = Candidate::select(['id', 'full_name', 'email', 'region', 'education_level', 'is_available', 'created_at']);
        
        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }
        
        if (isset($filters['education_level'])) {
            $query->where('education_level', $filters['education_level']);
        }
        
        if (isset($filters['is_available'])) {
            $query->where('is_available', $filters['is_available']);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Optimize blog article queries
     */
    public function getBlogArticlesOptimized(array $filters = [])
    {
        $query = BlogArticle::select(['id', 'title', 'slug', 'category', 'status', 'is_featured', 'created_at']);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get submission model by type
     */
    private function getSubmissionModel(string $type): string
    {
        return match($type) {
            'auto_entrepreneur' => AutoEntrepreneurSubmission::class,
            'idea_carrier' => IdeaCarrierSubmission::class,
            'project_carrier' => ProjectCarrierSubmission::class,
            'investment' => InvestmentSubmission::class,
            'indh' => INDHSubmission::class,
            'training' => TrainingSubmission::class,
            default => AutoEntrepreneurSubmission::class,
        };
    }

    /**
     * Optimize database queries with query builder
     */
    public function getOptimizedQueryResults(string $table, array $select = ['*'], array $where = [], int $limit = 100)
    {
        $query = DB::table($table)->select($select);
        
        foreach ($where as $column => $value) {
            $query->where($column, $value);
        }
        
        return $query->limit($limit)->get();
    }

    /**
     * Get database query performance metrics
     */
    public function getQueryPerformanceMetrics()
    {
        $queries = DB::select('SHOW STATUS LIKE "Slow_queries"');
        $slowQueries = $queries[0]->Value ?? 0;
        
        $queries = DB::select('SHOW STATUS LIKE "Queries"');
        $totalQueries = $queries[0]->Value ?? 0;
        
        return [
            'slow_queries' => $slowQueries,
            'total_queries' => $totalQueries,
            'slow_query_percentage' => $totalQueries > 0 ? round(($slowQueries / $totalQueries) * 100, 2) : 0,
        ];
    }
}






























