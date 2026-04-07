@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'System Logs')
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
    <a href="{{ route('admin.logs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • System Monitoring</p>
            <h1 class="text-3xl font-bold text-gray-900">System Logs</h1>
            <p class="mt-2 text-gray-600">Monitor system activity, errors, and security events across the platform.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.logs', array_merge($filters, ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 text-white font-medium shadow hover:bg-green-700">
                <i class="ri-download-line text-lg mr-2"></i> Export CSV
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                Showing {{ $logs->count() }} / {{ number_format($logs->total()) }} logs
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-text-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Logs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-information-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Info</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['by_level']['info'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="ri-error-warning-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Warnings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['by_level']['warning'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="ri-error-warning-fill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Errors</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['by_level']['error'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ri-alert-line text-2xl"></i>
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
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Action, description, IP, or user"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:ring-blue-500 focus:border-blue-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="level">Level</label>
                <select id="level" name="level" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Levels</option>
                    @foreach($levels as $value => $label)
                        <option value="{{ $value }}" @selected($filters['level'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="action">Action</label>
                <select id="action" name="action" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected($filters['action'] === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="user_id">User</label>
                <select id="user_id" name="user_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Users</option>
                    @foreach($usersWithLogs as $user)
                        <option value="{{ $user->id }}" @selected($filters['user_id'] == $user->id)>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="sort">Sort by</label>
                <select id="sort" name="sort" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" @selected($filters['sort'] === 'latest')>Latest</option>
                    <option value="oldest" @selected($filters['sort'] === 'oldest')>Oldest</option>
                    <option value="level" @selected($filters['sort'] === 'level')>Level</option>
                    <option value="action" @selected($filters['sort'] === 'action')>Action</option>
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
                <a href="{{ route('admin.logs') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Logs table -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">System Logs</h2>
                <p class="text-sm text-gray-500">Latest {{ $logs->count() }} records in this view</p>
            </div>
            <span class="text-sm text-gray-500">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</span>
        </div>

        @if($logs->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @php
                            $levelColors = [
                                'info' => 'bg-blue-100 text-blue-800',
                                'warning' => 'bg-yellow-100 text-yellow-800',
                                'error' => 'bg-red-100 text-red-800',
                                'critical' => 'bg-purple-100 text-purple-800',
                            ];
                        @endphp
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50" 
                                data-log-id="{{ $log->id }}"
                                data-log-level="{{ $log->level ?? '' }}"
                                data-log-action="{{ $log->action }}"
                                data-log-description="{{ e($log->description) }}"
                                data-log-ip="{{ $log->ip_address ?? '' }}"
                                data-log-user-agent="{{ e($log->user_agent ?? '') }}"
                                data-log-user="{{ $log->user ? e($log->user->username) : 'System' }}"
                                data-log-user-email="{{ $log->user ? e($log->user->email) : '' }}"
                                data-log-created="{{ $log->created_at->toDateTimeString() }}"
                                data-log-data="{{ $log->log_data ? json_encode($log->log_data) : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">#{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <p>{{ $log->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $levelColors[$log->level] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($log->level ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <span class="font-medium">{{ $log->action }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <p class="text-sm text-gray-900">{{ $log->user->username }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                    @else
                                        <span class="text-sm text-gray-500">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 max-w-md truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <span class="text-xs font-mono">{{ $log->ip_address ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <button type="button" onclick="showLogDetails(this)" class="text-blue-600 hover:text-blue-900 inline-flex items-center text-xs font-semibold">
                                        <i class="ri-eye-line text-lg mr-1"></i> View
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-file-search-line text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No logs match your filters</h3>
                <p class="text-gray-500 mb-6">Try broadening the filters or searching with different keywords.</p>
                <a href="{{ route('admin.logs') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Log Details</h3>
            <button onclick="closeLogDetails()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div id="logDetailsContent" class="space-y-4">
            <!-- Content will be loaded via AJAX or inline -->
        </div>
    </div>
</div>

<script>
function showLogDetails(button) {
    const row = button.closest('tr');
    const modal = document.getElementById('logDetailsModal');
    const content = document.getElementById('logDetailsContent');
    
    const logId = row.dataset.logId;
    const level = row.dataset.logLevel || 'N/A';
    const action = row.dataset.logAction;
    const description = row.dataset.logDescription;
    const ipAddress = row.dataset.logIp || 'N/A';
    const userAgent = row.dataset.logUserAgent;
    const user = row.dataset.logUser;
    const userEmail = row.dataset.logUserEmail;
    const created = row.dataset.logCreated;
    const logData = row.dataset.logData;
    
    let logDataHtml = '';
    if (logData) {
        try {
            const parsed = JSON.parse(logData);
            logDataHtml = `
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-2">Additional Data</p>
                    <pre class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg overflow-auto">${JSON.stringify(parsed, null, 2)}</pre>
                </div>
            `;
        } catch (e) {
            logDataHtml = '';
        }
    }
    
    content.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-medium text-gray-500">ID</p>
                <p class="text-sm text-gray-900">#${logId}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Date & Time</p>
                <p class="text-sm text-gray-900">${new Date(created).toLocaleString()}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Level</p>
                <p class="text-sm text-gray-900">${level}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Action</p>
                <p class="text-sm text-gray-900">${action}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">User</p>
                <p class="text-sm text-gray-900">${user}${userEmail ? ' (' + userEmail + ')' : ''}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">IP Address</p>
                <p class="text-sm text-gray-900 font-mono">${ipAddress}</p>
            </div>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2">Description</p>
            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">${description}</p>
        </div>
        ${userAgent ? `
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2">User Agent</p>
            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg font-mono text-xs">${userAgent}</p>
        </div>
        ` : ''}
        ${logDataHtml}
    `;
    
    modal.classList.remove('hidden');
}

function closeLogDetails() {
    document.getElementById('logDetailsModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('logDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogDetails();
    }
});
</script>
@endsection

