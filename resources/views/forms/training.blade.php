@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-t-xl p-8 text-white">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="ri-graduation-cap-line text-purple-500 text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Demande de Formation</h1>
                    <p class="text-purple-100">Développez vos compétences professionnelles</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-xl shadow-2xl p-8">
            <form action="{{ route('forms.training.submit') }}" method="POST" enctype="multipart/form-data" id="training-form" onsubmit="return handleFormSubmit(event)">
                @csrf

                <!-- Section 1: Personal Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-user-line text-purple-500 mr-3"></i>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre prénom">
                            @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de famille <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre nom de famille">
                            @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="votre@email.com">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="+212 XXX XXX XXX">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nationalité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Marocaine">
                            @error('nationality')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre adresse complète">
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre ville">
                            @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="region" value="{{ old('region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre région">
                            @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Code postal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="20000">
                            @error('postal_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Training Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-book-open-line text-purple-500 mr-3"></i>
                        Informations sur la Formation
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Titre de la formation <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="training_title" value="{{ old('training_title') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Formation en Développement Web Fullstack">
                            @error('training_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description de la formation <span class="text-red-500">*</span>
                            </label>
                            <textarea name="training_description" rows="5" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez en détail la formation que vous souhaitez suivre...">{{ old('training_description') }}</textarea>
                            @error('training_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type de formation <span class="text-red-500">*</span>
                            </label>
                            <select name="training_type" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un type</option>
                                <option value="technical" {{ old('training_type') == 'technical' ? 'selected' : '' }}>Technique</option>
                                <option value="business" {{ old('training_type') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="soft_skills" {{ old('training_type') == 'soft_skills' ? 'selected' : '' }}>Compétences Douces</option>
                                <option value="certification" {{ old('training_type') == 'certification' ? 'selected' : '' }}>Certification</option>
                                <option value="workshop" {{ old('training_type') == 'workshop' ? 'selected' : '' }}>Atelier</option>
                                <option value="seminar" {{ old('training_type') == 'seminar' ? 'selected' : '' }}>Séminaire</option>
                                <option value="language" {{ old('training_type') == 'language' ? 'selected' : '' }}>Langue</option>
                                <option value="digital_skills" {{ old('training_type') == 'digital_skills' ? 'selected' : '' }}>Compétences Numériques</option>
                                <option value="entrepreneurship" {{ old('training_type') == 'entrepreneurship' ? 'selected' : '' }}>Entrepreneuriat</option>
                                <option value="autre" {{ old('training_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('training_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Catégorie de formation <span class="text-red-500">*</span>
                            </label>
                            <select name="training_category" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="professional_development" {{ old('training_category') == 'professional_development' ? 'selected' : '' }}>Développement Professionnel</option>
                                <option value="skill_enhancement" {{ old('training_category') == 'skill_enhancement' ? 'selected' : '' }}>Amélioration des Compétences</option>
                                <option value="career_change" {{ old('training_category') == 'career_change' ? 'selected' : '' }}>Changement de Carrière</option>
                                <option value="compliance" {{ old('training_category') == 'compliance' ? 'selected' : '' }}>Conformité</option>
                                <option value="personal_growth" {{ old('training_category') == 'personal_growth' ? 'selected' : '' }}>Développement Personnel</option>
                                <option value="autre" {{ old('training_category') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('training_category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Public cible <span class="text-red-500">*</span>
                            </label>
                            <textarea name="target_audience" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez le public cible de cette formation...">{{ old('target_audience') }}</textarea>
                            @error('target_audience')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre de participants <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="participant_count" value="{{ old('participant_count') }}" required min="1" max="500"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: 25">
                            @error('participant_count')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Durée en heures <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" required min="1" max="200"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: 120">
                            @error('duration_hours')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Format de formation <span class="text-red-500">*</span>
                            </label>
                            <select name="training_format" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un format</option>
                                <option value="in_person" {{ old('training_format') == 'in_person' ? 'selected' : '' }}>Présentiel</option>
                                <option value="online" {{ old('training_format') == 'online' ? 'selected' : '' }}>En ligne</option>
                                <option value="hybrid" {{ old('training_format') == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                            </select>
                            @error('training_format')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Langue préférée <span class="text-red-500">*</span>
                            </label>
                            <select name="language_preference" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une langue</option>
                                <option value="french" {{ old('language_preference') == 'french' ? 'selected' : '' }}>Français</option>
                                <option value="arabic" {{ old('language_preference') == 'arabic' ? 'selected' : '' }}>Arabe</option>
                                <option value="english" {{ old('language_preference') == 'english' ? 'selected' : '' }}>Anglais</option>
                            </select>
                            @error('language_preference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Location & Schedule -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-calendar-line text-purple-500 mr-3"></i>
                        Localisation et Horaire
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lieu préféré
                            </label>
                            <input type="text" name="preferred_location" value="{{ old('preferred_location') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Casablanca, Rabat">
                            @error('preferred_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Horaire préféré
                            </label>
                            <input type="text" name="preferred_schedule" value="{{ old('preferred_schedule') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Matin, Après-midi, Soir">
                            @error('preferred_schedule')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="flexible_schedule" id="flexible_schedule" value="1" {{ old('flexible_schedule') ? 'checked' : '' }} 
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <span class="ml-2 block text-sm text-gray-900">
                                    Horaire flexible
                                </span>
                            </label>
                            @error('flexible_schedule')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de début préférée
                            </label>
                            <input type="date" name="start_date_preference" value="{{ old('start_date_preference') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            @error('start_date_preference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de fin préférée
                            </label>
                            <input type="date" name="end_date_preference" value="{{ old('end_date_preference') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            @error('end_date_preference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Financial Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-money-dollar-circle-line text-purple-500 mr-3"></i>
                        Informations Financières
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Budget disponible
                            </label>
                            <input type="number" name="budget_available" value="{{ old('budget_available') }}" step="0.01" min="0" max="999999999"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="0.00"
                                onchange="toggleBudgetCurrency()">
                            @error('budget_available')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div id="budget_currency_field" style="display: none;">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Devise
                            </label>
                            <select name="budget_currency" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une devise</option>
                                <option value="MAD" {{ old('budget_currency') == 'MAD' ? 'selected' : '' }}>MAD (Dirham Marocain)</option>
                                <option value="EUR" {{ old('budget_currency') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="USD" {{ old('budget_currency') == 'USD' ? 'selected' : '' }}>USD (Dollar US)</option>
                            </select>
                            @error('budget_currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Source de financement
                            </label>
                            <select name="funding_source" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une source</option>
                                <option value="personal" {{ old('funding_source') == 'personal' ? 'selected' : '' }}>Personnel</option>
                                <option value="employer" {{ old('funding_source') == 'employer' ? 'selected' : '' }}>Employeur</option>
                                <option value="government" {{ old('funding_source') == 'government' ? 'selected' : '' }}>Gouvernement</option>
                                <option value="scholarship" {{ old('funding_source') == 'scholarship' ? 'selected' : '' }}>Bourse</option>
                                <option value="autre" {{ old('funding_source') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('funding_source')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Plan de paiement
                            </label>
                            <input type="text" name="payment_plan" value="{{ old('payment_plan') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Mensuel, Trimestriel">
                            @error('payment_plan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 5: Requirements & Expectations -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-list-3-line text-purple-500 mr-3"></i>
                        Exigences et Attentes
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Exigences spécifiques
                            </label>
                            <textarea name="specific_requirements" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez vos exigences spécifiques...">{{ old('specific_requirements') }}</textarea>
                            @error('specific_requirements')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Objectifs d'apprentissage <span class="text-red-500">*</span>
                            </label>
                            <textarea name="learning_objectives" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Quels sont vos objectifs d'apprentissage ?">{{ old('learning_objectives') }}</textarea>
                            @error('learning_objectives')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Résultats attendus <span class="text-red-500">*</span>
                            </label>
                            <textarea name="expected_outcomes" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Quels résultats attendez-vous de cette formation ?">{{ old('expected_outcomes') }}</textarea>
                            @error('expected_outcomes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="certification_needed" id="certification_needed" value="1" {{ old('certification_needed') ? 'checked' : '' }} 
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                    onchange="toggleCertificationType()">
                                <span class="ml-2 block text-sm text-gray-900">
                                    Certification nécessaire
                                </span>
                            </label>
                            @error('certification_needed')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div id="certification_type_field" style="display: none;">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type de certification
                            </label>
                            <input type="text" name="certification_type" value="{{ old('certification_type') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Certificat professionnel, Diplôme">
                            @error('certification_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 6: Documents -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-upload-line text-purple-500 mr-3"></i>
                        Documents
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                CV (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
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
                                Lettre de motivation (optionnelle)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="motivation_letter" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('motivation_letter')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Certificats précédents (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="previous_certificates" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('previous_certificates')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Approbation employeur (optionnelle)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="employer_approval" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('employer_approval')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 7: Additional Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-information-line text-purple-500 mr-3"></i>
                        Informations Complémentaires
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Expérience précédente
                            </label>
                            <textarea name="previous_experience" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez votre expérience dans ce domaine...">{{ old('previous_experience') }}</textarea>
                            @error('previous_experience')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Compétences actuelles
                            </label>
                            <textarea name="current_skills" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Listez vos compétences actuelles...">{{ old('current_skills') }}</textarea>
                            @error('current_skills')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Défis rencontrés
                            </label>
                            <textarea name="challenges" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez les défis que vous rencontrez...">{{ old('challenges') }}</textarea>
                            @error('challenges')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Métriques de succès
                            </label>
                            <input type="text" name="success_metrics" value="{{ old('success_metrics') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Comment mesurerez-vous le succès ?">
                            @error('success_metrics')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivation <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motivation" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                placeholder="Expliquez votre motivation pour cette formation...">{{ old('motivation') }}</textarea>
                            @error('motivation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 8: Terms and Conditions -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-check-line text-purple-500 mr-3"></i>
                        Conditions et Consentements
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="checkbox" name="accept_terms" id="accept_terms" value="1" {{ old('accept_terms') ? 'checked' : '' }} required 
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-1">
                            <label for="accept_terms" class="ml-2 block text-sm text-gray-900">
                                J'accepte les <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">conditions d'utilisation</a> <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('accept_terms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <div class="flex items-start">
                            <input type="checkbox" name="accept_data_processing" id="accept_data_processing" value="1" {{ old('accept_data_processing') ? 'checked' : '' }} required 
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-1">
                            <label for="accept_data_processing" class="ml-2 block text-sm text-gray-900">
                                J'accepte le <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">traitement de mes données personnelles</a> <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('accept_data_processing')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-purple-500 text-purple-500 rounded-lg hover:bg-purple-50 transition-all">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Retour
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white text-lg font-semibold rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all transform hover:scale-105 shadow-lg">
                        <i class="ri-send-plane-line mr-2"></i>
                        Soumettre la Demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleBudgetCurrency() {
        const budgetInput = document.querySelector('[name="budget_available"]');
        const currencyField = document.getElementById('budget_currency_field');
        if (budgetInput.value && parseFloat(budgetInput.value) > 0) {
            currencyField.style.display = 'block';
        } else {
            currencyField.style.display = 'none';
            document.querySelector('[name="budget_currency"]').value = '';
        }
    }

    function toggleCertificationType() {
        const checkbox = document.getElementById('certification_needed');
        const field = document.getElementById('certification_type_field');
        if (checkbox.checked) {
            field.style.display = 'block';
        } else {
            field.style.display = 'none';
            document.querySelector('[name="certification_type"]').value = '';
        }
    }

    // Initialize fields on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleBudgetCurrency();
        toggleCertificationType();
    });

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














