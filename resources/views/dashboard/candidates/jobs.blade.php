@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Job Search')
@section('profile-route', route('candidate.profile'))
@section('settings-route', route('candidate.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('candidate.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('candidate.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
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
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Back to Site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Find Jobs</h1>
        <p class="mt-2 text-gray-600">Explore opportunities that match your profile and skills.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Total Openings</p>
            <p class="text-3xl font-bold text-gray-900">{{ $jobListings->total() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Preferred Region</p>
            <p class="text-3xl font-bold text-gray-900">{{ $candidate->region ?? 'Any' }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Preferred Job Type</p>
            <p class="text-3xl font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $candidate->preferred_job_type ?? 'Any') }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('candidate.jobs') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Job title, company, keyword"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                <input type="text" name="region" value="{{ request('region') ?? $candidate->region }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                <select name="job_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Any</option>
                    @foreach(['full-time','part-time','contract','internship'] as $type)
                        <option value="{{ $type }}" @selected(request('job_type') == $type)>{{ ucfirst(str_replace('-', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Experience Level</label>
                <select name="experience_level" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Any</option>
                    @foreach(['entry','junior','mid','senior','executive'] as $level)
                        <option value="{{ $level }}" @selected(request('experience_level') == $level)>{{ ucfirst($level) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="remote_only" value="1" id="remote_only" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" @checked(request('remote_only'))>
                <label for="remote_only" class="text-sm text-gray-700">Remote only</label>
            </div>
            <div class="lg:col-span-5 flex justify-end gap-3">
                <a href="{{ route('candidate.jobs') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Reset</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Search</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($jobListings as $job)
            <div class="bg-white shadow rounded-lg p-6 flex flex-col h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $job->company->company_name ?? 'Company' }} • {{ $job->location }}</p>
                    </div>
                    @if($job->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                            Featured
                        </span>
                    @endif
                </div>
                <div class="mt-4 text-sm text-gray-600 space-y-2">
                    <p><span class="font-medium text-gray-900">Employment:</span> {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</p>
                    <p><span class="font-medium text-gray-900">Job Type:</span> {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</p>
                    <p><span class="font-medium text-gray-900">Experience:</span> {{ ucfirst($job->experience_level) }}</p>
                    @if($job->salary_min || $job->salary_max)
                    <p><span class="font-medium text-gray-900">Salary:</span>
                        @if($job->salary_min && $job->salary_max)
                            {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} {{ $job->salary_currency ?? 'MAD' }}
                        @elseif($job->salary_min)
                            From {{ number_format($job->salary_min) }} {{ $job->salary_currency ?? 'MAD' }}
                        @endif
                    </p>
                    @endif
                </div>
                <p class="mt-4 text-sm text-gray-600 line-clamp-3">{{ $job->short_description ?? \Illuminate\Support\Str::limit(strip_tags($job->description), 180) }}</p>
                <div class="mt-6 flex items-center justify-between text-xs text-gray-500">
                    <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                    <span>Deadline {{ optional($job->application_deadline)->format('M d, Y') }}</span>
                </div>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('candidate.jobs.show', $job->id) }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                        View Details
                    </a>
                    <form action="{{ route('candidate.jobs.apply', $job->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Quick Apply
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-8 text-center">
                    <i class="ri-search-eye-line text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No jobs found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your filters or check back later for new opportunities.</p>
                    <a href="{{ route('candidate.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Back to Dashboard</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $jobListings->links() }}
    </div>
</div>
@endsection

