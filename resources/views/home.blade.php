@extends('layouts.app')

@section('meta_title', 'I.M System - Services Gouvernementaux Simplifiés')
@section('meta_description', 'Plateforme numérique pour accéder aux services gouvernementaux et programmes d\'aide à l\'entrepreneuriat au Maroc.')

@section('content')
    @include('layouts.navigation')

    <!-- Hero Section - Full Screen Banner with Advanced Effects -->
    <section class="relative h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 overflow-hidden">
        <!-- Animated Background Particles -->
        <div class="absolute inset-0 z-0 particles-container">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Animated Gradient Orbs -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="gradient-orb orb-1"></div>
            <div class="gradient-orb orb-2"></div>
            <div class="gradient-orb orb-3"></div>
        </div>

        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/90 via-blue-900/80 to-slate-800/90 z-10"></div>
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920')] bg-cover bg-center opacity-20 parallax-bg"></div>
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full flex items-center">
            <div class="text-center w-full py-8">
                <!-- Status Badge with Enhanced Animation -->
                <div class="flex items-center justify-center mb-4 sm:mb-6 animate-fade-in">
                    <div class="bg-green-500/90 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2 shadow-lg shadow-green-500/50 border border-green-400/30 hover:scale-110 transition-transform">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                        </span>
                        Système Opérationnel
                    </div>
                </div>

                <!-- Main Headline with Text Gradient Animation -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-2 sm:mb-3 animate-fade-in-up leading-tight">
                    <span class="bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent animate-gradient-x">
                        Services Gouvernementaux
                    </span>
                </h1>
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-5 animate-fade-in-up animation-delay-200 leading-tight">
                    <span class="bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-500 bg-clip-text text-transparent animate-gradient-x animation-delay-300">
                        Simplifiés
                    </span>
                </h2>

                <!-- Description -->
                <p class="text-base sm:text-lg md:text-xl text-slate-300 mb-6 sm:mb-8 max-w-3xl mx-auto leading-relaxed px-4 animate-fade-in-up animation-delay-400">
                    Accédez facilement à tous les services gouvernementaux et programmes d'aide à l'entrepreneuriat. Une plateforme moderne, sécurisée et accessible 24/7.
                </p>

                <!-- CTA Buttons with Enhanced Effects -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center mb-6 sm:mb-8 animate-fade-in-up animation-delay-600 px-4">
                    <a href="#services" class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-base sm:text-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2 shadow-xl shadow-blue-500/50 overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></span>
                        <span class="relative z-10 flex items-center gap-2">
                            Parcourir les Services
                            <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </a>
                    <a href="#help" class="group relative border-2 border-white/80 backdrop-blur-sm bg-white/5 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-base sm:text-lg font-semibold transition-all transform hover:scale-105 hover:bg-white hover:text-blue-600 hover:border-white overflow-hidden">
                        <span class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                        <span class="relative z-10">Obtenir de l'Aide</span>
                    </a>
                </div>

                <!-- Enhanced Search Bar with Glassmorphism -->
                <div class="max-w-2xl mx-auto mb-6 sm:mb-8 animate-fade-in-up animation-delay-800 px-4">
                    <div class="bg-white/10 backdrop-blur-xl rounded-xl p-2 flex items-center gap-2 border border-white/20 shadow-2xl shadow-black/20 hover:bg-white/15 transition-all">
                        <i class="ri-search-line text-white text-xl sm:text-2xl ml-2 sm:ml-4"></i>
                        <input type="text" placeholder="Rechercher un service..." class="flex-1 bg-transparent text-white placeholder-slate-300 outline-none text-sm sm:text-lg py-2 focus:placeholder-slate-500 transition-colors">
                        <button class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg transition-all flex items-center gap-2 text-sm sm:text-base shadow-lg hover:shadow-xl hover:scale-105">
                            Rechercher
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Statistics with Counter Animation -->
                <div class="grid grid-cols-3 gap-4 sm:gap-6 md:gap-8 max-w-4xl mx-auto animate-fade-in-up animation-delay-1000 px-4">
                    <div class="text-center group">
                        <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-blue-400 mb-1 sm:mb-2 counter-number" data-target="1.2">0</div>
                        <div class="text-slate-300 text-xs sm:text-sm group-hover:text-blue-300 transition-colors">Utilisateurs</div>
                    </div>
                    <div class="text-center group">
                        <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-blue-400 mb-1 sm:mb-2 counter-number" data-target="98.5">0</div>
                        <div class="text-slate-300 text-xs sm:text-sm group-hover:text-blue-300 transition-colors">Satisfaction</div>
                    </div>
                    <div class="text-center group">
                        <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-blue-400 mb-1 sm:mb-2">24/7</div>
                        <div class="text-slate-300 text-xs sm:text-sm group-hover:text-blue-300 transition-colors">Disponible</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
            <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/70 rounded-full mt-2 animate-scroll-indicator"></div>
            </div>
        </div>
    </section>

    <!-- Services Section with Enhanced Cards -->
    <section id="services" class="py-16 sm:py-20 bg-gradient-to-b from-gray-50 to-white scroll-mt-20 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(59, 130, 246, 0.5) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                    <span class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 bg-clip-text text-transparent">
                        Nos Services
                    </span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                    Accédez à tous les services gouvernementaux et programmes d'aide à l'entrepreneuriat
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Zone Industrielle -->
                <a href="{{ route('forms.investment') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-600/0 group-hover:from-blue-500/10 group-hover:to-blue-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-building-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Zone Industrielle</h3>
                        <p class="text-blue-100 text-sm mt-2 relative z-10">Demande d'Investissement</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Accédez aux zones industrielles et soumettez vos projets d'investissement pour développement économique.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">1,250+ projets</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Chambre du Commerce -->
                <a href="{{ route('forms.project-carrier') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/0 to-green-600/0 group-hover:from-green-500/10 group-hover:to-green-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-briefcase-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Chambre du Commerce</h3>
                        <p class="text-green-100 text-sm mt-2 relative z-10">Porteur de Projet</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Présentez votre projet commercial et obtenez le soutien de la Chambre du Commerce.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-green-50 group-hover:text-green-600 transition-colors">980+ projets</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Artisanat -->
                <a href="{{ route('forms.auto-entrepreneur') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-purple-600/0 group-hover:from-purple-500/10 group-hover:to-purple-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-tools-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Artisanat</h3>
                        <p class="text-purple-100 text-sm mt-2 relative z-10">Auto-Entrepreneur</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Créez votre activité artisanale et bénéficiez des services et accompagnements dédiés.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-purple-50 group-hover:text-purple-600 transition-colors">2,150+ artisans</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Auto-Entrepreneur -->
                <a href="{{ route('forms.auto-entrepreneur') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-orange-600/0 group-hover:from-orange-500/10 group-hover:to-orange-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-user-star-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Auto-Entrepreneur</h3>
                        <p class="text-orange-100 text-sm mt-2 relative z-10">Création d'Activité</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Créez votre activité d'auto-entrepreneur et bénéficiez des services et accompagnements dédiés.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-orange-50 group-hover:text-orange-600 transition-colors">3,450+ actifs</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Porteur d'Idée -->
                <a href="{{ route('forms.idea-carrier') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/0 to-yellow-600/0 group-hover:from-yellow-500/10 group-hover:to-yellow-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-lightbulb-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Porteur d'Idée</h3>
                        <p class="text-yellow-100 text-sm mt-2 relative z-10">Innovation</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Partagez votre idée innovante et recevez l'accompagnement pour la transformer en projet concret.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-yellow-50 group-hover:text-yellow-600 transition-colors">890+ idées</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Porteur de Projet -->
                <a href="{{ route('forms.project-carrier') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/0 to-red-600/0 group-hover:from-red-500/10 group-hover:to-red-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-red-500 to-red-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-rocket-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Porteur de Projet</h3>
                        <p class="text-red-100 text-sm mt-2 relative z-10">Développement</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Présentez votre projet et obtenez le soutien nécessaire pour le développement et le financement.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-red-50 group-hover:text-red-600 transition-colors">1,680+ projets</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Inscription Candidat -->
                <a href="{{ route('forms.training') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-600/0 group-hover:from-indigo-500/10 group-hover:to-purple-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-user-add-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Inscription Candidat</h3>
                        <p class="text-indigo-100 text-sm mt-2 relative z-10">Formation & Emploi</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Inscrivez-vous aux formations professionnelles et développez vos compétences pour l'emploi.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">5,200+ candidats</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="85"></div>
                        </div>
                    </div>
                </a>

                <!-- Espace Entreprises -->
                <a href="{{ route('companies.index') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 overflow-hidden scroll-fade-in">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-blue-600/0 group-hover:from-indigo-500/10 group-hover:to-blue-600/10 transition-all duration-500"></div>
                    <div class="relative bg-gradient-to-r from-blue-700 to-indigo-800 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <i class="ri-community-line text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold relative z-10">Espace Entreprises</h3>
                        <p class="text-indigo-100 text-sm mt-2 relative z-10">Vitrine & Emploi</p>
                    </div>
                    <div class="p-6 relative">
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Découvrez les entreprises locales, suivez leurs actualités et explorez les opportunités d'emploi directes.
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">Portails & Offres</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 h-2 rounded-full transition-all duration-1000 progress-bar" style="width: 0%" data-width="92"></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section with Enhanced Cards -->
    <section class="py-16 sm:py-20 bg-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                    <span class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 bg-clip-text text-transparent">
                        Pourquoi Choisir Notre Plateforme
                    </span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                    Une solution complète, sécurisée et accessible pour tous vos besoins
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <!-- Traitement Sécurisé -->
                <div class="text-center group scroll-fade-in">
                    <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl w-24 h-24 flex items-center justify-center mx-auto mb-6 group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 shadow-lg group-hover:shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="ri-shield-check-line text-4xl text-blue-600 group-hover:text-white transition-all relative z-10"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Traitement Sécurisé</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Vos données sont protégées selon les normes gouvernementales les plus strictes.
                    </p>
                    <span class="inline-block bg-gradient-to-r from-blue-100 to-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold group-hover:from-blue-600 group-hover:to-blue-700 group-hover:text-white transition-all">256-bit encryption</span>
                </div>

                <!-- Disponibilité 24/7 -->
                <div class="text-center group scroll-fade-in">
                    <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl w-24 h-24 flex items-center justify-center mx-auto mb-6 group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 shadow-lg group-hover:shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="ri-time-line text-4xl text-blue-600 group-hover:text-white transition-all relative z-10"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Disponibilité 24/7</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Accédez à nos services à tout moment, où que vous soyez.
                    </p>
                    <span class="inline-block bg-gradient-to-r from-blue-100 to-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold group-hover:from-blue-600 group-hover:to-blue-700 group-hover:text-white transition-all">99.9% uptime</span>
                </div>

                <!-- Suivi en Temps Réel -->
                <div class="text-center group scroll-fade-in">
                    <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl w-24 h-24 flex items-center justify-center mx-auto mb-6 group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 shadow-lg group-hover:shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="ri-radar-line text-4xl text-blue-600 group-hover:text-white transition-all relative z-10"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Suivi en Temps Réel</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Suivez l'état de vos demandes en temps réel avec notifications instantanées.
                    </p>
                    <span class="inline-block bg-gradient-to-r from-blue-100 to-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold group-hover:from-blue-600 group-hover:to-blue-700 group-hover:text-white transition-all">Notifications instantanées</span>
                </div>

                <!-- Support Expert -->
                <div class="text-center group scroll-fade-in">
                    <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl w-24 h-24 flex items-center justify-center mx-auto mb-6 group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 shadow-lg group-hover:shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="ri-customer-service-2-line text-4xl text-blue-600 group-hover:text-white transition-all relative z-10"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">Support Expert</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Une équipe d'experts à votre disposition pour vous accompagner.
                    </p>
                    <span class="inline-block bg-gradient-to-r from-blue-100 to-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold group-hover:from-blue-600 group-hover:to-blue-700 group-hover:text-white transition-all">Support multilingue</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section with Enhanced Design -->
    <section class="py-16 sm:py-20 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 text-white relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">
                    <span class="bg-gradient-to-r from-white via-blue-200 to-white bg-clip-text text-transparent">
                        Nos Résultats
                    </span>
                </h2>
                <p class="text-lg sm:text-xl text-blue-200 max-w-2xl mx-auto">
                    Des chiffres qui témoignent de notre engagement envers l'excellence
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div class="text-center group scroll-fade-in">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-blue-400/20 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                        <div class="relative text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-blue-400 mb-2 sm:mb-3 counter-number" data-target="1.2">0</div>
                    </div>
                    <div class="text-lg sm:text-xl text-blue-200 mb-1 sm:mb-2 group-hover:text-blue-100 transition-colors">Formulaires Traités</div>
                    <div class="text-xs sm:text-sm text-blue-300">+15% ce mois</div>
                </div>
                <div class="text-center group scroll-fade-in">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-blue-400/20 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                        <div class="relative text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-blue-400 mb-2 sm:mb-3">85+</div>
                    </div>
                    <div class="text-lg sm:text-xl text-blue-200 mb-1 sm:mb-2 group-hover:text-blue-100 transition-colors">Catégories de Services</div>
                    <div class="text-xs sm:text-sm text-blue-300">En constante évolution</div>
                </div>
                <div class="text-center group scroll-fade-in">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-blue-400/20 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                        <div class="relative text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-blue-400 mb-2 sm:mb-3 counter-number" data-target="98.5">0</div>
                    </div>
                    <div class="text-lg sm:text-xl text-blue-200 mb-1 sm:mb-2 group-hover:text-blue-100 transition-colors">Taux de Satisfaction</div>
                    <div class="text-xs sm:text-sm text-blue-300">Utilisateurs satisfaits</div>
                </div>
                <div class="text-center group scroll-fade-in">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-blue-400/20 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                        <div class="relative text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-blue-400 mb-2 sm:mb-3">24/7</div>
                    </div>
                    <div class="text-lg sm:text-xl text-blue-200 mb-1 sm:mb-2 group-hover:text-blue-100 transition-colors">Disponibilité du Service</div>
                    <div class="text-xs sm:text-sm text-blue-300">Sans interruption</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 sm:py-20 bg-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="scroll-fade-in">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-4 sm:mb-6">
                        <span class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 bg-clip-text text-transparent">
                            Modernisation des Services Gouvernementaux
                        </span>
                    </h2>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Notre plateforme représente une nouvelle ère dans l'accès aux services gouvernementaux. Nous avons conçu un système qui combine innovation technologique, sécurité renforcée et accessibilité prioritaire pour tous les citoyens.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="ri-check-line text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-green-600 transition-colors">Processus Rationalisés</h3>
                                <p class="text-gray-600">Simplification des démarches administratives pour un traitement plus rapide.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="ri-check-line text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-green-600 transition-colors">Sécurité Renforcée</h3>
                                <p class="text-gray-600">Protection maximale de vos données personnelles et professionnelles.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 group">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="ri-check-line text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-green-600 transition-colors">Accessibilité Prioritaire</h3>
                                <p class="text-gray-600">Interface intuitive accessible à tous, quel que soit le niveau technique.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative scroll-fade-in">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl group">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800" alt="Services Gouvernementaux Modernes" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section with Enhanced Design -->
    <section class="py-16 sm:py-20 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-600/50 via-transparent to-indigo-600/50 animate-gradient-shift"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 scroll-fade-in">
                <span class="bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent">
                    Prêt à Commencer ?
                </span>
            </h2>
            <p class="text-lg sm:text-xl mb-6 sm:mb-8 text-blue-100 max-w-2xl mx-auto scroll-fade-in">
                Rejoignez des milliers d'utilisateurs qui font confiance à notre plateforme pour leurs démarches administratives
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center scroll-fade-in">
                <a href="{{ route('register') }}" class="group relative bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-xl overflow-hidden">
                    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-blue-50 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></span>
                    <span class="relative z-10">Créer un Compte</span>
                </a>
                <a href="{{ route('contact') }}" class="group relative border-2 border-white/80 backdrop-blur-sm bg-white/10 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-blue-600 transition-all transform hover:scale-105 overflow-hidden">
                    <span class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                    <span class="relative z-10">Nous Contacter</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Help Center Section -->
    <section id="help" class="py-16 sm:py-20 bg-gradient-to-b from-gray-50 to-white scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16 scroll-fade-in">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                    <span class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 bg-clip-text text-transparent">
                        Centre d'Aide
                    </span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                    Trouvez rapidement les réponses à vos questions
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- FAQ -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 p-8 text-center scroll-fade-in group">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6">
                        <i class="ri-question-line text-4xl text-blue-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors">FAQ</h3>
                    <p class="text-gray-600 mb-6">
                        Consultez les questions fréquemment posées pour trouver des réponses rapides.
                    </p>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 flex items-center justify-center gap-2 group-hover:gap-3 transition-all">
                        Consulter la FAQ
                        <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 p-8 text-center scroll-fade-in group">
                    <div class="bg-gradient-to-br from-green-100 to-green-50 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:from-green-600 group-hover:to-green-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6">
                        <i class="ri-customer-service-2-line text-4xl text-green-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-green-600 transition-colors">Support</h3>
                    <p class="text-gray-600 mb-6">
                        Contactez notre équipe de support pour une assistance personnalisée.
                    </p>
                    <a href="{{ route('contact') }}" class="text-green-600 font-semibold hover:text-green-700 flex items-center justify-center gap-2 group-hover:gap-3 transition-all">
                        Contacter le Support
                        <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Documentation -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 hover:-translate-y-2 p-8 text-center scroll-fade-in group">
                    <div class="bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:from-purple-600 group-hover:to-purple-700 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6">
                        <i class="ri-book-line text-4xl text-purple-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-purple-600 transition-colors">Documentation</h3>
                    <p class="text-gray-600 mb-6">
                        Accédez à la documentation complète et aux guides d'utilisation.
                    </p>
                    <a href="#" class="text-purple-600 font-semibold hover:text-purple-700 flex items-center justify-center gap-2 group-hover:gap-3 transition-all">
                        Voir la Documentation
                        <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Branding -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-2 mr-3 shadow-lg">
                            <i class="ri-government-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Services Gouvernementaux</h3>
                            <p class="text-blue-200 text-sm">Plateforme Numérique</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm max-w-md leading-relaxed mt-4">
                        Plateforme numérique moderne pour accéder aux services gouvernementaux et programmes d'aide à l'entrepreneuriat au Maroc. Simplifiez vos démarches administratives.
                    </p>
                </div>

                <!-- Liens Rapides -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Liens Rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Accueil</a></li>
                        <li><a href="{{ route('about') }}" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">À Propos</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Contact</a></li>
                        <li><a href="#services" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Services</a></li>
                        <li><a href="#help" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Centre d'Aide</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">FAQ</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Contact Support</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Documentation</a></li>
                        <li><a href="{{ route('register') }}" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">S'inscrire</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-300 hover:text-white text-sm transition-colors hover:translate-x-1 inline-block">Se Connecter</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-12 pt-8 border-t border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center gap-3 group">
                        <i class="ri-phone-line text-blue-400 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <div class="text-slate-300 text-sm">Téléphone</div>
                            <a href="tel:+212XXXXXXXXX" class="text-white hover:text-blue-400 transition-colors">+212 5 37 77 12 34</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <i class="ri-mail-line text-blue-400 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <div class="text-slate-300 text-sm">Email</div>
                            <a href="mailto:contact@imsystem.gov.ma" class="text-white hover:text-blue-400 transition-colors">contact@imsystem.gov.ma</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <i class="ri-time-line text-blue-400 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <div class="text-slate-300 text-sm">Horaires</div>
                            <div class="text-white">24/7 - Disponible en permanence</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="mt-12 pt-8 border-t border-slate-700">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-slate-300 text-sm mb-4 md:mb-0">
                        © {{ date('Y') }} I.M System. Tous droits réservés.
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <a href="#" class="text-slate-300 hover:text-white text-sm transition-colors">Politique de Confidentialité</a>
                        <span class="text-slate-600">|</span>
                        <a href="#" class="text-slate-300 hover:text-white text-sm transition-colors">Conditions d'Utilisation</a>
                        <span class="text-slate-600">|</span>
                        <a href="#" class="text-slate-300 hover:text-white text-sm transition-colors">Sécurité</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Advanced Custom Styles and Animations -->
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Animated Particles */
        .particles-container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 6s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 8s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 10s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 12s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 14s; }

        @keyframes float {
            0%, 100% { transform: translateY(100vh) translateX(0) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(100px) scale(1); opacity: 0; }
        }

        /* Gradient Orbs */
        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: orb-float 20s infinite ease-in-out;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.8), rgba(99, 102, 241, 0.4));
            top: -200px;
            left: -200px;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.8), rgba(59, 130, 246, 0.4));
            bottom: -150px;
            right: -150px;
            animation-delay: -7s;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.6), rgba(139, 92, 246, 0.3));
            top: 50%;
            right: 10%;
            animation-delay: -14s;
        }

        @keyframes orb-float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -50px) scale(1.1); }
            66% { transform: translate(-50px, 50px) scale(0.9); }
        }

        /* Parallax Background */
        .parallax-bg {
            will-change: transform;
        }

        /* Gradient Text Animation */
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 3s ease infinite;
        }

        /* Scroll Indicator */
        @keyframes scroll-indicator {
            0% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(12px); opacity: 0; }
        }

        .animate-scroll-indicator {
            animation: scroll-indicator 1.5s ease-in-out infinite;
        }

        /* Scroll-triggered Fade In */
        .scroll-fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Progress Bar Animation */
        .progress-bar {
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Gradient Shift Animation */
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .animate-gradient-shift {
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }

        /* Optimized animations */
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
            will-change: opacity;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out;
            will-change: opacity, transform;
        }

        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-300 { animation-delay: 0.3s; }
        .animation-delay-400 { animation-delay: 0.4s; }
        .animation-delay-600 { animation-delay: 0.6s; }
        .animation-delay-800 { animation-delay: 0.8s; }
        .animation-delay-1000 { animation-delay: 1s; }

        /* Reduce motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Performance optimizations */
        section {
            contain: layout style paint;
        }

        img {
            loading: lazy;
        }
    </style>

    <!-- JavaScript for Scroll Animations and Counter -->
    <script>
        // Scroll-triggered animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Animate progress bars
                    const progressBar = entry.target.querySelector('.progress-bar');
                    if (progressBar) {
                        const width = progressBar.getAttribute('data-width');
                        setTimeout(() => {
                            progressBar.style.width = width + '%';
                        }, 200);
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-fade-in').forEach(el => {
            observer.observe(el);
        });

        // Counter animation
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;
            const isPercentage = target < 10; // If target is less than 10, it's a percentage

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    if (isPercentage) {
                        element.textContent = target.toFixed(1) + '%';
                    } else {
                        element.textContent = target.toFixed(1) + 'M+';
                    }
                    clearInterval(timer);
                } else {
                    if (isPercentage) {
                        element.textContent = current.toFixed(1) + '%';
                    } else {
                        element.textContent = current.toFixed(1) + 'M+';
                    }
                }
            }, 16);
        }

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    entry.target.classList.add('counted');
                    const target = parseFloat(entry.target.getAttribute('data-target'));
                    animateCounter(entry.target, target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.counter-number').forEach(el => {
            counterObserver.observe(el);
        });

        // Parallax effect for hero background
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.parallax-bg');
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
@endsection
