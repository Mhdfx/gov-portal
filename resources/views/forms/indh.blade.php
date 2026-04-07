@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-t-xl p-8 text-white">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="ri-community-line text-green-500 text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Formulaire INDH</h1>
                    <p class="text-green-100">Initiative Nationale pour le Développement Humain</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-xl shadow-2xl p-8">
            <form action="{{ route('forms.indh.submit') }}" method="POST" enctype="multipart/form-data" id="indh-form" onsubmit="return handleFormSubmit(event)">
                @csrf

                <!-- Section 1: Personal Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-user-line text-green-500 mr-3"></i>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre prénom">
                            @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de famille <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre nom de famille">
                            @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="votre@email.com">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="+212 XXX XXX XXX">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nationalité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Marocaine">
                            @error('nationality')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre adresse complète">
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre ville">
                            @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="region" value="{{ old('region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre région">
                            @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Code postal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="20000">
                            @error('postal_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Project Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-list-3-line text-green-500 mr-3"></i>
                        Informations sur le Projet
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Titre du projet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="project_title" value="{{ old('project_title') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Titre descriptif de votre projet">
                            @error('project_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description du projet <span class="text-red-500">*</span>
                            </label>
                            <textarea name="project_description" rows="5" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez en détail votre projet, ses objectifs et son impact attendu...">{{ old('project_description') }}</textarea>
                            @error('project_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type de projet <span class="text-red-500">*</span>
                            </label>
                            <select name="project_type" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un type</option>
                                <option value="social" {{ old('project_type') == 'social' ? 'selected' : '' }}>Social</option>
                                <option value="economic" {{ old('project_type') == 'economic' ? 'selected' : '' }}>Économique</option>
                                <option value="environmental" {{ old('project_type') == 'environmental' ? 'selected' : '' }}>Environnemental</option>
                                <option value="cultural" {{ old('project_type') == 'cultural' ? 'selected' : '' }}>Culturel</option>
                                <option value="educational" {{ old('project_type') == 'educational' ? 'selected' : '' }}>Éducatif</option>
                                <option value="health" {{ old('project_type') == 'health' ? 'selected' : '' }}>Santé</option>
                                <option value="infrastructure" {{ old('project_type') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                <option value="agriculture" {{ old('project_type') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                <option value="autre" {{ old('project_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('project_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Catégorie du projet <span class="text-red-500">*</span>
                            </label>
                            <select name="project_category" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="community_development" {{ old('project_category') == 'community_development' ? 'selected' : '' }}>Développement Communautaire</option>
                                <option value="youth_empowerment" {{ old('project_category') == 'youth_empowerment' ? 'selected' : '' }}>Autonomisation des Jeunes</option>
                                <option value="women_empowerment" {{ old('project_category') == 'women_empowerment' ? 'selected' : '' }}>Autonomisation des Femmes</option>
                                <option value="rural_development" {{ old('project_category') == 'rural_development' ? 'selected' : '' }}>Développement Rural</option>
                                <option value="urban_development" {{ old('project_category') == 'urban_development' ? 'selected' : '' }}>Développement Urbain</option>
                                <option value="autre" {{ old('project_category') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('project_category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Impact communautaire <span class="text-red-500">*</span>
                            </label>
                            <textarea name="community_impact" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez l'impact communautaire attendu de votre projet...">{{ old('community_impact') }}</textarea>
                            @error('community_impact')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre de bénéficiaires cibles <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="target_beneficiaries" value="{{ old('target_beneficiaries') }}" required min="1" max="10000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: 50">
                            @error('target_beneficiaries')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Groupes de bénéficiaires
                            </label>
                            <input type="text" name="beneficiary_groups" value="{{ old('beneficiary_groups') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Femmes, Jeunes, Personnes âgées">
                            @error('beneficiary_groups')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Objectifs du projet <span class="text-red-500">*</span>
                            </label>
                            <textarea name="project_goals" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Listez les objectifs principaux de votre projet...">{{ old('project_goals') }}</textarea>
                            @error('project_goals')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Résultats attendus <span class="text-red-500">*</span>
                            </label>
                            <textarea name="expected_outcomes" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez les résultats attendus de votre projet...">{{ old('expected_outcomes') }}</textarea>
                            @error('expected_outcomes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Financial Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-money-dollar-circle-line text-green-500 mr-3"></i>
                        Informations Financières
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Financement requis <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="funding_required" value="{{ old('funding_required') }}" required min="1000" max="999999999" step="0.01"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="0.00">
                            @error('funding_required')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Devise <span class="text-red-500">*</span>
                            </label>
                            <select name="funding_currency" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une devise</option>
                                <option value="MAD" {{ old('funding_currency') == 'MAD' ? 'selected' : '' }}>MAD (Dirham Marocain)</option>
                                <option value="EUR" {{ old('funding_currency') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="USD" {{ old('funding_currency') == 'USD' ? 'selected' : '' }}>USD (Dollar US)</option>
                            </select>
                            @error('funding_currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Répartition du financement
                            </label>
                            <textarea name="funding_breakdown" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez comment le financement sera utilisé...">{{ old('funding_breakdown') }}</textarea>
                            @error('funding_breakdown')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Sources de cofinancement
                            </label>
                            <input type="text" name="co_funding_sources" value="{{ old('co_funding_sources') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Autofinancement, Partenaires">
                            @error('co_funding_sources')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Plan de durabilité
                            </label>
                            <textarea name="sustainability_plan" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez comment le projet sera maintenu après le financement...">{{ old('sustainability_plan') }}</textarea>
                            @error('sustainability_plan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Timeline & Implementation -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-calendar-line text-green-500 mr-3"></i>
                        Calendrier et Mise en Œuvre
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Durée du projet (mois) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="project_duration_months" value="{{ old('project_duration_months') }}" required min="1" max="120"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: 12">
                            @error('project_duration_months')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de début prévue
                            </label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phases d'implémentation
                            </label>
                            <textarea name="implementation_phases" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez les phases principales du projet...">{{ old('implementation_phases') }}</textarea>
                            @error('implementation_phases')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jalons clés
                            </label>
                            <textarea name="key_milestones" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Listez les jalons importants du projet...">{{ old('key_milestones') }}</textarea>
                            @error('key_milestones')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 5: Location & Scope -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-map-pin-line text-green-500 mr-3"></i>
                        Localisation et Portée
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location_region" value="{{ old('location_region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Région du projet">
                            @error('location_region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location_city" value="{{ old('location_city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ville du projet">
                            @error('location_city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Portée du projet <span class="text-red-500">*</span>
                            </label>
                            <select name="project_scope" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une portée</option>
                                <option value="local" {{ old('project_scope') == 'local' ? 'selected' : '' }}>Local</option>
                                <option value="regional" {{ old('project_scope') == 'regional' ? 'selected' : '' }}>Régional</option>
                                <option value="national" {{ old('project_scope') == 'national' ? 'selected' : '' }}>National</option>
                            </select>
                            @error('project_scope')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Couverture géographique
                            </label>
                            <input type="text" name="geographic_coverage" value="{{ old('geographic_coverage') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Zones couvertes par le projet">
                            @error('geographic_coverage')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 6: Partnership & Collaboration -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-handshake-line text-green-500 mr-3"></i>
                        Partenariats et Collaboration
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Organisations partenaires
                            </label>
                            <textarea name="partner_organizations" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Listez les organisations partenaires...">{{ old('partner_organizations') }}</textarea>
                            @error('partner_organizations')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Soutien gouvernemental
                            </label>
                            <input type="text" name="government_support" value="{{ old('government_support') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez le soutien gouvernemental reçu ou attendu">
                            @error('government_support')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Implication communautaire <span class="text-red-500">*</span>
                            </label>
                            <textarea name="community_involvement" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez comment la communauté sera impliquée...">{{ old('community_involvement') }}</textarea>
                            @error('community_involvement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Engagement des parties prenantes
                            </label>
                            <textarea name="stakeholder_engagement" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez l'engagement des parties prenantes...">{{ old('stakeholder_engagement') }}</textarea>
                            @error('stakeholder_engagement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 7: Documents -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-upload-line text-green-500 mr-3"></i>
                        Documents
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                CV (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="cv" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('cv')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Proposition de projet (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="project_proposal" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('project_proposal')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Budget détaillé (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="budget_detailed" accept=".pdf,.doc,.docx,.xls,.xlsx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('budget_detailed')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lettres communautaires (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="community_letters" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('community_letters')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Accords de partenariat (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="partnership_agreements" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('partnership_agreements')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 8: Additional Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-list-3-line text-green-500 mr-3"></i>
                        Informations Complémentaires
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Expérience précédente
                            </label>
                            <textarea name="previous_experience" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez votre expérience dans des projets similaires...">{{ old('previous_experience') }}</textarea>
                            @error('previous_experience')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Défis rencontrés
                            </label>
                            <textarea name="challenges" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez les défis que vous anticipez...">{{ old('challenges') }}</textarea>
                            @error('challenges')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Métriques de succès
                            </label>
                            <input type="text" name="success_metrics" value="{{ old('success_metrics') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Comment mesurerez-vous le succès du projet ?">
                            @error('success_metrics')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Évaluation des risques
                            </label>
                            <textarea name="risk_assessment" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Identifiez les risques potentiels et comment les gérer...">{{ old('risk_assessment') }}</textarea>
                            @error('risk_assessment')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivation <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motivation" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Expliquez votre motivation pour ce projet...">{{ old('motivation') }}</textarea>
                            @error('motivation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 9: Terms and Conditions -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-check-line text-green-500 mr-3"></i>
                        Conditions et Consentements
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="checkbox" name="accept_terms" id="accept_terms" value="1" {{ old('accept_terms') ? 'checked' : '' }} required 
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                            <label for="accept_terms" class="ml-2 block text-sm text-gray-900">
                                J'accepte les <a href="#" class="text-green-600 hover:text-green-800 font-medium">conditions d'utilisation</a> <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('accept_terms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <div class="flex items-start">
                            <input type="checkbox" name="accept_data_processing" id="accept_data_processing" value="1" {{ old('accept_data_processing') ? 'checked' : '' }} required 
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                            <label for="accept_data_processing" class="ml-2 block text-sm text-gray-900">
                                J'accepte le <a href="#" class="text-green-600 hover:text-green-800 font-medium">traitement de mes données personnelles</a> <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('accept_data_processing')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-green-500 text-green-500 rounded-lg hover:bg-green-50 transition-all">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Retour
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-lg font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all transform hover:scale-105 shadow-lg">
                        <i class="ri-send-plane-line mr-2"></i>
                        Soumettre le Projet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function handleFormSubmit(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="ri-loader-4-line ri-spin mr-2"></i> Envoi en cours...';

        // Clear previous errors
        document.querySelectorAll('.text-red-600').forEach(el => el.remove());
        document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
        const errorContainer = document.getElementById('form-error-messages');
        if (errorContainer) errorContainer.remove();
        const successContainer = document.getElementById('form-success-message');
        if (successContainer) successContainer.remove();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.id = 'form-success-message';
                successDiv.className = 'mb-6 bg-green-50 border border-green-200 rounded-lg p-4';
                successDiv.innerHTML = `
                    <div class="flex">
                        <i class="ri-checkbox-circle-line text-green-400 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-800">${data.message}</p>
                            ${data.submission_number ? `<p class="text-sm text-green-700 mt-1">Numéro de soumission: <strong>${data.submission_number}</strong></p>` : ''}
                        </div>
                    </div>
                `;
                form.insertBefore(successDiv, form.firstChild);
                form.reset(); // Clear the form

                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("user.submissions") }}';
                }, 2000);
            } else {
                const error = await response.json();
                throw error;
            }
        } catch (error) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;

            let errorHtml = '<div id="form-error-messages" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4"><div class="flex"><i class="ri-error-warning-line text-red-400 text-xl mr-3"></i><div class="flex-1"><p class="text-sm font-medium text-red-800 mb-2">Erreurs de validation:</p><ul class="list-disc list-inside text-sm text-red-700">';

            if (error.errors) {
                Object.keys(error.errors).forEach(field => {
                    error.errors[field].forEach(msg => {
                        errorHtml += `<li>${msg}</li>`;
                    });
                    // Highlight field
                    const fieldInput = form.querySelector(`[name="${field}"]`);
                    if (fieldInput) {
                        fieldInput.classList.add('border-red-500');
                    }
                });
            } else if (error.message) {
                errorHtml += `<li>${error.message}</li>`;
            } else {
                errorHtml += '<li>Une erreur est survenue lors de la soumission du formulaire.</li>';
            }
            
            errorHtml += '</ul></div></div></div>';
            form.insertBefore(document.createRange().createContextualFragment(errorHtml), form.firstChild);
        }
        return false;
    }
</script>
@endpush
@endsection
