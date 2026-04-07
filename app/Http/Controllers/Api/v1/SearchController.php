<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use App\Models\INDHSubmission;
use App\Models\TrainingSubmission;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Advanced search across all submissions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, investment, project-carrier, etc.
        $status = $request->get('status', 'all');
        $sector = $request->get('sector', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        
        $results = collect();
        
        if ($type === 'all' || $type === 'investment') {
            $results = $results->merge($this->searchInvestment($query, $status, $sector, $dateFrom, $dateTo, $user));
        }
        
        if ($type === 'all' || $type === 'project-carrier') {
            $results = $results->merge($this->searchProjectCarrier($query, $status, $sector, $dateFrom, $dateTo, $user));
        }
        
        if ($type === 'all' || $type === 'auto-entrepreneur') {
            $results = $results->merge($this->searchAutoEntrepreneur($query, $status, $sector, $dateFrom, $dateTo, $user));
        }
        
        if ($type === 'all' || $type === 'indh') {
            $results = $results->merge($this->searchINDH($query, $status, $sector, $dateFrom, $dateTo, $user));
        }
        
        if ($type === 'all' || $type === 'training') {
            $results = $results->merge($this->searchTraining($query, $status, $sector, $dateFrom, $dateTo, $user));
        }
        
        // Sort by relevance/date
        $results = $results->sortByDesc('created_at');
        
        // Paginate
        $total = $results->count();
        $paginated = $results->slice(($page - 1) * $perPage, $perPage)->values();
        
        return response()->json([
            'success' => true,
            'data' => $paginated,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
            ],
            'query' => $query,
            'filters' => [
                'type' => $type,
                'status' => $status,
                'sector' => $sector,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]
        ]);
    }

    private function searchInvestment($query, $status, $sector, $dateFrom, $dateTo, $user)
    {
        $q = InvestmentSubmission::with('user');
        
        if ($user->role === 'user') {
            $q->where('user_id', $user->id);
        }
        
        if ($query) {
            $q->where(function($q) use ($query) {
                $q->where('project_name', 'like', "%{$query}%")
                  ->orWhere('project_description', 'like', "%{$query}%")
                  ->orWhere('submission_number', 'like', "%{$query}%");
            });
        }
        
        if ($status !== 'all') {
            $q->where('status', $status);
        }
        
        if ($sector) {
            $q->where('sector', $sector);
        }
        
        if ($dateFrom) {
            $q->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $q->where('created_at', '<=', $dateTo);
        }
        
        return $q->get()->map(function($item) {
            $item->submission_type = 'investment';
            $item->title = $item->project_name;
            return $item;
        });
    }

    private function searchProjectCarrier($query, $status, $sector, $dateFrom, $dateTo, $user)
    {
        $q = ProjectCarrierSubmission::with('user');
        
        if ($user->role === 'user') {
            $q->where('user_id', $user->id);
        }
        
        if ($query) {
            $q->where(function($q) use ($query) {
                $q->where('project_name', 'like', "%{$query}%")
                  ->orWhere('project_description', 'like', "%{$query}%")
                  ->orWhere('submission_number', 'like', "%{$query}%");
            });
        }
        
        if ($status !== 'all') {
            $q->where('status', $status);
        }
        
        if ($sector) {
            $q->where('sector', $sector);
        }
        
        if ($dateFrom) {
            $q->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $q->where('created_at', '<=', $dateTo);
        }
        
        return $q->get()->map(function($item) {
            $item->submission_type = 'project-carrier';
            $item->title = $item->project_name;
            return $item;
        });
    }

    private function searchAutoEntrepreneur($query, $status, $sector, $dateFrom, $dateTo, $user)
    {
        $q = AutoEntrepreneurSubmission::with('user');
        
        if ($user->role === 'user') {
            $q->where('user_id', $user->id);
        }
        
        if ($query) {
            $q->where(function($q) use ($query) {
                $q->where('business_name', 'like', "%{$query}%")
                  ->orWhere('business_description', 'like', "%{$query}%")
                  ->orWhere('submission_number', 'like', "%{$query}%");
            });
        }
        
        if ($status !== 'all') {
            $q->where('status', $status);
        }
        
        if ($sector) {
            $q->where('sector', $sector);
        }
        
        if ($dateFrom) {
            $q->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $q->where('created_at', '<=', $dateTo);
        }
        
        return $q->get()->map(function($item) {
            $item->submission_type = 'auto-entrepreneur';
            $item->title = $item->business_name;
            return $item;
        });
    }

    private function searchINDH($query, $status, $sector, $dateFrom, $dateTo, $user)
    {
        $q = INDHSubmission::with('user');
        
        if ($user->role === 'user') {
            $q->where('user_id', $user->id);
        }
        
        if ($query) {
            $q->where(function($q) use ($query) {
                $q->where('project_title', 'like', "%{$query}%")
                  ->orWhere('project_description', 'like', "%{$query}%")
                  ->orWhere('submission_number', 'like', "%{$query}%");
            });
        }
        
        if ($status !== 'all') {
            $q->where('status', $status);
        }
        
        if ($dateFrom) {
            $q->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $q->where('created_at', '<=', $dateTo);
        }
        
        return $q->get()->map(function($item) {
            $item->submission_type = 'indh';
            $item->title = $item->project_title;
            return $item;
        });
    }

    private function searchTraining($query, $status, $sector, $dateFrom, $dateTo, $user)
    {
        $q = TrainingSubmission::with('user');
        
        if ($user->role === 'user') {
            $q->where('user_id', $user->id);
        }
        
        if ($query) {
            $q->where(function($q) use ($query) {
                $q->where('training_title', 'like', "%{$query}%")
                  ->orWhere('training_description', 'like', "%{$query}%")
                  ->orWhere('submission_number', 'like', "%{$query}%");
            });
        }
        
        if ($status !== 'all') {
            $q->where('status', $status);
        }
        
        if ($dateFrom) {
            $q->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $q->where('created_at', '<=', $dateTo);
        }
        
        return $q->get()->map(function($item) {
            $item->submission_type = 'training';
            $item->title = $item->training_title;
            return $item;
        });
    }
}














