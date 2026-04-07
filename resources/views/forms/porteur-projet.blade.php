@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-t-xl p-8 text-white">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="ri-rocket-line text-red-500 text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Formulaire Porteur de Projet</h1>
                    <p class="text-red-100">Soumettez votre projet entrepreneurial pour un accompagnement complet</p>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="bg-slate-50 p-4 border-b">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-slate-700">Progression du formulaire</span>
                <span class="text-sm text-slate-600">100%</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-red-500 to-pink-500 h-2 rounded-full transition-all duration-500" style="width: 100%"></div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-xl shadow-2xl p-8">
            <form action="{{ route('forms.project-carrier.submit') }}" method="POST" enctype="multipart/form-data" id="project-carrier-form" onsubmit="return handleFormSubmit(event)">
                @csrf

                <!-- Error Messages -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="ri-error-warning-line text-red-400 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 mb-2">Erreurs de validation:</p>
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section 1: Personal Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-user-line text-red-500 mr-3"></i>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('first_name') border-red-500 @enderror"
                                placeholder="Votre prénom">
                            @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de famille <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('last_name') border-red-500 @enderror"
                                placeholder="Votre nom de famille">
                            @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                placeholder="votre@email.com">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                                placeholder="+212 XXX XXX XXX">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nationalité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nationality" value="{{ old('nationality', 'Marocaine') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('nationality') border-red-500 @enderror"
                                placeholder="Marocaine">
                            @error('nationality')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('address') border-red-500 @enderror"
                                placeholder="Votre adresse complète">
                            @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('city') border-red-500 @enderror"
                                placeholder="Votre ville">
                            @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="region" value="{{ old('region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('region') border-red-500 @enderror"
                                placeholder="Votre région">
                            @error('region')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Code postal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('postal_code') border-red-500 @enderror"
                                placeholder="20000">
                            @error('postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Project Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-rocket-2-line text-red-500 mr-3"></i>
                        Informations sur le Projet
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom du projet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="project_name" value="{{ old('project_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('project_name') border-red-500 @enderror"
                                placeholder="Le nom de votre projet">
                            @error('project_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description du projet <span class="text-red-500">*</span>
                            </label>
                            <textarea name="project_description" rows="5" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('project_description') border-red-500 @enderror"
                                placeholder="Décrivez votre projet, sa valeur ajoutée, et son marché cible...">{{ old('project_description') }}</textarea>
                            @error('project_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Secteur d'activité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sector" value="{{ old('sector') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('sector') border-red-500 @enderror"
                                placeholder="Technologie & IT, Agriculture, etc.">
                            @error('sector')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stade de développement <span class="text-red-500">*</span>
                            </label>
                            <select name="development_stage" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('development_stage') border-red-500 @enderror">
                                <option value="">Sélectionnez un stade</option>
                                <option value="idea" {{ old('development_stage') == 'idea' ? 'selected' : '' }}>Idée</option>
                                <option value="prototype" {{ old('development_stage') == 'prototype' ? 'selected' : '' }}>Prototype</option>
                                <option value="mvp" {{ old('development_stage') == 'mvp' ? 'selected' : '' }}>MVP</option>
                                <option value="scaling" {{ old('development_stage') == 'scaling' ? 'selected' : '' }}>En expansion</option>
                                <option value="established" {{ old('development_stage') == 'established' ? 'selected' : '' }}>Établi</option>
                            </select>
                            @error('development_stage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type de projet <span class="text-red-500">*</span>
                            </label>
                            <select name="project_type" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('project_type') border-red-500 @enderror">
                                <option value="">Sélectionnez un type</option>
                                <option value="startup" {{ old('project_type') == 'startup' ? 'selected' : '' }}>Startup</option>
                                <option value="expansion" {{ old('project_type') == 'expansion' ? 'selected' : '' }}>Expansion</option>
                                <option value="innovation" {{ old('project_type') == 'innovation' ? 'selected' : '' }}>Innovation</option>
                                <option value="research" {{ old('project_type') == 'research' ? 'selected' : '' }}>Recherche</option>
                                <option value="development" {{ old('project_type') == 'development' ? 'selected' : '' }}>Développement</option>
                                <option value="autre" {{ old('project_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('project_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Marché cible <span class="text-red-500">*</span>
                            </label>
                            <textarea name="target_market" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('target_market') border-red-500 @enderror"
                                placeholder="Décrivez votre marché cible...">{{ old('target_market') }}</textarea>
                            @error('target_market')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Taille de l'équipe <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="team_size" value="{{ old('team_size', 1) }}" min="1" max="100" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('team_size') border-red-500 @enderror"
                                placeholder="1">
                            @error('team_size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Compétences de l'équipe
                            </label>
                            <input type="text" name="team_skills" value="{{ old('team_skills') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('team_skills') border-red-500 @enderror"
                                placeholder="Compétences clés de l'équipe">
                            @error('team_skills')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Financial Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-money-dollar-circle-line text-red-500 mr-3"></i>
                        Informations Financières
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Financement requis (MAD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="funding_required" value="{{ old('funding_required') }}" min="1000" max="999999999" step="0.01" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('funding_required') border-red-500 @enderror"
                                placeholder="250000">
                            @error('funding_required')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Devise <span class="text-red-500">*</span>
                            </label>
                            <select name="funding_currency" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('funding_currency') border-red-500 @enderror">
                                <option value="">Sélectionnez une devise</option>
                                <option value="MAD" {{ old('funding_currency') == 'MAD' ? 'selected' : '' }}>MAD</option>
                                <option value="EUR" {{ old('funding_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('funding_currency') == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                            @error('funding_currency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Objectif du financement <span class="text-red-500">*</span>
                            </label>
                            <textarea name="funding_purpose" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('funding_purpose') border-red-500 @enderror"
                                placeholder="Décrivez l'objectif du financement...">{{ old('funding_purpose') }}</textarea>
                            @error('funding_purpose')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Source de financement
                            </label>
                            <select name="funding_source" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('funding_source') border-red-500 @enderror">
                                <option value="">Sélectionnez une source</option>
                                <option value="personal_savings" {{ old('funding_source') == 'personal_savings' ? 'selected' : '' }}>Épargne personnelle</option>
                                <option value="family_loan" {{ old('funding_source') == 'family_loan' ? 'selected' : '' }}>Prêt familial</option>
                                <option value="bank_loan" {{ old('funding_source') == 'bank_loan' ? 'selected' : '' }}>Prêt bancaire</option>
                                <option value="investor" {{ old('funding_source') == 'investor' ? 'selected' : '' }}>Investisseur</option>
                                <option value="government_support" {{ old('funding_source') == 'government_support' ? 'selected' : '' }}>Soutien gouvernemental</option>
                                <option value="autre" {{ old('funding_source') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('funding_source')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Location -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-map-pin-line text-red-500 mr-3"></i>
                        Localisation du Projet
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location_region" value="{{ old('location_region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('location_region') border-red-500 @enderror"
                                placeholder="Casablanca-Settat">
                            @error('location_region')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location_city" value="{{ old('location_city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('location_city') border-red-500 @enderror"
                                placeholder="Casablanca">
                            @error('location_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 5: Additional Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-text-line text-red-500 mr-3"></i>
                        Informations Complémentaires
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivation <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motivation" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('motivation') border-red-500 @enderror"
                                placeholder="Quelle est votre motivation pour ce projet?">{{ old('motivation') }}</textarea>
                            @error('motivation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Défis rencontrés
                            </label>
                            <textarea name="challenges" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('challenges') border-red-500 @enderror"
                                placeholder="Quels sont les principaux défis que vous rencontrez?">{{ old('challenges') }}</textarea>
                            @error('challenges')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 6: Documents -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-upload-line text-red-500 mr-3"></i>
                        Documents (Optionnels)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">CV</label>
                            <input type="file" name="cv" accept=".pdf,.doc,.docx" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('cv') border-red-500 @enderror">
                            @error('cv')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Plan d'affaires</label>
                            <input type="file" name="business_plan" accept=".pdf,.doc,.docx" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('business_plan') border-red-500 @enderror">
                            @error('business_plan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Projections financières</label>
                            <input type="file" name="financial_projections" accept=".pdf,.doc,.docx,.xls,.xlsx" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('financial_projections') border-red-500 @enderror">
                            @error('financial_projections')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Analyse de marché</label>
                            <input type="file" name="market_analysis" accept=".pdf,.doc,.docx" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('market_analysis') border-red-500 @enderror">
                            @error('market_analysis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-8">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <input type="checkbox" name="accept_terms" value="1" id="accept_terms" required 
                                    class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded @error('accept_terms') border-red-500 @enderror">
                                <label for="accept_terms" class="ml-3 text-sm text-gray-700">
                                    J'accepte les <a href="#" class="text-red-600 hover:text-red-800 underline">conditions d'utilisation</a> <span class="text-red-500">*</span>
                                </label>
                            </div>
                            @error('accept_terms')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="flex items-start">
                                <input type="checkbox" name="accept_data_processing" value="1" id="accept_data_processing" required 
                                    class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded @error('accept_data_processing') border-red-500 @enderror">
                                <label for="accept_data_processing" class="ml-3 text-sm text-gray-700">
                                    J'accepte le <a href="#" class="text-red-600 hover:text-red-800 underline">traitement de mes données personnelles</a> <span class="text-red-500">*</span>
                                </label>
                            </div>
                            @error('accept_data_processing')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Success Stories -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-red-900 mb-4">Nos Réussites</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-3xl font-bold text-red-600">950+</div>
                            <div class="text-sm text-red-800">Projets Évalués</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-red-600">78%</div>
                            <div class="text-sm text-red-800">Taux d'Acceptation</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-red-600">320+</div>
                            <div class="text-sm text-red-800">Projets Financés</div>
                        </div>
                    </div>
                </div>

                <!-- Information Panel -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <i class="ri-information-line mr-2"></i>
                        Processus de Traitement
                    </h3>
                    <div class="space-y-3 text-blue-800">
                        <p class="flex items-start">
                            <i class="ri-time-line mr-2 mt-1"></i>
                            <span>Délai d'évaluation : 7-10 jours ouvrables</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-team-line mr-2 mt-1"></i>
                            <span>Évaluation par un comité d'experts sectoriels</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-presentation-line mr-2 mt-1"></i>
                            <span>Possibilité de présentation orale du projet</span>
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition-all">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Retour
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white text-lg font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
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
        const response = await axios.post(form.action, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        // Display success message
        const successMessage = `
            <div id="form-success-message" class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <i class="ri-check-line text-green-400 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800">${response.data.message}</p>
                        <p class="text-xs text-green-700 mt-1">Numéro de soumission: ${response.data.submission_number}</p>
                    </div>
                </div>
            </div>
        `;
        form.insertAdjacentHTML('beforebegin', successMessage);
        form.reset();

        setTimeout(() => {
            window.location.href = "{{ route('user.submissions') }}";
        }, 3000);

    } catch (error) {
        let errorMessage = 'Une erreur est survenue lors de la soumission du formulaire.';
        let errorsHtml = '';

        if (error.response && error.response.data && error.response.data.errors) {
            const errors = error.response.data.errors;
            errorsHtml = '<ul class="list-disc list-inside text-sm text-red-700">';
            for (const field in errors) {
                document.querySelector(`[name="${field}"]`)?.classList.add('border-red-500');
                document.querySelector(`[name="${field}"]`)?.closest('div').insertAdjacentHTML('beforeend', `<p class="mt-1 text-sm text-red-600">${errors[field][0]}</p>`);
                errors[field].forEach(msg => {
                    errorsHtml += `<li>${msg}</li>`;
                });
            }
            errorsHtml += '</ul>';
            errorMessage = 'Veuillez corriger les erreurs ci-dessous.';
        } else if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        }

        const errorDisplay = `
            <div id="form-error-messages" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <i class="ri-error-warning-line text-red-400 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 mb-2">${errorMessage}</p>
                        ${errorsHtml}
                    </div>
                </div>
            </div>
        `;
        form.insertAdjacentHTML('beforebegin', errorDisplay);
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    }
}
</script>
@endpush
@endsection
