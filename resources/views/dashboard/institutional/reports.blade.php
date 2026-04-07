@extends('layouts.dashboard')

@section('dashboard-name', 'Institutional Admin Dashboard')
@section('dashboard-icon', 'ri-government-line')
@section('page-title', 'Reports & Analytics')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('institutional.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('institutional.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('institutional.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('institutional.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Institutional Analytics • Reports</p>
            <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="mt-2 text-gray-600">Comprehensive insights into submissions, trends, and institutional performance.</p>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-list-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports['submissions_by_type']->sum()) }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports['submissions_by_status']['pending'] ?? 0) }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports['submissions_by_status']['approved'] ?? 0) }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports['submissions_by_status']['rejected'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Submissions by Type -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submissions by Type</h2>
            <div class="space-y-4">
                @foreach($reports['submissions_by_type'] as $type => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">{{ $type }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($count) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $reports['submissions_by_type']->sum() > 0 ? ($count / $reports['submissions_by_type']->sum() * 100) : 0 }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submissions by Status -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submissions by Status</h2>
            <div class="space-y-4">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        'under_review' => 'bg-blue-500',
                    ];
                @endphp
                @foreach($reports['submissions_by_status'] as $status => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 {{ $statusColors[$status] ?? 'bg-gray-500' }} rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($count) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="{{ $statusColors[$status] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $reports['submissions_by_status']->sum() > 0 ? ($count / $reports['submissions_by_status']->sum() * 100) : 0 }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Submissions by Month -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submissions by Month</h2>
            @if($reports['submissions_by_month']->count() > 0)
                <div class="space-y-3">
                    @foreach($reports['submissions_by_month']->sortKeys() as $month => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $reports['submissions_by_month']->max() > 0 ? ($count / $reports['submissions_by_month']->max() * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12 text-right">{{ number_format($count) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No submission data available</p>
            @endif
        </div>

        <!-- Submissions by Region -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submissions by Region</h2>
            @if($reports['submissions_by_region']->count() > 0)
                <div class="space-y-3">
                    @foreach($reports['submissions_by_region']->sortDesc() as $region => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ $region }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $reports['submissions_by_region']->max() > 0 ? ($count / $reports['submissions_by_region']->max() * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12 text-right">{{ number_format($count) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No regional data available</p>
            @endif
        </div>
    </div>
</div>
@endsection








