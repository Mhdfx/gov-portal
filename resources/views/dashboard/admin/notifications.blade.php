@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Notifications Center')
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
    <a href="{{ route('admin.notifications.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i class="ri-checkbox-circle-line text-xl mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Communication</p>
            <h1 class="text-3xl font-bold text-gray-900">Notifications Center</h1>
            <p class="mt-2 text-gray-600">Manage and monitor all platform notifications across users and system events.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
                <input type="hidden" name="type" value="{{ $filters['type'] }}">
                <input type="hidden" name="priority" value="{{ $filters['priority'] }}">
                <input type="hidden" name="user_id" value="{{ $filters['user_id'] }}">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 text-white font-medium shadow hover:bg-green-700">
                    <i class="ri-check-double-line text-lg mr-2"></i> Mark All as Read
                </button>
            </form>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                Showing {{ $notifications->count() }} / {{ number_format($notifications->total()) }} notifications
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-notification-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="ri-notification-badge-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Unread</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['unread']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Read</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['read']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="ri-alert-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">High Priority</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['by_priority']['high'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ri-calendar-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Today</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['today']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-2xl p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="search">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Title, message, type, or user"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:ring-blue-500 focus:border-blue-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="unread" @selected($filters['status'] === 'unread')>Unread</option>
                    <option value="read" @selected($filters['status'] === 'read')>Read</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="type">Type</label>
                <select id="type" name="type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" @selected($filters['type'] === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="priority">Priority</label>
                <select id="priority" name="priority" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $value => $label)
                        <option value="{{ $value }}" @selected($filters['priority'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="user_id">User</label>
                <select id="user_id" name="user_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Users</option>
                    @foreach($usersWithNotifications as $user)
                        <option value="{{ $user->id }}" @selected($filters['user_id'] == $user->id)>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="sort">Sort by</label>
                <select id="sort" name="sort" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" @selected($filters['sort'] === 'latest')>Latest</option>
                    <option value="oldest" @selected($filters['sort'] === 'oldest')>Oldest</option>
                    <option value="priority" @selected($filters['sort'] === 'priority')>Priority</option>
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
            <div class="md:col-span-4 flex items-end gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    <i class="ri-filter-3-line text-lg mr-2"></i> Apply filters
                </button>
                <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Notifications list -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-500">Latest {{ $notifications->count() }} records in this view</p>
            </div>
            <span class="text-sm text-gray-500">Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}</span>
        </div>

        @if($notifications->count())
            <div class="divide-y divide-gray-200">
                @php
                    $priorityColors = [
                        'high' => 'bg-red-100 text-red-800 border-red-200',
                        'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'low' => 'bg-blue-100 text-blue-800 border-blue-200',
                    ];
                    $typeIcons = [
                        'submission_approved' => 'ri-checkbox-circle-line',
                        'submission_rejected' => 'ri-close-circle-line',
                        'submission_pending' => 'ri-time-line',
                        'submission_in_review' => 'ri-eye-line',
                        'company_approved' => 'ri-building-line',
                        'company_rejected' => 'ri-building-2-line',
                        'job_application' => 'ri-briefcase-line',
                        'newsletter' => 'ri-mail-line',
                        'system_alert' => 'ri-alert-line',
                        'maintenance' => 'ri-tools-line',
                    ];
                @endphp
                @foreach($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50/50' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full {{ $notification->is_read ? 'bg-gray-100' : 'bg-blue-100' }} flex items-center justify-center">
                                    <i class="{{ $typeIcons[$notification->type] ?? 'ri-notification-3-line' }} text-2xl {{ $notification->is_read ? 'text-gray-600' : 'text-blue-600' }}"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-base font-semibold text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                                {{ $notification->title }}
                                            </h3>
                                            @if($notification->priority)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold border {{ $priorityColors[$notification->priority] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                                    {{ ucfirst($notification->priority) }}
                                                </span>
                                            @endif
                                            @if(!$notification->is_read)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-600 text-white">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">{{ $notification->message }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="ri-user-line mr-1"></i>
                                                {{ $notification->user ? $notification->user->username : 'System' }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="ri-time-line mr-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="ri-tag-line mr-1"></i>
                                                {{ $notification->type }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if(!$notification->is_read)
                                            <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Mark as read">
                                                    <i class="ri-check-line text-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.notifications.mark-unread', $notification->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Mark as unread">
                                                    <i class="ri-close-circle-line text-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.notifications.delete', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-notification-off-line text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No notifications match your filters</h3>
                <p class="text-gray-500 mb-6">Try broadening the filters or searching with different keywords.</p>
                <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection







