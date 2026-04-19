<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PorteurProjetController;
use App\Http\Controllers\PorteurIdeeController;
use App\Http\Controllers\AutoEntrepreneurController;
use App\Http\Controllers\INDHController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CompanyPublicController;

// ============================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================

// Health check routes (public)
Route::get('/health', [HealthCheckController::class, 'index'])->name('health');
Route::get('/health/detailed', [HealthCheckController::class, 'detailed'])->name('health.detailed');

// Homepage route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Blog routes (public)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{article:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');
Route::post('/blog/{article}/like', [BlogController::class, 'like'])->name('blog.like')->middleware('auth');

// Job listings routes (public)
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{jobListing}', [JobListingController::class, 'show'])->name('jobs.show');

// Public Business Hub Routes
Route::get('/companies', [CompanyPublicController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [CompanyPublicController::class, 'show'])->name('companies.show');

// Service forms routes (requires authentication)
Route::middleware('auth')->group(function () {
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'getPreferences'])->name('settings');
    Route::post('/settings/preferences', [SettingsController::class, 'savePreferences'])->name('settings.save');
    Route::post('/settings/dark-mode', [SettingsController::class, 'toggleDarkMode'])->name('settings.dark-mode');
    
    // Investment Form
    Route::get('/forms/investment', [InvestmentController::class, 'index'])->name('forms.investment');
    Route::post('/forms/investment', [FormSubmissionController::class, 'submitInvestment'])->name('forms.investment.submit')->middleware('throttle:10,1'); // 10 submissions per minute
    
    // Project Carrier Form
    Route::get('/forms/project-carrier', [PorteurProjetController::class, 'index'])->name('forms.project-carrier');
    Route::post('/forms/project-carrier', [FormSubmissionController::class, 'submitProjectCarrier'])->name('forms.project-carrier.submit')->middleware('throttle:10,1');
    
    // Idea Carrier Form
    Route::get('/forms/idea-carrier', [PorteurIdeeController::class, 'index'])->name('forms.idea-carrier');
    Route::post('/forms/idea-carrier', [FormSubmissionController::class, 'submitIdeaCarrier'])->name('forms.idea-carrier.submit')->middleware('throttle:10,1');
    
    // Auto-Entrepreneur Form
    Route::get('/forms/auto-entrepreneur', [AutoEntrepreneurController::class, 'index'])->name('forms.auto-entrepreneur');
    Route::post('/forms/auto-entrepreneur', [FormSubmissionController::class, 'submitAutoEntrepreneur'])->name('forms.auto-entrepreneur.submit')->middleware('throttle:10,1');
    
    // INDH Form
    Route::get('/forms/indh', [INDHController::class, 'index'])->name('forms.indh');
    Route::post('/forms/indh', [FormSubmissionController::class, 'submitINDH'])->name('forms.indh.submit')->middleware('throttle:10,1');
    
    // Training Form
    Route::get('/forms/training', [TrainingController::class, 'index'])->name('forms.training');
    Route::post('/forms/training', [FormSubmissionController::class, 'submitTraining'])->name('forms.training.submit')->middleware('throttle:10,1');
});

// Static pages routes (public)
Route::get('/about', [StaticController::class, 'about'])->name('about');
Route::get('/contact', [StaticController::class, 'contact'])->name('contact');
Route::post('/contact', [StaticController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:10,1'); // 10 submissions per minute

// Newsletter routes (public)
Route::get('/newsletter/subscribe', [NewsletterController::class, 'showSubscriptionForm'])->name('newsletter.subscribe');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe.post');
Route::get('/newsletter/unsubscribe/{email?}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe.post');

// Search route (public)
Route::get('/search', [SearchController::class, 'searchResults'])->name('search');

// Analytics dashboard (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/analytics', function () {
        return view('analytics.dashboard');
    })->name('analytics.dashboard');
});

// Language switching route (public)
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

// 2FA routes
Route::middleware('auth')->group(function () {
    Route::get('/2fa/setup', [\App\Http\Controllers\Auth\TwoFactorAuthController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa/enable', [\App\Http\Controllers\Auth\TwoFactorAuthController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [\App\Http\Controllers\Auth\TwoFactorAuthController::class, 'disable'])->name('2fa.disable');
});

// 2FA verification (public, but requires session)
Route::get('/2fa/verify', [\App\Http\Controllers\Auth\TwoFactorAuthController::class, 'showVerification'])->name('2fa.verify');
Route::post('/2fa/verify', [\App\Http\Controllers\Auth\TwoFactorAuthController::class, 'verify'])->name('2fa.verify.post');

// Registration routes (public)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:3,1'); // 3 attempts per minute

// Candidate registration routes (public)
Route::get('/candidates/register', [CandidateController::class, 'showRegistrationForm'])->name('candidates.register');
Route::post('/candidates/register', [CandidateController::class, 'register'])->name('candidates.register.post');
// Backward-compatible route names expected by some redirects/views
Route::get('/candidate/register', [CandidateController::class, 'showRegistrationForm'])->name('candidate.register');
Route::post('/candidate/register', [CandidateController::class, 'register'])->name('candidate.register.post');

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated dashboards
Route::middleware(['auth'])->group(function () {
    Route::middleware('role:main_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [AdminDashboardController::class, 'updatePassword'])->name('profile.password');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/companies', [AdminDashboardController::class, 'companies'])->name('companies');
        Route::post('/companies/{id}/approve', [AdminDashboardController::class, 'approveCompany'])->name('companies.approve');
        Route::post('/companies/{id}/reject', [AdminDashboardController::class, 'rejectCompany'])->name('companies.reject');
        Route::get('/submissions', [AdminDashboardController::class, 'submissions'])->name('submissions');
        Route::post('/submissions/{type}/{id}/status', [AdminDashboardController::class, 'updateSubmissionStatus'])->name('submissions.status');
        Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports.index');
        Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [AdminDashboardController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/{id}/unread', [AdminDashboardController::class, 'markNotificationAsUnread'])->name('notifications.mark-unread');
        Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [AdminDashboardController::class, 'deleteNotification'])->name('notifications.delete');
        Route::post('/notifications/bulk-delete', [AdminDashboardController::class, 'bulkDeleteNotifications'])->name('notifications.bulk-delete');
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
        Route::get('/files', [AdminDashboardController::class, 'files'])->name('files.index');
        Route::get('/newsletter', [AdminDashboardController::class, 'newsletter'])->name('newsletter.index');
        Route::get('/newsletter/compose', [AdminDashboardController::class, 'composeNewsletter'])->name('newsletter.compose');
        Route::post('/newsletter/{id}/unsubscribe', [AdminDashboardController::class, 'unsubscribeSubscriber'])->name('newsletter.unsubscribe');
        Route::post('/newsletter/{id}/resubscribe', [AdminDashboardController::class, 'resubscribeSubscriber'])->name('newsletter.resubscribe');
        Route::get('/security/audit-log', [AdminDashboardController::class, 'securityAuditLog'])->name('security.audit-log');
    });

    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/submissions', [UserDashboardController::class, 'submissions'])->name('submissions');
        Route::get('/files', [UserDashboardController::class, 'files'])->name('files');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [UserDashboardController::class, 'updateSettings'])->name('settings.update');
    });

    Route::middleware('role:institutional_admin')->prefix('institutional')->name('institutional.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\InstitutionalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/submissions', [App\Http\Controllers\InstitutionalDashboardController::class, 'submissions'])->name('submissions');
        Route::get('/submissions/{type}/{id}', [App\Http\Controllers\InstitutionalDashboardController::class, 'showSubmission'])->name('submissions.show');
        Route::post('/submissions/{type}/{id}/status', [App\Http\Controllers\InstitutionalDashboardController::class, 'updateSubmissionStatus'])->name('submissions.status');
        Route::get('/reports', [App\Http\Controllers\InstitutionalDashboardController::class, 'reports'])->name('reports');
        Route::get('/notifications', [App\Http\Controllers\InstitutionalDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\InstitutionalDashboardController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/{id}/unread', [App\Http\Controllers\InstitutionalDashboardController::class, 'markNotificationAsUnread'])->name('notifications.mark-unread');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\InstitutionalDashboardController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/users', [App\Http\Controllers\InstitutionalDashboardController::class, 'users'])->name('users');
        Route::get('/companies', [App\Http\Controllers\InstitutionalDashboardController::class, 'companies'])->name('companies');
        Route::get('/profile', [App\Http\Controllers\InstitutionalDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\InstitutionalDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\InstitutionalDashboardController::class, 'updatePassword'])->name('profile.password');
        Route::get('/settings', [App\Http\Controllers\InstitutionalDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\InstitutionalDashboardController::class, 'updateSettings'])->name('settings.update');
    });

    Route::middleware('role:sectoral_admin')->prefix('sectoral')->name('sectoral.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SectoralDashboardController::class, 'index'])->name('dashboard');
        Route::get('/submissions', [App\Http\Controllers\SectoralDashboardController::class, 'submissions'])->name('submissions');
        Route::get('/submissions/{type}/{id}', [App\Http\Controllers\SectoralDashboardController::class, 'showSubmission'])->name('submissions.show');
        Route::post('/submissions/{type}/{id}/status', [App\Http\Controllers\SectoralDashboardController::class, 'updateSubmissionStatus'])->name('submissions.status');
        Route::get('/analysis', [App\Http\Controllers\SectoralDashboardController::class, 'analysis'])->name('analysis');
        Route::get('/reports', [App\Http\Controllers\SectoralDashboardController::class, 'reports'])->name('reports');
        Route::get('/notifications', [App\Http\Controllers\SectoralDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\SectoralDashboardController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/{id}/unread', [App\Http\Controllers\SectoralDashboardController::class, 'markNotificationAsUnread'])->name('notifications.mark-unread');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\SectoralDashboardController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/companies', [App\Http\Controllers\SectoralDashboardController::class, 'companies'])->name('companies');
        Route::get('/profile', [App\Http\Controllers\SectoralDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\SectoralDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\SectoralDashboardController::class, 'updatePassword'])->name('profile.password');
        Route::get('/settings', [App\Http\Controllers\SectoralDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\SectoralDashboardController::class, 'updateSettings'])->name('settings.update');
    });

    Route::middleware('role:company')->prefix('company')->name('company.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\CompanyDashboardController::class, 'index'])->name('dashboard');
        Route::get('/setup', [App\Http\Controllers\CompanyDashboardController::class, 'setup'])->name('setup');
        Route::post('/setup', [App\Http\Controllers\CompanyDashboardController::class, 'store'])->name('store');
        Route::get('/profile', [App\Http\Controllers\CompanyDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\CompanyDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/products', [App\Http\Controllers\CompanyDashboardController::class, 'products'])->name('products');
        Route::get('/products/create', [App\Http\Controllers\CompanyDashboardController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\CompanyDashboardController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [App\Http\Controllers\CompanyDashboardController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\CompanyDashboardController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\CompanyDashboardController::class, 'destroyProduct'])->name('products.destroy');
        Route::get('/orders', [App\Http\Controllers\CompanyDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [App\Http\Controllers\CompanyDashboardController::class, 'showOrder'])->name('orders.show');
        Route::get('/jobs', [App\Http\Controllers\CompanyDashboardController::class, 'jobs'])->name('jobs');
        Route::get('/jobs/create', [App\Http\Controllers\CompanyDashboardController::class, 'createJob'])->name('jobs.create');
        Route::post('/jobs', [App\Http\Controllers\CompanyDashboardController::class, 'storeJob'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [App\Http\Controllers\CompanyDashboardController::class, 'editJob'])->name('jobs.edit');
        Route::put('/jobs/{job}', [App\Http\Controllers\CompanyDashboardController::class, 'updateJob'])->name('jobs.update');
        Route::delete('/jobs/{job}', [App\Http\Controllers\CompanyDashboardController::class, 'destroyJob'])->name('jobs.destroy');
        Route::get('/applications', [App\Http\Controllers\CompanyDashboardController::class, 'jobApplications'])->name('applications');
        Route::get('/applications/{application}', [App\Http\Controllers\CompanyDashboardController::class, 'showJobApplication'])->name('applications.show');
        Route::get('/documents', [App\Http\Controllers\CompanyDashboardController::class, 'documents'])->name('documents');
        Route::post('/documents', [App\Http\Controllers\CompanyDashboardController::class, 'storeDocument'])->name('documents.store');
        Route::delete('/documents/{document}', [App\Http\Controllers\CompanyDashboardController::class, 'destroyDocument'])->name('documents.destroy');
        Route::get('/notifications', [App\Http\Controllers\CompanyDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{notification}/read', [App\Http\Controllers\CompanyDashboardController::class, 'markNotificationRead'])->name('notifications.read');
        Route::get('/settings', [App\Http\Controllers\CompanyDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\CompanyDashboardController::class, 'updateSettings'])->name('settings.update');

        // Business Hub Management
        Route::get('/public-profile', [App\Http\Controllers\CompanyDashboardController::class, 'publicProfile'])->name('public-profile');
        Route::post('/public-profile', [App\Http\Controllers\CompanyDashboardController::class, 'updatePublicProfile'])->name('public-profile.update');
        Route::post('/updates', [App\Http\Controllers\CompanyDashboardController::class, 'storeUpdate'])->name('updates.store');
        Route::delete('/updates/{update}', [App\Http\Controllers\CompanyDashboardController::class, 'deleteUpdate'])->name('updates.delete');
    });

    Route::middleware('role:candidate')->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/register', [App\Http\Controllers\CandidateController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\CandidateController::class, 'register'])->name('register.submit');
        Route::get('/dashboard', [App\Http\Controllers\CandidateController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\CandidateController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\CandidateController::class, 'updateProfile'])->name('profile.update');
        Route::get('/jobs', [App\Http\Controllers\CandidateController::class, 'jobListings'])->name('jobs');
        Route::get('/jobs/{jobListing}', [App\Http\Controllers\CandidateController::class, 'showJobListing'])->name('jobs.show');
        Route::post('/jobs/{jobListing}/apply', [App\Http\Controllers\CandidateController::class, 'applyForJob'])->name('jobs.apply');
        Route::get('/applications', [App\Http\Controllers\CandidateController::class, 'applications'])->name('applications');
        Route::get('/applications/{application}', [App\Http\Controllers\CandidateController::class, 'showApplication'])->name('applications.show');
        Route::get('/documents', [App\Http\Controllers\CandidateController::class, 'documents'])->name('documents');
        Route::post('/documents', [App\Http\Controllers\CandidateController::class, 'storeDocument'])->name('documents.store');
        Route::delete('/documents/{document}', [App\Http\Controllers\CandidateController::class, 'destroyDocument'])->name('documents.destroy');
        Route::get('/notifications', [App\Http\Controllers\CandidateController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{notification}/read', [App\Http\Controllers\CandidateController::class, 'markNotificationRead'])->name('notifications.read');
        Route::get('/cv', [App\Http\Controllers\CandidateController::class, 'cv'])->name('cv');
        Route::post('/cv', [App\Http\Controllers\CandidateController::class, 'updateCv'])->name('cv.update');
        Route::get('/settings', [App\Http\Controllers\CandidateController::class, 'settings'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\CandidateController::class, 'updateSettings'])->name('settings.update');
    });
});
