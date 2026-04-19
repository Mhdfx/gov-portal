<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\FormSubmissionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Api\v1\ExportController;
use App\Http\Controllers\Api\v1\AnalyticsController;
use App\Http\Controllers\Api\v1\SearchController as V1SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User routes
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    
    // Search API routes
    Route::get('/search', [SearchController::class, 'globalSearch']);
    Route::get('/search/suggestions', [SearchController::class, 'getSuggestions']);
    Route::get('/search/advanced', [SearchController::class, 'advancedSearch']);
    
    // File upload routes
    Route::get('/files', [FileUploadController::class, 'getUserFiles']);
    Route::get('/files/{fileId}', [FileUploadController::class, 'getFileInfo']);
    Route::delete('/files/{fileId}', [FileUploadController::class, 'deleteFile']);
    Route::post('/upload/cv', [FileUploadController::class, 'uploadCV']);
    Route::post('/upload/business-plan', [FileUploadController::class, 'uploadBusinessPlan']);
    Route::post('/upload/file', [FileUploadController::class, 'uploadFile']);
    
    // Form submission routes
    Route::post('/submissions/auto-entrepreneur', [FormSubmissionController::class, 'submitAutoEntrepreneur']);
    Route::post('/submissions/idea-carrier', [FormSubmissionController::class, 'submitIdeaCarrier']);
    Route::post('/submissions/project-carrier', [FormSubmissionController::class, 'submitProjectCarrier']);
    Route::post('/submissions/investment', [FormSubmissionController::class, 'submitInvestment']);
    Route::post('/submissions/indh', [FormSubmissionController::class, 'submitINDH']);
    Route::post('/submissions/training', [FormSubmissionController::class, 'submitTraining']);
    
    // Company routes
    Route::get('/company/profile', [CompanyController::class, 'profile']);
    Route::put('/company/profile', [CompanyController::class, 'updateProfile']);
    
    // Admin API routes (protected by admin middleware)
    Route::middleware('role:main_admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
        Route::get('/users', [AdminApiController::class, 'users']);
        Route::get('/companies', [AdminApiController::class, 'companies']);
        Route::get('/submissions', [AdminApiController::class, 'submissions']);
        Route::get('/candidates', [AdminApiController::class, 'candidates']);
        Route::get('/job-listings', [AdminApiController::class, 'jobListings']);
        Route::get('/blog-articles', [AdminApiController::class, 'blogArticles']);
        Route::get('/newsletter-subscriptions', [AdminApiController::class, 'newsletterSubscriptions']);
        Route::get('/statistics', [AdminApiController::class, 'statistics']);
        
        // Update routes
        Route::put('/users/{id}/status', [AdminApiController::class, 'updateUserStatus']);
        Route::put('/companies/{id}/status', [AdminApiController::class, 'updateCompanyStatus']);
    });
    
    // Institutional Admin API routes
    Route::middleware('role:institutional_admin')->prefix('institutional')->group(function () {
        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
        Route::get('/submissions', [AdminApiController::class, 'submissions']);
        Route::get('/statistics', [AdminApiController::class, 'statistics']);
    });
    
    // Sectoral Admin API routes
    Route::middleware('role:sectoral_admin')->prefix('sectoral')->group(function () {
        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
        Route::get('/submissions', [AdminApiController::class, 'submissions']);
        Route::get('/statistics', [AdminApiController::class, 'statistics']);
    });
    
    // API v1 - Advanced Features
    Route::prefix('v1')->group(function () {
        // Analytics API
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
        Route::get('/analytics/submissions-by-type', [AnalyticsController::class, 'submissionsByType']);
        Route::get('/analytics/user-stats', [AnalyticsController::class, 'userStats']);
        Route::get('/analytics/trends', [AnalyticsController::class, 'trends']);
        Route::get('/analytics/top-sectors', [AnalyticsController::class, 'topSectors']);
        Route::get('/analytics/status-distribution', [AnalyticsController::class, 'statusDistribution']);
        
        // Advanced Search API
        Route::get('/search', [V1SearchController::class, 'search']);
        
        // Export API
        Route::get('/export/excel', [ExportController::class, 'exportExcel']);
        Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
        Route::get('/export/csv', [ExportController::class, 'exportCsv']);

        // Bulk Operations API
        Route::post('/bulk/update-status', [\App\Http\Controllers\Api\v1\BulkOperationsController::class, 'bulkUpdateStatus']);
        Route::post('/bulk/delete', [\App\Http\Controllers\Api\v1\BulkOperationsController::class, 'bulkDelete']);
    });
    
    // Performance Monitoring Routes
    Route::get('/performance/metrics', [\App\Http\Controllers\PerformanceMonitoringController::class, 'metrics']);
    Route::get('/performance/slow-queries', [\App\Http\Controllers\PerformanceMonitoringController::class, 'slowQueries']);
    
    // Realtime Stats Route
    Route::get('/realtime/stats', [\App\Http\Controllers\Api\v1\RealtimeController::class, 'getStats']);
    Route::post('/realtime/authenticate', [\App\Http\Controllers\Api\v1\RealtimeController::class, 'authenticate']);
});

// Rate limiting for API routes
Route::middleware('throttle:60,1')->group(function () {
    // High-frequency routes with rate limiting
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('throttle:100,1')->group(function () {
    // General API routes with rate limiting
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user/profile', [UserController::class, 'profile']);
        Route::get('/files', [FileUploadController::class, 'getUserFiles']);
    });
});