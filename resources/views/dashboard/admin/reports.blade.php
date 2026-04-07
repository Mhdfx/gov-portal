@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Reports & Statistics')
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
    <a href="{{ route('admin.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('admin.files.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-line text-xl mr-3"></i>
        Files
    </a>
    <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Insights</p>
            <h1 class="text-3xl font-bold text-gray-900">Reports & Statistics</h1>
            <p class="mt-2 text-gray-600">Monitor submissions, growth, and platform health across the I.M System.</p>
        </div>
        <form method="GET">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Time range</label>
            <select name="range" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                @foreach($rangeOptions as $value => $label)
                    <option value="{{ $value }}" @selected($range === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Submission status stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-list-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format(array_sum($submissionCounts)) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($statusBreakdown['pending']) }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($statusBreakdown['approved']) }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($statusBreakdown['rejected']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission type breakdown -->
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Submission Types</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($submissionCounts as $type => $count)
                <div class="border border-gray-100 rounded-xl p-4">
                    <p class="text-xs uppercase text-gray-500">{{ ucfirst(str_replace('_', ' ', $type)) }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($count) }}</p>
                    <p class="text-xs text-gray-400">In selected range</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Growth cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                    <p class="text-sm text-gray-500">New accounts created during this period</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $rangeOptions[$range] }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">New users</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($userGrowth['new_users']) }}</dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">Verified</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($userGrowth['verified_users']) }}</dd>
                </div>
            </dl>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Company Growth</h3>
                    <p class="text-sm text-gray-500">New companies onboarding the platform</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">{{ $rangeOptions[$range] }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">New companies</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($companyGrowth['new_companies']) }}</dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">Approved</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($companyGrowth['approved_companies']) }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Recent submissions -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Recent submissions activity</h2>
                <p class="text-sm text-gray-500">Latest 8 updates across all submission types</p>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentSubmissions as $submission)
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $submission->user->username ?? 'Unknown user' }}</p>
                        <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $submission->submission_type ?? 'submission')) }}</p>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p>{{ $submission->created_at->format('M d, Y h:i A') }}</p>
                        <p class="text-xs text-gray-400">{{ $submission->status ? 'Status: ' . ucfirst(str_replace('_', ' ', $submission->status)) : 'Pending review' }}</p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-10 text-center text-gray-500">
                    No recent submissions in this period.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

