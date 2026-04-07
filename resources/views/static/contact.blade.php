@extends('layouts.app')

@section('content')
    @include('layouts.navigation')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Contactez-nous</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Notre équipe est à votre disposition pour répondre à toutes vos questions
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-map-pin-line text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Adresse</h3>
                            <p class="text-gray-600">
                                Avenue Mohammed V<br>
                                Rabat, Maroc
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-phone-line text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Téléphone</h3>
                            <p class="text-gray-600">
                                +212 5XX XXX XXX<br>
                                +212 6XX XXX XXX
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-mail-line text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">
                                info@boiema.ma<br>
                                support@boiema.ma
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-time-line text-orange-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Horaires</h3>
                            <p class="text-gray-600">
                                Lun - Ven: 8h00 - 17h00<br>
                                Sam: 9h00 - 13h00
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>
                    
                    <form action="{{ route('contact.submit') }}" method="POST" data-ajax="true">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Votre nom complet">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="votre@email.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" name="phone" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="+212 XXX XXX XXX">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Sujet <span class="text-red-500">*</span>
                                </label>
                                <select name="subject" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Sélectionnez un sujet</option>
                                    <option value="information">Demande d'information</option>
                                    <option value="support">Support technique</option>
                                    <option value="partnership">Partenariat</option>
                                    <option value="complaint">Réclamation</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" rows="6" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Écrivez votre message ici..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="ri-shield-check-line text-green-600"></i>
                                <span>Vos données sont sécurisées et confidentielles</span>
                            </div>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg">
                                <i class="ri-send-plane-line mr-2"></i>
                                Envoyer le Message
                            </button>
                        </div>
                    </form>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Questions Fréquentes</h2>
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Comment créer un compte ?</h3>
                            <p class="text-gray-600">Cliquez sur "S'inscrire" en haut de la page et remplissez le formulaire d'inscription.</p>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Combien de temps prend le traitement d'une demande ?</h3>
                            <p class="text-gray-600">Le délai moyen de traitement est de 5 à 7 jours ouvrables.</p>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Puis-je modifier ma demande après soumission ?</h3>
                            <p class="text-gray-600">Oui, connectez-vous à votre espace personnel et accédez à "Mes Demandes".</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Les services sont-ils gratuits ?</h3>
                            <p class="text-gray-600">Oui, tous nos services de base sont entièrement gratuits.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('layouts.footer')
@endsection
