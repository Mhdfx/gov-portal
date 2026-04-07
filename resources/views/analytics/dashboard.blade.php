@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Analytics Dashboard</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Comprehensive insights and statistics</p>
        </div>

        <!-- Period Selector -->
        <div class="mb-6">
            <select id="period-selector" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="365">Last year</option>
            </select>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Submissions</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-submissions">0</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <i class="ri-file-list-3-line text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="pending-submissions">0</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <i class="ri-time-line text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="approved-submissions">0</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <i class="ri-checkbox-circle-line text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400" id="rejected-submissions">0</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <i class="ri-close-circle-line text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Trends Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Submission Trends</h2>
                <canvas id="trends-chart" height="300"></canvas>
            </div>

            <!-- Status Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Status Distribution</h2>
                <canvas id="status-chart" height="300"></canvas>
            </div>
        </div>

        <!-- Top Sectors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Top Sectors</h2>
            <div id="top-sectors" class="space-y-3">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <!-- Submission Types Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Submissions by Type</h2>
            <canvas id="types-chart" height="200"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let trendsChart, statusChart, typesChart;

    async function loadAnalytics(period = 30) {
        try {
            const response = await fetch(`/api/v1/analytics/dashboard?period=${period}`, {
                headers: {
                    'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                updateOverview(data.data.overview);
                updateTrendsChart(data.data.trends);
                updateStatusChart(data.data.status_distribution);
                updateTypesChart(data.data.submissions);
                updateTopSectors(data.data.top_sectors);
            }
        } catch (error) {
            console.error('Failed to load analytics:', error);
        }
    }

    function updateOverview(overview) {
        document.getElementById('total-submissions').textContent = overview.total_submissions || 0;
        document.getElementById('pending-submissions').textContent = overview.pending_submissions || 0;
        document.getElementById('approved-submissions').textContent = overview.approved_submissions || 0;
        document.getElementById('rejected-submissions').textContent = overview.rejected_submissions || 0;
    }

    function updateTrendsChart(trends) {
        const ctx = document.getElementById('trends-chart').getContext('2d');
        
        if (trendsChart) {
            trendsChart.destroy();
        }

        trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: trends.map(t => new Date(t.date).toLocaleDateString()),
                datasets: [{
                    label: 'Submissions',
                    data: trends.map(t => t.count),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    function updateStatusChart(statusData) {
        const ctx = document.getElementById('status-chart').getContext('2d');
        
        if (statusChart) {
            statusChart.destroy();
        }

        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        'rgb(59, 130, 246)', // blue - pending
                        'rgb(34, 197, 94)',  // green - approved
                        'rgb(239, 68, 68)',  // red - rejected
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    function updateTypesChart(submissions) {
        const ctx = document.getElementById('types-chart').getContext('2d');
        
        if (typesChart) {
            typesChart.destroy();
        }

        typesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(submissions).map(k => k.replace('_', ' ').toUpperCase()),
                datasets: [{
                    label: 'Submissions',
                    data: Object.values(submissions),
                    backgroundColor: 'rgb(59, 130, 246)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    function updateTopSectors(sectors) {
        const container = document.getElementById('top-sectors');
        container.innerHTML = '';

        sectors.forEach((sector, index) => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
            div.innerHTML = `
                <div class="flex items-center">
                    <span class="text-lg font-bold text-gray-400 mr-4">#${index + 1}</span>
                    <span class="text-gray-900 dark:text-white font-medium">${sector.sector}</span>
                </div>
                <span class="text-gray-600 dark:text-gray-400 font-bold">${sector.count}</span>
            `;
            container.appendChild(div);
        });
    }

    // Initialize
    document.getElementById('period-selector').addEventListener('change', (e) => {
        loadAnalytics(e.target.value);
    });

    loadAnalytics(30);
</script>
@endpush
@endsection














