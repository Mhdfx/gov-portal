<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\CacheService;
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

class StatisticsService
{
    /**
     * Get dashboard statistics with caching.
     */
    public function getDashboardStatistics(): array
    {
        return Cache::remember('dashboard_statistics', 300, function () {
            return [
                'users' => $this->getUserStatistics(),
                'companies' => $this->getCompanyStatistics(),
                'submissions' => $this->getSubmissionStatistics(),
                'candidates' => $this->getCandidateStatistics(),
                'jobs' => $this->getJobStatistics(),
                'blog' => $this->getBlogStatistics(),
                'newsletter' => $this->getNewsletterStatistics(),
            ];
        });
    }

    /**
     * Get user statistics.
     */
    public function getUserStatistics(): array
    {
        return Cache::remember('user_statistics', 600, function () {
            return [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'by_role' => User::selectRaw('role, count(*) as count')
                    ->groupBy('role')
                    ->get()
                    ->pluck('count', 'role'),
                'monthly_registrations' => $this->getMonthlyRegistrations(User::class),
                'recent_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
            ];
        });
    }

    /**
     * Get company statistics.
     */
    public function getCompanyStatistics(): array
    {
        return Cache::remember('company_statistics', 600, function () {
            return [
                'total' => Company::count(),
                'approved' => Company::where('status', 'approved')->count(),
                'pending' => Company::where('status', 'pending')->count(),
                'rejected' => Company::where('status', 'rejected')->count(),
                'by_sector' => Company::selectRaw('sector, count(*) as count')
                    ->groupBy('sector')
                    ->get()
                    ->pluck('count', 'sector'),
                'by_region' => Company::selectRaw('region, count(*) as count')
                    ->groupBy('region')
                    ->get()
                    ->pluck('count', 'region'),
            ];
        });
    }

    /**
     * Get submission statistics.
     */
    public function getSubmissionStatistics(): array
    {
        return Cache::remember('submission_statistics', 300, function () {
            $autoEntrepreneur = AutoEntrepreneurSubmission::count();
            $porteurIdee = PorteurIdeeSubmission::count();
            $porteurProjet = PorteurProjetSubmission::count();
            $investment = InvestmentSubmission::count();
            $indh = INDHSubmission::count();
            $training = TrainingSubmission::count();

            return [
                'total' => $autoEntrepreneur + $porteurIdee + $porteurProjet + $investment + $indh + $training,
                'auto_entrepreneur' => $autoEntrepreneur,
                'porteur_idee' => $porteurIdee,
                'porteur_projet' => $porteurProjet,
                'investment' => $investment,
                'indh' => $indh,
                'training' => $training,
                'by_status' => $this->getSubmissionStatusStatistics(),
                'by_region' => $this->getSubmissionRegionStatistics(),
                'by_sector' => $this->getSubmissionSectorStatistics(),
                'monthly_submissions' => $this->getMonthlySubmissions(),
            ];
        });
    }

    /**
     * Get candidate statistics.
     */
    public function getCandidateStatistics(): array
    {
        return Cache::remember('candidate_statistics', 600, function () {
            return [
                'total' => Candidate::count(),
                'active' => Candidate::where('is_available', true)->count(),
                'verified' => Candidate::where('is_verified', true)->count(),
                'by_region' => Candidate::selectRaw('region, count(*) as count')
                    ->groupBy('region')
                    ->get()
                    ->pluck('count', 'region'),
                'by_education' => Candidate::selectRaw('education_level, count(*) as count')
                    ->groupBy('education_level')
                    ->get()
                    ->pluck('count', 'education_level'),
                'recent_registrations' => Candidate::where('created_at', '>=', now()->subDays(30))->count(),
            ];
        });
    }

    /**
     * Get job statistics.
     */
    public function getJobStatistics(): array
    {
        return Cache::remember('job_statistics', 300, function () {
            return [
                'total' => JobListing::count(),
                'active' => JobListing::where('status', 'active')->count(),
                'featured' => JobListing::where('is_featured', true)->count(),
                'by_region' => JobListing::selectRaw('region, count(*) as count')
                    ->groupBy('region')
                    ->get()
                    ->pluck('count', 'region'),
                'by_type' => JobListing::selectRaw('job_type, count(*) as count')
                    ->groupBy('job_type')
                    ->get()
                    ->pluck('count', 'job_type'),
                'monthly_postings' => $this->getMonthlyRegistrations(JobListing::class),
            ];
        });
    }

    /**
     * Get blog statistics.
     */
    public function getBlogStatistics(): array
    {
        return Cache::remember('blog_statistics', 600, function () {
            return [
                'total' => BlogArticle::count(),
                'published' => BlogArticle::where('status', 'published')->count(),
                'featured' => BlogArticle::where('is_featured', true)->count(),
                'by_category' => BlogArticle::selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->get()
                    ->pluck('count', 'category'),
                'monthly_articles' => $this->getMonthlyRegistrations(BlogArticle::class),
            ];
        });
    }

    /**
     * Get newsletter statistics.
     */
    public function getNewsletterStatistics(): array
    {
        return Cache::remember('newsletter_statistics', 600, function () {
            return [
                'total' => NewsletterSubscription::count(),
                'active' => NewsletterSubscription::where('status', 'active')->count(),
                'inactive' => NewsletterSubscription::where('status', 'inactive')->count(),
                'unsubscribed' => NewsletterSubscription::where('status', 'unsubscribed')->count(),
                'recent_subscriptions' => NewsletterSubscription::where('created_at', '>=', now()->subDays(30))->count(),
                'monthly_subscriptions' => $this->getMonthlyRegistrations(NewsletterSubscription::class),
            ];
        });
    }

    /**
     * Get submission status statistics.
     */
    private function getSubmissionStatusStatistics(): array
    {
        $statuses = ['pending', 'approved', 'rejected', 'in_review'];
        $result = [];

        foreach ($statuses as $status) {
            $result[$status] = AutoEntrepreneurSubmission::where('status', $status)->count() +
                              PorteurIdeeSubmission::where('status', $status)->count() +
                              PorteurProjetSubmission::where('status', $status)->count() +
                              InvestmentSubmission::where('status', $status)->count() +
                              INDHSubmission::where('status', $status)->count() +
                              TrainingSubmission::where('status', $status)->count();
        }

        return $result;
    }

    /**
     * Get submission region statistics.
     */
    private function getSubmissionRegionStatistics(): array
    {
        $regions = AutoEntrepreneurSubmission::selectRaw('business_region as region, count(*) as count')
            ->groupBy('business_region')
            ->get()
            ->pluck('count', 'region');

        return $regions->toArray();
    }

    /**
     * Get submission sector statistics.
     */
    private function getSubmissionSectorStatistics(): array
    {
        $sectors = AutoEntrepreneurSubmission::selectRaw('business_sector as sector, count(*) as count')
            ->groupBy('business_sector')
            ->get()
            ->pluck('count', 'sector');

        return $sectors->toArray();
    }

    /**
     * Get monthly registrations for a model.
     */
    private function getMonthlyRegistrations(string $model): array
    {
        return $model::where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
    }

    /**
     * Get monthly submissions.
     */
    private function getMonthlySubmissions(): array
    {
        $months = [];
        $currentDate = now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $month = $currentDate->format('Y-m');
            $nextMonth = $currentDate->copy()->addMonth();

            $count = AutoEntrepreneurSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count() +
                    PorteurIdeeSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count() +
                    PorteurProjetSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count() +
                    InvestmentSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count() +
                    INDHSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count() +
                    TrainingSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();

            $months[$month] = $count;
            $currentDate->addMonth();
        }

        return $months;
    }

    /**
     * Get performance metrics.
     */
    public function getPerformanceMetrics(): array
    {
        return Cache::remember('performance_metrics', 1800, function () {
            return [
                'response_times' => $this->getResponseTimeMetrics(),
                'error_rates' => $this->getErrorRateMetrics(),
                'user_activity' => $this->getUserActivityMetrics(),
                'system_health' => $this->getSystemHealthMetrics(),
            ];
        });
    }

    /**
     * Get response time metrics.
     */
    private function getResponseTimeMetrics(): array
    {
        // This would typically come from monitoring tools
        return [
            'average' => 150, // ms
            'p95' => 300, // ms
            'p99' => 500, // ms
        ];
    }

    /**
     * Get error rate metrics.
     */
    private function getErrorRateMetrics(): array
    {
        // This would typically come from monitoring tools
        return [
            'total_errors' => 0,
            'error_rate' => 0.01, // 1%
            'critical_errors' => 0,
        ];
    }

    /**
     * Get user activity metrics.
     */
    private function getUserActivityMetrics(): array
    {
        return [
            'active_users_today' => User::where('last_login_at', '>=', now()->startOfDay())->count(),
            'active_users_week' => User::where('last_login_at', '>=', now()->subWeek())->count(),
            'active_users_month' => User::where('last_login_at', '>=', now()->subMonth())->count(),
        ];
    }

    /**
     * Get system health metrics.
     */
    private function getSystemHealthMetrics(): array
    {
        return [
            'database_connections' => DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0,
            'cache_hit_rate' => 0.95, // 95%
            'disk_usage' => $this->getDiskUsage(),
            'memory_usage' => $this->getMemoryUsage(),
        ];
    }

    /**
     * Get disk usage percentage.
     */
    private function getDiskUsage(): float
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        return round((($total - $free) / $total) * 100, 2);
    }

    /**
     * Get memory usage percentage.
     */
    private function getMemoryUsage(): float
    {
        $memory = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        return round(($memory / $memoryLimitBytes) * 100, 2);
    }

    /**
     * Convert memory limit string to bytes.
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Clear all statistics cache.
     */
    public function clearCache(): void
    {
        CacheService::invalidateStatistics();
        Cache::forget('dashboard_statistics');
        Cache::forget('user_statistics');
        Cache::forget('company_statistics');
        Cache::forget('submission_statistics');
        Cache::forget('candidate_statistics');
        Cache::forget('job_statistics');
        Cache::forget('blog_statistics');
        Cache::forget('newsletter_statistics');
        Cache::forget('performance_metrics');
    }

    /**
     * Get export data for reports.
     */
    public function getExportData(string $type, array $filters = []): array
    {
        switch ($type) {
            case 'users':
                return $this->getUserExportData($filters);
            case 'companies':
                return $this->getCompanyExportData($filters);
            case 'submissions':
                return $this->getSubmissionExportData($filters);
            case 'candidates':
                return $this->getCandidateExportData($filters);
            case 'jobs':
                return $this->getJobExportData($filters);
            default:
                return [];
        }
    }

    /**
     * Get user export data.
     */
    private function getUserExportData(array $filters): array
    {
        $query = User::query();

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get company export data.
     */
    private function getCompanyExportData(array $filters): array
    {
        $query = Company::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['sector'])) {
            $query->where('sector', $filters['sector']);
        }

        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get submission export data.
     */
    private function getSubmissionExportData(array $filters): array
    {
        $submissions = collect();

        // Get all submission types
        $types = ['auto_entrepreneur', 'porteur_idee', 'porteur_projet', 'investment', 'indh', 'training'];
        
        foreach ($types as $type) {
            $model = $this->getSubmissionModel($type);
            $query = $model::query();

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['date_from'])) {
                $query->where('created_at', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $query->where('created_at', '<=', $filters['date_to']);
            }

            $typeSubmissions = $query->get()->map(function($submission) use ($type) {
                $submission->submission_type = $type;
                return $submission;
            });

            $submissions = $submissions->merge($typeSubmissions);
        }

        return $submissions->sortByDesc('created_at')->values()->toArray();
    }

    /**
     * Get candidate export data.
     */
    private function getCandidateExportData(array $filters): array
    {
        $query = Candidate::query();

        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (isset($filters['education'])) {
            $query->where('education_level', $filters['education']);
        }

        if (isset($filters['available'])) {
            $query->where('is_available', $filters['available']);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get job export data.
     */
    private function getJobExportData(array $filters): array
    {
        $query = JobListing::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (isset($filters['type'])) {
            $query->where('job_type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get submission model by type.
     */
    private function getSubmissionModel(string $type): string
    {
        return match($type) {
            'auto_entrepreneur' => AutoEntrepreneurSubmission::class,
            'porteur_idee' => PorteurIdeeSubmission::class,
            'porteur_projet' => PorteurProjetSubmission::class,
            'investment' => InvestmentSubmission::class,
            'indh' => INDHSubmission::class,
            'training' => TrainingSubmission::class,
            default => AutoEntrepreneurSubmission::class,
        };
    }
}
