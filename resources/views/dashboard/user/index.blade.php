@extends('layouts.dashboard')

@section('dashboard-name', 'Espace Utilisateur')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Tableau de Bord')
@section('profile-route', route('user.profile'))
@section('settings-route', route('user.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('user.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('user.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Mes Soumissions
        @if(isset($stats['pending_submissions']) && $stats['pending_submissions'] > 0)
        <span class="ml-auto bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['pending_submissions'] }}</span>
        @endif
    </a>
    
    <a href="{{ route('user.files') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-line text-xl mr-3"></i>
        Mes Fichiers
    </a>
    
    @if(Route::has('user.notifications'))
    <a href="{{ route('user.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
        @if(isset($stats['unread_notifications']) && $stats['unread_notifications'] > 0)
        <span class="ml-auto bg-red-100 text-red-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['unread_notifications'] }}</span>
        @endif
    </a>
    @endif
    
    <a href="{{ route('user.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-settings-line text-xl mr-3"></i>
        Mon Profil
    </a>
    
    <a href="{{ route('user.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
        <p class="mt-2 text-gray-600">Bienvenue sur votre tableau de bord</p>
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
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_submissions'] ?? 0 }}</dd>
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
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_submissions'] ?? 0 }}</dd>
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
                            <i class="ri-checkbox-circle-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Approuvées</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['approved_submissions'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Files -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-folder-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Fichiers</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_files'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Submissions -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Soumissions Récentes</h3>
                <a href="{{ route('user.submissions') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Voir tout <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            
            @if(collect($submissions)->flatten()->count() > 0)
            <div class="space-y-4">
                @foreach(collect($submissions)->flatten()->take(5) as $submission)
                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                @if(isset($submission->submission_type_label))
                                    {{ $submission->submission_type_label }}
                                @else
                                    Soumission #{{ $submission->id }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                @if(isset($submission->submission_number))
                                    N°: {{ $submission->submission_number }}
                                @endif
                                • {{ $submission->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="ml-4">
                            @if($submission->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    En attente
                                </span>
                            @elseif($submission->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approuvée
                                </span>
                            @elseif($submission->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rejetée
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="ri-file-list-3-line text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Aucune soumission pour le moment</p>
                <a href="{{ route('forms.investment') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Créer une soumission
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('forms.investment') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-money-dollar-circle-line text-2xl text-blue-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Demande d'Investissement</span>
                </a>
                <a href="{{ route('forms.project-carrier') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-projector-line text-2xl text-green-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Porteur de Projet</span>
                </a>
                <a href="{{ route('forms.idea-carrier') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-lightbulb-line text-2xl text-yellow-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Porteur d'Idée</span>
                </a>
                <a href="{{ route('forms.auto-entrepreneur') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-user-add-line text-2xl text-purple-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Auto-Entrepreneur</span>
                </a>
                <a href="{{ route('forms.indh') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-community-line text-2xl text-indigo-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Projet INDH</span>
                </a>
                <a href="{{ route('forms.training') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ri-graduation-cap-line text-2xl text-red-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Demande de Formation</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
