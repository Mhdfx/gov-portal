@extends('layouts.dashboard')

@section('dashboard-name', 'Company Dashboard')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Job Applications')
@section('profile-route', route('company.profile'))
@section('settings-route', route('company.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    
    <a href="{{ route('company.setup') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Setup
    </a>
    
    <a href="{{ route('company.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    
    <a href="{{ route('company.products') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-bag-line text-xl mr-3"></i>
        Products
    </a>
    
    <a href="{{ route('company.orders') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-cart-line text-xl mr-3"></i>
        Orders
    </a>
    
    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Listings
    </a>
    
    <a href="{{ route('company.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Applications
    </a>
    
    <a href="{{ route('company.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Settings
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
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Job Applications</h1>
            <p class="mt-2 text-gray-600">Monitor and review all applications submitted to your job listings.</p>
        </div>
        <a href="{{ route('company.jobs') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
            <i class="ri-briefcase-line mr-2"></i>
            Manage Job Listings
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Total Applications</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_applications']) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_applications']) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">In Review</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['reviewed_applications']) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Accepted</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['accepted_applications']) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">Rejected</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['rejected_applications']) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5">
            <p class="text-sm text-gray-500">This Week</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['this_week_applications']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('company.applications') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate or job"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Job Listing</label>
                <select name="job_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Jobs</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" @selected(request('job_id') == $job->id)>{{ \Illuminate\Support\Str::limit($job->title, 40) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach(['pending','reviewed','accepted','rejected'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors w-full">
                    Filter
                </button>
                <a href="{{ route('company.applications') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors w-full text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Applications ({{ $applications->total() }})</h3>
        </div>
        @if($applications->count())
            <div class="divide-y divide-gray-200">
                @foreach($applications as $application)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    {{ $application->candidate->full_name ?? 'Candidate' }}
                                </h4>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'reviewed' => 'bg-blue-100 text-blue-800',
                                        'accepted' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($application->status ?? 'pending') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class="ri-briefcase-line mr-2"></i>
                                {{ $application->jobListing->title ?? 'Job Listing Removed' }}
                            </p>
                            <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="ri-calendar-line mr-1"></i>
                                    Applied {{ optional($application->applied_at)->format('M d, Y') ?? $application->created_at->format('M d, Y') }}
                                </span>
                                @if($application->candidate && $application->candidate->city)
                                <span class="flex items-center">
                                    <i class="ri-map-pin-line mr-1"></i>
                                    {{ $application->candidate->city }}, {{ $application->candidate->region }}
                                </span>
                                @endif
                                <span class="flex items-center">
                                    <i class="ri-mail-line mr-1"></i>
                                    {{ $application->candidate->email ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($application->cv_url)
                            <a href="{{ $application->cv_url }}" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2">
                                <i class="ri-file-text-line"></i>
                                CV
                            </a>
                            @endif
                            <a href="{{ route('company.applications.show', $application->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center gap-2">
                                <i class="ri-eye-line"></i>
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-file-list-3-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No applications yet</h3>
                <p class="text-gray-500 mb-6">You haven't received any job applications yet. Promote your job listings to attract candidates.</p>
                <a href="{{ route('company.jobs.create') }}" class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Post a Job
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

