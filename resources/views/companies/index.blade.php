@extends('layouts.app')

@section('meta_title', 'Annuaire des Entreprises - I.M System')
@section('meta_description', 'Découvrez les entreprises enregistrées sur notre plateforme et explorez leurs opportunités.')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 text-white py-16 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                Annuaire des Entreprises
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto mb-8">
                Explorez l'écosystème des entreprises locales, suivez leurs actualités et trouvez votre prochain défi professionnel.
            </p>
            
            <!-- Search & Filter Bar -->
            <form action="{{ route('companies.index') }}" method="GET" class="max-w-4xl mx-auto bg-white p-2 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-2">
                <div class="flex-1 flex items-center px-4 gap-2">
                    <i class="ri-search-line text-gray-400 text-xl"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom de l'entreprise..." class="w-full py-3 outline-none text-gray-700 bg-transparent">
                </div>
                <div class="md:w-1/4 border-l border-gray-100 px-4 flex items-center gap-2">
                    <i class="ri-map-pin-line text-gray-400 text-xl"></i>
                    <select name="city" class="w-full py-3 outline-none text-gray-700 bg-transparent appearance-none">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                    Rechercher
                    <i class="ri-arrow-right-line"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Companies Grid -->
    <div class="max-w-7xl mx-auto px-4 mt-12">
        @if($companies->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($companies as $company)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-transparent hover:border-blue-100 flex flex-col h-full overflow-hidden">
                        <!-- Banner Placeholder or Cover -->
                        <div class="h-32 bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden">
                            @if($company->cover_banner)
                                <img src="{{ asset('storage/' . $company->cover_banner) }}" alt="{{ $company->company_name }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-all"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-1 flex flex-col relative pt-12">
                            <!-- Logo Circle Overlay -->
                            <div class="absolute -top-10 left-6 w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-lg overflow-hidden group-hover:scale-110 transition-transform duration-300">
                                @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->company_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
                                        {{ substr($company->company_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-1 truncate">
                                {{ $company->company_name }}
                            </h3>
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                                <i class="ri-map-pin-line text-blue-500"></i>
                                {{ $company->city }}
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-6 line-clamp-3 leading-relaxed">
                                {{ $company->description ?? 'Aucune description disponible.' }}
                            </p>

                            <!-- Footer Info -->
                            <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Opportunités</span>
                                    <span class="font-bold text-gray-900">{{ $company->job_listings_count }} offre(s)</span>
                                </div>
                                <a href="{{ route('companies.show', $company->slug) }}" class="p-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all transform hover:rotate-6">
                                    <i class="ri-arrow-right-up-line text-xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $companies->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-search-line text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune entreprise trouvée</h3>
                <p class="text-gray-500">Essayez d'ajuster vos critères de recherche.</p>
                <a href="{{ route('companies.index') }}" class="inline-block mt-4 text-blue-600 font-bold hover:underline">Voir toutes les entreprises</a>
            </div>
        @endif
    </div>
</div>
@endsection
