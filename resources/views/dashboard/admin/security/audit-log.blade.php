@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Security Audit Log')
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
    <a href="{{ route('admin.security.audit-log') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Security Monitoring</p>
            <h1 class="text-3xl font-bold text-gray-900">Security Audit Log</h1>
            <p class="mt-2 text-gray-600">Monitor authentication events, permission changes, and security anomalies across the platform.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.security.audit-log', array_merge($filters, ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 text-white font-medium shadow hover:bg-green-700">
                <i class="ri-download-line text-lg mr-2"></i> Export CSV
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                Showing {{ $paginator->count() }} / {{ number_format($paginator->total()) }} events
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-shield-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_events']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="ri-alert-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">High Risk</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['high_risk']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="ri-error-warning-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Failed Logins</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['failed_logins']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Unique IPs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['unique_ips']) }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['today_events']) }}</p>
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
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Event, description, IP, or user"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:ring-blue-500 focus:border-blue-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="event_type">Event Type</label>
                <select id="event_type" name="event_type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Events</option>
                    <option value="login_attempts" @selected($filters['event_type'] === 'login_attempts')>Login Attempts</option>
                    <option value="system_events" @selected($filters['event_type'] === 'system_events')>System Events</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="risk_level">Risk Level</label>
                <select id="risk_level" name="risk_level" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Levels</option>
                    <option value="high" @selected($filters['risk_level'] === 'high')>High Risk</option>
                    <option value="medium" @selected($filters['risk_level'] === 'medium')>Medium Risk</option>
                    <option value="low" @selected($filters['risk_level'] === 'low')>Low Risk</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="ip_address">IP Address</label>
                <select id="ip_address" name="ip_address" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All IPs</option>
                    @foreach($uniqueIps as $ip)
                        <option value="{{ $ip }}" @selected($filters['ip_address'] === $ip)>{{ $ip }}</option>
                    @endforeach
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
                <a href="{{ route('admin.security.audit-log') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Audit log table -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Security Events</h2>
                <p class="text-sm text-gray-500">Latest {{ $paginator->count() }} records in this view</p>
            </div>
            <span class="text-sm text-gray-500">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
        </div>

        @if($paginator->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Level</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @php
                            $riskColors = [
                                'high' => 'bg-red-100 text-red-800 border-red-200',
                                'medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'low' => 'bg-green-100 text-green-800 border-green-200',
                            ];
                            $eventIcons = [
                                'login_success' => 'ri-login-box-line text-green-600',
                                'login_failed' => 'ri-error-warning-line text-red-600',
                                'logout' => 'ri-logout-box-line text-gray-600',
                                'password_change' => 'ri-lock-password-line text-blue-600',
                                'role_change' => 'ri-user-settings-line text-purple-600',
                                'permission_change' => 'ri-shield-user-line text-orange-600',
                                'user_deleted' => 'ri-user-unfollow-line text-red-600',
                                'security_alert' => 'ri-alert-line text-red-600',
                            ];
                        @endphp
                        @foreach($paginator as $event)
                            <tr class="hover:bg-gray-50 {{ $event['risk_level'] === 'high' ? 'bg-red-50/30' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <p>{{ $event['timestamp']->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $event['timestamp']->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $event['type'] === 'login_attempt' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $event['type'] === 'login_attempt' ? 'Login' : 'System' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="{{ $eventIcons[$event['event']] ?? 'ri-information-line text-gray-600' }} text-lg"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $event['event'])) }}</p>
                                            <p class="text-xs text-gray-500 max-w-md truncate" title="{{ $event['description'] }}">{{ $event['description'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event['user'])
                                        <p class="text-sm text-gray-900">{{ $event['user']->username }}</p>
                                        <p class="text-xs text-gray-500">{{ $event['user']->email }}</p>
                                    @elseif(isset($event['identifier']))
                                        <p class="text-sm text-gray-900">{{ $event['identifier'] }}</p>
                                        <p class="text-xs text-gray-500">Not registered</p>
                                    @else
                                        <span class="text-sm text-gray-500">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <span class="text-xs font-mono">{{ $event['ip_address'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $riskColors[$event['risk_level']] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($event['risk_level']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <button type="button" onclick="showEventDetails(@json($event))" class="text-blue-600 hover:text-blue-900 inline-flex items-center text-xs font-semibold">
                                        <i class="ri-eye-line text-lg mr-1"></i> View
                                    </button>
                                    @if($event['risk_level'] === 'high' && $event['type'] === 'login_attempt' && !($event['success'] ?? false))
                                        <button type="button" onclick="blockIp('{{ $event['ip_address'] }}')" class="ml-2 text-red-600 hover:text-red-900 inline-flex items-center text-xs font-semibold" title="Block IP">
                                            <i class="ri-shield-cross-line text-lg"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $paginator->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-shield-check-line text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No security events match your filters</h3>
                <p class="text-gray-500 mb-6">Try broadening the filters or searching with different keywords.</p>
                <a href="{{ route('admin.security.audit-log') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Event Details Modal -->
<div id="eventDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
            <button onclick="closeEventDetails()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div id="eventDetailsContent" class="space-y-4">
            <!-- Content will be loaded via JavaScript -->
        </div>
    </div>
</div>

<script>
function showEventDetails(event) {
    const modal = document.getElementById('eventDetailsModal');
    const content = document.getElementById('eventDetailsContent');
    
    const riskColors = {
        'high': 'bg-red-100 text-red-800',
        'medium': 'bg-yellow-100 text-yellow-800',
        'low': 'bg-green-100 text-green-800',
    };
    
    content.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-medium text-gray-500">Type</p>
                <p class="text-sm text-gray-900">${event.type === 'login_attempt' ? 'Login Attempt' : 'System Event'}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Event</p>
                <p class="text-sm text-gray-900">${event.event.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Timestamp</p>
                <p class="text-sm text-gray-900">${new Date(event.timestamp).toLocaleString()}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Risk Level</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${riskColors[event.risk_level] || 'bg-gray-100 text-gray-800'}">
                    ${event.risk_level.charAt(0).toUpperCase() + event.risk_level.slice(1)}
                </span>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">User</p>
                <p class="text-sm text-gray-900">${event.user ? (event.user.username + ' (' + event.user.email + ')') : (event.identifier || 'System')}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">IP Address</p>
                <p class="text-sm text-gray-900 font-mono">${event.ip_address || 'N/A'}</p>
            </div>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2">Description</p>
            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">${event.description}</p>
        </div>
        ${event.user_agent ? `
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2">User Agent</p>
            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg font-mono text-xs">${event.user_agent}</p>
        </div>
        ` : ''}
        ${event.data ? `
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2">Additional Data</p>
            <pre class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg overflow-auto">${JSON.stringify(event.data, null, 2)}</pre>
        </div>
        ` : ''}
        ${event.success !== undefined ? `
        <div>
            <p class="text-xs font-medium text-gray-500 mb-2">Status</p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${event.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${event.success ? 'Success' : 'Failed'}
            </span>
        </div>
        ` : ''}
    `;
    
    modal.classList.remove('hidden');
}

function closeEventDetails() {
    document.getElementById('eventDetailsModal').classList.add('hidden');
}

function blockIp(ip) {
    if (confirm(`Are you sure you want to block IP address ${ip}?`)) {
        // In production, this would make an AJAX call to block the IP
        alert('IP blocking functionality would be implemented here. This would typically call an API endpoint to block the IP address.');
    }
}

// Close modal on outside click
document.getElementById('eventDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEventDetails();
    }
});
</script>
@endsection






