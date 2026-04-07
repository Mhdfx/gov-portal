@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', $jobListing->title)
@section('profile-route', route('candidate.profile'))
@section('settings-route', route('candidate.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('candidate.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('candidate.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Search
    </a>
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Applications
    </a>
    <a href="{{ route('candidate.cv') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-paper-line text-xl mr-3"></i>
        CV Management
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('candidate.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-arrow-left-line text-xl mr-3"></i>
        Back to Jobs
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="text-sm text-gray-500">Job ID: {{ $jobListing->id }}</p>
        <h1 class="text-3xl font-bold text-gray-900">{{ $jobListing->title }}</h1>
        <p class="text-gray-600">{{ $jobListing->company->company_name ?? 'Company' }} • {{ $jobListing->location }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">About the Role</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($jobListing->description)) !!}
                </div>
            </div>

            @if($jobListing->responsibilities)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Responsibilities</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($jobListing->responsibilities)) !!}
                </div>
            </div>
            @endif

            @if($jobListing->requirements)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Requirements</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($jobListing->requirements)) !!}
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Position Details</h2>
                <dl class="space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Employment Type</dt>
                        <dd>{{ ucfirst(str_replace('_', ' ', $jobListing->employment_type)) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Job Type</dt>
                        <dd>{{ ucfirst(str_replace('-', ' ', $jobListing->job_type)) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Experience</dt>
                        <dd>{{ ucfirst($jobListing->experience_level) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Education</dt>
                        <dd>{{ $jobListing->education_required ?? 'Not specified' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Location</dt>
                        <dd>{{ $jobListing->location }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Remote</dt>
                        <dd>{{ $jobListing->is_remote ? 'Available' : 'On-site' }}</dd>
                    </div>
                    @if($jobListing->salary_min || $jobListing->salary_max)
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Salary Range</dt>
                        <dd>
                            @if($jobListing->salary_min && $jobListing->salary_max)
                                {{ number_format($jobListing->salary_min) }} - {{ number_format($jobListing->salary_max) }} {{ $jobListing->salary_currency ?? 'MAD' }}
                            @elseif($jobListing->salary_min)
                                From {{ number_format($jobListing->salary_min) }} {{ $jobListing->salary_currency ?? 'MAD' }}
                            @endif
                        </dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-900">Application Deadline</dt>
                        <dd>{{ optional($jobListing->application_deadline)->format('M d, Y') ?? 'Open' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Apply Now</h2>
                @if($hasApplied)
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                        <i class="ri-check-line mr-2"></i>
                        You have already applied to this position.
                    </div>
                @else
                    <form method="POST" action="{{ route('candidate.jobs.apply', $jobListing->id) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Letter (optional)</label>
                            <textarea name="cover_letter" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Updated CV (optional)</label>
                            <input type="file" name="cv_file" accept=".pdf,.doc,.docx" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC up to 10MB</p>
                            @error('cv_file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Additional Documents</label>
                            <input type="file" name="additional_documents" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @error('additional_documents')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit Application</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection








