@extends('layouts.dashboard')

@section('dashboard-name', 'Admin Principal')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Tableau de Bord Principal')
@section('profile-route', route('admin.profile'))
@section('settings-route', route('admin.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('admin.users') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Utilisateurs
        <span class="ml-auto bg-gray-200 text-gray-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['total_users'] }}</span>
    </a>
    
    <a href="{{ route('admin.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Entreprises
        @if($stats['pending_companies'] > 0)
        <span class="ml-auto bg-orange-100 text-orange-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['pending_companies'] }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Soumissions
        @if($stats['pending_submissions'] > 0)
        <span class="ml-auto bg-red-100 text-red-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['pending_submissions'] }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.files.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-line text-xl mr-3"></i>
        Fichiers
    </a>
    
    <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-box-line text-xl mr-3"></i>
        Rapports
    </a>
    
    <a href="{{ route('admin.newsletter.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-mail-send-line text-xl mr-3"></i>
        Newsletter
    </a>
    
    <a href="{{ route('admin.notifications.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    
    <a href="{{ route('admin.security.audit-log') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shield-check-line text-xl mr-3"></i>
        Sécurité
    </a>
    
    <a href="{{ route('admin.logs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-text-line text-xl mr-3"></i>
        Journaux
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Retour au site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="ri-user-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Utilisateurs</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.users') }}" class="font-medium text-blue-600 hover:text-blue-500">Voir tout →</a>
                </div>
            </div>
        </div>

        <!-- Total Companies -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-building-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Entreprises</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_companies'] }}</div>
                                @if($stats['pending_companies'] > 0)
                                <span class="ml-2 text-sm font-medium text-orange-600">({{ $stats['pending_companies'] }} en attente)</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.companies') }}" class="font-medium text-purple-600 hover:text-purple-500">Voir tout →</a>
                </div>
            </div>
        </div>

        <!-- Total Submissions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="ri-file-list-3-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Soumissions</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_submissions'] }}</div>
                                @if($stats['pending_submissions'] > 0)
                                <span class="ml-2 text-sm font-medium text-red-600">({{ $stats['pending_submissions'] }} en attente)</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.submissions') }}" class="font-medium text-green-600 hover:text-green-500">Voir tout →</a>
                </div>
            </div>
        </div>

        <!-- Approved/Rejected -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <i class="ri-pie-chart-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Approuvées / Rejetées</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-green-600">{{ $stats['approved_submissions'] }}</div>
                                <span class="mx-2 text-gray-400">/</span>
                                <div class="text-2xl font-semibold text-red-600">{{ $stats['rejected_submissions'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.reports.index') }}" class="font-medium text-orange-600 hover:text-orange-500">Voir rapports →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Submissions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Soumissions Récentes</h3>
            </div>
            <div class="p-6">
                @if($recentSubmissions && $recentSubmissions->count() > 0)
                <div class="space-y-4">
                    @foreach($recentSubmissions->take(5) as $submission)
                    <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex-shrink-0">
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-600',
                                    'approved' => 'bg-green-100 text-green-600',
                                    'rejected' => 'bg-red-100 text-red-600',
                                    'under_review' => 'bg-blue-100 text-blue-600',
                                ];
                                $statusColor = $colors[$submission->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ class_basename(get_class($submission)) }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $submission->user->username ?? 'N/A' }} • {{ $submission->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-8">Aucune soumission récente</p>
                @endif
            </div>
        </div>

        <!-- Recent Companies -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Entreprises Récentes</h3>
            </div>
            <div class="p-6">
                @if($recentCompanies && $recentCompanies->count() > 0)
                <div class="space-y-4">
                    @foreach($recentCompanies->take(5) as $company)
                    <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex-shrink-0">
                            @php
                                $companyColors = [
                                    'pending' => 'bg-orange-100 text-orange-600',
                                    'approved' => 'bg-green-100 text-green-600',
                                    'rejected' => 'bg-red-100 text-red-600',
                                ];
                                $companyStatusColor = $companyColors[$company->approval_status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $companyStatusColor }}">
                                {{ ucfirst($company->approval_status) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $company->company_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $company->city }} • {{ $company->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            @if($company->approval_status === 'pending')
                            <form action="{{ route('admin.companies.approve', $company->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-700" title="Approuver">
                                    <i class="ri-check-line text-xl"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.companies.reject', $company->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-700" title="Rejeter">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-8">Aucune entreprise récente</p>
                @endif
            </div>
        </div>
    </div>

    <!-- System Logs -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Activités Système Récentes</h3>
        </div>
        <div class="p-6">
            @if($recentLogs && $recentLogs->count() > 0)
            <div class="space-y-3">
                @foreach($recentLogs->take(10) as $log)
                <div class="flex items-center text-sm text-gray-600 pb-2 border-b border-gray-100 last:border-0">
                    <i class="ri-file-text-line text-gray-400 mr-2"></i>
                    <span class="flex-1">{{ $log->description }}</span>
                    <span class="text-gray-400 text-xs">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 text-center py-8">Aucune activité récente</p>
            @endif
        </div>
    </div>
</div>
@endsection
