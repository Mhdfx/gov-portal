@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-t-xl p-8 text-white">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="ri-user-star-line text-orange-500 text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Formulaire Auto-Entrepreneur</h1>
                    <p class="text-orange-100">Inscription et gestion pour les auto-entrepreneurs</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-xl shadow-2xl p-8">
            <form action="{{ route('forms.auto-entrepreneur.submit') }}" method="POST" enctype="multipart/form-data" id="auto-entrepreneur-form" onsubmit="return handleFormSubmit(event)">
                @csrf

                <!-- Section 1: Personal Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-user-line text-orange-500 mr-3"></i>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre prénom">
                            @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de famille <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre nom de famille">
                            @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="votre@email.com">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="+212 XXX XXX XXX">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nationalité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Marocaine">
                            @error('nationality')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre adresse complète">
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre ville">
                            @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="region" value="{{ old('region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre région">
                            @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Code postal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="20000">
                            @error('postal_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Business Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-briefcase-line text-orange-500 mr-3"></i>
                        Informations sur l'Entreprise
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Nom de votre entreprise">
                            @error('business_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <textarea name="business_description" rows="4" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez votre activité d'entreprise">{{ old('business_description') }}</textarea>
                            @error('business_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type d'entreprise <span class="text-red-500">*</span>
                            </label>
                            <select name="business_type" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un type</option>
                                <option value="commerce" {{ old('business_type') == 'commerce' ? 'selected' : '' }}>Commerce</option>
                                <option value="service" {{ old('business_type') == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="artisanat" {{ old('business_type') == 'artisanat' ? 'selected' : '' }}>Artisanat</option>
                                <option value="profession_liberale" {{ old('business_type') == 'profession_liberale' ? 'selected' : '' }}>Profession Libérale</option>
                                <option value="autre" {{ old('business_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('business_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Secteur d'activité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_sector" value="{{ old('business_sector') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Services, Commerce, etc.">
                            @error('business_sector')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de début d'activité <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Chiffre d'affaires mensuel prévu (MAD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="expected_monthly_revenue" value="{{ old('expected_monthly_revenue') }}" step="0.01" min="0" max="999999.99" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="0.00">
                            @error('expected_monthly_revenue')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_address" value="{{ old('business_address') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Adresse complète de l'entreprise">
                            @error('business_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_city" value="{{ old('business_city') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ville">
                            @error('business_city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Région de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_region" value="{{ old('business_region') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Région">
                            @error('business_region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Legal Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-paper-line text-orange-500 mr-3"></i>
                        Informations Légales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_legal_status" id="has_legal_status" value="1" {{ old('has_legal_status') ? 'checked' : '' }} 
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                                    onchange="toggleLegalStatusFields()">
                                <span class="ml-2 block text-sm text-gray-900">
                                    J'ai un statut légal <span class="text-red-500">*</span>
                                </span>
                            </label>
                            @error('has_legal_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div id="legal_status_type_field" style="display: none;">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type de statut légal
                            </label>
                            <select name="legal_status_type" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un type</option>
                                <option value="auto_entrepreneur" {{ old('legal_status_type') == 'auto_entrepreneur' ? 'selected' : '' }}>Auto-Entrepreneur</option>
                                <option value="entreprise_individuale" {{ old('legal_status_type') == 'entreprise_individuale' ? 'selected' : '' }}>Entreprise Individuelle</option>
                                <option value="autre" {{ old('legal_status_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('legal_status_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro d'enregistrement
                            </label>
                            <input type="text" name="registration_number" value="{{ old('registration_number') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Numéro d'enregistrement">
                            @error('registration_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro fiscal
                            </label>
                            <input type="text" name="tax_number" value="{{ old('tax_number') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Numéro fiscal">
                            @error('tax_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Financial Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-money-dollar-circle-line text-orange-500 mr-3"></i>
                        Informations Financières
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Investissement initial (MAD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="initial_investment" value="{{ old('initial_investment') }}" step="0.01" min="0" max="999999.99" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="0.00">
                            @error('initial_investment')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Source de financement <span class="text-red-500">*</span>
                            </label>
                            <select name="funding_source" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez une source</option>
                                <option value="personal_savings" {{ old('funding_source') == 'personal_savings' ? 'selected' : '' }}>Épargne personnelle</option>
                                <option value="family_loan" {{ old('funding_source') == 'family_loan' ? 'selected' : '' }}>Prêt familial</option>
                                <option value="bank_loan" {{ old('funding_source') == 'bank_loan' ? 'selected' : '' }}>Prêt bancaire</option>
                                <option value="investor" {{ old('funding_source') == 'investor' ? 'selected' : '' }}>Investisseur</option>
                                <option value="government_support" {{ old('funding_source') == 'government_support' ? 'selected' : '' }}>Soutien gouvernemental</option>
                                <option value="autre" {{ old('funding_source') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('funding_source')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Dépenses mensuelles (MAD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="monthly_expenses" value="{{ old('monthly_expenses') }}" step="0.01" min="0" max="999999.99" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="0.00">
                            @error('monthly_expenses')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_bank_account" id="has_bank_account" value="1" {{ old('has_bank_account') ? 'checked' : '' }} 
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                                    onchange="toggleBankNameField()">
                                <span class="ml-2 block text-sm text-gray-900">
                                    J'ai un compte bancaire <span class="text-red-500">*</span>
                                </span>
                            </label>
                            @error('has_bank_account')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div id="bank_name_field" style="display: none;">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de la banque
                            </label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Nom de votre banque">
                            @error('bank_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 5: Documents -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-upload-line text-orange-500 mr-3"></i>
                        Documents
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Document d'identité <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="identity_document" required accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, PNG jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('identity_document')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Plan d'affaires (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="business_plan" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('business_plan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Projections financières (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Télécharger un fichier</span>
                                            <input type="file" name="financial_projections" accept=".pdf,.doc,.docx,.xls,.xlsx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX jusqu'à 10MB</p>
                                </div>
                            </div>
                            @error('financial_projections')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                CV (optionnel)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-upload-cloud-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
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
                    </div>
                </div>

                <!-- Section 6: Additional Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-list-3-line text-orange-500 mr-3"></i>
                        Informations Complémentaires
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Expérience précédente
                            </label>
                            <textarea name="previous_experience" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez votre expérience professionnelle précédente">{{ old('previous_experience') }}</textarea>
                            @error('previous_experience')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Compétences
                            </label>
                            <textarea name="skills" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Listez vos compétences principales">{{ old('skills') }}</textarea>
                            @error('skills')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Langues parlées
                            </label>
                            <input type="text" name="languages" value="{{ old('languages') }}" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Français, Arabe, Anglais">
                            @error('languages')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Défis rencontrés
                            </label>
                            <textarea name="challenges" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez les défis que vous rencontrez">{{ old('challenges') }}</textarea>
                            @error('challenges')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivation <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motivation" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Expliquez votre motivation pour devenir auto-entrepreneur">{{ old('motivation') }}</textarea>
                            @error('motivation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Aide nécessaire
                            </label>
                            <textarea name="support_needed" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez le type d'aide dont vous avez besoin">{{ old('support_needed') }}</textarea>
                            @error('support_needed')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 7: Marketing & Sales -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-marketing-line text-orange-500 mr-3"></i>
                        Marketing et Ventes
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Marché cible <span class="text-red-500">*</span>
                            </label>
                            <textarea name="target_market" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez votre marché cible">{{ old('target_market') }}</textarea>
                            @error('target_market')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stratégie marketing
                            </label>
                            <textarea name="marketing_strategy" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Décrivez votre stratégie marketing">{{ old('marketing_strategy') }}</textarea>
                            @error('marketing_strategy')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Avantage concurrentiel
                            </label>
                            <textarea name="competitive_advantage" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                                placeholder="Quel est votre avantage concurrentiel ?">{{ old('competitive_advantage') }}</textarea>
                            @error('competitive_advantage')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 8: Terms and Conditions -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-check-line text-orange-500 mr-3"></i>
                        Conditions et Consentements
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="checkbox" name="accept_terms" id="accept_terms" value="1" {{ old('accept_terms') ? 'checked' : '' }} required 
                                class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-1">
                            <label for="accept_terms" class="ml-2 block text-sm text-gray-900">
                                J'accepte les <a href="#" class="text-orange-600 hover:text-orange-800 font-medium">conditions d'utilisation</a> <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('accept_terms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <div class="flex items-start">
                            <input type="checkbox" name="accept_data_processing" id="accept_data_processing" value="1" {{ old('accept_data_processing') ? 'checked' : '' }} required 
                                class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-1">
                            <label for="accept_data_processing" class="ml-2 block text-sm text-gray-900">
                                J'accepte le <a href="#" class="text-orange-600 hover:text-orange-800 font-medium">traitement de mes données personnelles</a> <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('accept_data_processing')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-orange-500 text-orange-500 rounded-lg hover:bg-orange-50 transition-all">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Retour
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-lg font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg">
                        <i class="ri-send-plane-line mr-2"></i>
                        Soumettre le Formulaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleLegalStatusFields() {
        const checkbox = document.getElementById('has_legal_status');
        const field = document.getElementById('legal_status_type_field');
        if (checkbox.checked) {
            field.style.display = 'block';
        } else {
            field.style.display = 'none';
            document.querySelector('[name="legal_status_type"]').value = '';
        }
    }

    function toggleBankNameField() {
        const checkbox = document.getElementById('has_bank_account');
        const field = document.getElementById('bank_name_field');
        if (checkbox.checked) {
            field.style.display = 'block';
        } else {
            field.style.display = 'none';
            document.querySelector('[name="bank_name"]').value = '';
        }
    }

    // Initialize fields on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleLegalStatusFields();
        toggleBankNameField();
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
