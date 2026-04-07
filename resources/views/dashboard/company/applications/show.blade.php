@extends('layouts.dashboard')

@section('dashboard-name', 'Company Dashboard')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Application Details')
@section('profile-route', route('company.profile'))
@section('settings-route', route('company.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Listings
    </a>
    <a href="{{ route('company.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Applications
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
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">Application #{{ $application->id }}</p>
            <h1 class="text-3xl font-bold text-gray-900">Application Details</h1>
        </div>
        <a href="{{ route('company.applications') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
            <i class="ri-arrow-left-line mr-2"></i>
            Back to Applications
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs uppercase text-gray-500">Candidate</p>
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $application->candidate->full_name ?? 'Candidate' }}
                        </h2>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'reviewed' => 'bg-blue-100 text-blue-800',
                            'accepted' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($application->status ?? 'pending') }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
                    <div>
                        <p class="font-medium text-gray-900">Email</p>
                        <p>{{ $application->candidate->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Phone</p>
                        <p>{{ $application->candidate->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Location</p>
                        <p>{{ $application->candidate->city ?? 'N/A' }}, {{ $application->candidate->region ?? '' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Experience</p>
                        <p>{{ $application->candidate->years_of_experience ?? 'N/A' }} years</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Application Details</h2>
                <div class="space-y-4 text-sm text-gray-600">
                    <div>
                        <p class="font-medium text-gray-900">Applied For</p>
                        <p>{{ $application->jobListing->title ?? 'Job Listing Removed' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Applied On</p>
                        <p>{{ optional($application->applied_at)->format('M d, Y h:i A') ?? $application->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($application->cover_letter)
                    <div>
                        <p class="font-medium text-gray-900 mb-2">Cover Letter</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $application->cover_letter }}
                        </div>
                    </div>
                    @endif
                    @if($application->admin_notes)
                    <div>
                        <p class="font-medium text-gray-900 mb-2">Internal Notes</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-900 whitespace-pre-line">
                            {{ $application->admin_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Attached Documents</h2>
                <div class="space-y-3">
                    @if($application->cv_url)
                        <a href="{{ $application->cv_url }}" target="_blank" class="flex items-center justify-between px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i class="ri-file-text-line text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Curriculum Vitae</p>
                                    <p class="text-xs text-gray-500">Uploaded by candidate</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-blue-600">View</span>
                        </a>
                    @else
                        <p class="text-sm text-gray-500">No CV provided with this application.</p>
                    @endif

                    @if($application->additional_documents_url)
                        <a href="{{ $application->additional_documents_url }}" target="_blank" class="flex items-center justify-between px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i class="ri-attachment-2 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Additional Documents</p>
                                    <p class="text-xs text-gray-500">Supporting materials</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-blue-600">View</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Listing</h2>
                <p class="text-gray-900 font-medium">{{ $application->jobListing->title ?? 'Job Listing Removed' }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $application->jobListing->location ?? 'Location not set' }}</p>
                <div class="mt-4 text-sm text-gray-600 space-y-2">
                    <p><span class="font-medium text-gray-900">Employment Type:</span> {{ ucfirst(str_replace('_', ' ', $application->jobListing->employment_type ?? 'N/A')) }}</p>
                    <p><span class="font-medium text-gray-900">Job Type:</span> {{ ucfirst(str_replace('-', ' ', $application->jobListing->job_type ?? 'N/A')) }}</p>
                    <p><span class="font-medium text-gray-900">Experience Level:</span> {{ ucfirst($application->jobListing->experience_level ?? 'N/A') }}</p>
                    @if($application->jobListing && ($application->jobListing->salary_min || $application->jobListing->salary_max))
                    <p><span class="font-medium text-gray-900">Salary Range:</span>
                        @if($application->jobListing->salary_min && $application->jobListing->salary_max)
                            {{ number_format($application->jobListing->salary_min) }} - {{ number_format($application->jobListing->salary_max) }} {{ $application->jobListing->salary_currency ?? 'MAD' }}
                        @elseif($application->jobListing->salary_min)
                            From {{ number_format($application->jobListing->salary_min) }} {{ $application->jobListing->salary_currency ?? 'MAD' }}
                        @endif
                    </p>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700 disabled:opacity-50" disabled>
                        <i class="ri-mail-send-line"></i>
                        Contact Candidate (Coming Soon)
                    </button>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm flex items-center justify-center gap-2 hover:bg-green-100 disabled:opacity-50" disabled>
                            <i class="ri-checkbox-circle-line"></i>
                            Accept
                        </button>
                        <button class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm flex items-center justify-center gap-2 hover:bg-red-100 disabled:opacity-50" disabled>
                            <i class="ri-close-circle-line"></i>
                            Reject
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 text-center">Status actions will be available once workflow is finalized.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection








