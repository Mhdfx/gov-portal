<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\IdeaCarrierSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\InvestmentSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Models\SystemLog;
use App\Events\SubmissionStatusUpdated;
use SEO;

class StatusManagementController extends Controller
{
    /**
     * Update submission status
     */
    public function updateStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,in_review,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $submission = $this->getSubmissionByType($type, $id);
        
        if (!$submission) {
            return redirect()->back()->with('error', 'Submission not found.');
        }

        $oldStatus = $submission->status;
        $submission->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'status_updated_at' => now(),
            'status_updated_by' => Auth::id(),
        ]);

        // Fire real-time event
        event(new SubmissionStatusUpdated($submission, $oldStatus, $request->status, $submission->user_id));

        // Log the status change
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'status_update',
            'description' => "Updated {$type} submission #{$id} status from {$oldStatus} to {$request->status}",
            'metadata' => [
                'submission_type' => $type,
                'submission_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin_notes' => $request->admin_notes,
            ],
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    /**
     * Get submission by type and ID
     */
    private function getSubmissionByType($type, $id)
    {
        switch ($type) {
            case 'auto_entrepreneur':
                return AutoEntrepreneurSubmission::find($id);
            case 'idea_carrier':
                return IdeaCarrierSubmission::find($id);
            case 'project_carrier':
                return ProjectCarrierSubmission::find($id);
            case 'investment':
                return InvestmentSubmission::find($id);
            case 'indh':
                return INDHSubmission::find($id);
            case 'training':
                return TrainingSubmission::find($id);
            default:
                return null;
        }
    }

    /**
     * Generate tracking number for submission
     */
    public function generateTrackingNumber($type, $id)
    {
        $submission = $this->getSubmissionByType($type, $id);
        
        if (!$submission) {
            return redirect()->back()->with('error', 'Submission not found.');
        }

        if ($submission->tracking_number) {
            return redirect()->back()->with('info', 'Tracking number already exists: ' . $submission->tracking_number);
        }

        $prefix = strtoupper(substr($type, 0, 2));
        $trackingNumber = $prefix . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
        
        $submission->update([
            'tracking_number' => $trackingNumber,
        ]);

        // Log the tracking number generation
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'tracking_generated',
            'description' => "Generated tracking number {$trackingNumber} for {$type} submission #{$id}",
            'metadata' => [
                'submission_type' => $type,
                'submission_id' => $id,
                'tracking_number' => $trackingNumber,
            ],
        ]);

        return redirect()->back()->with('success', 'Tracking number generated: ' . $trackingNumber);
    }

    /**
     * Get status statistics
     */
    public function getStatusStatistics()
    {
        $stats = [
            'auto_entrepreneur' => [
                'total' => AutoEntrepreneurSubmission::count(),
                'pending' => AutoEntrepreneurSubmission::where('status', 'pending')->count(),
                'approved' => AutoEntrepreneurSubmission::where('status', 'approved')->count(),
                'rejected' => AutoEntrepreneurSubmission::where('status', 'rejected')->count(),
                'in_review' => AutoEntrepreneurSubmission::where('status', 'in_review')->count(),
            ],
            'idea_carrier' => [
                'total' => IdeaCarrierSubmission::count(),
                'pending' => IdeaCarrierSubmission::where('status', 'pending')->count(),
                'approved' => IdeaCarrierSubmission::where('status', 'approved')->count(),
                'rejected' => IdeaCarrierSubmission::where('status', 'rejected')->count(),
                'in_review' => IdeaCarrierSubmission::where('status', 'in_review')->count(),
            ],
            'project_carrier' => [
                'total' => ProjectCarrierSubmission::count(),
                'pending' => ProjectCarrierSubmission::where('status', 'pending')->count(),
                'approved' => ProjectCarrierSubmission::where('status', 'approved')->count(),
                'rejected' => ProjectCarrierSubmission::where('status', 'rejected')->count(),
                'in_review' => ProjectCarrierSubmission::where('status', 'in_review')->count(),
            ],
            'investment' => [
                'total' => InvestmentSubmission::count(),
                'pending' => InvestmentSubmission::where('status', 'pending')->count(),
                'approved' => InvestmentSubmission::where('status', 'approved')->count(),
                'rejected' => InvestmentSubmission::where('status', 'rejected')->count(),
                'in_review' => InvestmentSubmission::where('status', 'in_review')->count(),
            ],
            'indh' => [
                'total' => INDHSubmission::count(),
                'pending' => INDHSubmission::where('status', 'pending')->count(),
                'approved' => INDHSubmission::where('status', 'approved')->count(),
                'rejected' => INDHSubmission::where('status', 'rejected')->count(),
                'in_review' => INDHSubmission::where('status', 'in_review')->count(),
            ],
            'training' => [
                'total' => TrainingSubmission::count(),
                'pending' => TrainingSubmission::where('status', 'pending')->count(),
                'approved' => TrainingSubmission::where('status', 'approved')->count(),
                'rejected' => TrainingSubmission::where('status', 'rejected')->count(),
                'in_review' => TrainingSubmission::where('status', 'in_review')->count(),
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Get status history for a submission
     */
    public function getStatusHistory($type, $id)
    {
        $submission = $this->getSubmissionByType($type, $id);
        
        if (!$submission) {
            return response()->json(['error' => 'Submission not found'], 404);
        }

        $history = SystemLog::where('action', 'status_update')
            ->whereJsonContains('metadata->submission_type', $type)
            ->whereJsonContains('metadata->submission_id', (int)$id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($history);
    }

    /**
     * Bulk status update
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'required|string',
            'status' => 'required|in:pending,approved,rejected,in_review,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $updatedCount = 0;
        $errors = [];

        foreach ($request->submission_ids as $submissionId) {
            list($type, $id) = explode(':', $submissionId);
            
            $submission = $this->getSubmissionByType($type, $id);
            
            if ($submission) {
                $oldStatus = $submission->status;
                $submission->update([
                    'status' => $request->status,
                    'admin_notes' => $request->admin_notes,
                    'status_updated_at' => now(),
                    'status_updated_by' => Auth::id(),
                ]);

                // Log the status change
                SystemLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'bulk_status_update',
                    'description' => "Bulk updated {$type} submission #{$id} status from {$oldStatus} to {$request->status}",
                    'metadata' => [
                        'submission_type' => $type,
                        'submission_id' => $id,
                        'old_status' => $oldStatus,
                        'new_status' => $request->status,
                        'admin_notes' => $request->admin_notes,
                    ],
                ]);

                $updatedCount++;
            } else {
                $errors[] = "Submission {$type}:{$id} not found";
            }
        }

        $message = "Updated {$updatedCount} submissions successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }
}






























