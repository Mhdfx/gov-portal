@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'My Applications')
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
    
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Applications</h1>
        <p class="mt-2 text-gray-600">Track your job applications and their status</p>
    </div>

    <!-- Stats Cards -->
    @php
        $totalApplications = $applications->total();
        $pendingApplications = $applications->filter(fn($app) => $app->status == 'pending')->count();
        $reviewedApplications = $applications->filter(fn($app) => $app->status == 'reviewed')->count();
        $acceptedApplications = $applications->filter(fn($app) => $app->status == 'accepted')->count();
        $rejectedApplications = $applications->filter(fn($app) => $app->status == 'rejected')->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-list-3-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalApplications }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-time-line text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingApplications }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-eye-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Under Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reviewedApplications }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $acceptedApplications }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-close-circle-line text-red-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $rejectedApplications }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Applications ({{ $applications->total() }})</h3>
        </div>
        
        @if($applications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($applications as $application)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $application->jobListing->title ?? 'Job Title' }}
                                </h3>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'reviewed' => 'bg-blue-100 text-blue-800',
                                        'accepted' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusColor = $statusColors[$application->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($application->status ?? 'pending') }}
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <i class="ri-building-line mr-1"></i>
                                    {{ $application->jobListing->company->company_name ?? 'Company Name' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="ri-map-pin-line mr-1"></i>
                                    {{ $application->jobListing->location ?? 'Location' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="ri-calendar-line mr-1"></i>
                                    Applied {{ $application->applied_at ? $application->applied_at->diffForHumans() : $application->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            @if($application->cover_letter)
                            <p class="text-sm text-gray-700 mb-3">
                                <strong>Cover Letter:</strong> {{ \Illuminate\Support\Str::limit($application->cover_letter, 150) }}
                            </p>
                            @endif
                            
                            @if($application->admin_notes)
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-900">
                                    <strong>Admin Notes:</strong> {{ $application->admin_notes }}
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="ml-4 flex space-x-2">
                            <a href="{{ route('candidate.jobs.show', $application->jobListing->id ?? '#') }}" 
                               class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50" 
                               title="View Job">
                                <i class="ri-eye-line text-xl"></i>
                            </a>
                            @if($application->cv_file_path)
                            <a href="{{ asset('storage/' . $application->cv_file_path) }}" 
                               target="_blank"
                               class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50" 
                               title="View CV">
                                <i class="ri-file-pdf-line text-xl"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-file-list-3-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No applications yet</h3>
                <p class="text-gray-500 mb-6">
                    You haven't applied for any jobs yet. Start browsing available positions and submit your applications.
                </p>
                <a href="{{ route('candidate.jobs') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="ri-briefcase-line mr-2"></i>
                    Browse Jobs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection








