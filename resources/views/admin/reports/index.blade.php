@extends('layouts.admin')

@section('meta_title', 'Reports & Analytics | Admin Dashboard')
@section('meta_description', 'Comprehensive reports and analytics for the I.M System platform.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Reports & Analytics</h1>
        <div class="flex space-x-4">
            <button onclick="clearCache()" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                <i class="ri-refresh-line mr-2"></i>Refresh Cache
            </button>
            <button onclick="exportData('users')" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="ri-download-line mr-2"></i>Export Data
            </button>
        </div>
    </div>

    <!-- Real-time Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="ri-user-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Online Users</p>
                    <p class="text-2xl font-bold text-gray-900" id="online-users">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="ri-file-text-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today's Submissions</p>
                    <p class="text-2xl font-bold text-gray-900" id="today-submissions">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="ri-time-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                    <p class="text-2xl font-bold text-gray-900" id="pending-approvals">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="ri-briefcase-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Jobs</p>
                    <p class="text-2xl font-bold text-gray-900" id="active-jobs">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Tabs -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Analytics Dashboard</h2>
            <div class="flex space-x-2">
                <select id="chart-period" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="6">Last 6 months</option>
                    <option value="12" selected>Last 12 months</option>
                    <option value="24">Last 24 months</option>
                </select>
            </div>
        </div>

        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showChart('user_registrations')" class="chart-tab py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                    User Registrations
                </button>
                <button onclick="showChart('submission_trends')" class="chart-tab py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Submission Trends
                </button>
                <button onclick="showChart('company_approvals')" class="chart-tab py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Company Approvals
                </button>
                <button onclick="showChart('job_postings')" class="chart-tab py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Job Postings
                </button>
                <button onclick="showChart('newsletter_subscriptions')" class="chart-tab py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Newsletter Subscriptions
                </button>
            </nav>
        </div>

        <div id="chart-container" class="h-96">
            <canvas id="analytics-chart"></canvas>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- User Statistics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">User Statistics</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Users</span>
                    <span class="font-semibold">{{ $statistics['users']['total'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Active Users</span>
                    <span class="font-semibold text-green-600">{{ $statistics['users']['active'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Recent Registrations (30 days)</span>
                    <span class="font-semibold">{{ $statistics['users']['recent_registrations'] }}</span>
                </div>
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">By Role</h4>
                    <div class="space-y-2">
                        @foreach($statistics['users']['by_role'] as $role => $count)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">{{ ucfirst($role) }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Statistics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Submission Statistics</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Submissions</span>
                    <span class="font-semibold">{{ $statistics['submissions']['total'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Auto Entrepreneur</span>
                    <span class="font-semibold">{{ $statistics['submissions']['auto_entrepreneur'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Porteur d'Idée</span>
                    <span class="font-semibold">{{ $statistics['submissions']['porteur_idee'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Porteur de Projet</span>
                    <span class="font-semibold">{{ $statistics['submissions']['porteur_projet'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Investment</span>
                    <span class="font-semibold">{{ $statistics['submissions']['investment'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">INDH</span>
                    <span class="font-semibold">{{ $statistics['submissions']['indh'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Training</span>
                    <span class="font-semibold">{{ $statistics['submissions']['training'] }}</span>
                </div>
            </div>
        </div>

        <!-- Company Statistics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Company Statistics</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Companies</span>
                    <span class="font-semibold">{{ $statistics['companies']['total'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Approved</span>
                    <span class="font-semibold text-green-600">{{ $statistics['companies']['approved'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pending</span>
                    <span class="font-semibold text-yellow-600">{{ $statistics['companies']['pending'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Rejected</span>
                    <span class="font-semibold text-red-600">{{ $statistics['companies']['rejected'] }}</span>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Metrics</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Average Response Time</span>
                    <span class="font-semibold">{{ $performanceMetrics['response_times']['average'] }}ms</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Error Rate</span>
                    <span class="font-semibold">{{ number_format($performanceMetrics['error_rates']['error_rate'] * 100, 2) }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Cache Hit Rate</span>
                    <span class="font-semibold">{{ number_format($performanceMetrics['system_health']['cache_hit_rate'] * 100, 1) }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Disk Usage</span>
                    <span class="font-semibold">{{ $performanceMetrics['system_health']['disk_usage'] }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentChart = null;
let chartData = {};

// Load real-time stats
function loadRealTimeStats() {
    fetch('/admin/reports/real-time-stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('online-users').textContent = data.data.online_users;
                document.getElementById('today-submissions').textContent = data.data.today_submissions;
                document.getElementById('pending-approvals').textContent = data.data.pending_approvals;
                document.getElementById('active-jobs').textContent = data.data.active_jobs;
            }
        })
        .catch(error => console.error('Error loading real-time stats:', error));
}

// Load chart data
function loadChartData(metric, period = 12) {
    if (chartData[metric + '_' + period]) {
        return Promise.resolve(chartData[metric + '_' + period]);
    }

    return fetch(`/admin/reports/chart-data?metric=${metric}&period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chartData[metric + '_' + period] = data.data;
                return data.data;
            }
            return [];
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            return [];
        });
}

// Show chart
function showChart(metric) {
    const period = document.getElementById('chart-period').value;
    
    // Update tab styles
    document.querySelectorAll('.chart-tab').forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-blue-500', 'text-blue-600');
    
    // Load and display chart
    loadChartData(metric, period).then(data => {
        renderChart(data, metric);
    });
}

// Render chart using simple CSS bars (no heavy libraries)
function renderChart(data, metric) {
    const container = document.getElementById('chart-container');
    container.innerHTML = '';
    
    if (data.length === 0) {
        container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No data available</div>';
        return;
    }
    
    const maxValue = Math.max(...data.map(item => {
        if (typeof item === 'object') {
            return Math.max(...Object.values(item).filter(val => typeof val === 'number'));
        }
        return item.count || item;
        }));
    
    const chartHtml = `
        <div class="h-96 overflow-x-auto">
            <div class="flex items-end justify-between h-full space-x-2 p-4">
                ${data.map(item => {
                    const value = typeof item === 'object' ? (item.count || Math.max(...Object.values(item).filter(val => typeof val === 'number'))) : item;
                    const height = (value / maxValue) * 100;
                    const label = item.month || item.label || '';
                    
                    return `
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-full bg-blue-500 rounded-t" style="height: ${height}%"></div>
                            <div class="text-xs text-gray-600 mt-2 text-center">${label}</div>
                            <div class="text-xs font-semibold text-gray-800 mt-1">${value}</div>
                        </div>
                    `;
                }).join('')}
            </div>
        </div>
    `;
    
    container.innerHTML = chartHtml;
}

// Clear cache
function clearCache() {
    fetch('/admin/reports/clear-cache', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache cleared successfully');
                location.reload();
            } else {
                alert('Error clearing cache');
            }
        })
        .catch(error => {
            console.error('Error clearing cache:', error);
            alert('Error clearing cache');
        });
}

// Export data
function exportData(type) {
    const url = `/admin/reports/export?type=${type}`;
    window.open(url, '_blank');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadRealTimeStats();
    showChart('user_registrations');
    
    // Refresh real-time stats every 30 seconds
    setInterval(loadRealTimeStats, 30000);
    
    // Handle period change
    document.getElementById('chart-period').addEventListener('change', function() {
        const activeTab = document.querySelector('.chart-tab.border-blue-500');
        if (activeTab) {
            const metric = activeTab.textContent.toLowerCase().replace(/\s+/g, '_');
            showChart(metric);
        }
    });
});
</script>
@endsection






























