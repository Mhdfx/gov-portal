<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\FileUpload;
use App\Models\User;
use SEO;

class FileManagementController extends Controller
{
    /**
     * Display all uploaded files with filtering and search
     */
    public function index(Request $request)
    {
        SEO::setTitle('File Management | Admin Dashboard');
        SEO::setDescription('Manage all uploaded files and documents on the Boiema Platform.');

        $query = FileUpload::with(['user', 'submission']);

        // Filter by file type
        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        // Filter by submission type
        if ($request->filled('submission_type')) {
            $query->where('submission_type', $request->submission_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by filename
        if ($request->filled('search')) {
            $query->where('original_name', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $fileTypes = FileUpload::distinct()->pluck('file_type')->filter();
        $submissionTypes = FileUpload::distinct()->pluck('submission_type')->filter();
        $users = User::whereIn('id', FileUpload::distinct()->pluck('user_id'))->get(['id', 'username', 'email']);

        return view('admin.files.index', compact('files', 'fileTypes', 'submissionTypes', 'users'));
    }

    /**
     * Download a file
     */
    public function download($id)
    {
        $file = FileUpload::findOrFail($id);
        
        // Check if file exists in storage
        if (!Storage::exists($file->file_path)) {
            return redirect()->back()->with('error', 'File not found in storage.');
        }

        return Storage::download($file->file_path, $file->original_name);
    }

    /**
     * Preview a file (for images and PDFs)
     */
    public function preview($id)
    {
        $file = FileUpload::findOrFail($id);
        
        // Check if file exists in storage
        if (!Storage::exists($file->file_path)) {
            return redirect()->back()->with('error', 'File not found in storage.');
        }

        $filePath = Storage::path($file->file_path);
        $mimeType = Storage::mimeType($file->file_path);

        // For images and PDFs, return the file directly
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'])) {
            return response()->file($filePath);
        }

        // For other file types, redirect to download
        return $this->download($id);
    }

    /**
     * Delete a file
     */
    public function destroy($id)
    {
        $file = FileUpload::findOrFail($id);
        
        // Delete from storage
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        // Delete from database
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    /**
     * Get file statistics
     */
    public function statistics()
    {
        $stats = [
            'total_files' => FileUpload::count(),
            'total_size' => FileUpload::sum('file_size'),
            'files_by_type' => FileUpload::selectRaw('file_type, COUNT(*) as count')
                ->groupBy('file_type')
                ->pluck('count', 'file_type'),
            'files_by_submission' => FileUpload::selectRaw('submission_type, COUNT(*) as count')
                ->groupBy('submission_type')
                ->pluck('count', 'submission_type'),
            'recent_uploads' => FileUpload::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Bulk download files
     */
    public function bulkDownload(Request $request)
    {
        $fileIds = $request->input('file_ids', []);
        
        if (empty($fileIds)) {
            return redirect()->back()->with('error', 'No files selected for download.');
        }

        $files = FileUpload::whereIn('id', $fileIds)->get();
        
        if ($files->count() === 1) {
            return $this->download($files->first()->id);
        }

        // For multiple files, create a zip archive
        $zip = new \ZipArchive();
        $zipName = 'files_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with('error', 'Cannot create zip file.');
        }

        foreach ($files as $file) {
            if (Storage::exists($file->file_path)) {
                $zip->addFile(Storage::path($file->file_path), $file->original_name);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}






























