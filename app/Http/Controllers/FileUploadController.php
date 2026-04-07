<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FileUploadService;
use App\Models\FileUpload;

class FileUploadController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload a general file.
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'upload_type' => 'required|string|in:cv,business_plan,company_document,general',
        ]);

        try {
            $file = $request->file('file');
            $uploadType = $request->upload_type;

            // Upload file based on type
            $fileInfo = $this->uploadFileByType($file, $uploadType);

            // Save to database
            $fileUpload = FileUpload::create([
                'user_id' => Auth::id(),
                'original_name' => $fileInfo['original_name'],
                'stored_name' => $fileInfo['stored_name'],
                'file_path' => $fileInfo['file_path'],
                'file_size' => $fileInfo['file_size'],
                'mime_type' => $fileInfo['mime_type'],
                'upload_type' => $uploadType,
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'file' => $fileUpload,
                'file_url' => $this->fileUploadService->getFileUrl($fileInfo['file_path']),
                'status' => 'success'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'File upload failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * Upload a CV file.
     */
    public function uploadCV(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('cv');
            $fileInfo = $this->fileUploadService->uploadCV($file);

            // Save to database
            $fileUpload = FileUpload::create([
                'user_id' => Auth::id(),
                'original_name' => $fileInfo['original_name'],
                'stored_name' => $fileInfo['stored_name'],
                'file_path' => $fileInfo['file_path'],
                'file_size' => $fileInfo['file_size'],
                'mime_type' => $fileInfo['mime_type'],
                'upload_type' => 'cv',
            ]);

            return response()->json([
                'message' => 'CV uploaded successfully',
                'file' => $fileUpload,
                'file_url' => $this->fileUploadService->getFileUrl($fileInfo['file_path']),
                'status' => 'success'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'CV upload failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * Upload a business plan file.
     */
    public function uploadBusinessPlan(Request $request)
    {
        $request->validate([
            'business_plan' => 'required|file|mimes:pdf,doc,docx,txt,rtf|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('business_plan');
            $fileInfo = $this->fileUploadService->uploadBusinessPlan($file);

            // Save to database
            $fileUpload = FileUpload::create([
                'user_id' => Auth::id(),
                'original_name' => $fileInfo['original_name'],
                'stored_name' => $fileInfo['stored_name'],
                'file_path' => $fileInfo['file_path'],
                'file_size' => $fileInfo['file_size'],
                'mime_type' => $fileInfo['mime_type'],
                'upload_type' => 'business_plan',
            ]);

            return response()->json([
                'message' => 'Business plan uploaded successfully',
                'file' => $fileUpload,
                'file_url' => $this->fileUploadService->getFileUrl($fileInfo['file_path']),
                'status' => 'success'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Business plan upload failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * Get user's uploaded files.
     */
    public function getUserFiles(Request $request)
    {
        $uploadType = $request->get('upload_type');
        
        $query = FileUpload::where('user_id', Auth::id());
        
        if ($uploadType) {
            $query->where('upload_type', $uploadType);
        }

        $files = $query->orderBy('created_at', 'desc')->get();

        // Add file URLs
        $files->transform(function ($file) {
            $file->file_url = $this->fileUploadService->getFileUrl($file->file_path);
            $file->file_size_human = $this->fileUploadService->getHumanReadableFileSize($file->file_size);
            return $file;
        });

        return response()->json([
            'files' => $files,
            'status' => 'success'
        ]);
    }

    /**
     * Delete a file.
     */
    public function deleteFile(Request $request, $fileId)
    {
        $file = FileUpload::where('id', $fileId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$file) {
            return response()->json([
                'message' => 'File not found',
                'status' => 'error'
            ], 404);
        }

        try {
            // Delete file from storage
            $this->fileUploadService->deleteFile($file->file_path);

            // Delete from database
            $file->delete();

            return response()->json([
                'message' => 'File deleted successfully',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'File deletion failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    /**
     * Upload file by type.
     */
    private function uploadFileByType($file, string $uploadType): array
    {
        switch ($uploadType) {
            case 'cv':
                return $this->fileUploadService->uploadCV($file);
            case 'business_plan':
                return $this->fileUploadService->uploadBusinessPlan($file);
            case 'company_document':
                return $this->fileUploadService->uploadCompanyDocument($file, 'company_documents');
            default:
                return $this->fileUploadService->uploadFile($file, 'uploads/general');
        }
    }
}
