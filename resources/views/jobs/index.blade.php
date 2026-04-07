@extends('layouts.app')

@section('meta_title', 'Offres d\'Emploi | I.M System')
@section('meta_description', 'Découvrez les dernières offres d\'emploi et opportunités de carrière sur I.M System.')

@section('content')
    @include('layouts.navigation')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                <i class="ri-briefcase-line text-indigo-600"></i>
                Offres d'Emploi
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Trouvez votre prochain défi professionnel parmi nos opportunités de carrière
            </p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                        <input type="text" name="search" placeholder="Titre du poste, mots-clés..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <select name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Toutes les régions</option>
                        <option value="casablanca">Casablanca</option>
                        <option value="rabat">Rabat</option>
                        <option value="marrakech">Marrakech</option>
                        <option value="tanger">Tanger</option>
                        <option value="fes">Fès</option>
                        <option value="agadir">Agadir</option>
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Type de contrat</option>
                        <option value="full_time">Temps plein</option>
                        <option value="part_time">Temps partiel</option>
                        <option value="contract">Contrat</option>
                        <option value="internship">Stage</option>
                        <option value="remote">Télétravail</option>
                    </select>
                </div>

                <!-- Additional Filters -->
                <div class="md:col-span-4 flex flex-wrap gap-4">
                    <select name="sector" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Secteur d'activité</option>
                        <option value="tech">Technologie</option>
                        <option value="finance">Finance</option>
                        <option value="marketing">Marketing</option>
                        <option value="healthcare">Santé</option>
                        <option value="education">Éducation</option>
                    </select>

                    <select name="experience" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Niveau d'expérience</option>
                        <option value="entry">Débutant</option>
                        <option value="mid">Intermédiaire</option>
                        <option value="senior">Senior</option>
                        <option value="executive">Exécutif</option>
                    </select>

                    <button type="submit" class="flex-1 md:flex-none bg-indigo-600 text-white px-8 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="ri-search-line mr-2"></i>
                        Rechercher
                    </button>

                    <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">
                <span class="font-semibold text-gray-900">{{ $jobListings->total() }}</span> offres d'emploi trouvées
            </p>
            <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="recent">Plus récentes</option>
                <option value="salary_high">Salaire (Haut-Bas)</option>
                <option value="salary_low">Salaire (Bas-Haut)</option>
                <option value="company">Entreprise (A-Z)</option>
            </select>
        </div>

        <!-- Job Listings Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @forelse($jobListings as $job)
            <a href="{{ route('jobs.show', $job->id) }}" class="group">
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-indigo-300">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-2">
                                {{ $job->title }}
                            </h3>
                            <p class="text-gray-600 font-medium">
                                <i class="ri-building-line mr-1"></i>
                                {{ $job->company->legal_name ?? 'Entreprise confidentielle' }}
                            </p>
                        </div>
                        @if($job->is_featured)
                        <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                            <i class="ri-star-fill"></i> Vedette
                        </span>
                        @endif
                    </div>

                    <!-- Job Details -->
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="inline-flex items-center text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            <i class="ri-map-pin-line mr-1"></i>
                            {{ $job->city ?? $job->location }}
                        </span>
                        <span class="inline-flex items-center text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            <i class="ri-time-line mr-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $job->employment_type ?? $job->job_type)) }}
                        </span>
                        <span class="inline-flex items-center text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            <i class="ri-bar-chart-line mr-1"></i>
                            {{ ucfirst($job->experience_level) }}
                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 line-clamp-2 mb-4">
                        {{ Str::limit(strip_tags($job->description), 120) }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="text-lg font-bold text-indigo-600">
                            @if($job->salary_min && $job->salary_max)
                                {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} DH
                            @elseif($job->salary_min)
                                À partir de {{ number_format($job->salary_min) }} DH
                            @else
                                Salaire à négocier
                            @endif
                        </div>
                        <span class="text-sm text-gray-500">
                            <i class="ri-calendar-line mr-1"></i>
                            {{ $job->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-2 bg-white rounded-xl shadow-md p-12 text-center">
                <i class="ri-briefcase-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune offre trouvée</h3>
                <p class="text-gray-600 mb-6">Essayez de modifier vos critères de recherche</p>
                <a href="{{ route('jobs.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    Voir toutes les offres
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($jobListings->hasPages())
        <div class="flex justify-center">
            {{ $jobListings->links() }}
        </div>
        @endif
    </div>
</div>
    @include('layouts.footer')
@endsection



























