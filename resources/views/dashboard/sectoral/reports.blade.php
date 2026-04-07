@extends('layouts.dashboard')

@section('dashboard-name', 'Admin Sectoriel')
@section('dashboard-icon', 'ri-folder-chart-line')
@section('page-title', 'Rapports Sectoriels')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('sectoral.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('sectoral.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Soumissions
    </a>
    
    <a href="{{ route('sectoral.analysis') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-line-chart-line text-xl mr-3"></i>
        Analyse Sectorielle
    </a>
    
    <a href="{{ route('sectoral.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Rapports
    </a>
    
    <a href="{{ route('sectoral.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Entreprises
    </a>
    
    <a href="{{ route('sectoral.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    
    <a href="{{ route('sectoral.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Rapports Sectoriels</h1>
        <p class="mt-2 text-gray-600">Consultez les rapports et analyses de votre secteur</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Monthly Report -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="ri-calendar-line text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Rapport Mensuel</h3>
                        <p class="text-sm text-gray-500">{{ now()->format('F Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Soumissions</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $reports['monthly_report']['total_submissions'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Approuvées</span>
                    <span class="text-lg font-semibold text-green-600">{{ $reports['monthly_report']['approved_submissions'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">En Attente</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $reports['monthly_report']['pending_submissions'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Quarterly Report -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="ri-calendar-2-line text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Rapport Trimestriel</h3>
                        <p class="text-sm text-gray-500">Q{{ ceil(now()->month / 3) }} {{ now()->year }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Soumissions</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $reports['quarterly_report']['total_submissions'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Approuvées</span>
                    <span class="text-lg font-semibold text-green-600">{{ $reports['quarterly_report']['approved_submissions'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">En Attente</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $reports['quarterly_report']['pending_submissions'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Annual Report -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="ri-calendar-check-line text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Rapport Annuel</h3>
                        <p class="text-sm text-gray-500">{{ now()->year }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Soumissions</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $reports['annual_report']['total_submissions'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Approuvées</span>
                    <span class="text-lg font-semibold text-green-600">{{ $reports['annual_report']['approved_submissions'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">En Attente</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $reports['annual_report']['pending_submissions'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Statistiques Détaillées</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $reports['annual_report']['total_submissions'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Total Annuel</div>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $reports['annual_report']['approved_submissions'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Approuvées</div>
            </div>
            
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $reports['annual_report']['pending_submissions'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">En Attente</div>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                @php
                    $total = $reports['annual_report']['total_submissions'] ?? 1;
                    $approved = $reports['annual_report']['approved_submissions'] ?? 0;
                    $approvalRate = $total > 0 ? round(($approved / $total) * 100, 1) : 0;
                @endphp
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $approvalRate }}%</div>
                <div class="text-sm text-gray-600">Taux d'Approbation</div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Exporter les Rapports</h2>
        <div class="flex flex-wrap gap-3">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ri-file-pdf-line mr-2"></i>
                Exporter en PDF
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="ri-file-excel-line mr-2"></i>
                Exporter en Excel
            </button>
            <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="ri-file-text-line mr-2"></i>
                Exporter en CSV
            </button>
        </div>
    </div>
</div>
@endsection


























