@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Analytics & Insights')
@section('profile-route', route('admin.profile'))
@section('settings-route', route('admin.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('admin.analytics') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Analytics</p>
            <h1 class="text-3xl font-bold text-gray-900">Analytics & Insights</h1>
            <p class="mt-2 text-gray-600">Track adoption, submission outcomes, and growth trends across the I.M System.</p>
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

    <!-- KPI cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-group-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($kpis['total_users']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-user-add-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">New Users (range)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($kpis['new_users']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-list-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($kpis['total_submissions']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                    <i class="ri-bar-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Approval Rate</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $kpis['approval_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">User Growth</h2>
                    <p class="text-sm text-gray-500">Verification onboarding for the selected period</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $rangeOptions[$range] }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">Verified</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($userStats['verified']) }}</dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">Pending verification</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($userStats['pending_verification']) }}</dd>
                </div>
            </dl>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Company Pipeline</h2>
                    <p class="text-sm text-gray-500">Approvals and onboarding for companies</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">{{ $rangeOptions[$range] }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">Total companies</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ number_format($companyStats['total']) }}</dd>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <dt class="text-xs uppercase text-gray-500">Approval rate</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ $companyStats['approval_rate'] }}%</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Trend & approval breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submissions Trend</h2>
            @if($submissionsTrend->count())
                <div class="space-y-4">
                    @foreach($submissionsTrend as $month => $count)
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($count) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                @php
                                    $max = $submissionsTrend->max() ?: 1;
                                @endphp
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($count / $max) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-6">No submissions recorded for this period.</p>
            @endif
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Approval Breakdown</h2>
            <div class="space-y-3">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'under_review' => 'bg-blue-500',
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                    ];
                @endphp
                @foreach($approvalBreakdown as $status => $count)
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span class="capitalize">{{ str_replace('_', ' ', $status) }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($count) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            @php
                                $total = array_sum($approvalBreakdown) ?: 1;
                            @endphp
                            <div class="{{ $statusColors[$status] ?? 'bg-gray-400' }} h-2 rounded-full" style="width: {{ ($count / $total) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Role distribution & sectors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Role Distribution</h2>
            @if($roleDistribution->count())
                <div class="space-y-4">
                    @foreach($roleDistribution as $role => $total)
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span class="uppercase tracking-wide text-xs text-gray-500">{{ str_replace('_', ' ', $role) }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($total) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                @php
                                    $maxRole = $roleDistribution->max() ?: 1;
                                @endphp
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ ($total / $maxRole) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-6">No role data available.</p>
            @endif
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Sectors</h2>
            @if($topSectors->count())
                <div class="space-y-3">
                    @foreach($topSectors as $sector => $count)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $sector }}</p>
                                <p class="text-xs text-gray-500">Submissions: {{ number_format($count) }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                {{ number_format($count) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-6">No sector information available.</p>
            @endif
        </div>
    </div>
</div>
@endsection








