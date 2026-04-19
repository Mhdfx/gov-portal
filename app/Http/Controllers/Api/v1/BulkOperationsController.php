<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use App\Constants\AppConstants;
use App\Services\LoggingService;

class BulkOperationsController extends Controller
{
    protected $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    /**
     * Bulk update submission statuses
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdateStatus(Request $request)
    {
        $user = Auth::user();
        
        // Only admins can perform bulk operations
        if (!in_array($user->role, [AppConstants::ROLE_MAIN_ADMIN, AppConstants::ROLE_INSTITUTIONAL_ADMIN, AppConstants::ROLE_SECTORAL_ADMIN])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'required|integer',
            'status' => 'required|in:' . implode(',', [
                AppConstants::STATUS_PENDING,
                AppConstants::STATUS_APPROVED,
                AppConstants::STATUS_REJECTED,
                AppConstants::STATUS_UNDER_REVIEW
            ]),
            'type' => 'required|in:investment,project-carrier,idea-carrier,auto-entrepreneur,indh,training,all',
            'notes' => 'nullable|string|max:1000'
        ]);

        $submissionIds = $request->submission_ids;
        $status = $request->status;
        $type = $request->type;
        $notes = $request->notes;
        
        $updated = 0;
        $models = $this->getModelsByType($type);
        
        DB::beginTransaction();
        try {
            foreach ($models as $modelClass) {
                $query = $modelClass::whereIn('id', $submissionIds);
                
                // Sectoral admins can only update their sector
                if ($user->role === AppConstants::ROLE_SECTORAL_ADMIN) {
                    $sector = $user->profile->sector ?? null;
                    if ($sector) {
                        $query->where('sector', $sector);
                    }
                }
                
                $count = $query->update([
                    'status' => $status,
                    'reviewed_at' => now(),
                    'reviewed_by' => $user->id,
                    'admin_notes' => $notes ? ($query->first()->admin_notes ?? '') . "\n" . $notes : null
                ]);
                
                $updated += $count;
            }
            
            DB::commit();
            
            $this->loggingService->log('bulk_update_status', 'Bulk status update performed', [
                'user_id' => $user->id,
                'updated_count' => $updated,
                'status' => $status,
                'type' => $type
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updated} submission(s)",
                'updated_count' => $updated
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->loggingService->logError('Bulk status update failed', $e);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete submissions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request)
    {
        $user = Auth::user();
        
        // Only main admin can delete
        if ($user->role !== AppConstants::ROLE_MAIN_ADMIN) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Only main admin can delete submissions'
            ], 403);
        }

        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'required|integer',
            'type' => 'required|in:investment,project-carrier,idea-carrier,auto-entrepreneur,indh,training,all'
        ]);

        $submissionIds = $request->submission_ids;
        $type = $request->type;
        $deleted = 0;
        $models = $this->getModelsByType($type);
        
        DB::beginTransaction();
        try {
            foreach ($models as $modelClass) {
                $count = $modelClass::whereIn('id', $submissionIds)->delete();
                $deleted += $count;
            }
            
            DB::commit();
            
            $this->loggingService->log('bulk_delete', 'Bulk delete performed', [
                'user_id' => $user->id,
                'deleted_count' => $deleted,
                'type' => $type
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deleted} submission(s)",
                'deleted_count' => $deleted
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->loggingService->logError('Bulk delete failed', $e);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk export submissions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkExport(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'required|integer',
            'type' => 'required|in:investment,project-carrier,idea-carrier,auto-entrepreneur,indh,training,all',
            'format' => 'required|in:excel,pdf,csv'
        ]);

        // This will be handled by ExportController
        return response()->json([
            'success' => true,
            'message' => 'Use /api/v1/export/{format} endpoint with submission_ids parameter'
        ]);
    }

    /**
     * Get models by type
     */
    private function getModelsByType(string $type): array
    {
        $models = [];
        
        if ($type === 'all' || $type === 'investment') {
            $models[] = InvestmentSubmission::class;
        }
        
        if ($type === 'all' || $type === 'project-carrier') {
            $models[] = ProjectCarrierSubmission::class;
        }
        
        if ($type === 'all' || $type === 'idea-carrier') {
            $models[] = \App\Models\IdeaCarrierSubmission::class;
        }
        
        if ($type === 'all' || $type === 'auto-entrepreneur') {
            $models[] = AutoEntrepreneurSubmission::class;
        }
        
        if ($type === 'all' || $type === 'indh') {
            $models[] = INDHSubmission::class;
        }
        
        if ($type === 'all' || $type === 'training') {
            $models[] = TrainingSubmission::class;
        }
        
        return $models;
    }
}














