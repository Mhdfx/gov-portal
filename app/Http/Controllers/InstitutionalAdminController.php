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
use SEO;

class InstitutionalAdminController extends Controller
{
    /**
     * Show the institutional admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Set SEO meta tags
        SEO::setTitle('Administration Institutionnelle - Tableau de Bord');
        SEO::setDescription('Gérez les soumissions et activités de votre institution.');
        SEO::setCanonical(url()->current());
        
        // Get institution-specific submissions based on user's region/province
        $stats = [
            'total_submissions' => $this->getInstitutionSubmissions()->count(),
            'pending_submissions' => $this->getInstitutionSubmissions()->where('status', 'pending')->count(),
            'approved_submissions' => $this->getInstitutionSubmissions()->where('status', 'approved')->count(),
            'rejected_submissions' => $this->getInstitutionSubmissions()->where('status', 'rejected')->count(),
            'under_review' => $this->getInstitutionSubmissions()->where('status', 'under_review')->count(),
        ];
        
        // Get recent submissions for this institution
        $recentSubmissions = $this->getInstitutionSubmissions()->sortByDesc('created_at')->take(10);
        
        // Get regional statistics
        $regionalStats = $this->getRegionalStatistics();
        
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
        
        return view('dashboard.institutional-admin.index', compact(
            'user',
            'stats',
            'recentSubmissions',
            'regionalStats',
            'recentActivities',
            'notifications'
        ));
    }
    
    /**
     * Show submissions assigned to this institution.
     */
    public function submissions()
    {
        $user = Auth::user();
        
        SEO::setTitle('Mes Soumissions - Administration Institutionnelle');
        SEO::setDescription('Gérez les soumissions assignées à votre institution.');
        
        $submissions = $this->getInstitutionSubmissions()->sortByDesc('created_at');
        
        return view('dashboard.institutional-admin.submissions', compact('user', 'submissions'));
    }
    
    /**
     * Show institution profile.
     */
    public function profile()
    {
        $user = Auth::user();
        
        SEO::setTitle('Mon Profil - Administration Institutionnelle');
        SEO::setDescription('Gérez vos informations institutionnelles.');
        
        return view('dashboard.institutional-admin.profile', compact('user'));
    }
    
    /**
     * Show reports.
     */
    public function reports()
    {
        $user = Auth::user();
        
        SEO::setTitle('Rapports - Administration Institutionnelle');
        SEO::setDescription('Consultez les rapports et statistiques de votre institution.');
        
        $monthlyStats = $this->getMonthlyStatistics();
        $submissionsByType = $this->getSubmissionsByType();
        $submissionsByStatus = $this->getSubmissionsByStatus();
        
        return view('dashboard.institutional-admin.reports', compact(
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
     * Get submissions for this institution based on region/province.
     */
    private function getInstitutionSubmissions()
    {
        $user = Auth::user();
        $region = $user->profile->region ?? null;
        $province = $user->profile->province ?? null;
        
        $submissions = collect();
        
        // Get all submission types filtered by region/province
        if ($region) {
            $submissions = $submissions->merge(InvestmentSubmission::where('region', $region)->get());
            $submissions = $submissions->merge(ProjectCarrierSubmission::where('region', $region)->get());
            $submissions = $submissions->merge(IdeaCarrierSubmission::where('region', $region)->get());
            $submissions = $submissions->merge(AutoEntrepreneurSubmission::where('region', $region)->get());
            $submissions = $submissions->merge(INDHSubmission::where('region', $region)->get());
            $submissions = $submissions->merge(TrainingSubmission::where('region', $region)->get());
        }
        
        return $submissions;
    }
    
    /**
     * Get regional statistics.
     */
    private function getRegionalStatistics()
    {
        $submissions = $this->getInstitutionSubmissions();
        
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
        $submissions = $this->getInstitutionSubmissions();
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
        $submissions = $this->getInstitutionSubmissions();
        
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
        $submissions = $this->getInstitutionSubmissions();
        
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






























