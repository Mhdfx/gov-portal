@extends('layouts.dashboard')

@section('dashboard-name', 'Espace Entreprise')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Tableau de Bord Entreprise')
@section('profile-route', route('company.profile'))
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('company.products') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-product-hunt-line text-xl mr-3"></i>
        Mes Produits
    </a>
    
    <a href="{{ route('company.orders') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-cart-line text-xl mr-3"></i>
        Mes Commandes
        @if(isset($stats['pending_orders']) && $stats['pending_orders'] > 0)
        <span class="ml-auto bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs rounded-full">{{ $stats['pending_orders'] }}</span>
        @endif
    </a>
    
    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Offres d'Emploi
    </a>
    
    <a href="{{ route('company.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-settings-line text-xl mr-3"></i>
        Mon Profil
    </a>
    
    <a href="{{ route('company.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
        <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ $company->company_name ?? 'Entreprise' }}!</h1>
        <p class="mt-2 text-gray-600">Bienvenue sur votre tableau de bord entreprise</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Products -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="ri-product-hunt-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Produits</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="ri-shopping-cart-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Commandes en Attente</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Job Listings -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="ri-briefcase-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Offres d'Emploi Actives</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['active_jobs'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-shopping-bag-line text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Commandes</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</dd>
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
            <a href="{{ route('company.products') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-product-hunt-line text-blue-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Gérer les Produits</h3>
                        <p class="text-sm text-gray-500">Ajouter ou modifier</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('company.orders') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-shopping-cart-line text-green-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Voir les Commandes</h3>
                        <p class="text-sm text-gray-500">Gérer les commandes</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('company.jobs') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-briefcase-line text-yellow-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Offres d'Emploi</h3>
                        <p class="text-sm text-gray-500">Créer ou gérer</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('company.profile') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center">
                    <i class="ri-user-settings-line text-purple-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Mon Profil</h3>
                        <p class="text-sm text-gray-500">Modifier les infos</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Commandes Récentes</h3>
                <a href="{{ route('company.orders') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
            </div>
            <div class="p-6">
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders->take(5) as $order)
                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                            <div class="flex-shrink-0">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-600',
                                        'processing' => 'bg-blue-100 text-blue-600',
                                        'completed' => 'bg-green-100 text-green-600',
                                        'cancelled' => 'bg-red-100 text-red-600'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En Attente',
                                        'processing' => 'En Traitement',
                                        'completed' => 'Terminée',
                                        'cancelled' => 'Annulée'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    Commande #{{ $order->id }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Par {{ $order->user->username ?? 'Client' }} • {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ri-shopping-cart-line text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">Aucune commande récente</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Job Applications -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Candidatures Récentes</h3>
                <a href="{{ route('company.jobs') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
            </div>
            <div class="p-6">
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentApplications->take(5) as $application)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="ri-user-line text-gray-400 text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $application->jobListing->title ?? 'Poste' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Candidature reçue {{ $application->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @php
                                $appStatusColors = [
                                    'pending' => 'bg-yellow-500',
                                    'reviewed' => 'bg-blue-500',
                                    'accepted' => 'bg-green-500',
                                    'rejected' => 'bg-red-500'
                                ];
                            @endphp
                            <span class="flex-shrink-0 w-2 h-2 {{ $appStatusColors[$application->status] ?? 'bg-gray-500' }} rounded-full"></span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ri-user-line text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">Aucune candidature récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
