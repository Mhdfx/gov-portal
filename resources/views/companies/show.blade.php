@extends('layouts.app')

@section('meta_title', $company->company_name . ' - Profil Entreprise')
@section('meta_description', $company->description ?? 'Consultez les informations et les offres d\'emploi de ' . $company->company_name)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Cover Section with Premium Design -->
    <div class="relative h-64 md:h-96 w-full">
        @if($company->cover_banner)
            <img src="{{ asset('storage/' . $company->cover_banner) }}" alt="Banner" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 relative">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.3) 1px, transparent 0); background-size: 30px 30px;"></div>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-4 md:p-8">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-end gap-6">
                <!-- Company Logo -->
                <div class="relative -mb-16 md:-mb-24 w-32 h-32 md:w-48 md:h-48 rounded-3xl bg-white p-1 shadow-2xl border-4 border-white overflow-hidden">
                    @if($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="w-full h-full object-cover rounded-2xl">
                    @else
                        <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 text-5xl font-bold rounded-2xl">
                            {{ substr($company->company_name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <!-- Company Basic Info -->
                <div class="flex-1 text-white pb-4">
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 drop-shadow-lg">{{ $company->company_name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm md:text-base text-blue-50">
                        <span class="flex items-center gap-1"><i class="ri-map-pin-line"></i> {{ $company->city }}, {{ $company->country }}</span>
                        <span class="flex items-center gap-1"><i class="ri-building-line"></i> {{ $company->company_type }}</span>
                        <span class="bg-blue-500/30 backdrop-blur-md px-3 py-1 rounded-full border border-blue-400/30">
                            {{ $company->business_sectors[0] ?? '' }}
                        </span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pb-4 hidden md:block">
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition-all flex items-center gap-2 shadow-xl">
                            Visiter le Site Web
                            <i class="ri-external-link-line"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 mt-20 md:mt-32 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Feed & Bio -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Bio Section -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="ri-information-line text-blue-600"></i>
                        À propos de l'entreprise
                    </h2>
                    <div class="prose max-w-none text-gray-600 leading-relaxed text-lg">
                        {{ $company->description ?? 'Aucune description disponible pour le moment.' }}
                    </div>

                    @if($company->social_links)
                        <div class="mt-8 pt-8 border-t border-gray-50 flex flex-wrap gap-4">
                            @foreach($company->social_links as $plateform => $url)
                                @if($url)
                                    <a href="{{ $url }}" target="_blank" class="w-12 h-12 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1">
                                        <i class="ri-{{ $plateform }}-line text-2xl"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Feed Section: Social Posts -->
                <div class="space-y-6">
                    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
                        <i class="ri-rss-line text-blue-600"></i>
                        Actualités & Publications
                    </h2>

                    @forelse($company->updates as $update)
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transform transition-all hover:shadow-md">
                            <div class="p-4 flex items-center gap-3 border-b border-gray-50">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $company->logo) }}" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $company->company_name }}</h4>
                                    <p class="text-xs text-gray-400">{{ $update->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-wrap mb-4">{{ $update->content }}</p>
                                @if($update->image_path)
                                    <div class="rounded-2xl overflow-hidden mt-4 shadow-sm border border-gray-50">
                                        <img src="{{ asset('storage/' . $update->image_path) }}" alt="Post image" class="w-full h-auto">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm">
                            <i class="ri-ghost-line text-5xl text-gray-100 mb-4 inline-block"></i>
                            <p class="text-gray-400">Aucune actualité publiée pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Column: Sidebar (Jobs, Products, Stats) -->
            <div class="space-y-8">
                
                <!-- Job Offers Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-blue-600 text-white">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <i class="ri-briefcase-line"></i>
                            Offres d'emploi ({{ $company->jobListings->count() }})
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($company->jobListings as $job)
                            <a href="{{ route('jobs.show', $job->id) }}" class="block p-4 rounded-2xl bg-gray-50 hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all group">
                                <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">{{ $job->title }}</h4>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><i class="ri-map-pin-line"></i> {{ $job->city }}</span>
                                    <span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</span>
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-sm text-gray-400 py-4 italic">Aucune offre de recrutement active.</p>
                        @endforelse

                        @if($company->jobListings->count() > 0)
                            <a href="{{ route('jobs.index', ['search' => $company->company_name]) }}" class="block w-full py-3 text-center text-blue-600 font-bold hover:bg-blue-50 rounded-xl transition-all border border-blue-100 mt-4">
                                Voir toutes les offres
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Products Showcase -->
                @if($company->products->count() > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="ri-shopping-bag-line text-blue-600"></i>
                            Derniers Produits
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($company->products as $product)
                                <div class="group cursor-pointer">
                                    <div class="aspect-square rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden mb-2">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-200"><i class="ri-image-line text-2xl"></i></div>
                                        @endif
                                    </div>
                                    <h5 class="text-xs font-bold text-gray-900 truncate">{{ $product->name }}</h5>
                                    <p class="text-[10px] text-blue-600 font-bold">{{ number_format($product->price, 2) }} MAD</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Contact & Location Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ri-contacts-book-line text-blue-600"></i>
                        Contact
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0"><i class="ri-phone-line text-xl"></i></div>
                            <div>
                                <p class="text-xs text-gray-400">Téléphone</p>
                                <p class="text-sm font-bold text-gray-700">{{ $company->phone ?? 'Non renseigné' }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0"><i class="ri-mail-line text-xl"></i></div>
                            <div>
                                <p class="text-xs text-gray-400">Email</p>
                                <p class="text-sm font-bold text-gray-700">{{ $company->email ?? 'Non renseigné' }}</p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
