<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Logo and Description -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Boiema Platform" onerror="this.style.display='none'">
                    <span class="ml-2 text-xl font-bold text-gray-900">Boiema Platform</span>
                </div>
                <p class="text-gray-600 text-sm max-w-md">
                    Plateforme numérique pour la mise en relation des porteurs de projets, auto-entrepreneurs, investisseurs, entreprises et institutions publiques au Maroc.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Liens Rapides</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Accueil</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Blog</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Emplois</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">À Propos</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Contact</a></li>
                    @guest
                        <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">S'inscrire</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Se Connecter</a></li>
                    @endguest
                </ul>
            </div>
            
            <!-- Services -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Services</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('forms.investment') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Investissement</a></li>
                    <li><a href="{{ route('forms.project-carrier') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Porteur de Projet</a></li>
                    <li><a href="{{ route('forms.idea-carrier') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Porteur d'Idée</a></li>
                    <li><a href="{{ route('forms.auto-entrepreneur') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Auto-Entrepreneur</a></li>
                    <li><a href="{{ route('forms.indh') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Projet INDH</a></li>
                    <li><a href="{{ route('forms.training') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Formation</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Contact</h3>
                <ul class="space-y-2">
                    <li class="text-gray-600 text-sm">
                        <i class="ri-mail-line mr-2"></i>
                        <a href="mailto:contact@boiema.gov.ma" class="hover:text-gray-900 transition-colors duration-200">contact@boiema.gov.ma</a>
                    </li>
                    <li class="text-gray-600 text-sm">
                        <i class="ri-phone-line mr-2"></i>
                        <a href="tel:+212XXXXXXXXX" class="hover:text-gray-900 transition-colors duration-200">+212 XX XXX XXXX</a>
                    </li>
                    <li class="text-gray-600 text-sm">
                        <i class="ri-map-pin-line mr-2"></i>
                        Rabat, Maroc
                    </li>
                    <li class="text-gray-600 text-sm mt-4">
                        <a href="{{ route('newsletter.subscribe') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors duration-200">
                            <i class="ri-mail-add-line mr-2"></i>S'abonner à la Newsletter
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Section -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-600 text-sm">
                    © {{ date('Y') }} Boiema Platform. Tous droits réservés.
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-4">
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">À Propos</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Contact</a>
                    <a href="{{ route('search') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Recherche</a>
                    <span class="text-gray-400">|</span>
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Mentions Légales</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Politique de Confidentialité</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm transition-colors duration-200">Conditions d'Utilisation</a>
                </div>
            </div>
        </div>
    </div>
</footer>



























