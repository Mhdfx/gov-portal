<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\FileUpload;

class FileUploadController extends Controller
{
    /**
     * Get user's files.
     */
    public function getUserFiles(Request $request): JsonResponse
    {
        $user = $request->user();
        $files = $user->fileUploads()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $files->items(),
            'pagination' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
            ],
        ]);
    }

    /**
     * Get file information.
     */
    public function getFileInfo(Request $request, $fileId): JsonResponse
    {
        $user = $request->user();
        $file = $user->fileUploads()->findOrFail($fileId);

        return response()->json([
            'success' => true,
            'data' => $file,
        ]);
    }

    /**
     * Delete file.
     */
    public function deleteFile(Request $request, $fileId): JsonResponse
    {
        $user = $request->user();
        $file = $user->fileUploads()->findOrFail($fileId);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
        ]);
    }

    /**
     * Upload CV file.
     */
    public function uploadCV(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $user = $request->user();
        $file = $request->file('file');
        $path = $file->store('cvs', 'public');

        $fileUpload = FileUpload::create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => 'cv',
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'CV uploaded successfully',
            'data' => $fileUpload,
        ]);
    }

    /**
     * Upload business plan file.
     */
    public function uploadBusinessPlan(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $user = $request->user();
        $file = $request->file('file');
        $path = $file->store('business-plans', 'public');

        $fileUpload = FileUpload::create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => 'business_plan',
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Business plan uploaded successfully',
            'data' => $fileUpload,
        ]);
    }

    /**
     * Upload general file.
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'type' => 'required|string|in:document,certificate,other',
        ]);

        $user = $request->user();
        $file = $request->file('file');
        $path = $file->store('files', 'public');

        $fileUpload = FileUpload::create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $request->type,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => $fileUpload,
        ]);
    }
}