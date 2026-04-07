<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobListing;
use App\Models\JobApplication;
use App\Models\Company;

class JobListingController extends Controller
{
    /**
     * Display a listing of job listings.
     */
    public function index(Request $request)
    {
        $query = JobListing::active()->with('company');

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('region')) {
            $query->where('location', 'like', '%' . $request->region . '%');
        }

        if ($request->filled('job_type')) {
            $query->where('employment_type', $request->job_type);
        }

        if ($request->filled('experience_level')) {
            $query->withExperienceLevel($request->experience_level);
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        if ($request->filled('salary_max')) {
            $query->where('salary_max', '<=', $request->salary_max);
        }

        if ($request->filled('is_remote')) {
            $query->where('is_remote', $request->is_remote);
        }

        // Sort by featured first, then by date
        $jobListings = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get filter options
        $regions = JobListing::active()->distinct()->pluck('location')->filter();
        $jobTypes = JobListing::active()->distinct()->pluck('employment_type')->filter();
        $experienceLevels = JobListing::active()->distinct()->pluck('experience_level')->filter();

        return view('jobs.index', compact('jobListings', 'regions', 'jobTypes', 'experienceLevels'));
    }

    /**
     * Display the specified job listing.
     */
    public function show(JobListing $jobListing)
    {
        // Increment view count
        $jobListing->incrementViews();

        // Load related data
        $jobListing->load('company');
        
        // Get similar jobs
        $similarJobs = JobListing::active()
            ->where('id', '!=', $jobListing->id)
            ->where(function($query) use ($jobListing) {
                $query->where('location', $jobListing->location)
                      ->orWhere('employment_type', $jobListing->employment_type)
                      ->orWhere('experience_level', $jobListing->experience_level);
            })
            ->limit(4)
            ->get();

        // Check if user has applied (if authenticated)
        $hasApplied = false;
        if (Auth::check()) {
            $candidate = Auth::user()->candidate;
            if ($candidate) {
                $hasApplied = JobApplication::where('candidate_id', $candidate->id)
                    ->where('job_listing_id', $jobListing->id)
                    ->exists();
            }
        }

        return view('jobs.show', compact('jobListing', 'similarJobs', 'hasApplied'));
    }

    /**
     * Show the form for creating a new job listing.
     */
    public function create()
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.setup')->with('error', 'Please complete your company profile first.');
        }

        return view('jobs.create', compact('company'));
    }

    /**
     * Store a newly created job listing.
     */
    public function store(Request $request)
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('company.setup')->with('error', 'Please complete your company profile first.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'requirements' => 'required|string|max:3000',
            'responsibilities' => 'required|string|max:3000',
            'job_type' => 'required|in:full_time,part_time,contract,internship',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'education_required' => 'required|string|max:100',
            'required_skills' => 'required|array|min:1',
            'required_skills.*' => 'string|max:100',
            'preferred_skills' => 'nullable|array',
            'preferred_skills.*' => 'string|max:100',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'is_remote' => 'boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'required|string|max:3',
            'benefits' => 'nullable|string|max:1000',
            'application_deadline' => 'required|date|after:today',
            'start_date' => 'nullable|date|after:today',
            'is_featured' => 'boolean',
        ]);

        $jobListing = JobListing::create([
            'company_id' => $company->id,
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_required' => $request->education_required,
            'required_skills' => $request->required_skills,
            'preferred_skills' => $request->preferred_skills,
            'location' => $request->location,
            'city' => $request->city,
            'region' => $request->region,
            'is_remote' => $request->boolean('is_remote'),
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_currency' => $request->salary_currency,
            'benefits' => $request->benefits,
            'application_deadline' => $request->application_deadline,
            'start_date' => $request->start_date,
            'is_featured' => $request->boolean('is_featured'),
            'featured_until' => $request->boolean('is_featured') ? now()->addDays(30) : null,
        ]);

        return redirect()->route('company.jobs')->with('success', 'Job listing created successfully!');
    }

    /**
     * Show the form for editing the specified job listing.
     */
    public function edit(JobListing $jobListing)
    {
        // Check if user owns this job listing
        if ($jobListing->company->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('jobs.edit', compact('jobListing'));
    }

    /**
     * Update the specified job listing.
     */
    public function update(Request $request, JobListing $jobListing)
    {
        // Check if user owns this job listing
        if ($jobListing->company->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'requirements' => 'required|string|max:3000',
            'responsibilities' => 'required|string|max:3000',
            'job_type' => 'required|in:full_time,part_time,contract,internship',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'education_required' => 'required|string|max:100',
            'required_skills' => 'required|array|min:1',
            'required_skills.*' => 'string|max:100',
            'preferred_skills' => 'nullable|array',
            'preferred_skills.*' => 'string|max:100',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'is_remote' => 'boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'required|string|max:3',
            'benefits' => 'nullable|string|max:1000',
            'application_deadline' => 'required|date|after:today',
            'start_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,paused,closed',
            'is_featured' => 'boolean',
        ]);

        $jobListing->update([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_required' => $request->education_required,
            'required_skills' => $request->required_skills,
            'preferred_skills' => $request->preferred_skills,
            'location' => $request->location,
            'city' => $request->city,
            'region' => $request->region,
            'is_remote' => $request->boolean('is_remote'),
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_currency' => $request->salary_currency,
            'benefits' => $request->benefits,
            'application_deadline' => $request->application_deadline,
            'start_date' => $request->start_date,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'featured_until' => $request->boolean('is_featured') ? now()->addDays(30) : null,
        ]);

        return redirect()->route('company.jobs')->with('success', 'Job listing updated successfully!');
    }

    /**
     * Remove the specified job listing.
     */
    public function destroy(JobListing $jobListing)
    {
        // Check if user owns this job listing
        if ($jobListing->company->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $jobListing->delete();

        return redirect()->route('company.jobs')->with('success', 'Job listing deleted successfully!');
    }

    /**
     * Get job applications for a specific job listing.
     */
    public function applications(JobListing $jobListing)
    {
        // Check if user owns this job listing
        if ($jobListing->company->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $applications = JobApplication::where('job_listing_id', $jobListing->id)
            ->with('candidate.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('jobs.applications', compact('jobListing', 'applications'));
    }

    /**
     * Update application status.
     */
    public function updateApplicationStatus(Request $request, JobListing $jobListing, JobApplication $application)
    {
        // Check if user owns this job listing
        if ($jobListing->company->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,hired',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }
}