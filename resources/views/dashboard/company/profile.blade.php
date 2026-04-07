@extends('layouts.dashboard')

@section('dashboard-name', 'Espace Entreprise')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Profil de l\'Entreprise')
@section('profile-route', route('company.profile'))
@section('settings-route', route('company.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('company.setup') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Configuration
    </a>
    
    <a href="{{ route('company.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-user-line text-xl mr-3"></i>
        Profil
    </a>
    
    <a href="{{ route('company.products') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-bag-line text-xl mr-3"></i>
        Produits
    </a>
    
    <a href="{{ route('company.orders') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-cart-line text-xl mr-3"></i>
        Commandes
    </a>
    
    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Offres d'Emploi
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
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Profil de l'Entreprise</h1>
        <p class="mt-2 text-gray-600">Gérez les informations de votre entreprise</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Company Profile Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('company.profile.update') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <div class="md:col-span-2">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'Entreprise *</label>
                    <input type="text" name="company_name" id="company_name" required
                           value="{{ old('company_name', $company->company_name ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Company Type -->
                <div>
                    <label for="company_type" class="block text-sm font-medium text-gray-700 mb-2">Type d'Entreprise *</label>
                    <select name="company_type" id="company_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="SARL" {{ old('company_type', $company->company_type ?? '') == 'SARL' ? 'selected' : '' }}>SARL</option>
                        <option value="SA" {{ old('company_type', $company->company_type ?? '') == 'SA' ? 'selected' : '' }}>SA</option>
                        <option value="SAS" {{ old('company_type', $company->company_type ?? '') == 'SAS' ? 'selected' : '' }}>SAS</option>
                        <option value="SNC" {{ old('company_type', $company->company_type ?? '') == 'SNC' ? 'selected' : '' }}>SNC</option>
                        <option value="SPA" {{ old('company_type', $company->company_type ?? '') == 'SPA' ? 'selected' : '' }}>SPA</option>
                        <option value="Cooperative" {{ old('company_type', $company->company_type ?? '') == 'Cooperative' ? 'selected' : '' }}>Coopérative</option>
                        <option value="Association" {{ old('company_type', $company->company_type ?? '') == 'Association' ? 'selected' : '' }}>Association</option>
                        <option value="Autre" {{ old('company_type', $company->company_type ?? '') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                
                <!-- Registration Number -->
                <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro d'Enregistrement *</label>
                    <input type="text" name="registration_number" id="registration_number" required
                           value="{{ old('registration_number', $company->registration_number ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Tax Number -->
                <div>
                    <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro Fiscal</label>
                    <input type="text" name="tax_number" id="tax_number"
                           value="{{ old('tax_number', $company->tax_number ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Décrivez votre entreprise, sa mission et ses principales activités...">{{ old('description', $company->description ?? '') }}</textarea>
                </div>
                
                <!-- Website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Site Web</label>
                    <input type="url" name="website" id="website"
                           value="{{ old('website', $company->website ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://www.example.com">
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                    <input type="tel" name="phone" id="phone" required
                           value="{{ old('phone', $company->phone ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email', $company->email ?? $user->email ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                    <input type="text" name="address" id="address" required
                           value="{{ old('address', $company->address ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                    <input type="text" name="city" id="city" required
                           value="{{ old('city', $company->city ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Region -->
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région *</label>
                    <select name="region" id="region" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une région</option>
                        <option value="Rabat-Salé-Kénitra" {{ old('region', $company->region ?? '') == 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>Rabat-Salé-Kénitra</option>
                        <option value="Casablanca-Settat" {{ old('region', $company->region ?? '') == 'Casablanca-Settat' ? 'selected' : '' }}>Casablanca-Settat</option>
                        <option value="Fès-Meknès" {{ old('region', $company->region ?? '') == 'Fès-Meknès' ? 'selected' : '' }}>Fès-Meknès</option>
                        <option value="Tanger-Tétouan-Al Hoceïma" {{ old('region', $company->region ?? '') == 'Tanger-Tétouan-Al Hoceïma' ? 'selected' : '' }}>Tanger-Tétouan-Al Hoceïma</option>
                        <option value="Marrakech-Safi" {{ old('region', $company->region ?? '') == 'Marrakech-Safi' ? 'selected' : '' }}>Marrakech-Safi</option>
                        <option value="Oriental" {{ old('region', $company->region ?? '') == 'Oriental' ? 'selected' : '' }}>Oriental</option>
                        <option value="Béni Mellal-Khénifra" {{ old('region', $company->region ?? '') == 'Béni Mellal-Khénifra' ? 'selected' : '' }}>Béni Mellal-Khénifra</option>
                        <option value="Souss-Massa" {{ old('region', $company->region ?? '') == 'Souss-Massa' ? 'selected' : '' }}>Souss-Massa</option>
                        <option value="Guelmim-Oued Noun" {{ old('region', $company->region ?? '') == 'Guelmim-Oued Noun' ? 'selected' : '' }}>Guelmim-Oued Noun</option>
                        <option value="Laâyoune-Sakia El Hamra" {{ old('region', $company->region ?? '') == 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>Laâyoune-Sakia El Hamra</option>
                        <option value="Dakhla-Oued Ed-Dahab" {{ old('region', $company->region ?? '') == 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>Dakhla-Oued Ed-Dahab</option>
                    </select>
                </div>
                
                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code Postal</label>
                    <input type="text" name="postal_code" id="postal_code"
                           value="{{ old('postal_code', $company->postal_code ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Employee Count -->
                <div>
                    <label for="employee_count" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'Employés</label>
                    <input type="number" name="employee_count" id="employee_count" min="1"
                           value="{{ old('employee_count', $company->employee_count ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Annual Revenue -->
                <div>
                    <label for="annual_revenue" class="block text-sm font-medium text-gray-700 mb-2">Chiffre d'Affaires Annuel (MAD)</label>
                    <input type="number" name="annual_revenue" id="annual_revenue" min="0" step="0.01"
                           value="{{ old('annual_revenue', $company->annual_revenue ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Founded Year -->
                <div>
                    <label for="founded_year" class="block text-sm font-medium text-gray-700 mb-2">Année de Fondation</label>
                    <input type="number" name="founded_year" id="founded_year" min="1900" max="{{ date('Y') }}"
                           value="{{ old('founded_year', $company->founded_year ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="ri-save-line mr-2"></i>
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


