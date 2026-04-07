@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Application Details')
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
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-arrow-left-line text-xl mr-3"></i>
        Back to applications
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Application #{{ $application->id }}</p>
            <h1 class="text-3xl font-bold text-gray-900">{{ optional($application->jobListing)->title ?? 'Job Listing' }}</h1>
            <p class="text-gray-500">
                {{ optional(optional($application->jobListing)->company)->company_name ?? 'Company' }}
                • {{ optional($application->jobListing)->location ?? 'Location' }}
            </p>
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
            {{ ucfirst($application->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Application Overview</h2>
                <dl class="grid md:grid-cols-2 gap-y-4 text-sm text-gray-600">
                    <div>
                        <dt class="text-gray-500 uppercase text-xs">Position</dt>
                        <dd class="font-medium text-gray-900">{{ optional($application->jobListing)->title ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 uppercase text-xs">Company</dt>
                        <dd>{{ optional(optional($application->jobListing)->company)->company_name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 uppercase text-xs">Applied On</dt>
                        <dd>{{ optional($application->applied_at ?: $application->created_at)->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 uppercase text-xs">Location</dt>
                        <dd>{{ optional($application->jobListing)->location ?? 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-gray-500 uppercase text-xs">Cover Letter</dt>
                        <dd class="mt-2 text-gray-700 whitespace-pre-line">{{ $application->cover_letter ?: 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            @if($application->jobListing)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Job Description</h2>
                <div class="space-y-4 text-gray-700">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">About the Role</h3>
                        <p class="mt-2">{!! nl2br(e($application->jobListing->description)) !!}</p>
                    </div>
                    @if($application->jobListing->requirements)
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Requirements</h3>
                            <p class="mt-2">{!! nl2br(e($application->jobListing->requirements)) !!}</p>
                        </div>
                    @endif
                    @if($application->jobListing->responsibilities)
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Responsibilities</h3>
                            <p class="mt-2">{!! nl2br(e($application->jobListing->responsibilities)) !!}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                <div class="space-y-3">
                    @if($application->cv_url)
                        <a href="{{ $application->cv_url }}" target="_blank" class="flex items-center justify-between border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i class="ri-attachment-2 text-blue-600 text-xl"></i>
                                <span>Resume / CV</span>
                            </div>
                            <span class="text-blue-600 text-sm">View</span>
                        </a>
                    @else
                        <p class="text-sm text-gray-500">No CV uploaded for this application.</p>
                    @endif
                    @if($application->additional_documents_path)
                        <a href="{{ $application->additional_documents_url }}" target="_blank" class="flex items-center justify-between border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i class="ri-file-copy-line text-indigo-600 text-xl"></i>
                                <span>Additional Documents</span>
                            </div>
                            <span class="text-blue-600 text-sm">Download</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Timeline</h3>
                <ul class="space-y-4 text-sm text-gray-700">
                    <li>
                        <p class="font-medium text-gray-900">Submitted</p>
                        <p>{{ optional($application->created_at)->format('F j, Y h:i A') }}</p>
                    </li>
                    @if($application->reviewed_at)
                        <li>
                            <p class="font-medium text-gray-900">Reviewed</p>
                            <p>{{ $application->reviewed_at->format('F j, Y h:i A') }}</p>
                            @if($application->reviewer)
                                <p class="text-xs text-gray-500">Reviewed by {{ $application->reviewer->name }}</p>
                            @endif
                        </li>
                    @endif
                    <li>
                        <p class="font-medium text-gray-900">Current Status</p>
                        <p>{{ ucfirst($application->status) }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

