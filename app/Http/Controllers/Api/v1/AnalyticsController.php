<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\User;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get comprehensive analytics dashboard data
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30'); // days
        
        $cacheKey = 'analytics_dashboard_' . $user->id . '_' . $period;
        
        $data = Cache::remember($cacheKey, 300, function() use ($user, $period) {
            return [
                'overview' => $this->getOverview($user, $period),
                'submissions' => $this->getSubmissionStats($user, $period),
                'users' => $this->getUserStats($user, $period),
                'trends' => $this->getTrends($user, $period),
                'top_sectors' => $this->getTopSectors($user, $period),
                'status_distribution' => $this->getStatusDistribution($user),
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period . ' days',
            'generated_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Get overview statistics
     */
    private function getOverview($user, $period)
    {
        $startDate = Carbon::now()->subDays($period);
        
        return [
            'total_submissions' => $this->getTotalSubmissions($user, $startDate),
            'pending_submissions' => $this->getPendingSubmissions($user, $startDate),
            'approved_submissions' => $this->getApprovedSubmissions($user, $startDate),
            'rejected_submissions' => $this->getRejectedSubmissions($user, $startDate),
            'total_users' => $user->role !== 'user' ? User::where('created_at', '>=', $startDate)->count() : null,
            'new_submissions_today' => $this->getNewSubmissionsToday($user),
        ];
    }

    /**
     * Get submission statistics by type
     */
    private function getSubmissionStats($user, $period)
    {
        $startDate = Carbon::now()->subDays($period);
        
        $stats = [
            'investment' => $this->getSubmissionCount(InvestmentSubmission::class, $user, $startDate),
            'project_carrier' => $this->getSubmissionCount(ProjectCarrierSubmission::class, $user, $startDate),
            'auto_entrepreneur' => $this->getSubmissionCount(AutoEntrepreneurSubmission::class, $user, $startDate),
            'indh' => $this->getSubmissionCount(INDHSubmission::class, $user, $startDate),
            'training' => $this->getSubmissionCount(TrainingSubmission::class, $user, $startDate),
        ];
        
        return $stats;
    }

    /**
     * Get user statistics
     */
    private function getUserStats($user, $period)
    {
        if ($user->role === 'user') {
            return null; // Users don't see user stats
        }
        
        $startDate = Carbon::now()->subDays($period);
        
        return [
            'total' => User::where('created_at', '>=', $startDate)->count(),
            'verified' => User::where('verification_status', 'verified')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'pending' => User::where('verification_status', 'pending')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'by_role' => User::where('created_at', '>=', $startDate)
                ->select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->pluck('total', 'role')
                ->toArray(),
        ];
    }

    /**
     * Get trends over time
     */
    private function getTrends($user, $period)
    {
        $startDate = Carbon::now()->subDays($period);
        $trends = [];
        
        for ($i = $period; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $nextDate = $date->copy()->addDay();
            
            $count = $this->getTotalSubmissions($user, $date, $nextDate);
            
            $trends[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count,
            ];
        }
        
        return $trends;
    }

    /**
     * Get top sectors
     */
    private function getTopSectors($user, $period)
    {
        $startDate = Carbon::now()->subDays($period);
        
        $sectors = collect();
        
        // Get sectors from different submission types
        $investmentSectors = InvestmentSubmission::where('created_at', '>=', $startDate)
            ->select('sector', DB::raw('count(*) as count'))
            ->groupBy('sector')
            ->get();
        
        $projectSectors = ProjectCarrierSubmission::where('created_at', '>=', $startDate)
            ->select('sector', DB::raw('count(*) as count'))
            ->groupBy('sector')
            ->get();
        
        $autoSectors = AutoEntrepreneurSubmission::where('created_at', '>=', $startDate)
            ->select('sector', DB::raw('count(*) as count'))
            ->groupBy('sector')
            ->get();
        
        $allSectors = $investmentSectors->merge($projectSectors)->merge($autoSectors);
        
        return $allSectors->groupBy('sector')
            ->map(function($group) {
                return $group->sum('count');
            })
            ->sortDesc()
            ->take(10)
            ->map(function($count, $sector) {
                return ['sector' => $sector, 'count' => $count];
            })
            ->values();
    }

    /**
     * Get status distribution
     */
    private function getStatusDistribution($user)
    {
        $allSubmissions = collect();
        
        $allSubmissions = $allSubmissions->merge(
            $this->getSubmissions(InvestmentSubmission::class, $user)
        );
        $allSubmissions = $allSubmissions->merge(
            $this->getSubmissions(ProjectCarrierSubmission::class, $user)
        );
        $allSubmissions = $allSubmissions->merge(
            $this->getSubmissions(AutoEntrepreneurSubmission::class, $user)
        );
        $allSubmissions = $allSubmissions->merge(
            $this->getSubmissions(INDHSubmission::class, $user)
        );
        $allSubmissions = $allSubmissions->merge(
            $this->getSubmissions(TrainingSubmission::class, $user)
        );
        
        return $allSubmissions->groupBy('status')
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();
    }

    // Helper methods
    private function getTotalSubmissions($user, $startDate = null, $endDate = null)
    {
        $count = 0;
        $count += $this->getSubmissionCount(InvestmentSubmission::class, $user, $startDate, $endDate);
        $count += $this->getSubmissionCount(ProjectCarrierSubmission::class, $user, $startDate, $endDate);
        $count += $this->getSubmissionCount(AutoEntrepreneurSubmission::class, $user, $startDate, $endDate);
        $count += $this->getSubmissionCount(INDHSubmission::class, $user, $startDate, $endDate);
        $count += $this->getSubmissionCount(TrainingSubmission::class, $user, $startDate, $endDate);
        return $count;
    }

    private function getSubmissionCount($model, $user, $startDate = null, $endDate = null)
    {
        $query = $model::query();
        
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<', $endDate);
        }
        
        return $query->count();
    }

    private function getPendingSubmissions($user, $startDate)
    {
        return $this->getSubmissionsByStatus($user, 'pending', $startDate);
    }

    private function getApprovedSubmissions($user, $startDate)
    {
        return $this->getSubmissionsByStatus($user, 'approved', $startDate);
    }

    private function getRejectedSubmissions($user, $startDate)
    {
        return $this->getSubmissionsByStatus($user, 'rejected', $startDate);
    }

    private function getSubmissionsByStatus($user, $status, $startDate)
    {
        $count = 0;
        $models = [
            InvestmentSubmission::class,
            ProjectCarrierSubmission::class,
            AutoEntrepreneurSubmission::class,
            INDHSubmission::class,
            TrainingSubmission::class,
        ];
        
        foreach ($models as $model) {
            $query = $model::where('status', $status)
                ->where('created_at', '>=', $startDate);
            
            if ($user->role === 'user') {
                $query->where('user_id', $user->id);
            }
            
            $count += $query->count();
        }
        
        return $count;
    }

    private function getNewSubmissionsToday($user)
    {
        $today = Carbon::today();
        return $this->getTotalSubmissions($user, $today);
    }

    private function getSubmissions($model, $user)
    {
        $query = $model::query();
        
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }
        
        return $query->get();
    }

    /**
     * Get submissions by type
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submissionsByType(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30');
        
        $data = $this->getSubmissionStats($user, $period);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period . ' days'
        ]);
    }

    /**
     * Get user statistics
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userStats(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30');
        
        $data = $this->getUserStats($user, $period);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period . ' days'
        ]);
    }

    /**
     * Get trends over time
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trends(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30');
        
        $data = $this->getTrends($user, $period);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period . ' days'
        ]);
    }

    /**
     * Get top sectors
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function topSectors(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30');
        
        $data = $this->getTopSectors($user, $period);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => $period . ' days'
        ]);
    }

    /**
     * Get status distribution
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusDistribution(Request $request)
    {
        $user = Auth::user();
        
        $data = $this->getStatusDistribution($user);
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}

