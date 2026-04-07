@extends('layouts.dashboard')

@section('dashboard-name', 'Espace Entreprise')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Configuration de l\'Entreprise')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('company.setup') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Configuration
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
        <h1 class="text-3xl font-bold text-gray-900">Configuration de l'Entreprise</h1>
        <p class="mt-2 text-gray-600">Complétez les informations de votre entreprise pour accéder à toutes les fonctionnalités</p>
    </div>

    <!-- Setup Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('company.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <div class="md:col-span-2">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'Entreprise *</label>
                    <input type="text" name="company_name" id="company_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Company Type -->
                <div>
                    <label for="company_type" class="block text-sm font-medium text-gray-700 mb-2">Type d'Entreprise *</label>
                    <select name="company_type" id="company_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="SARL">SARL</option>
                        <option value="SA">SA</option>
                        <option value="SAS">SAS</option>
                        <option value="SNC">SNC</option>
                        <option value="SPA">SPA</option>
                        <option value="Cooperative">Coopérative</option>
                        <option value="Association">Association</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                
                <!-- Registration Number -->
                <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro d'Enregistrement *</label>
                    <input type="text" name="registration_number" id="registration_number" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Tax Number -->
                <div>
                    <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro Fiscal</label>
                    <input type="text" name="tax_number" id="tax_number"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Décrivez votre entreprise, sa mission et ses principales activités..."></textarea>
                </div>
                
                <!-- Website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Site Web</label>
                    <input type="url" name="website" id="website"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://www.example.com">
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                    <input type="tel" name="phone" id="phone" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                    <input type="text" name="address" id="address" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                    <input type="text" name="city" id="city" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Region -->
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région *</label>
                    <select name="region" id="region" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une région</option>
                        <option value="Rabat-Salé-Kénitra">Rabat-Salé-Kénitra</option>
                        <option value="Casablanca-Settat">Casablanca-Settat</option>
                        <option value="Fès-Meknès">Fès-Meknès</option>
                        <option value="Tanger-Tétouan-Al Hoceïma">Tanger-Tétouan-Al Hoceïma</option>
                        <option value="Marrakech-Safi">Marrakech-Safi</option>
                        <option value="Oriental">Oriental</option>
                        <option value="Béni Mellal-Khénifra">Béni Mellal-Khénifra</option>
                        <option value="Souss-Massa">Souss-Massa</option>
                        <option value="Guelmim-Oued Noun">Guelmim-Oued Noun</option>
                        <option value="Laâyoune-Sakia El Hamra">Laâyoune-Sakia El Hamra</option>
                        <option value="Dakhla-Oued Ed-Dahab">Dakhla-Oued Ed-Dahab</option>
                    </select>
                </div>
                
                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code Postal</label>
                    <input type="text" name="postal_code" id="postal_code"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Business Sectors -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secteurs d'Activité *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Technology" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Technologie</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Manufacturing" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Manufacturing</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Retail" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Commerce</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Services" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Services</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Agriculture" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Agriculture</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Construction" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Construction</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Healthcare" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Santé</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="business_sectors[]" value="Education" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Éducation</span>
                        </label>
                    </div>
                </div>
                
                <!-- Employee Count -->
                <div>
                    <label for="employee_count" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'Employés</label>
                    <input type="number" name="employee_count" id="employee_count" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Annual Revenue -->
                <div>
                    <label for="annual_revenue" class="block text-sm font-medium text-gray-700 mb-2">Chiffre d'Affaires Annuel (MAD)</label>
                    <input type="number" name="annual_revenue" id="annual_revenue" min="0" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Founded Year -->
                <div>
                    <label for="founded_year" class="block text-sm font-medium text-gray-700 mb-2">Année de Fondation</label>
                    <input type="number" name="founded_year" id="founded_year" min="1900" max="{{ date('Y') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="ri-check-line mr-2"></i>
                    Enregistrer les Informations
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
