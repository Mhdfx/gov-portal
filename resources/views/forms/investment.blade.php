@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-t-xl p-8 text-white">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="ri-funds-line text-blue-500 text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Formulaire Investment</h1>
                    <p class="text-blue-100">Trouvez des opportunités d'investissement prometteuses</p>
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
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-500" style="width: 100%"></div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-xl shadow-2xl p-8">
            <form action="{{ route('forms.investment.submit') }}" method="POST" enctype="multipart/form-data" id="investment-form" onsubmit="return handleFormSubmit(event)">
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
                        <i class="ri-user-line text-blue-500 mr-3"></i>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('first_name') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('last_name') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nationalité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nationality" value="{{ old('nationality', 'Marocaine') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nationality') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('address') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('city') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('region') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('postal_code') border-red-500 @enderror"
                                placeholder="00000">
                            @error('postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Project Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-projector-line text-blue-500 mr-3"></i>
                        Informations sur le Projet
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom du projet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="project_name" value="{{ old('project_name') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('project_name') border-red-500 @enderror"
                                placeholder="Nom de votre projet">
                            @error('project_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description du projet <span class="text-red-500">*</span>
                            </label>
                            <textarea name="project_description" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('project_description') border-red-500 @enderror"
                                placeholder="Décrivez votre projet en détail...">{{ old('project_description') }}</textarea>
                            @error('project_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Montant de l'investissement (MAD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="investment_amount" value="{{ old('investment_amount') }}" required min="1000" step="0.01"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('investment_amount') border-red-500 @enderror"
                                placeholder="Ex: 500000">
                            @error('investment_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Devise <span class="text-red-500">*</span>
                            </label>
                            <select name="currency" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('currency') border-red-500 @enderror">
                                <option value="">Sélectionnez une devise</option>
                                <option value="MAD" {{ old('currency') == 'MAD' ? 'selected' : '' }}>MAD</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                            @error('currency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Type d'investissement <span class="text-red-500">*</span>
                            </label>
                            <select name="investment_type" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('investment_type') border-red-500 @enderror">
                                <option value="">Sélectionnez un type</option>
                                <option value="equity" {{ old('investment_type') == 'equity' ? 'selected' : '' }}>Capital (Equity)</option>
                                <option value="loan" {{ old('investment_type') == 'loan' ? 'selected' : '' }}>Prêt (Loan)</option>
                                <option value="grant" {{ old('investment_type') == 'grant' ? 'selected' : '' }}>Subvention (Grant)</option>
                                <option value="partnership" {{ old('investment_type') == 'partnership' ? 'selected' : '' }}>Partenariat</option>
                                <option value="venture_capital" {{ old('investment_type') == 'venture_capital' ? 'selected' : '' }}>Capital-risque</option>
                                <option value="angel_investment" {{ old('investment_type') == 'angel_investment' ? 'selected' : '' }}>Investissement d'ange</option>
                                <option value="autre" {{ old('investment_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('investment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Secteur d'activité <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sector" value="{{ old('sector') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('sector') border-red-500 @enderror"
                                placeholder="Ex: Technologie, Agriculture, Industrie...">
                            @error('sector')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Objectif de l'investissement <span class="text-red-500">*</span>
                            </label>
                            <textarea name="investment_purpose" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('investment_purpose') border-red-500 @enderror"
                                placeholder="Décrivez l'objectif de votre demande d'investissement...">{{ old('investment_purpose') }}</textarea>
                            @error('investment_purpose')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stade de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <select name="business_stage" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('business_stage') border-red-500 @enderror">
                                <option value="">Sélectionnez un stade</option>
                                <option value="startup" {{ old('business_stage') == 'startup' ? 'selected' : '' }}>Startup</option>
                                <option value="growth" {{ old('business_stage') == 'growth' ? 'selected' : '' }}>Croissance</option>
                                <option value="expansion" {{ old('business_stage') == 'expansion' ? 'selected' : '' }}>Expansion</option>
                                <option value="mature" {{ old('business_stage') == 'mature' ? 'selected' : '' }}>Mature</option>
                                <option value="autre" {{ old('business_stage') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('business_stage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Marché cible <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="target_market" value="{{ old('target_market') }}" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('target_market') border-red-500 @enderror"
                                placeholder="Décrivez votre marché cible">
                            @error('target_market')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivation <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motivation" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('motivation') border-red-500 @enderror"
                                placeholder="Quelle est votre motivation pour ce projet?">{{ old('motivation') }}</textarea>
                            @error('motivation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Documents -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-line text-blue-500 mr-3"></i>
                        Documents (Optionnel)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                CV
                            </label>
                            <input type="file" name="cv" accept=".pdf,.doc,.docx"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('cv') border-red-500 @enderror">
                            @error('cv')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Plan d'affaires
                            </label>
                            <input type="file" name="business_plan" accept=".pdf,.doc,.docx"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('business_plan') border-red-500 @enderror">
                            @error('business_plan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Projections financières
                            </label>
                            <input type="file" name="financial_projections" accept=".pdf,.doc,.docx,.xls,.xlsx"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('financial_projections') border-red-500 @enderror">
                            @error('financial_projections')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-8">
                    <div class="space-y-4">
                        <label class="flex items-start space-x-3">
                            <input type="checkbox" name="accept_terms" value="1" required
                                class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 @error('accept_terms') border-red-500 @enderror">
                            <span class="text-sm font-semibold text-gray-700">
                                J'accepte les conditions d'utilisation <span class="text-red-500">*</span>
                            </span>
                        </label>
                        @error('accept_terms')
                        <p class="ml-8 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <label class="flex items-start space-x-3">
                            <input type="checkbox" name="accept_data_processing" value="1" required
                                class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 @error('accept_data_processing') border-red-500 @enderror">
                            <span class="text-sm font-semibold text-gray-700">
                                J'accepte le traitement de mes données personnelles <span class="text-red-500">*</span>
                            </span>
                        </label>
                        @error('accept_data_processing')
                        <p class="ml-8 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Investment Opportunities -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Opportunités d'Investissement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600">150+</div>
                            <div class="text-sm text-blue-800">Projets Disponibles</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600">12.5%</div>
                            <div class="text-sm text-blue-800">ROI Moyen</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600">95%</div>
                            <div class="text-sm text-blue-800">Taux de Réussite</div>
                        </div>
                    </div>
                </div>

                <!-- Information Panel -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                        <i class="ri-information-line mr-2"></i>
                        Avantages pour les Investisseurs
                    </h3>
                    <div class="space-y-3 text-green-800">
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-green-600"></i>
                            <span>Accès à des projets pré-évalués par nos experts</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-green-600"></i>
                            <span>Accompagnement personnalisé dans vos choix d'investissement</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-green-600"></i>
                            <span>Possibilité de co-investissement avec d'autres investisseurs</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-green-600"></i>
                            <span>Suivi régulier de vos investissements</span>
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 transition-all">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Retour
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-lg font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg">
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
function handleFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    
    // Disable submit button
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Envoi en cours...';
    
    // Clear previous errors
    document.querySelectorAll('.text-red-600, .border-red-500').forEach(el => {
        el.classList.remove('text-red-600', 'border-red-500');
    });
    const errorContainer = document.querySelector('.error-container');
    if (errorContainer) errorContainer.remove();
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
        return response.json().then(err => Promise.reject(err));
    })
    .then(data => {
        if (data.status === 'success') {
            // Show success message
            const successDiv = document.createElement('div');
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
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = '{{ route("user.submissions") }}';
            }, 2000);
        }
    })
    .catch(error => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
        
        // Show error messages
        let errorHtml = '<div class="error-container mb-6 bg-red-50 border border-red-200 rounded-lg p-4"><div class="flex"><i class="ri-error-warning-line text-red-400 text-xl mr-3"></i><div class="flex-1"><p class="text-sm font-medium text-red-800 mb-2">Erreurs de validation:</p><ul class="list-disc list-inside text-sm text-red-700">';
        
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
    });
    
    return false;
}
</script>
@endpush
@endsection
