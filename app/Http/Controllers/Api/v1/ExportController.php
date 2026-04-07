<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\InvestmentSubmission;
use App\Models\ProjectCarrierSubmission;
use App\Models\AutoEntrepreneurSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmissionsExport;
use PDF;

class ExportController extends Controller
{
    /**
     * Export submissions to Excel
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all'); // all, investment, project-carrier, auto-entrepreneur
        $format = $request->get('format', 'xlsx'); // xlsx, csv
        
        $export = new SubmissionsExport($type, $user);
        
        $filename = 'submissions_' . $type . '_' . now()->format('Y-m-d_His') . '.' . $format;
        
        return Excel::download($export, $filename);
    }

    /**
     * Export submissions to PDF
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        
        $submissions = $this->getSubmissions($type, $user);
        
        $pdf = PDF::loadView('exports.submissions-pdf', [
            'submissions' => $submissions,
            'type' => $type,
            'user' => $user,
            'generated_at' => now()
        ]);
        
        $filename = 'submissions_' . $type . '_' . now()->format('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export submissions to CSV
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        
        $submissions = $this->getSubmissions($type, $user);
        
        $filename = 'submissions_' . $type . '_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Type', 'Submission Number', 'Status', 'Created At', 'User']);
            
            // Add data
            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->id,
                    $submission->submission_type ?? 'unknown',
                    $submission->submission_number ?? 'N/A',
                    $submission->status,
                    $submission->created_at->format('Y-m-d H:i:s'),
                    $submission->user->email ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get submissions based on type and user role
     */
    private function getSubmissions(string $type, $user)
    {
        $submissions = collect();
        
        if ($type === 'all' || $type === 'investment') {
            $query = InvestmentSubmission::with('user');
            if ($user->role === 'user') {
                $query->where('user_id', $user->id);
            }
            $submissions = $submissions->merge(
                $query->get()->map(function($item) {
                    $item->submission_type = 'investment';
                    return $item;
                })
            );
        }
        
        if ($type === 'all' || $type === 'project-carrier') {
            $query = ProjectCarrierSubmission::with('user');
            if ($user->role === 'user') {
                $query->where('user_id', $user->id);
            }
            $submissions = $submissions->merge(
                $query->get()->map(function($item) {
                    $item->submission_type = 'project-carrier';
                    return $item;
                })
            );
        }
        
        if ($type === 'all' || $type === 'auto-entrepreneur') {
            $query = AutoEntrepreneurSubmission::with('user');
            if ($user->role === 'user') {
                $query->where('user_id', $user->id);
            }
            $submissions = $submissions->merge(
                $query->get()->map(function($item) {
                    $item->submission_type = 'auto-entrepreneur';
                    return $item;
                })
            );
        }
        
        return $submissions;
    }
}














