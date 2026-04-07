@extends('layouts.dashboard')

@section('dashboard-name', 'Institutional Admin Dashboard')
@section('dashboard-icon', 'ri-government-line')
@section('page-title', 'Submission Details')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('institutional.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('institutional.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('institutional.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('institutional.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('institutional.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-arrow-left-line text-xl mr-3"></i>
        Back to submissions
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Institutional Review • Submission #{{ $submission->id }}</p>
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

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Submission Information -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Submission Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Submission Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->submission_number ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->tracking_number ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Submitted At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->updated_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @if($submission->reviewed_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reviewed At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->reviewed_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Applicant Information -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Applicant Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->full_name ?? $submission->user->username ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->email ?? $submission->user->email ?? 'N/A' }}</dd>
                    </div>
                    @if(isset($submission->phone))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->phone }}</dd>
                    </div>
                    @endif
                    @if(isset($submission->cin))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CIN</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->cin }}</dd>
                    </div>
                    @endif
                    @if(isset($submission->region))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Region</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->region }}</dd>
                    </div>
                    @endif
                    @if(isset($submission->city))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">City</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->city }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Project/Submission Details -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Details</h2>
                <dl class="space-y-4">
                    @if(isset($submission->project_name))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Project Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->project_name }}</dd>
                    </div>
                    @endif
                    @if(isset($submission->project_description))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Project Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $submission->project_description }}</dd>
                    </div>
                    @endif
                    @if(isset($submission->investment_amount))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Investment Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($submission->investment_amount, 2) }} MAD</dd>
                    </div>
                    @endif
                    @if(isset($submission->sector))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sector</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $submission->sector }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Attached Documents -->
            @if(isset($submission->business_plan_path) || isset($submission->financial_statements_path) || isset($submission->other_documents_path))
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Attached Documents</h2>
                <div class="space-y-3">
                    @if(isset($submission->business_plan_path))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="ri-file-pdf-line text-red-600 text-2xl mr-3"></i>
                            <span class="text-sm text-gray-900">Business Plan</span>
                        </div>
                        <a href="{{ Storage::url($submission->business_plan_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="ri-download-line mr-1"></i> Download
                        </a>
                    </div>
                    @endif
                    @if(isset($submission->financial_statements_path))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="ri-file-pdf-line text-red-600 text-2xl mr-3"></i>
                            <span class="text-sm text-gray-900">Financial Statements</span>
                        </div>
                        <a href="{{ Storage::url($submission->financial_statements_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="ri-download-line mr-1"></i> Download
                        </a>
                    </div>
                    @endif
                    @if(isset($submission->other_documents_path))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="ri-file-line text-gray-600 text-2xl mr-3"></i>
                            <span class="text-sm text-gray-900">Other Documents</span>
                        </div>
                        <a href="{{ Storage::url($submission->other_documents_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="ri-download-line mr-1"></i> Download
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Status Update Form -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h2>
                <form action="{{ route('institutional.submissions.status', ['type' => $type, 'id' => $submission->id]) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="pending" @selected($submission->status === 'pending')>Pending</option>
                                <option value="under_review" @selected($submission->status === 'under_review')>Under Review</option>
                                <option value="approved" @selected($submission->status === 'approved')>Approved</option>
                                <option value="rejected" @selected($submission->status === 'rejected')>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label for="institutional_notes" class="block text-sm font-medium text-gray-700 mb-2">Institutional Notes</label>
                            <textarea name="institutional_notes" id="institutional_notes" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Add notes about this submission...">{{ $submission->institutional_notes ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="next_steps" class="block text-sm font-medium text-gray-700 mb-2">Next Steps</label>
                            <textarea name="next_steps" id="next_steps" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe next steps...">{{ $submission->next_steps ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                            <i class="ri-save-line mr-2"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Review History -->
            @if($submission->reviewed_at)
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Review History</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Reviewed By</p>
                        <p class="text-sm text-gray-600">
                            @if($submission->reviewed_by && $submission->reviewer)
                                {{ $submission->reviewer->username }}
                            @elseif($submission->reviewed_by)
                                User #{{ $submission->reviewed_by }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Reviewed At</p>
                        <p class="text-sm text-gray-600">{{ $submission->reviewed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($submission->institutional_notes)
                    <div>
                        <p class="text-sm font-medium text-gray-700">Notes</p>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $submission->institutional_notes }}</p>
                    </div>
                    @endif
                    @if($submission->next_steps)
                    <div>
                        <p class="text-sm font-medium text-gray-700">Next Steps</p>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $submission->next_steps }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

