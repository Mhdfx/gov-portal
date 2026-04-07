@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-t-xl p-8 text-white">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="ri-user-add-line text-indigo-500 text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Inscription Candidat</h1>
                    <p class="text-indigo-100">Créez votre profil professionnel et accédez aux meilleures offres d'emploi</p>
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
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-500" style="width: 100%"></div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-b-xl shadow-2xl p-8">
            <form action="{{ route('candidates.register') }}" method="POST" enctype="multipart/form-data" data-ajax="true">
                @csrf

                <!-- Section 1: Personal Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-user-line text-indigo-500 mr-3"></i>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom_complet" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre nom complet">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="votre@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="telephone" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="+212 XXX XXX XXX">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Ville <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ville" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="Votre ville">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_naissance" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Professional Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-briefcase-line text-indigo-500 mr-3"></i>
                        Informations Professionnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Niveau d'études <span class="text-red-500">*</span>
                            </label>
                            <select name="niveau_etudes" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un niveau</option>
                                <option value="bac">Baccalauréat</option>
                                <option value="bac+2">Bac+2 (DUT/BTS)</option>
                                <option value="bac+3">Bac+3 (Licence)</option>
                                <option value="bac+5">Bac+5 (Master)</option>
                                <option value="doctorat">Doctorat</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Domaine de compétence <span class="text-red-500">*</span>
                            </label>
                            <select name="domaine_competence" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez un domaine</option>
                                <option value="informatique">Informatique / IT</option>
                                <option value="commerce">Commerce / Vente</option>
                                <option value="finance">Finance / Comptabilité</option>
                                <option value="ingenierie">Ingénierie</option>
                                <option value="marketing">Marketing / Communication</option>
                                <option value="rh">Ressources Humaines</option>
                                <option value="sante">Santé</option>
                                <option value="education">Éducation</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Années d'expérience <span class="text-red-500">*</span>
                            </label>
                            <select name="annees_experience" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez votre expérience</option>
                                <option value="0-1">Débutant (0-1 an)</option>
                                <option value="1-3">Junior (1-3 ans)</option>
                                <option value="3-5">Intermédiaire (3-5 ans)</option>
                                <option value="5-10">Confirmé (5-10 ans)</option>
                                <option value="10+">Expert (10+ ans)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Salaire souhaité (MAD/mois)
                            </label>
                            <input type="number" name="salaire_souhaite" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: 8000">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Additional Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-list-3-line text-indigo-500 mr-3"></i>
                        Informations Complémentaires
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Compétences techniques
                            </label>
                            <textarea name="competences_techniques" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="Listez vos compétences techniques principales..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Langues parlées
                            </label>
                            <textarea name="langues_parlees" rows="2"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ex: Arabe (natif), Français (courant), Anglais (intermédiaire)"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Disponibilité <span class="text-red-500">*</span>
                            </label>
                            <select name="disponibilite" required 
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez votre disponibilité</option>
                                <option value="immediate">Immédiate</option>
                                <option value="1mois">Sous 1 mois</option>
                                <option value="2mois">Sous 2 mois</option>
                                <option value="3mois">Sous 3 mois</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 4: File Uploads -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="ri-file-upload-line text-indigo-500 mr-3"></i>
                        Documents
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                CV <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-file-pdf-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                            <span>Télécharger votre CV</span>
                                            <input type="file" name="cv" required accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 5MB</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lettre de motivation (Optionnelle)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="ri-file-text-line text-gray-400 text-4xl"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                            <span>Télécharger une lettre</span>
                                            <input type="file" name="lettre_motivation" accept=".pdf,.doc,.docx" class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX jusqu'à 3MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Panel -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <i class="ri-information-line mr-2"></i>
                        Services Inclus
                    </h3>
                    <div class="space-y-3 text-blue-800">
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-blue-600"></i>
                            <span>Accès à 850+ offres d'emploi exclusives</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-blue-600"></i>
                            <span>Matching intelligent avec les entreprises</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-blue-600"></i>
                            <span>Conseils carrière personnalisés</span>
                        </p>
                        <p class="flex items-start">
                            <i class="ri-check-line mr-2 mt-1 text-blue-600"></i>
                            <span>Formations gratuites en ligne</span>
                        </p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4">Nos Résultats</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-3xl font-bold text-indigo-600">2,500+</div>
                            <div class="text-sm text-indigo-800">Candidats Inscrits</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-indigo-600">850+</div>
                            <div class="text-sm text-indigo-800">Offres d'Emploi</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-indigo-600">92%</div>
                            <div class="text-sm text-indigo-800">Taux de Placement</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-indigo-500 text-indigo-500 rounded-lg hover:bg-indigo-50 transition-all">
                        <i class="ri-arrow-left-line mr-2"></i>
                        Retour
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-lg font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                        <i class="ri-user-add-line mr-2"></i>
                        Créer mon Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
