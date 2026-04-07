<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\Notification;
use App\Models\SystemLog;
use App\Constants\AppConstants;
use SEO;

class SectoralAdminController extends Controller
{
    /**
     * Show the sectoral admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Set SEO meta tags
        SEO::setTitle('Administration Sectorielle - Tableau de Bord');
        SEO::setDescription('Gérez les soumissions de votre secteur d\'activité.');
        SEO::setCanonical(url()->current());
        
        // Get sector-specific submissions
        $stats = [
            'total_submissions' => $this->getSectorSubmissions()->count(),
            'pending_submissions' => $this->getSectorSubmissions()->where('status', AppConstants::STATUS_PENDING)->count(),
            'approved_submissions' => $this->getSectorSubmissions()->where('status', AppConstants::STATUS_APPROVED)->count(),
            'rejected_submissions' => $this->getSectorSubmissions()->where('status', AppConstants::STATUS_REJECTED)->count(),
            'under_review' => $this->getSectorSubmissions()->where('status', AppConstants::STATUS_UNDER_REVIEW)->count(),
        ];
        
        // Get recent submissions for this sector
        $recentSubmissions = $this->getSectorSubmissions()->sortByDesc('created_at')->take(10);
        
        // Get sector statistics
        $sectorStats = $this->getSectorStatistics();
        
        // Get recent activities
        $recentActivities = SystemLog::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();
        
        // Get notifications
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('dashboard.sectoral-admin.index', compact(
            'user',
            'stats',
            'recentSubmissions',
            'sectorStats',
            'recentActivities',
            'notifications'
        ));
    }
    
    /**
     * Show submissions assigned to this sector.
     */
    public function submissions()
    {
        $user = Auth::user();
        
        SEO::setTitle('Mes Soumissions - Administration Sectorielle');
        SEO::setDescription('Gérez les soumissions assignées à votre secteur.');
        
        $submissions = $this->getSectorSubmissions()->sortByDesc('created_at');
        
        return view('dashboard.sectoral-admin.submissions', compact('user', 'submissions'));
    }
    
    /**
     * Show sector profile.
     */
    public function profile()
    {
        $user = Auth::user();
        
        SEO::setTitle('Mon Profil - Administration Sectorielle');
        SEO::setDescription('Gérez vos informations sectorielles.');
        
        return view('dashboard.sectoral-admin.profile', compact('user'));
    }
    
    /**
     * Show reports.
     */
    public function reports()
    {
        $user = Auth::user();
        
        SEO::setTitle('Rapports - Administration Sectorielle');
        SEO::setDescription('Consultez les rapports et statistiques de votre secteur.');
        
        $monthlyStats = $this->getMonthlyStatistics();
        $submissionsByType = $this->getSubmissionsByType();
        $submissionsByStatus = $this->getSubmissionsByStatus();
        
        return view('dashboard.sectoral-admin.reports', compact(
            'user',
            'monthlyStats',
            'submissionsByType',
            'submissionsByStatus'
        ));
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
     * Get submissions for this sector based on business sector.
     */
    private function getSectorSubmissions()
    {
        $user = Auth::user();
        $sector = $user->profile->sector ?? $user->profile->business_sector ?? null;
        
        $submissions = collect();
        
        // Get submissions filtered by business sector
        if ($sector) {
            $submissions = $submissions->merge(
                InvestmentSubmission::where('sector', $sector)->get()
            );
            $submissions = $submissions->merge(
                ProjectCarrierSubmission::where('sector', $sector)->get()
            );
            $submissions = $submissions->merge(
                IdeaCarrierSubmission::where('sector', $sector)->get()
            );
            $submissions = $submissions->merge(
                AutoEntrepreneurSubmission::where('sector', $sector)->get()
            );
        }
        
        return $submissions;
    }
    
    /**
     * Get sector statistics.
     */
    private function getSectorStatistics()
    {
        $submissions = $this->getSectorSubmissions();
        
        return [
            'total' => $submissions->count(),
            'this_month' => $submissions->where('created_at', '>=', now()->startOfMonth())->count(),
            'this_week' => $submissions->where('created_at', '>=', now()->startOfWeek())->count(),
            'today' => $submissions->where('created_at', '>=', now()->startOfDay())->count(),
        ];
    }
    
    /**
     * Get monthly statistics.
     */
    private function getMonthlyStatistics()
    {
        $submissions = $this->getSectorSubmissions();
        $monthlyData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => $submissions->whereBetween('created_at', [
                    $month->startOfMonth(),
                    $month->endOfMonth()
                ])->count()
            ];
        }
        
        return $monthlyData;
    }
    
    /**
     * Get submissions grouped by type.
     */
    private function getSubmissionsByType()
    {
        $submissions = $this->getSectorSubmissions();
        
        return $submissions->groupBy(function($submission) {
            return class_basename(get_class($submission));
        })->map(function($group) {
            return $group->count();
        });
    }
    
    /**
     * Get submissions grouped by status.
     */
    private function getSubmissionsByStatus()
    {
        $submissions = $this->getSectorSubmissions();
        
        return $submissions->groupBy('status')->map(function($group) {
            return $group->count();
        });
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




























