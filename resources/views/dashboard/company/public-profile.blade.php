@extends('layouts.dashboard')

@section('dashboard-name', 'Gestion du Profil Public')
@section('dashboard-icon', 'ri-layout-masonry-line')
@section('page-title', 'Vitrine de l\'Entreprise')

@section('content')
<div class="space-y-8">
    
    <!-- Top Alert about Public Status -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="bg-blue-100 text-blue-600 p-2 rounded-xl">
                <i class="ri-eye-line text-2xl"></i>
            </div>
            <div>
                <h3 class="text-blue-900 font-bold text-lg">Votre vitrine est active !</h3>
                <p class="text-blue-700">Toutes les modifications apportées ici sont directement visibles par le public sur la plateforme. Utilisez cet espace pour attirer des clients et des talents.</p>
                <div class="mt-4">
                    <a href="{{ route('companies.show', $company->slug) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline">
                        <i class="ri-external-link-line"></i>
                        Voir mon profil public
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Profile Editor -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Modifier l'apparence du profil</h3>
                </div>
                <div class="p-8">
                    <form action="{{ route('company.public-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Banner Upload -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider">Bannière de couverture (Recommandé 1200x400px)</label>
                            <div class="relative h-48 w-full bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden hover:border-blue-400 transition-colors group">
                                @if($company->cover_banner)
                                    <img src="{{ asset('storage/' . $company->cover_banner) }}" id="banner-preview" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center p-4">
                                        <i class="ri-image-add-line text-4xl text-gray-300"></i>
                                        <p class="text-sm text-gray-400 mt-2">Cliquez pour télécharger une bannière</p>
                                    </div>
                                @endif
                                <input type="file" name="cover_banner" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this, 'banner-preview')">
                            </div>
                        </div>

                        <!-- Description / Bio -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider">Description de l'entreprise (Vitrine)</label>
                            <textarea name="description" rows="6" class="w-full bg-gray-50 border-gray-100 rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" placeholder="Présentez votre entreprise de manière attractive...">{{ old('description', $company->description) }}</textarea>
                            <p class="text-xs text-gray-400 text-right">Supporte le formatage simple</p>
                        </div>

                        <!-- Social & Web -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700">Site Web Officiel</label>
                                <div class="flex items-center bg-gray-50 border border-gray-100 rounded-xl px-4 focus-within:ring-2 ring-blue-500 transition-all">
                                    <i class="ri-global-line text-gray-400"></i>
                                    <input type="url" name="website" value="{{ old('website', $company->website) }}" placeholder="https://..." class="w-full bg-transparent border-none py-3 outline-none px-3 text-sm">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700 text-blue-600"><i class="ri-facebook-box-line"></i> Facebook</label>
                                <input type="url" name="social_links[facebook]" value="{{ old('social_links.facebook', $company->social_links['facebook'] ?? '') }}" placeholder="https://facebook.com/..." class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm">
                            </div>
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700 text-blue-400"><i class="ri-twitter-x-line"></i> Twitter / X</label>
                                <input type="url" name="social_links[twitter]" value="{{ old('social_links.twitter', $company->social_links['twitter'] ?? '') }}" placeholder="https://twitter.com/..." class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm">
                            </div>
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700 text-indigo-600"><i class="ri-linkedin-box-line"></i> LinkedIn</label>
                                <input type="url" name="social_links[linkedin]" value="{{ old('social_links.linkedin', $company->social_links['linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/..." class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50">
                            <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20 transform hover:-translate-y-1">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: New Social Update Feed -->
        <div class="space-y-8">
            <!-- Create Update Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-900 text-white flex items-center gap-2">
                    <i class="ri-chat-new-line"></i>
                    <h3 class="font-bold">Publier une actualité</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('company.updates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <textarea name="content" rows="4" class="w-full bg-gray-50 border-gray-100 rounded-2xl p-4 focus:ring-blue-500 text-sm" placeholder="Partagez une nouvelle, un événement ou un succès..."></textarea>
                        
                        <div class="relative overflow-hidden">
                            <button type="button" class="w-full bg-gray-50 text-gray-600 py-3 rounded-xl border border-gray-100 flex items-center justify-center gap-2 text-sm hover:bg-gray-100 transition-all">
                                <i class="ri-image-line"></i>
                                Ajouter une photo
                            </button>
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg">
                            Publier maintenant
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Updates List -->
            <div class="space-y-4">
                <h3 class="font-bold text-gray-900 px-2 flex items-center gap-2">
                    <i class="ri-history-line"></i>
                    Mes dernières publications
                </h3>
                @forelse($updates as $update)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 group relative">
                        <form action="{{ route('company.updates.delete', $update->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $update->content }}</p>
                        @if($update->image_path)
                            <div class="rounded-xl overflow-hidden mb-3 h-24">
                                <img src="{{ asset('storage/' . $update->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <span class="text-[10px] text-gray-400">{{ $update->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4 italic">Aucune publication récente.</p>
                @endforelse
                
                <div class="mt-4">
                    {{ $updates->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
