@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Dashboard')
@section('profile-route', route('candidate.profile'))
@section('settings-route', route('candidate.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('candidate.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    
    <a href="{{ route('candidate.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        My Applications
    </a>
    
    <a href="{{ route('candidate.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Search
    </a>
    
    <a href="{{ route('candidate.documents') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-line text-xl mr-3"></i>
        My Documents
    </a>
    
    <a href="{{ route('candidate.cv') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-paper-line text-xl mr-3"></i>
        CV Management
    </a>
    
    <a href="{{ route('candidate.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $candidate->first_name }}!</h1>
        <p class="mt-2 text-gray-600">Here's an overview of your job search activity</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-list-3-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-time-line text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_applications'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-checkbox-circle-line text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Accepted</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['accepted_applications'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-briefcase-line text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Matching Jobs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['matching_jobs'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Applications -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Recent Applications</h3>
                <a href="{{ route('candidate.applications') }}" class="text-sm text-blue-600 hover:text-blue-900">
                    View All →
                </a>
            </div>
            
            @if($applications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($applications as $application)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 mb-1">
                                    {{ $application->jobListing->title ?? 'Job Title' }}
                                </h4>
                                <p class="text-xs text-gray-500 mb-2">
                                    {{ $application->jobListing->company->company_name ?? 'Company' }}
                                </p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>{{ $application->created_at->diffForHumans() }}</span>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusColor = $statusColors[$application->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($application->status ?? 'pending') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="ri-file-list-3-line text-gray-400 text-4xl mb-4"></i>
                    <p class="text-sm text-gray-500 mb-4">No applications yet</p>
                    <a href="{{ route('candidate.jobs') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Browse Jobs →
                    </a>
                </div>
            @endif
        </div>

        <!-- Recommended Jobs -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Recommended Jobs</h3>
                <a href="{{ route('candidate.jobs') }}" class="text-sm text-blue-600 hover:text-blue-900">
                    View All →
                </a>
            </div>
            
            @if($recommendedJobs->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($recommendedJobs as $job)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 mb-1">
                                    {{ $job->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mb-2">
                                    {{ $job->company->company_name ?? 'Company' }} • {{ $job->location }}
                                </p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>{{ ucfirst(str_replace('-', ' ', $job->job_type ?? 'N/A')) }}</span>
                                    @if($job->salary_min || $job->salary_max)
                                    <span>
                                        @if($job->salary_min && $job->salary_max)
                                            {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} {{ $job->salary_currency ?? 'MAD' }}
                                        @elseif($job->salary_min)
                                            From {{ number_format($job->salary_min) }} {{ $job->salary_currency ?? 'MAD' }}
                                        @endif
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('candidate.jobs.show', $job->id) }}" 
                               class="ml-4 text-blue-600 hover:text-blue-900">
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="ri-briefcase-line text-gray-400 text-4xl mb-4"></i>
                    <p class="text-sm text-gray-500 mb-4">No recommended jobs at the moment</p>
                    <a href="{{ route('candidate.jobs') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Browse All Jobs →
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('candidate.profile') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="ri-user-line text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Update Profile</p>
                    <p class="text-xs text-gray-500">Edit your information</p>
                </div>
            </a>
            
            <a href="{{ route('candidate.jobs') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="ri-briefcase-line text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Browse Jobs</p>
                    <p class="text-xs text-gray-500">Find new opportunities</p>
                </div>
            </a>
            
            <a href="{{ route('candidate.applications') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="ri-file-list-3-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">View Applications</p>
                    <p class="text-xs text-gray-500">Track your applications</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection








