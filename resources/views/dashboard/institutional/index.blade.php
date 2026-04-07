@extends('layouts.dashboard')

@section('dashboard-name', 'Admin Institutionnel')
@section('dashboard-icon', 'ri-government-line')
@section('page-title', 'Tableau de Bord Institutionnel')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('institutional.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('institutional.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Soumissions
        @if($stats['pending_submissions'] > 0)
        <span class="ml-auto bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['pending_submissions'] }}</span>
        @endif
    </a>
    
    <a href="{{ route('institutional.users') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Utilisateurs
    </a>
    
    <a href="{{ route('institutional.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Entreprises
    </a>
    
    <a href="{{ route('institutional.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Rapports
    </a>
    
    <a href="{{ route('institutional.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
        @if($notifications->where('is_read', false)->count() > 0)
        <span class="ml-auto bg-red-100 text-red-700 px-2 py-0.5 text-xs rounded-full">{{ $notifications->where('is_read', false)->count() }}</span>
        @endif
    </a>
    
    <a href="{{ route('institutional.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Paramètres
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
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ $user->username }}!</h1>
        <p class="mt-2 text-gray-600">Bienvenue sur votre tableau de bord institutionnel</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Submissions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="ri-file-list-3-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Soumissions</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_submissions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Submissions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="ri-time-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En Attente</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_submissions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Submissions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="ri-check-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Approuvées</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['approved_submissions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Under Review Submissions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-eye-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En Révision</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['under_review_submissions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('institutional.submissions') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-file-list-3-line text-blue-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Gérer les Soumissions</h3>
                        <p class="text-sm text-gray-500">Examiner et traiter les demandes</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('institutional.reports') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-bar-chart-line text-green-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Rapports</h3>
                        <p class="text-sm text-gray-500">Consulter les analyses</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('institutional.notifications') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-notification-3-line text-yellow-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Notifications</h3>
                        <p class="text-sm text-gray-500">Voir les alertes</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('institutional.settings') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-settings-3-line text-gray-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Paramètres</h3>
                        <p class="text-sm text-gray-500">Configurer l'institution</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Notifications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Submissions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Soumissions Récentes</h3>
                <a href="{{ route('institutional.submissions') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
            </div>
            <div class="p-6">
                @if($recentSubmissions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentSubmissions->take(5) as $submission)
                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                            <div class="flex-shrink-0">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-600',
                                        'approved' => 'bg-green-100 text-green-600',
                                        'rejected' => 'bg-red-100 text-red-600',
                                        'under_review' => 'bg-blue-100 text-blue-600'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En Attente',
                                        'approved' => 'Approuvée',
                                        'rejected' => 'Rejetée',
                                        'under_review' => 'En Révision'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$submission->status] ?? ucfirst($submission->status) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ class_basename($submission) }} #{{ $submission->id }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Par {{ $submission->user->username ?? 'Utilisateur inconnu' }} • {{ $submission->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ri-file-list-3-line text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">Aucune soumission récente</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Notifications Récentes</h3>
                <a href="{{ route('institutional.notifications') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
            </div>
            <div class="p-6">
                @if($notifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($notifications->take(5) as $notification)
                        <div class="flex items-start space-x-3 {{ !$notification->is_read ? 'bg-blue-50 p-3 rounded-lg' : '' }}">
                            <div class="flex-shrink-0">
                                <i class="ri-notification-3-line text-gray-400 text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                <p class="text-sm text-gray-500">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->is_read)
                                <span class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ri-notification-3-line text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">Aucune notification récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection