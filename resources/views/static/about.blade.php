@extends('layouts.app')

@section('content')
    @include('layouts.navigation')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">À Propos de Boiema</h1>
                <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto">
                    Votre partenaire pour l'entrepreneuriat et l'investissement au Maroc
                </p>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Notre Mission</h2>
                    <p class="text-lg text-gray-600 mb-4">
                        Boiema est une plateforme gouvernementale dédiée à faciliter l'accès aux services d'entrepreneuriat, 
                        d'investissement et d'emploi au Maroc.
                    </p>
                    <p class="text-lg text-gray-600 mb-4">
                        Nous connectons les porteurs de projets, les investisseurs et les candidats avec les institutions 
                        et les opportunités qui correspondent le mieux à leurs besoins.
                    </p>
                    <p class="text-lg text-gray-600">
                        Notre objectif est de simplifier et accélérer les démarches administratives tout en garantissant 
                        un accompagnement de qualité.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-2">15,000+</div>
                        <div class="text-gray-600">Utilisateurs</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-2">3,200+</div>
                        <div class="text-gray-600">Projets Financés</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-2">850+</div>
                        <div class="text-gray-600">Offres d'Emploi</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-2">95%</div>
                        <div class="text-gray-600">Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Nos Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-orange-50 to-yellow-50 p-8 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-yellow-500 rounded-full flex items-center justify-center mb-4">
                        <i class="ri-user-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Entrepreneuriat</h3>
                    <p class="text-gray-600">
                        Support complet pour les auto-entrepreneurs, porteurs d'idées et porteurs de projets.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mb-4">
                        <i class="ri-funds-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Investissement</h3>
                    <p class="text-gray-600">
                        Accès à des opportunités d'investissement vérifiées et prometteuses.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center mb-4">
                        <i class="ri-briefcase-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Emploi</h3>
                    <p class="text-gray-600">
                        Plateforme de recrutement connectant candidats et entreprises.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Nos Valeurs</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-shield-check-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Transparence</h3>
                    <p class="text-gray-600">Processus clairs et traçables</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-speed-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Efficacité</h3>
                    <p class="text-gray-600">Réponses rapides et précises</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-team-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Accompagnement</h3>
                    <p class="text-gray-600">Support personnalisé</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-lightbulb-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Innovation</h3>
                    <p class="text-gray-600">Solutions modernes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Rejoignez-nous aujourd'hui</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Faites partie d'une communauté dynamique d'entrepreneurs et d'investisseurs
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                    Créer un Compte
                </a>
                <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all transform hover:scale-105">
                    Nous Contacter
                </a>
            </div>
        </div>
    </section>
</div>
    @include('layouts.footer')
@endsection
