<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        use Artesaos\SEOTools\Facades\SEOTools;
    @endphp
    
    {!! SEOTools::generate() !!}
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen"
             x-cloak
             @click.away="sidebarOpen = false"
             class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl lg:hidden"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            <div class="flex h-full flex-col">
                <!-- Mobile sidebar header -->
                <div class="flex items-center justify-between h-16 px-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <span class="text-xl font-bold text-white">@yield('dashboard-name', 'Dashboard')</span>
                    <button @click="sidebarOpen = false" class="text-white hover:text-gray-200">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                
                <!-- Mobile sidebar content -->
                <div class="flex-1 overflow-y-auto">
                    @yield('sidebar')
                </div>
            </div>
        </div>

        <!-- Desktop sidebar -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
            <div class="flex flex-col flex-1 min-h-0 bg-white shadow-xl">
                <!-- Desktop sidebar header -->
                <div class="flex items-center h-16 px-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <i class="@yield('dashboard-icon', 'ri-dashboard-line') text-white text-2xl mr-3"></i>
                    <span class="text-xl font-bold text-white">@yield('dashboard-name', 'Dashboard')</span>
                </div>
                
                <!-- Desktop sidebar content -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    @yield('sidebar')
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex flex-col flex-1 lg:pl-64">
            <!-- Top navigation bar -->
            <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow">
                <button @click="sidebarOpen = true" class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden">
                    <i class="ri-menu-line text-2xl"></i>
                </button>
                
                <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-1 items-center">
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500">
                                <i class="ri-notification-3-line text-2xl"></i>
                                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                                @endif
                            </button>
                            
                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                                 x-transition>
                                <div class="p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Notifications</h3>
                                    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                                        <div class="space-y-2 max-h-96 overflow-y-auto">
                                            @foreach($recentNotifications as $notification)
                                            <div class="p-2 hover:bg-gray-50 rounded">
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">Aucune notification</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- User menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->username, 0, 2) }}</span>
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->username }}</span>
                                <i class="ri-arrow-down-s-line text-gray-400"></i>
                            </button>
                            
                            <div x-show="open"
                                 x-cloak
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                                 x-transition>
                                <div class="py-1">
                                    <a href="@yield('profile-route', '#')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="ri-user-line mr-2"></i>Mon Profil
                                    </a>
                                    <a href="@yield('settings-route', '#')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="ri-settings-3-line mr-2"></i>Paramètres
                                    </a>
                                    <hr class="my-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            <i class="ri-logout-box-line mr-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto">
                <!-- Flash messages -->
                @if(session('success'))
                <div class="m-4 rounded-lg bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <i class="ri-checkbox-circle-line text-green-400 text-xl mr-3"></i>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="m-4 rounded-lg bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <i class="ri-error-warning-line text-red-400 text-xl mr-3"></i>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                
                @if($errors->any())
                <div class="m-4 rounded-lg bg-red-50 p-4 border border-red-200">
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
                
                <!-- Main content -->
                <div class="py-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>






























