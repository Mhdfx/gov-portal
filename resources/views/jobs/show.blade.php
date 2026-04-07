@extends('layouts.app')

@section('meta_title', $jobListing->title . ' | I.M System')
@section('meta_description', Str::limit(strip_tags($jobListing->description), 155))

@section('content')
    @include('layouts.navigation')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">
                        <i class="ri-home-line mr-2"></i>
                        Accueil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                        <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-indigo-600">
                            Offres d'emploi
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                        <span class="text-gray-900 font-medium">{{ $jobListing->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Job Header -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                {{ $jobListing->title }}
                            </h1>
                            <div class="flex items-center text-xl text-gray-600 mb-4">
                                <i class="ri-building-line mr-2"></i>
                                <span class="font-semibold">{{ $jobListing->company->legal_name ?? 'Entreprise confidentielle' }}</span>
                            </div>
                        </div>
                        @if($jobListing->is_featured)
                        <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 rounded-full font-semibold">
                            <i class="ri-star-fill"></i> Vedette
                        </span>
                        @endif
                    </div>

                    <!-- Quick Info -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <i class="ri-map-pin-line text-2xl text-indigo-600 mb-2"></i>
                            <p class="text-sm text-gray-600">Localisation</p>
                            <p class="font-semibold text-gray-900">{{ $jobListing->city ?? $jobListing->location }}</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <i class="ri-time-line text-2xl text-green-600 mb-2"></i>
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $jobListing->employment_type ?? $jobListing->job_type)) }}</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <i class="ri-bar-chart-line text-2xl text-purple-600 mb-2"></i>
                            <p class="text-sm text-gray-600">Expérience</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($jobListing->experience_level) }}</p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <i class="ri-money-dollar-circle-line text-2xl text-yellow-600 mb-2"></i>
                            <p class="text-sm text-gray-600">Salaire</p>
                            <p class="font-semibold text-gray-900">
                                @if($jobListing->salary_min && $jobListing->salary_max)
                                    {{ number_format($jobListing->salary_min / 1000) }}K-{{ number_format($jobListing->salary_max / 1000) }}K
                                @else
                                    Négociable
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    @auth
                    <form action="{{ route('user.candidate.job.apply', $jobListing->id) }}" method="POST" class="mb-6">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold text-lg">
                            <i class="ri-send-plane-fill mr-2"></i>
                            Postuler maintenant
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold text-lg text-center">
                        <i class="ri-login-box-line mr-2"></i>
                        Connectez-vous pour postuler
                    </a>
                    @endauth
                </div>

                <!-- Job Description -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-text-line text-indigo-600 mr-3"></i>
                        Description du poste
                    </h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($jobListing->description)) !!}
                    </div>
                </div>

                <!-- Requirements -->
                @if($jobListing->requirements)
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-checkbox-multiple-line text-indigo-600 mr-3"></i>
                        Exigences
                    </h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($jobListing->requirements)) !!}
                    </div>
                </div>
                @endif

                <!-- Responsibilities -->
                @if($jobListing->responsibilities)
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-tasks-line text-indigo-600 mr-3"></i>
                        Responsabilités
                    </h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($jobListing->responsibilities)) !!}
                    </div>
                </div>
                @endif

                <!-- Skills -->
                @if($jobListing->required_skills || $jobListing->preferred_skills)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-lightbulb-line text-indigo-600 mr-3"></i>
                        Compétences
                    </h2>
                    
                    @if($jobListing->required_skills)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Compétences requises</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($jobListing->required_skills as $skill)
                            <span class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($jobListing->preferred_skills)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Compétences souhaitées</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($jobListing->preferred_skills as $skill)
                            <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Company Info -->
                @if($jobListing->company)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        À propos de l'entreprise
                    </h3>
                    <div class="mb-4">
                        <p class="font-semibold text-gray-900 text-lg">{{ $jobListing->company->legal_name }}</p>
                        @if($jobListing->company->website)
                        <a href="{{ $jobListing->company->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center mt-2">
                            <i class="ri-external-link-line mr-1"></i>
                            Visiter le site web
                        </a>
                        @endif
                    </div>

                    @if($jobListing->company->description)
                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                        {{ Str::limit($jobListing->company->description, 200) }}
                    </p>
                    @endif

                    <div class="space-y-3 text-sm">
                        @if($jobListing->company->business_sectors)
                        <div class="flex items-start">
                            <i class="ri-briefcase-line text-gray-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-gray-500">Secteur</p>
                                <p class="font-medium text-gray-900">{{ is_array($jobListing->company->business_sectors) ? implode(', ', $jobListing->company->business_sectors) : $jobListing->company->business_sectors }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start">
                            <i class="ri-map-pin-line text-gray-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-gray-500">Localisation</p>
                                <p class="font-medium text-gray-900">{{ $jobListing->company->city }}, {{ $jobListing->company->region }}</p>
                            </div>
                        </div>

                        @if($jobListing->company->employee_count)
                        <div class="flex items-start">
                            <i class="ri-team-line text-gray-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-gray-500">Taille</p>
                                <p class="font-medium text-gray-900">{{ $jobListing->company->employee_count }} employés</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Job Stats -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Statistiques de l'offre</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="flex items-center">
                                <i class="ri-eye-line mr-2"></i>
                                Vues
                            </span>
                            <span class="font-bold">{{ $jobListing->views_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="flex items-center">
                                <i class="ri-user-line mr-2"></i>
                                Candidatures
                            </span>
                            <span class="font-bold">{{ $jobListing->applications_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="flex items-center">
                                <i class="ri-calendar-line mr-2"></i>
                                Publié
                            </span>
                            <span class="font-bold">{{ $jobListing->created_at->diffForHumans() }}</span>
                        </div>
                        @if($jobListing->application_deadline)
                        <div class="flex justify-between items-center">
                            <span class="flex items-center">
                                <i class="ri-time-line mr-2"></i>
                                Date limite
                            </span>
                            <span class="font-bold">{{ $jobListing->application_deadline->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('layouts.footer')
@endsection



























