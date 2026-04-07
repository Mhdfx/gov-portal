@extends('layouts.dashboard')

@section('dashboard-name', 'Company Dashboard')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Job Listings')
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
    
    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Listings
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
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Job Listings</h1>
            <p class="mt-2 text-gray-600">Manage your job postings and track applications</p>
        </div>
        <a href="{{ route('company.jobs.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors">
            <i class="ri-add-line mr-2"></i>
            Post New Job
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-briefcase-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Jobs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_jobs'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-checkbox-circle-line text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Active Jobs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_jobs'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-list-3-line text-purple-600 text-2xl"></i>
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
                    <p class="text-sm text-gray-600">Pending Reviews</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_applications'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('company.jobs') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Job title, location..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Job Type</label>
                <select name="job_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type</label>
                <select name="employment_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Employment Types</option>
                    <option value="permanent" {{ request('employment_type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                    <option value="temporary" {{ request('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                    <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="ri-search-line mr-2"></i>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'job_type', 'employment_type']))
                <a href="{{ route('company.jobs') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="ri-close-line"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Jobs List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Job Listings ({{ $jobs->total() }})</h3>
        </div>
        
        @if($jobs->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($jobs as $job)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $job->title }}</h3>
                                @if($job->is_featured && $job->isFeatured())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="ri-star-fill mr-1"></i>Featured
                                </span>
                                @endif
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'paused' => 'bg-yellow-100 text-yellow-800',
                                        'closed' => 'bg-gray-100 text-gray-800',
                                        'draft' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $statusColor = $statusColors[$job->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                <span class="flex items-center">
                                    <i class="ri-briefcase-line mr-1"></i>
                                    {{ ucfirst(str_replace('-', ' ', $job->job_type ?? 'N/A')) }}
                                </span>
                                <span class="flex items-center">
                                    <i class="ri-map-pin-line mr-1"></i>
                                    {{ $job->location ?? 'Not specified' }}
                                    @if($job->is_remote)
                                        <span class="ml-1 text-blue-600">(Remote)</span>
                                    @endif
                                </span>
                                @if($job->salary_min || $job->salary_max)
                                <span class="flex items-center">
                                    <i class="ri-money-dollar-circle-line mr-1"></i>
                                    @if($job->salary_min && $job->salary_max)
                                        {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} {{ $job->salary_currency ?? 'MAD' }}
                                    @elseif($job->salary_min)
                                        From {{ number_format($job->salary_min) }} {{ $job->salary_currency ?? 'MAD' }}
                                    @else
                                        Up to {{ number_format($job->salary_max) }} {{ $job->salary_currency ?? 'MAD' }}
                                    @endif
                                </span>
                                @endif
                                @if($job->application_deadline)
                                <span class="flex items-center">
                                    <i class="ri-calendar-line mr-1"></i>
                                    Deadline: {{ $job->application_deadline->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-700 mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($job->description), 150) }}</p>
                            
                            <div class="flex items-center space-x-6 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="ri-file-list-3-line mr-1"></i>
                                    {{ $job->job_applications_count }} application(s)
                                </span>
                                <span class="flex items-center">
                                    <i class="ri-eye-line mr-1"></i>
                                    {{ $job->views_count ?? 0 }} views
                                </span>
                                <span class="text-gray-400">
                                    Posted {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="ml-4 flex space-x-2">
                            <a href="#" 
                               class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50" 
                               title="View Applications"
                               onclick="alert('Applications page coming soon!'); return false;">
                                <i class="ri-file-list-3-line text-xl"></i>
                            </a>
                            <a href="{{ route('company.jobs.edit', $job->id) }}" 
                               class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50" 
                               title="Edit">
                                <i class="ri-edit-line text-xl"></i>
                            </a>
                            <form action="{{ route('company.jobs.destroy', $job->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this job listing?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50" title="Delete">
                                    <i class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-briefcase-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No job listings found</h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->hasAny(['search', 'status', 'job_type', 'employment_type']))
                        No jobs match your filter criteria. Try adjusting your filters.
                    @else
                        You haven't posted any jobs yet. Create your first job listing to start attracting candidates.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'job_type', 'employment_type']))
                <a href="{{ route('company.jobs') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 font-medium transition-colors mr-2">
                    Clear Filters
                </a>
                @endif
                <a href="{{ route('company.jobs.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Post Your First Job
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
