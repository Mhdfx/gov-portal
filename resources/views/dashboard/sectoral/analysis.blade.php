@extends('layouts.dashboard')

@section('dashboard-name', 'Sectoral Admin Dashboard')
@section('dashboard-icon', 'ri-folder-chart-line')
@section('page-title', 'Sectoral Analysis')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('sectoral.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('sectoral.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('sectoral.analysis') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-line-chart-line text-xl mr-3"></i>
        Analysis
    </a>
    <a href="{{ route('sectoral.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('sectoral.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Sectoral Analytics • Deep Analysis</p>
            <h1 class="text-3xl font-bold text-gray-900">Sectoral Analysis</h1>
            <p class="mt-2 text-gray-600">Comprehensive sector-specific insights, trends, and performance metrics.</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-list-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($analysis['submissions_by_type']->sum()) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-line-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Growth Rate</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($analysis['sector_trends']['growth_rate'] ?? 0, 1) }}%</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Success Rate</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($analysis['sector_trends']['success_rate'] ?? 0, 1) }}%</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pending Review</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($analysis['submissions_by_status']['pending'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Submissions by Type -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submissions by Type</h2>
            <div class="space-y-4">
                @foreach($analysis['submissions_by_type'] as $type => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">{{ $type }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($count) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $analysis['submissions_by_type']->sum() > 0 ? ($count / $analysis['submissions_by_type']->sum() * 100) : 0 }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submissions by Status -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Distribution</h2>
            <div class="space-y-4">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-500',
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        'under_review' => 'bg-blue-500',
                    ];
                @endphp
                @foreach($analysis['submissions_by_status'] as $status => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 {{ $statusColors[$status] ?? 'bg-gray-500' }} rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($count) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="{{ $statusColors[$status] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $analysis['submissions_by_status']->sum() > 0 ? ($count / $analysis['submissions_by_status']->sum() * 100) : 0 }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Trends -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trends</h2>
            @if($analysis['submissions_by_month']->count() > 0)
                <div class="space-y-3">
                    @foreach($analysis['submissions_by_month']->sortKeys() as $month => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $analysis['submissions_by_month']->max() > 0 ? ($count / $analysis['submissions_by_month']->max() * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12 text-right">{{ number_format($count) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No monthly data available</p>
            @endif
        </div>

        <!-- Regional Distribution -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Regional Distribution</h2>
            @if($analysis['submissions_by_region']->count() > 0)
                <div class="space-y-3">
                    @foreach($analysis['submissions_by_region']->sortDesc() as $region => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ $region }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $analysis['submissions_by_region']->max() > 0 ? ($count / $analysis['submissions_by_region']->max() * 100) : 0 }}%"></div>
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

    <!-- Sector Trends -->
    @if(isset($analysis['sector_trends']))
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Sector Trends & Insights</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-xl p-4">
                <p class="text-sm font-medium text-gray-500 mb-2">Popular Sectors</p>
                <div class="space-y-2">
                    @if(isset($analysis['sector_trends']['popular_sectors']) && is_array($analysis['sector_trends']['popular_sectors']))
                        @foreach($analysis['sector_trends']['popular_sectors'] as $sector)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 mr-2 mb-2">
                                {{ $sector }}
                            </span>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500">No sector data available</p>
                    @endif
                </div>
            </div>
            <div class="border border-gray-200 rounded-xl p-4">
                <p class="text-sm font-medium text-gray-500 mb-2">Growth Rate</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($analysis['sector_trends']['growth_rate'] ?? 0, 1) }}%</p>
                <p class="text-xs text-gray-500 mt-1">Compared to previous period</p>
            </div>
            <div class="border border-gray-200 rounded-xl p-4">
                <p class="text-sm font-medium text-gray-500 mb-2">Success Rate</p>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($analysis['sector_trends']['success_rate'] ?? 0, 1) }}%</p>
                <p class="text-xs text-gray-500 mt-1">Approval success rate</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection








