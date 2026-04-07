@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Newsletter Management')
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
    <a href="{{ route('admin.newsletter.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-mail-line text-xl mr-3"></i>
        Newsletter
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Communication</p>
            <h1 class="text-3xl font-bold text-gray-900">Newsletter Management</h1>
            <p class="mt-2 text-gray-600">Manage newsletter subscribers, compose campaigns, and track delivery metrics.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.newsletter.compose') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                <i class="ri-mail-send-line text-lg mr-2"></i> Compose Newsletter
            </a>
            <a href="{{ route('admin.newsletter.index', array_merge($filters, ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 text-white font-medium shadow hover:bg-green-700">
                <i class="ri-download-line text-lg mr-2"></i> Export CSV
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                {{ number_format($subscribers->total()) }} subscribers
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-user-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Subscribers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Recent (30d)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['recent']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="ri-close-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Unsubscribed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['unsubscribed']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ri-line-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Growth Rate</p>
                    <p class="text-2xl font-semibold text-gray-900 {{ $stats['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($stats['growth_rate'], 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Trends Chart -->
    <div class="bg-white shadow rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscription Trends (Last 6 Months)</h2>
        <div class="grid grid-cols-6 gap-4">
            @foreach($trends as $trend)
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-2">{{ $trend['month'] }}</p>
                    <div class="space-y-1">
                        <div class="flex items-center justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs font-semibold text-gray-900">{{ $trend['subscribed'] }}</span>
                        </div>
                        @if($trend['unsubscribed'] > 0)
                            <div class="flex items-center justify-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <span class="text-xs text-gray-500">{{ $trend['unsubscribed'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-2xl p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="search">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Email or name"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:ring-blue-500 focus:border-blue-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="sort">Sort by</label>
                <select id="sort" name="sort" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" @selected($filters['sort'] === 'latest')>Latest</option>
                    <option value="oldest" @selected($filters['sort'] === 'oldest')>Oldest</option>
                    <option value="email" @selected($filters['sort'] === 'email')>Email</option>
                    <option value="name" @selected($filters['sort'] === 'name')>Name</option>
                    <option value="status" @selected($filters['sort'] === 'status')>Status</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date range</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="md:col-span-4 flex items-end gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    <i class="ri-filter-3-line text-lg mr-2"></i> Apply filters
                </button>
                <a href="{{ route('admin.newsletter.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Subscribers table -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Subscribers</h2>
                <p class="text-sm text-gray-500">Latest {{ $subscribers->count() }} records in this view</p>
            </div>
            <span class="text-sm text-gray-500">Page {{ $subscribers->currentPage() }} of {{ $subscribers->lastPage() }}</span>
        </div>

        @if($subscribers->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unsubscribed</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-yellow-100 text-yellow-800',
                                'unsubscribed' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        @foreach($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-900">{{ $subscriber->email }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">{{ $subscriber->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$subscriber->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($subscriber->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    @if($subscriber->subscribed_at)
                                        <p>{{ $subscriber->subscribed_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $subscriber->subscribed_at->format('h:i A') }}</p>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    @if($subscriber->unsubscribed_at)
                                        <p>{{ $subscriber->unsubscribed_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $subscriber->unsubscribed_at->format('h:i A') }}</p>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    @if($subscriber->status === 'unsubscribed')
                                        <form action="{{ route('admin.newsletter.resubscribe', $subscriber->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 inline-flex items-center text-xs font-semibold" title="Resubscribe">
                                                <i class="ri-checkbox-circle-line text-lg mr-1"></i> Resubscribe
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.newsletter.unsubscribe', $subscriber->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to unsubscribe this subscriber?');">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center text-xs font-semibold" title="Unsubscribe">
                                                <i class="ri-close-circle-line text-lg mr-1"></i> Unsubscribe
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $subscribers->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-mail-line text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No subscribers match your filters</h3>
                <p class="text-gray-500 mb-6">Try broadening the filters or searching with different keywords.</p>
                <a href="{{ route('admin.newsletter.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

