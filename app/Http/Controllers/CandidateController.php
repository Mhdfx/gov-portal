<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Candidate;
use App\Models\JobListing;
use App\Models\JobApplication;
use App\Models\FileUpload;
use App\Models\Notification;
use SEO;

class CandidateController extends Controller
{
    /**
     * Display the candidate registration form.
     */
    public function showRegistrationForm()
    {
        SEO::setTitle('Candidate Registration | Boiema Platform');
        SEO::setDescription('Register as a candidate to access job opportunities on the Boiema Platform.');
        
        return view('candidates.register');
    }

    /**
     * Handle candidate registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'education_level' => 'required|string|max:100',
            'field_of_study' => 'nullable|string|max:200',
            'university' => 'nullable|string|max:200',
            'years_of_experience' => 'required|integer|min:0',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            'professional_summary' => 'nullable|string|max:2000',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'availability' => 'required|in:immediate,1_month,3_months,6_months,flexible',
            'expected_salary' => 'nullable|numeric|min:0',
            'preferred_job_type' => 'required|in:full_time,part_time,contract,internship',
            'preferred_locations' => 'nullable|array',
            'preferred_locations.*' => 'string|max:100',
            'preferred_sectors' => 'nullable|array',
            'preferred_sectors.*' => 'string|max:100',
        ]);

        // Handle file uploads
        $cvPath = null;
        $coverLetterPath = null;
        $profilePicturePath = null;

        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('candidates/cvs', 'public');
        }

        if ($request->hasFile('cover_letter')) {
            $coverLetterPath = $request->file('cover_letter')->store('candidates/cover-letters', 'public');
        }

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('candidates/profile-pictures', 'public');
        }

        // Create candidate profile
        $candidate = Candidate::create([
            'user_id' => Auth::id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'postal_code' => $request->postal_code,
            'education_level' => $request->education_level,
            'field_of_study' => $request->field_of_study,
            'university' => $request->university,
            'years_of_experience' => $request->years_of_experience,
            'skills' => $request->skills,
            'languages' => $request->languages,
            'professional_summary' => $request->professional_summary,
            'cv_file_path' => $cvPath,
            'cover_letter_path' => $coverLetterPath,
            'profile_picture_path' => $profilePicturePath,
            'availability' => $request->availability,
            'expected_salary' => $request->expected_salary,
            'preferred_job_type' => $request->preferred_job_type,
            'preferred_locations' => $request->preferred_locations,
            'preferred_sectors' => $request->preferred_sectors,
        ]);

        return redirect()->route('candidate.dashboard')->with('success', 'Your candidate profile has been created successfully!');
    }

    /**
     * Display the candidate dashboard.
     */
    public function dashboard()
    {
        SEO::setTitle('Candidate Dashboard | Boiema Platform');
        SEO::setDescription('Manage your candidate profile and job applications on the Boiema Platform.');

        $candidate = Candidate::where('user_id', Auth::id())->first();
        
        if (!$candidate) {
            // Show onboarding screen instead of forcing redirect loop
            return view('dashboard.candidates.onboarding');
        }

        $allApplications = JobApplication::where('candidate_id', $candidate->id)->get();
        
        $applications = JobApplication::where('candidate_id', $candidate->id)
            ->with('jobListing.company')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recommendedJobs = JobListing::active()
            ->where('region', $candidate->region)
            ->where('job_type', $candidate->preferred_job_type)
            ->limit(5)
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_applications' => $allApplications->count(),
            'pending_applications' => $allApplications->where('status', 'pending')->count(),
            'under_review' => $allApplications->where('status', 'reviewed')->count(),
            'accepted_applications' => $allApplications->where('status', 'accepted')->count(),
            'total_jobs_available' => JobListing::active()->count(),
            'matching_jobs' => JobListing::active()
                ->where('region', $candidate->region)
                ->where('job_type', $candidate->preferred_job_type)
                ->count(),
        ];

        return view('dashboard.candidates.dashboard', compact('candidate', 'applications', 'recommendedJobs', 'stats'));
    }

    /**
     * Display the candidate profile.
     */
    public function profile()
    {
        SEO::setTitle('My Profile | Candidate Dashboard');
        SEO::setDescription('View and edit your candidate profile information.');

        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();
        
        return view('dashboard.candidates.profile', compact('candidate'));
    }

    /**
     * Update the candidate profile.
     */
    public function updateProfile(Request $request)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        // Handle skills and languages from comma-separated input
        $skills = [];
        $languages = [];
        
        if ($request->filled('skills_input')) {
            $skills = array_filter(array_map('trim', explode(',', $request->skills_input)));
        } elseif ($request->filled('skills')) {
            $skills = $request->skills;
        }
        
        if ($request->filled('languages_input')) {
            $languages = array_filter(array_map('trim', explode(',', $request->languages_input)));
        } elseif ($request->filled('languages')) {
            $languages = $request->languages;
        }
        
        $request->merge(['skills' => $skills, 'languages' => $languages]);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'education_level' => 'required|string|max:100',
            'field_of_study' => 'nullable|string|max:200',
            'university' => 'nullable|string|max:200',
            'years_of_experience' => 'required|integer|min:0',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            'professional_summary' => 'nullable|string|max:2000',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'availability' => 'required|in:immediate,1_month,3_months,6_months,flexible',
            'expected_salary' => 'nullable|numeric|min:0',
            'preferred_job_type' => 'required|in:full_time,part_time,contract,internship',
            'preferred_locations' => 'nullable|array',
            'preferred_locations.*' => 'string|max:100',
            'preferred_sectors' => 'nullable|array',
            'preferred_sectors.*' => 'string|max:100',
        ]);

        // Handle file uploads
        if ($request->hasFile('cv_file')) {
            if ($candidate->cv_file_path) {
                Storage::disk('public')->delete($candidate->cv_file_path);
            }
            $cvPath = $request->file('cv_file')->store('candidates/cvs', 'public');
        } else {
            $cvPath = $candidate->cv_file_path;
        }

        if ($request->hasFile('cover_letter')) {
            if ($candidate->cover_letter_path) {
                Storage::disk('public')->delete($candidate->cover_letter_path);
            }
            $coverLetterPath = $request->file('cover_letter')->store('candidates/cover-letters', 'public');
        } else {
            $coverLetterPath = $candidate->cover_letter_path;
        }

        if ($request->hasFile('profile_picture')) {
            if ($candidate->profile_picture_path) {
                Storage::disk('public')->delete($candidate->profile_picture_path);
            }
            $profilePicturePath = $request->file('profile_picture')->store('candidates/profile-pictures', 'public');
        } else {
            $profilePicturePath = $candidate->profile_picture_path;
        }

        // Update candidate profile
        $candidate->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'postal_code' => $request->postal_code,
            'education_level' => $request->education_level,
            'field_of_study' => $request->field_of_study,
            'university' => $request->university,
            'years_of_experience' => $request->years_of_experience,
            'skills' => $request->skills,
            'languages' => $request->languages,
            'professional_summary' => $request->professional_summary,
            'cv_file_path' => $cvPath,
            'cover_letter_path' => $coverLetterPath,
            'profile_picture_path' => $profilePicturePath,
            'availability' => $request->availability,
            'expected_salary' => $request->expected_salary,
            'preferred_job_type' => $request->preferred_job_type,
            'preferred_locations' => $request->preferred_locations,
            'preferred_sectors' => $request->preferred_sectors,
        ]);

        return redirect()->route('candidate.profile')->with('success', 'Your profile has been updated successfully!');
    }

    /**
     * Display job listings.
     */
    public function jobListings(Request $request)
    {
        $candidate = Candidate::where('user_id', Auth::id())->first();

        if (!$candidate) {
            return redirect()->route('candidate.register')->with('info', 'Please complete your candidate profile first.');
        }

        SEO::setTitle('Job Search | Candidate Dashboard');
        SEO::setDescription('Browse available job opportunities tailored to your preferences.');

        $query = JobListing::active()->with('company');

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('region')) {
            $query->inRegion($request->region);
        }

        if ($request->filled('job_type')) {
            $query->ofType($request->job_type);
        }

        if ($request->filled('experience_level')) {
            $query->withExperienceLevel($request->experience_level);
        }

        if ($request->boolean('remote_only')) {
            $query->where('is_remote', true);
        }

        $jobListings = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.candidates.jobs', compact('jobListings', 'candidate'));
    }

    /**
     * Display a specific job listing.
     */
    public function showJobListing(JobListing $jobListing)
    {
        SEO::setTitle($jobListing->title . ' | Job Listing');
        SEO::setDescription($jobListing->description);

        // Increment view count
        $jobListing->incrementViews();

        $candidate = Candidate::where('user_id', Auth::id())->first();
        $hasApplied = false;

        if ($candidate) {
            $hasApplied = JobApplication::where('candidate_id', $candidate->id)
                ->where('job_listing_id', $jobListing->id)
                ->exists();
        }

        $jobListing->load('company');

        return view('dashboard.candidates.jobs.show', compact('jobListing', 'hasApplied', 'candidate'));
    }

    /**
     * Apply for a job.
     */
    public function applyForJob(Request $request, JobListing $jobListing)
    {
        $candidate = Candidate::where('user_id', Auth::id())->first();

        if (!$candidate) {
            return redirect()->route('candidate.register')->with('error', 'Please complete your candidate profile first.');
        }

        // Check if already applied
        $existingApplication = JobApplication::where('candidate_id', $candidate->id)
            ->where('job_listing_id', $jobListing->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'additional_documents' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Handle file uploads
        $cvPath = $candidate->cv_file_path; // Use existing CV by default
        $additionalDocumentsPath = null;

        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('applications/cvs', 'public');
        }

        if ($request->hasFile('additional_documents')) {
            $additionalDocumentsPath = $request->file('additional_documents')->store('applications/documents', 'public');
        }

        // Create job application
        JobApplication::create([
            'candidate_id' => $candidate->id,
            'job_listing_id' => $jobListing->id,
            'cover_letter' => $request->cover_letter,
            'cv_file_path' => $cvPath,
            'additional_documents_path' => $additionalDocumentsPath,
            'applied_at' => now(),
        ]);

        // Increment applications count
        $jobListing->incrementApplications();

        return redirect()->route('candidate.dashboard')->with('success', 'Your application has been submitted successfully!');
    }

    /**
     * Display CV & documents management page.
     */
    public function cv()
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        SEO::setTitle('CV & Documents | Candidate Dashboard');
        SEO::setDescription('Manage your curriculum vitae and supporting documents.');

        return view('dashboard.candidates.cv', compact('candidate'));
    }

    /**
     * Update CV & supporting documents.
     */
    public function updateCv(Request $request)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'professional_summary' => 'nullable|string|max:2000',
        ]);

        $data = [
            'professional_summary' => $request->professional_summary,
        ];

        if ($request->hasFile('cv_file')) {
            if ($candidate->cv_file_path) {
                Storage::disk('public')->delete($candidate->cv_file_path);
            }
            $data['cv_file_path'] = $request->file('cv_file')->store('candidates/cvs', 'public');
        }

        if ($request->hasFile('cover_letter')) {
            if ($candidate->cover_letter_path) {
                Storage::disk('public')->delete($candidate->cover_letter_path);
            }
            $data['cover_letter_path'] = $request->file('cover_letter')->store('candidates/cover-letters', 'public');
        }

        $candidate->update(array_filter($data, fn ($value) => !is_null($value)));

        return redirect()->route('candidate.cv')->with('success', 'Your documents have been updated successfully.');
    }

    /**
     * Display candidate's job applications.
     */
    public function applications()
    {
        SEO::setTitle('My Applications | Candidate Dashboard');
        SEO::setDescription('Track your job applications and their status.');

        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        $applications = JobApplication::where('candidate_id', $candidate->id)
            ->with('jobListing.company')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.candidates.applications', compact('applications', 'candidate'));
    }

    /**
     * Show a single application with details.
     */
    public function showApplication(JobApplication $application)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        if ($application->candidate_id !== $candidate->id) {
            abort(403);
        }

        $application->load(['jobListing.company', 'reviewer']);

        SEO::setTitle('Application #' . $application->id . ' | Candidate Dashboard');
        SEO::setDescription('Review the details and status timeline of your application.');

        return view('dashboard.candidates.applications.show', compact('application', 'candidate'));
    }

    /**
     * Manage candidate document library.
     */
    public function documents(Request $request)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        SEO::setTitle('My Documents | Candidate Dashboard');
        SEO::setDescription('Upload and organize your supporting documents for faster applications.');

        $documentTypes = [
            'resume' => 'Resume / CV',
            'cover_letter' => 'Cover Letter',
            'certificate' => 'Certificate',
            'portfolio' => 'Portfolio',
            'other' => 'Other',
        ];

        $documentsQuery = FileUpload::where('user_id', $candidate->user_id)
            ->where('upload_type', 'candidate_document');

        if ($request->filled('type')) {
            $documentsQuery->where('file_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $documentsQuery->where(function ($query) use ($search) {
                $query->where('original_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $documents = $documentsQuery->latest()->paginate(12)->withQueryString();

        $documents->getCollection()->transform(function ($document) {
            $document->download_url = Storage::disk('public')->url($document->file_path);
            $document->file_size_human = $this->formatFileSize($document->file_size);
            return $document;
        });

        $stats = [
            'total' => FileUpload::where('user_id', $candidate->user_id)->where('upload_type', 'candidate_document')->count(),
            'by_type' => FileUpload::where('user_id', $candidate->user_id)
                ->where('upload_type', 'candidate_document')
                ->selectRaw('file_type, count(*) as total')
                ->groupBy('file_type')
                ->pluck('total', 'file_type')
                ->toArray(),
        ];

        return view('dashboard.candidates.documents', compact('candidate', 'documents', 'documentTypes', 'stats'));
    }

    /**
     * Upload a candidate document.
     */
    public function storeDocument(Request $request)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_type' => 'required|string|in:resume,cover_letter,certificate,portfolio,other',
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('document');
        $path = $file->store('candidate-documents/' . $candidate->id, 'public');

        FileUpload::create([
            'user_id' => $candidate->user_id,
            'file_name' => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => basename($path),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'file_type' => $request->document_type,
            'upload_type' => 'candidate_document',
            'description' => $request->description,
        ]);

        return redirect()->route('candidate.documents')->with('success', 'Document uploaded successfully.');
    }

    /**
     * Remove a candidate document.
     */
    public function destroyDocument(FileUpload $document)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        if ($document->user_id !== $candidate->user_id || $document->upload_type !== 'candidate_document') {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('candidate.documents')->with('success', 'Document removed successfully.');
    }

    /**
     * Candidate notifications center.
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        $candidate = Candidate::where('user_id', $user->id)->firstOrFail();

        SEO::setTitle('Notifications | Candidate Dashboard');
        SEO::setDescription('Stay on top of your application updates and account alerts.');

        $notificationsQuery = Notification::where('user_id', $user->id);

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $notificationsQuery->where('is_read', false);
            } elseif ($request->status === 'read') {
                $notificationsQuery->where('is_read', true);
            }
        }

        if ($request->filled('search')) {
            $notificationsQuery->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $notifications = $notificationsQuery->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ];

        return view('dashboard.candidates.notifications', compact('candidate', 'notifications', 'stats'));
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead(Notification $notification)
    {
        $user = Auth::user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Candidate account settings.
     */
    public function settings()
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        SEO::setTitle('Account Settings | Candidate Dashboard');
        SEO::setDescription('Control your visibility and preference settings.');

        return view('dashboard.candidates.settings', compact('candidate'));
    }

    /**
     * Update candidate settings.
     */
    public function updateSettings(Request $request)
    {
        $candidate = Candidate::where('user_id', Auth::id())->firstOrFail();

        $preferredLocations = $this->parseCommaSeparated($request->input('preferred_locations_input'));
        $preferredSectors = $this->parseCommaSeparated($request->input('preferred_sectors_input'));

        $request->merge([
            'preferred_locations' => $preferredLocations,
            'preferred_sectors' => $preferredSectors,
        ]);

        $request->validate([
            'is_available' => 'nullable|boolean',
            'availability' => 'required|in:immediate,1_month,3_months,6_months,flexible',
            'preferred_job_type' => 'required|in:full_time,part_time,contract,internship',
            'preferred_locations' => 'nullable|array',
            'preferred_locations.*' => 'string|max:100',
            'preferred_sectors' => 'nullable|array',
            'preferred_sectors.*' => 'string|max:100',
            'expected_salary' => 'nullable|numeric|min:0',
        ]);

        $candidate->update([
            'is_available' => $request->boolean('is_available'),
            'availability' => $request->availability,
            'preferred_job_type' => $request->preferred_job_type,
            'preferred_locations' => $preferredLocations,
            'preferred_sectors' => $preferredSectors,
            'expected_salary' => $request->expected_salary,
        ]);

        return redirect()->route('candidate.settings')->with('success', 'Settings saved successfully.');
    }

    /**
     * Helper to format bytes.
     */
    protected function formatFileSize(?int $bytes): string
    {
        if (!$bytes) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Helper to parse comma separated inputs.
     */
    protected function parseCommaSeparated(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $value)));
    }
}