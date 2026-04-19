<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 nav-header" 
     x-data="{ 
         scrolled: false, 
         mobileMenuOpen: false,
         formsOpen: false,
         userMenuOpen: false
     }" 
     @scroll.window="scrolled = window.scrollY > 20"
     :class="scrolled ? 'nav-scrolled' : ''">
    
    <!-- Animated Background with Glassmorphism -->
    <div class="absolute inset-0 transition-all duration-500"
         :class="scrolled ? 'bg-white/95 dark:bg-slate-900/95 shadow-2xl' : 'bg-white/90 dark:bg-slate-900/90 shadow-md'">
        <div class="absolute inset-0 backdrop-blur-xl"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 via-purple-600/5 to-indigo-600/5 opacity-0 transition-opacity duration-500"
             :class="scrolled ? 'opacity-100' : ''"></div>
    </div>
    
    <!-- Animated Border -->
    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-blue-600 to-transparent transition-all duration-500"
         :class="scrolled ? 'opacity-100' : 'opacity-0'"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20">
            <!-- Logo Section with Enhanced Animation -->
            <div class="flex items-center flex-shrink-0 z-10">
                <a href="{{ route('home') }}" class="flex items-center group relative">
                    <!-- Animated Glow Effect -->
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-xl opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-500"></div>
                    
                    <!-- Logo Container -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl blur-md opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-xl p-2.5 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg group-hover:shadow-2xl">
                            <i class="ri-government-line text-white text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                    
                    <!-- Brand Text -->
                    <div class="ml-3 sm:ml-4">
                        <span class="text-xl sm:text-2xl font-extrabold bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 bg-clip-text text-transparent dark:from-white dark:via-blue-200 dark:to-white group-hover:from-blue-600 group-hover:via-indigo-600 group-hover:to-purple-600 transition-all duration-500 block">
                            I.M System
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block font-medium mt-0.5">Services Gouvernementaux</p>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex lg:items-center lg:space-x-1 xl:space-x-2">
                <a href="{{ route('home') }}" 
                   class="nav-link-premium {{ request()->routeIs('home') ? 'nav-link-active' : '' }}"
                   data-route="home">
                    <span class="nav-link-icon"><i class="ri-home-line"></i></span>
                    <span class="nav-link-text">Accueil</span>
                    <span class="nav-link-underline"></span>
                </a>
                
                <a href="{{ route('blog.index') }}" 
                   class="nav-link-premium {{ request()->routeIs('blog.*') ? 'nav-link-active' : '' }}"
                   data-route="blog">
                    <span class="nav-link-icon"><i class="ri-article-line"></i></span>
                    <span class="nav-link-text">Blog</span>
                    <span class="nav-link-underline"></span>
                </a>
                
                <a href="{{ route('jobs.index') }}" 
                   class="nav-link-premium {{ request()->routeIs('jobs.*') ? 'nav-link-active' : '' }}"
                   data-route="jobs">
                    <span class="nav-link-icon"><i class="ri-briefcase-line"></i></span>
                    <span class="nav-link-text">Emplois</span>
                    <span class="nav-link-underline"></span>
                </a>
                
                <a href="{{ route('companies.index') }}" 
                   class="nav-link-premium {{ request()->routeIs('companies.*') ? 'nav-link-active' : '' }}"
                   data-route="companies">
                    <span class="nav-link-icon"><i class="ri-community-line"></i></span>
                    <span class="nav-link-text">Entreprises</span>
                    <span class="nav-link-underline"></span>
                </a>
                
                <a href="{{ route('about') }}" 
                   class="nav-link-premium {{ request()->routeIs('about') ? 'nav-link-active' : '' }}"
                   data-route="about">
                    <span class="nav-link-icon"><i class="ri-information-line"></i></span>
                    <span class="nav-link-text">À Propos</span>
                    <span class="nav-link-underline"></span>
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="nav-link-premium {{ request()->routeIs('contact') ? 'nav-link-active' : '' }}"
                   data-route="contact">
                    <span class="nav-link-icon"><i class="ri-mail-line"></i></span>
                    <span class="nav-link-text">Contact</span>
                    <span class="nav-link-underline"></span>
                </a>
                
                <!-- Forms Dropdown - Fixed to work on click -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" 
                            type="button" 
                            class="nav-link-premium nav-link-dropdown"
                            :class="open ? 'nav-link-active' : ''">
                        <span class="nav-link-icon"><i class="ri-file-list-3-line"></i></span>
                        <span class="nav-link-text">Formulaires</span>
                        <i class="ri-arrow-down-s-line ml-1.5 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        <span class="nav-link-underline"></span>
                    </button>
                    
                    <!-- Mega Dropdown Menu -->
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute left-0 mt-3 w-80 rounded-2xl shadow-2xl bg-white/98 dark:bg-slate-800/98 backdrop-blur-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden z-50"
                         style="display: none;">
                        <!-- Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border-b border-gray-200/50 dark:border-gray-700/50">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center">
                                <i class="ri-file-list-3-line mr-2 text-blue-600 dark:text-blue-400"></i>
                                Tous les Formulaires
                            </h3>
                        </div>
                        
                        <!-- Forms List -->
                        <div class="p-2 max-h-96 overflow-y-auto">
                            <a href="{{ route('forms.auto-entrepreneur') }}" 
                               class="dropdown-item-premium group/item"
                               @click="open = false">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mr-3 group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300 shadow-lg group-hover/item:shadow-xl">
                                        <i class="ri-user-star-line text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white group-hover/item:text-orange-600 dark:group-hover/item:text-orange-400 transition-colors text-sm">Auto-Entrepreneur</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">Création d'Activité</div>
                                    </div>
                                    <i class="ri-arrow-right-line text-gray-400 group-hover/item:text-orange-600 group-hover/item:translate-x-1 transition-all ml-2"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('forms.idea-carrier') }}" 
                               class="dropdown-item-premium group/item"
                               @click="open = false">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center mr-3 group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300 shadow-lg group-hover/item:shadow-xl">
                                        <i class="ri-lightbulb-line text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white group-hover/item:text-yellow-600 dark:group-hover/item:text-yellow-400 transition-colors text-sm">Porteur d'Idée</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">Innovation</div>
                                    </div>
                                    <i class="ri-arrow-right-line text-gray-400 group-hover/item:text-yellow-600 group-hover/item:translate-x-1 transition-all ml-2"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('forms.project-carrier') }}" 
                               class="dropdown-item-premium group/item"
                               @click="open = false">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mr-3 group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300 shadow-lg group-hover/item:shadow-xl">
                                        <i class="ri-rocket-line text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white group-hover/item:text-red-600 dark:group-hover/item:text-red-400 transition-colors text-sm">Porteur de Projet</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">Développement</div>
                                    </div>
                                    <i class="ri-arrow-right-line text-gray-400 group-hover/item:text-red-600 group-hover/item:translate-x-1 transition-all ml-2"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('forms.investment') }}" 
                               class="dropdown-item-premium group/item"
                               @click="open = false">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-3 group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300 shadow-lg group-hover/item:shadow-xl">
                                        <i class="ri-money-dollar-circle-line text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors text-sm">Investissement</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">Zone Industrielle</div>
                                    </div>
                                    <i class="ri-arrow-right-line text-gray-400 group-hover/item:text-blue-600 group-hover/item:translate-x-1 transition-all ml-2"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('forms.indh') }}" 
                               class="dropdown-item-premium group/item"
                               @click="open = false">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mr-3 group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300 shadow-lg group-hover/item:shadow-xl">
                                        <i class="ri-community-line text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white group-hover/item:text-green-600 dark:group-hover/item:text-green-400 transition-colors text-sm">Projet INDH</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">Développement Humain</div>
                                    </div>
                                    <i class="ri-arrow-right-line text-gray-400 group-hover/item:text-green-600 group-hover/item:translate-x-1 transition-all ml-2"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('forms.training') }}" 
                               class="dropdown-item-premium group/item"
                               @click="open = false">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mr-3 group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300 shadow-lg group-hover/item:shadow-xl">
                                        <i class="ri-graduation-cap-line text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 dark:text-white group-hover/item:text-purple-600 dark:group-hover/item:text-purple-400 transition-colors text-sm">Formation</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">Inscription Candidat</div>
                                    </div>
                                    <i class="ri-arrow-right-line text-gray-400 group-hover/item:text-purple-600 group-hover/item:translate-x-1 transition-all ml-2"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                @auth
                    @if(auth()->user()->hasRole('user'))
                        <a href="{{ route('user.dashboard') }}" 
                           class="nav-link-premium {{ request()->routeIs('user.*') ? 'nav-link-active' : '' }}">
                            <span class="nav-link-icon"><i class="ri-dashboard-line"></i></span>
                            <span class="nav-link-text">Tableau de Bord</span>
                            <span class="nav-link-underline"></span>
                        </a>
                    @elseif(auth()->user()->hasRole('company'))
                        <a href="{{ route('company.dashboard') }}" 
                           class="nav-link-premium {{ request()->routeIs('company.*') ? 'nav-link-active' : '' }}">
                            <span class="nav-link-icon"><i class="ri-building-line"></i></span>
                            <span class="nav-link-text">Mon Entreprise</span>
                            <span class="nav-link-underline"></span>
                        </a>
                    @elseif(auth()->user()->hasRole('main_admin'))
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-link-premium nav-link-admin {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">
                            <span class="nav-link-icon"><i class="ri-shield-user-line"></i></span>
                            <span class="nav-link-text">Administration</span>
                            <span class="nav-link-underline"></span>
                        </a>
                    @elseif(auth()->user()->hasRole('institutional_admin'))
                        <a href="{{ route('institutional.dashboard') }}" 
                           class="nav-link-premium {{ request()->routeIs('institutional.*') ? 'nav-link-active' : '' }}">
                            <span class="nav-link-icon"><i class="ri-government-line"></i></span>
                            <span class="nav-link-text">Admin Institutionnel</span>
                            <span class="nav-link-underline"></span>
                        </a>
                    @elseif(auth()->user()->hasRole('sectoral_admin'))
                        <a href="{{ route('sectoral.dashboard') }}" 
                           class="nav-link-premium {{ request()->routeIs('sectoral.*') ? 'nav-link-active' : '' }}">
                            <span class="nav-link-icon"><i class="ri-building-2-line"></i></span>
                            <span class="nav-link-text">Admin Sectoriel</span>
                            <span class="nav-link-underline"></span>
                        </a>
                    @endif
                @endauth
            </div>
            
            <!-- Right Side Actions -->
            <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4">
                <!-- Language Switcher -->
                <div class="hidden sm:block">
                    <x-language-switcher />
                </div>
                
                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                type="button" 
                                class="relative flex items-center focus:outline-none group"
                                id="user-menu-button">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full blur-md opacity-0 group-hover:opacity-50 transition-opacity"></div>
                                <div class="relative h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform ring-2 ring-white dark:ring-slate-800">
                                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                                </div>
                                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-white dark:border-slate-800 animate-pulse"></div>
                            </div>
                        </button>

                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute right-0 mt-3 w-64 rounded-2xl shadow-2xl bg-white/98 dark:bg-slate-800/98 backdrop-blur-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden z-50"
                             style="display: none;">
                            <div class="px-4 py-4 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-900/20">
                                <div class="font-bold text-gray-900 dark:text-white text-sm">{{ auth()->user()->username }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                            </div>
                            
                            <div class="py-2">
                                @if(auth()->user()->hasRole('user'))
                                    <a href="{{ route('user.dashboard') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-dashboard-line"></i>
                                        <span>Tableau de Bord</span>
                                    </a>
                                    <a href="{{ route('user.profile') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-user-line"></i>
                                        <span>Mon Profil</span>
                                    </a>
                                    <a href="{{ route('user.submissions') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-file-list-3-line"></i>
                                        <span>Mes Soumissions</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('company'))
                                    <a href="{{ route('company.dashboard') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-dashboard-line"></i>
                                        <span>Tableau de Bord</span>
                                    </a>
                                    <a href="{{ route('company.profile') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-building-line"></i>
                                        <span>Mon Entreprise</span>
                                    </a>
                                    <a href="{{ route('company.public-profile') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-layout-masonry-line"></i>
                                        <span>Profil Public</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('main_admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-shield-user-line"></i>
                                        <span>Administration</span>
                                    </a>
                                    <a href="{{ route('admin.users') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-user-line"></i>
                                        <span>Utilisateurs</span>
                                    </a>
                                    <a href="{{ route('admin.companies') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-building-line"></i>
                                        <span>Entreprises</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('institutional_admin'))
                                    <a href="{{ route('institutional.dashboard') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-government-line"></i>
                                        <span>Admin Institutionnel</span>
                                    </a>
                                @elseif(auth()->user()->hasRole('sectoral_admin'))
                                    <a href="{{ route('sectoral.dashboard') }}" class="user-menu-item-premium" @click="open = false">
                                        <i class="ri-building-2-line"></i>
                                        <span>Admin Sectoriel</span>
                                    </a>
                                @endif
                                
                                <div class="border-t border-gray-200/50 dark:border-gray-700/50 my-2"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="user-menu-item-premium user-menu-item-danger w-full text-left" @click="open = false">
                                        <i class="ri-logout-box-line"></i>
                                        <span>Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Actions -->
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <a href="{{ route('login') }}" 
                           class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20">
                            <i class="ri-login-box-line mr-1.5"></i>
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}" 
                           class="relative inline-flex items-center px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 overflow-hidden group">
                            <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></span>
                            <span class="relative z-10 flex items-center">
                                <i class="ri-user-add-line mr-1.5"></i>
                                <span class="hidden sm:inline">S'inscrire</span>
                                <span class="sm:hidden">Inscription</span>
                            </span>
                        </a>
                    </div>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="lg:hidden p-2 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 hover:scale-110">
                    <i class="ri-menu-line text-2xl transition-transform duration-300" :class="mobileMenuOpen ? 'rotate-90' : ''"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden pb-6 border-t border-gray-200/50 dark:border-gray-700/50 mt-2 pt-4"
             style="display: none;">
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link-premium {{ request()->routeIs('home') ? 'mobile-nav-link-active' : '' }}" @click="mobileMenuOpen = false">
                    <i class="ri-home-line"></i>
                    <span>Accueil</span>
                </a>
                <a href="{{ route('blog.index') }}" class="mobile-nav-link-premium {{ request()->routeIs('blog.*') ? 'mobile-nav-link-active' : '' }}" @click="mobileMenuOpen = false">
                    <i class="ri-article-line"></i>
                    <span>Blog</span>
                </a>
                <a href="{{ route('jobs.index') }}" class="mobile-nav-link-premium {{ request()->routeIs('jobs.*') ? 'mobile-nav-link-active' : '' }}" @click="mobileMenuOpen = false">
                    <i class="ri-briefcase-line"></i>
                    <span>Emplois</span>
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link-premium {{ request()->routeIs('about') ? 'mobile-nav-link-active' : '' }}" @click="mobileMenuOpen = false">
                    <i class="ri-information-line"></i>
                    <span>À Propos</span>
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link-premium {{ request()->routeIs('contact') ? 'mobile-nav-link-active' : '' }}" @click="mobileMenuOpen = false">
                    <i class="ri-mail-line"></i>
                    <span>Contact</span>
                </a>
                
                <!-- Mobile Forms Links -->
                <div class="pt-3 border-t border-gray-200/50 dark:border-gray-700/50 mt-3">
                    <div class="px-4 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center">
                        <i class="ri-file-list-3-line mr-2"></i>
                        Formulaires
                    </div>
                    <a href="{{ route('forms.auto-entrepreneur') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                        <i class="ri-user-star-line"></i>
                        <span>Auto-Entrepreneur</span>
                    </a>
                    <a href="{{ route('forms.idea-carrier') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                        <i class="ri-lightbulb-line"></i>
                        <span>Porteur d'Idée</span>
                    </a>
                    <a href="{{ route('forms.project-carrier') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                        <i class="ri-rocket-line"></i>
                        <span>Porteur de Projet</span>
                    </a>
                    <a href="{{ route('forms.investment') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span>Investissement</span>
                    </a>
                    <a href="{{ route('forms.indh') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                        <i class="ri-community-line"></i>
                        <span>Projet INDH</span>
                    </a>
                    <a href="{{ route('forms.training') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                        <i class="ri-graduation-cap-line"></i>
                        <span>Formation</span>
                    </a>
                </div>
                
                @auth
                    <div class="pt-3 border-t border-gray-200/50 dark:border-gray-700/50 mt-3">
                        @if(auth()->user()->hasRole('user'))
                            <a href="{{ route('user.dashboard') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                                <i class="ri-dashboard-line"></i>
                                <span>Tableau de Bord</span>
                            </a>
                        @elseif(auth()->user()->hasRole('company'))
                            <a href="{{ route('company.dashboard') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                                <i class="ri-building-line"></i>
                                <span>Mon Entreprise</span>
                            </a>
                        @elseif(auth()->user()->hasRole('main_admin'))
                            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                                <i class="ri-shield-user-line"></i>
                                <span>Administration</span>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="pt-3 border-t border-gray-200/50 dark:border-gray-700/50 mt-3 space-y-2">
                        <a href="{{ route('login') }}" class="mobile-nav-link-premium" @click="mobileMenuOpen = false">
                            <i class="ri-login-box-line"></i>
                            <span>Se connecter</span>
                        </a>
                        <a href="{{ route('register') }}" class="block w-full px-4 py-3 text-center text-sm font-bold text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-xl shadow-lg">
                            S'inscrire
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Spacer to prevent content from going under fixed header -->
<div class="h-16 sm:h-20"></div>

<style>
    /* Premium Navigation Link Styles */
    .nav-link-premium {
        @apply relative px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-300 flex items-center;
        @apply hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/20;
    }
    
    .nav-link-premium .nav-link-icon {
        @apply mr-2 text-base transition-transform duration-300;
    }
    
    .nav-link-premium:hover .nav-link-icon {
        @apply scale-110;
    }
    
    .nav-link-premium .nav-link-text {
        @apply relative z-10;
    }
    
    .nav-link-premium .nav-link-underline {
        @apply absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-300;
    }
    
    .nav-link-premium:hover .nav-link-underline {
        @apply w-3/4;
    }
    
    .nav-link-active {
        @apply text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/20;
    }
    
    .nav-link-active .nav-link-underline {
        @apply w-full;
    }
    
    .nav-link-admin {
        @apply bg-gradient-to-r from-red-50/50 to-orange-50/50 dark:from-red-900/20 dark:to-orange-900/20;
    }
    
    /* Premium Dropdown Item Styles */
    .dropdown-item-premium {
        @apply block px-4 py-3 mx-2 my-1 rounded-xl transition-all duration-300;
        @apply hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50/50 dark:hover:from-gray-700/50 dark:hover:to-blue-900/20;
        @apply hover:shadow-lg hover:-translate-y-0.5;
    }
    
    /* Premium User Menu Item Styles */
    .user-menu-item-premium {
        @apply flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 transition-all duration-300;
        @apply hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50/50 dark:hover:from-gray-700/50 dark:hover:to-blue-900/20;
        @apply hover:text-blue-600 dark:hover:text-blue-400 hover:translate-x-1;
    }
    
    .user-menu-item-premium i {
        @apply mr-3 w-5 text-center text-lg;
    }
    
    .user-menu-item-danger {
        @apply hover:from-red-50 hover:to-red-50 dark:hover:from-red-900/20 dark:hover:to-red-900/20;
        @apply hover:text-red-600 dark:hover:text-red-400;
    }
    
    /* Premium Mobile Nav Link Styles */
    .mobile-nav-link-premium {
        @apply flex items-center px-4 py-3 text-base font-semibold text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-300;
        @apply hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50/50 dark:hover:from-gray-800 dark:hover:to-blue-900/20;
        @apply hover:text-blue-600 dark:hover:text-blue-400 hover:translate-x-2;
    }
    
    .mobile-nav-link-premium i {
        @apply mr-3 w-6 text-center text-xl;
    }
    
    .mobile-nav-link-active {
        @apply bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-600 dark:text-blue-400;
    }
    
    /* Header Scroll Effect */
    .nav-header {
        will-change: transform, background-color, box-shadow;
    }
    
    .nav-scrolled {
        backdrop-filter: blur(20px);
    }
    
    /* Smooth transitions */
    * {
        scroll-margin-top: 5rem;
    }
    
    /* Animation for icons */
    @keyframes icon-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-2px); }
    }
    
    .nav-link-premium:hover .nav-link-icon {
        animation: icon-bounce 0.6s ease-in-out;
    }
</style>
