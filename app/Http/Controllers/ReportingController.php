<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Services\StatisticsService;
use SEO;

class ReportingController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display reporting dashboard.
     */
    public function index(Request $request)
    {
        SEO::setTitle('Reports & Analytics | Admin Dashboard');
        SEO::setDescription('Comprehensive reports and analytics for the Boiema platform.');

        $statistics = $this->statisticsService->getDashboardStatistics();
        $performanceMetrics = $this->statisticsService->getPerformanceMetrics();

        return view('admin.reports.index', compact('statistics', 'performanceMetrics'));
    }

    /**
     * Get statistics data for charts.
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $type = $request->get('type', 'dashboard');
        
        switch ($type) {
            case 'users':
                $data = $this->statisticsService->getUserStatistics();
                break;
            case 'companies':
                $data = $this->statisticsService->getCompanyStatistics();
                break;
            case 'submissions':
                $data = $this->statisticsService->getSubmissionStatistics();
                break;
            case 'candidates':
                $data = $this->statisticsService->getCandidateStatistics();
                break;
            case 'jobs':
                $data = $this->statisticsService->getJobStatistics();
                break;
            case 'blog':
                $data = $this->statisticsService->getBlogStatistics();
                break;
            case 'newsletter':
                $data = $this->statisticsService->getNewsletterStatistics();
                break;
            case 'performance':
                $data = $this->statisticsService->getPerformanceMetrics();
                break;
            default:
                $data = $this->statisticsService->getDashboardStatistics();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Export data to CSV.
     */
    public function export(Request $request): Response
    {
        $type = $request->get('type');
        $filters = $request->except(['type', '_token']);

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Export type is required',
            ], 400);
        }

        $data = $this->statisticsService->getExportData($type, $filters);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data found for export',
            ], 404);
        }

        $filename = $type . '_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if (!empty($data)) {
                // Add CSV headers
                fputcsv($file, array_keys($data[0]));
                
                // Add data rows
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get chart data for specific metrics.
     */
    public function getChartData(Request $request): JsonResponse
    {
        $metric = $request->get('metric');
        $period = $request->get('period', '12'); // months

        $data = [];

        switch ($metric) {
            case 'user_registrations':
                $data = $this->getUserRegistrationChartData($period);
                break;
            case 'submission_trends':
                $data = $this->getSubmissionTrendsChartData($period);
                break;
            case 'company_approvals':
                $data = $this->getCompanyApprovalChartData($period);
                break;
            case 'job_postings':
                $data = $this->getJobPostingChartData($period);
                break;
            case 'newsletter_subscriptions':
                $data = $this->getNewsletterSubscriptionChartData($period);
                break;
            default:
                $data = [];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get user registration chart data.
     */
    private function getUserRegistrationChartData(int $period): array
    {
        $data = [];
        $currentDate = now()->subMonths($period - 1)->startOfMonth();

        for ($i = 0; $i < $period; $i++) {
            $month = $currentDate->format('Y-m');
            $nextMonth = $currentDate->copy()->addMonth();

            $count = \App\Models\User::whereBetween('created_at', [$currentDate, $nextMonth])->count();

            $data[] = [
                'month' => $currentDate->format('M Y'),
                'count' => $count,
            ];

            $currentDate->addMonth();
        }

        return $data;
    }

    /**
     * Get submission trends chart data.
     */
    private function getSubmissionTrendsChartData(int $period): array
    {
        $data = [];
        $currentDate = now()->subMonths($period - 1)->startOfMonth();

        for ($i = 0; $i < $period; $i++) {
            $month = $currentDate->format('Y-m');
            $nextMonth = $currentDate->copy()->addMonth();

            $autoEntrepreneur = \App\Models\AutoEntrepreneurSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();
            $porteurIdee = \App\Models\PorteurIdeeSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();
            $porteurProjet = \App\Models\PorteurProjetSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();
            $investment = \App\Models\InvestmentSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();
            $indh = \App\Models\INDHSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();
            $training = \App\Models\TrainingSubmission::whereBetween('created_at', [$currentDate, $nextMonth])->count();

            $data[] = [
                'month' => $currentDate->format('M Y'),
                'auto_entrepreneur' => $autoEntrepreneur,
                'porteur_idee' => $porteurIdee,
                'porteur_projet' => $porteurProjet,
                'investment' => $investment,
                'indh' => $indh,
                'training' => $training,
                'total' => $autoEntrepreneur + $porteurIdee + $porteurProjet + $investment + $indh + $training,
            ];

            $currentDate->addMonth();
        }

        return $data;
    }

    /**
     * Get company approval chart data.
     */
    private function getCompanyApprovalChartData(int $period): array
    {
        $data = [];
        $currentDate = now()->subMonths($period - 1)->startOfMonth();

        for ($i = 0; $i < $period; $i++) {
            $month = $currentDate->format('Y-m');
            $nextMonth = $currentDate->copy()->addMonth();

            $approved = \App\Models\Company::where('status', 'approved')
                ->whereBetween('created_at', [$currentDate, $nextMonth])
                ->count();
            $pending = \App\Models\Company::where('status', 'pending')
                ->whereBetween('created_at', [$currentDate, $nextMonth])
                ->count();
            $rejected = \App\Models\Company::where('status', 'rejected')
                ->whereBetween('created_at', [$currentDate, $nextMonth])
                ->count();

            $data[] = [
                'month' => $currentDate->format('M Y'),
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
                'total' => $approved + $pending + $rejected,
            ];

            $currentDate->addMonth();
        }

        return $data;
    }

    /**
     * Get job posting chart data.
     */
    private function getJobPostingChartData(int $period): array
    {
        $data = [];
        $currentDate = now()->subMonths($period - 1)->startOfMonth();

        for ($i = 0; $i < $period; $i++) {
            $month = $currentDate->format('Y-m');
            $nextMonth = $currentDate->copy()->addMonth();

            $count = \App\Models\JobListing::whereBetween('created_at', [$currentDate, $nextMonth])->count();

            $data[] = [
                'month' => $currentDate->format('M Y'),
                'count' => $count,
            ];

            $currentDate->addMonth();
        }

        return $data;
    }

    /**
     * Get newsletter subscription chart data.
     */
    private function getNewsletterSubscriptionChartData(int $period): array
    {
        $data = [];
        $currentDate = now()->subMonths($period - 1)->startOfMonth();

        for ($i = 0; $i < $period; $i++) {
            $month = $currentDate->format('Y-m');
            $nextMonth = $currentDate->copy()->addMonth();

            $count = \App\Models\NewsletterSubscription::whereBetween('created_at', [$currentDate, $nextMonth])->count();

            $data[] = [
                'month' => $currentDate->format('M Y'),
                'count' => $count,
            ];

            $currentDate->addMonth();
        }

        return $data;
    }

    /**
     * Clear statistics cache.
     */
    public function clearCache(): JsonResponse
    {
        $this->statisticsService->clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Statistics cache cleared successfully',
        ]);
    }

    /**
     * Get real-time statistics.
     */
    public function getRealTimeStats(): JsonResponse
    {
        $stats = [
            'online_users' => \App\Models\User::where('last_login_at', '>=', now()->subMinutes(15))->count(),
            'today_submissions' => \App\Models\AutoEntrepreneurSubmission::whereDate('created_at', today())->count() +
                                 \App\Models\PorteurIdeeSubmission::whereDate('created_at', today())->count() +
                                 \App\Models\PorteurProjetSubmission::whereDate('created_at', today())->count() +
                                 \App\Models\InvestmentSubmission::whereDate('created_at', today())->count() +
                                 \App\Models\INDHSubmission::whereDate('created_at', today())->count() +
                                 \App\Models\TrainingSubmission::whereDate('created_at', today())->count(),
            'pending_approvals' => \App\Models\Company::where('status', 'pending')->count(),
            'active_jobs' => \App\Models\JobListing::where('status', 'active')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}