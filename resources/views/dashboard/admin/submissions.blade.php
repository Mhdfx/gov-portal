@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Submissions Management')
@section('profile-route', route('admin.profile'))
@section('settings-route', route('admin.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('admin.analytics') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-2-line text-xl mr-3"></i>
        Analytics
    </a>
    <a href="{{ route('admin.users') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Users
    </a>
    <a href="{{ route('admin.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Companies
    </a>
    <a href="{{ route('admin.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
        @if($stats['pending'] > 0)
        <span class="ml-auto bg-red-100 text-red-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['pending'] }}</span>
        @endif
    </a>
    <a href="{{ route('admin.files.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-line text-xl mr-3"></i>
        Files
    </a>
    <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-box-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('admin.notifications.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    <a href="{{ route('admin.security.audit-log') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shield-check-line text-xl mr-3"></i>
        Security
    </a>
    <a href="{{ route('admin.logs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-text-line text-xl mr-3"></i>
        Logs
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Back to site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Forms</p>
            <h1 class="text-3xl font-bold text-gray-900">Submissions Management</h1>
            <p class="mt-2 text-gray-600">Review, filter, and moderate all form submissions across the platform.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                {{ $filters['sort'] === 'latest' ? 'Sort: Latest' : 'Sort: ' . ucfirst($filters['sort']) }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                Showing {{ $submissions->count() }} / {{ number_format($submissions->total()) }} submissions
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-list-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pending review</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['pending']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Approved</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['approved']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="ri-close-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['rejected']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-2xl p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="search">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="User, email, or type"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:ring-blue-500 focus:border-blue-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="type">Type</label>
                <select id="type" name="type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All types</option>
                    @foreach($submissionTypes as $value => $label)
                        <option value="{{ $value }}" @selected($filters['type'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All statuses</option>
                    <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
                    <option value="approved" @selected($filters['status'] === 'approved')>Approved</option>
                    <option value="rejected" @selected($filters['status'] === 'rejected')>Rejected</option>
                    <option value="in_review" @selected($filters['status'] === 'in_review')>In Review</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="sort">Sort by</label>
                <select id="sort" name="sort" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" @selected($filters['sort'] === 'latest')>Latest</option>
                    <option value="oldest" @selected($filters['sort'] === 'oldest')>Oldest</option>
                    <option value="status" @selected($filters['sort'] === 'status')>Status</option>
                    <option value="type" @selected($filters['sort'] === 'type')>Type</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date range</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="md:col-span-3 flex items-end gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    <i class="ri-filter-3-line text-lg mr-2"></i> Apply filters
                </button>
                <a href="{{ route('admin.submissions') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Submissions table -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Submissions</h2>
                <p class="text-sm text-gray-500">Latest {{ $submissions->count() }} records in this view</p>
            </div>
            <span class="text-sm text-gray-500">Page {{ $submissions->currentPage() }} of {{ $submissions->lastPage() }}</span>
        </div>

        @if($submissions->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewed</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'in_review' => 'bg-blue-100 text-blue-800',
                            ];
                        @endphp
                        @foreach($submissions as $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $submission->user->username ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $submission->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $submission->submission_type_label ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <p>{{ $submission->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $submission->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    @if($submission->reviewed_at)
                                        <p>{{ $submission->reviewed_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $submission->reviewed_at->format('h:i A') }}</p>
                                    @else
                                        <span class="text-xs text-gray-400">Not reviewed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 inline-flex items-center text-xs font-semibold" title="View submission details">
                                            <i class="ri-eye-line text-lg mr-1"></i> View
                                        </a>
                                        <button type="button" class="text-gray-500 hover:text-gray-900 inline-flex items-center text-xs font-semibold">
                                            <i class="ri-more-2-line text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-file-search-line text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No submissions match your filters</h3>
                <p class="text-gray-500 mb-6">Try broadening the filters or searching with different keywords.</p>
                <a href="{{ route('admin.submissions') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
