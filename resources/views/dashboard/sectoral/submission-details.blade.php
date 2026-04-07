@extends('layouts.dashboard')

@section('dashboard-name', 'Sectoral Admin Dashboard')
@section('dashboard-icon', 'ri-folder-chart-line')
@section('page-title', 'Submission Details')
@section('profile-route', route('sectoral.profile'))
@section('settings-route', route('sectoral.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('sectoral.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('sectoral.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('sectoral.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('sectoral.analysis') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-line-chart-line text-xl mr-3"></i>
        Analysis
    </a>
    <a href="{{ route('sectoral.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    <a href="{{ route('sectoral.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Companies
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('sectoral.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-arrow-left-line text-xl mr-3"></i>
        Back to submissions
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8 space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i class="ri-checkbox-circle-line text-xl mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
            <i class="ri-error-warning-line text-xl mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Sectoral Review • Submission #{{ $submission->id }}</p>
            <h1 class="text-3xl font-bold text-gray-900">Submission Details</h1>
            <p class="mt-2 text-gray-600">Review and manage this submission from {{ $submission->user->username ?? 'N/A' }}.</p>
        </div>
        <div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'under_review' => 'bg-blue-100 text-blue-800',
                ];
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Submission Information -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Submission Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Submission Number</dt>
                        <dd class="mt-1 text-gray-900 font-mono">{{ $submission->submission_number ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-gray-900 capitalize">{{ str_replace('_', ' ', $type) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Submitted By</dt>
                        <dd class="mt-1 text-gray-900">{{ $submission->user->username ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Submitted On</dt>
                        <dd class="mt-1 text-gray-900">{{ $submission->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @if($submission->reviewed_at)
                        <div>
                            <dt class="font-medium text-gray-500">Reviewed At</dt>
                            <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($submission->reviewed_at)->format('M d, Y h:i A') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Submission Details -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Details</h2>
                <div class="prose max-w-none">
                    @if(isset($submission->project_title))
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Project Title</h3>
                            <p class="text-gray-700">{{ $submission->project_title }}</p>
                        </div>
                    @endif
                    @if(isset($submission->description))
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $submission->description }}</p>
                        </div>
                    @endif
                    @if(isset($submission->sector))
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Sector</h3>
                            <p class="text-gray-700">{{ $submission->sector }}</p>
                        </div>
                    @endif
                    @if(isset($submission->location))
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Location</h3>
                            <p class="text-gray-700">{{ $submission->location }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sectoral Notes -->
            @if(isset($submission->sectoral_notes) && $submission->sectoral_notes)
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Sectoral Notes</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $submission->sectoral_notes }}</p>
                </div>
            @endif

            <!-- Recommendations -->
            @if(isset($submission->recommendations) && $submission->recommendations)
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Recommendations</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $submission->recommendations }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Update Form -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Update Status</h2>
                <form method="POST" action="{{ route('sectoral.submissions.status', [$type, $submission->id]) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $submission->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ $submission->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved" {{ $submission->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $submission->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sectoral Notes</label>
                        <textarea name="sectoral_notes" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Add sectoral review notes...">{{ old('sectoral_notes', $submission->sectoral_notes ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recommendations</label>
                        <textarea name="recommendations" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Add recommendations...">{{ old('recommendations', $submission->recommendations ?? '') }}</textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i class="ri-save-line mr-2"></i>Update Status
                    </button>
                </form>
            </div>

            <!-- User Information -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Submitter Information</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Username</dt>
                        <dd class="mt-1 text-gray-900">{{ $submission->user->username ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-gray-900">{{ $submission->user->email ?? 'N/A' }}</dd>
                    </div>
                    @if($submission->user->region)
                        <div>
                            <dt class="font-medium text-gray-500">Region</dt>
                            <dd class="mt-1 text-gray-900">{{ $submission->user->region }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

