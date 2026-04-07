<?php

namespace App\Services;

use App\Constants\AppConstants;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload and validate a file
     * 
     * @param UploadedFile $file The uploaded file
     * @param string $directory Storage directory
     * @param array $allowedMimeTypes Allowed MIME types
     * @param int $maxSize Maximum file size in KB
     * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
     */
    public function uploadFile(
        UploadedFile $file,
        string $directory,
        array $allowedMimeTypes = [],
        int $maxSize = null
    ): array {
        try {
            // Validate file size
            $maxSize = $maxSize ?? AppConstants::MAX_FILE_SIZE;
            if ($file->getSize() > ($maxSize * 1024)) {
                return [
                    'success' => false,
                    'path' => null,
                    'error' => "File size exceeds maximum allowed size of {$maxSize}KB"
                ];
            }

            // Validate MIME type
            $mimeType = $file->getMimeType();
            if (!empty($allowedMimeTypes) && !in_array($mimeType, $allowedMimeTypes)) {
                return [
                    'success' => false,
                    'path' => null,
                    'error' => 'Invalid file type. Allowed types: ' . implode(', ', $allowedMimeTypes)
                ];
            }

            // Validate file extension
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = array_merge(
                AppConstants::ALLOWED_IMAGE_TYPES,
                AppConstants::ALLOWED_DOCUMENT_TYPES
            );
            
            if (!in_array($extension, $allowedExtensions)) {
                return [
                    'success' => false,
                    'path' => null,
                    'error' => 'Invalid file extension. Allowed extensions: ' . implode(', ', $allowedExtensions)
                ];
            }

            // Sanitize file name
            $sanitizedFileName = $this->sanitizeFileName($file->getClientOriginalName());
            
            // Generate unique file name
            $fileName = time() . '_' . Str::random(10) . '_' . $sanitizedFileName;
            
            // Store file
            $path = $file->storeAs($directory, $fileName, 'public');

            return [
                'success' => true,
                'path' => $path,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'path' => null,
                'error' => 'File upload failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sanitize file name to prevent security issues
     * 
     * @param string $fileName Original file name
     * @return string Sanitized file name
     */
    public function sanitizeFileName(string $fileName): string
    {
        // Remove path components
        $fileName = basename($fileName);
        
        // Remove special characters except dots, hyphens, and underscores
        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        
        // Remove multiple consecutive underscores
        $fileName = preg_replace('/_+/', '_', $fileName);
        
        // Limit length
        $fileName = Str::limit($fileName, 100, '');
        
        return $fileName;
    }

    /**
     * Get allowed MIME types for documents
     * 
     * @return array
     */
    public function getAllowedDocumentMimeTypes(): array
    {
        return [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
    }

    /**
     * Get allowed MIME types for images
     * 
     * @return array
     */
    public function getAllowedImageMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
        ];
    }

    /**
     * Delete a file
     * 
     * @param string $path File path
     * @return bool Success status
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate file is not executable
     * 
     * @param UploadedFile $file
     * @return bool
     */
    public function isExecutable(UploadedFile $file): bool
    {
        $executableExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $executableExtensions);
    }
}
