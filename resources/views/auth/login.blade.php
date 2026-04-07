<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Boiema Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Boiema</h1>
            <p class="text-gray-600">Platform for Economic Development</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sign In</h2>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="ri-error-warning-line text-xl mr-2"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-user-line text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            value="{{ old('username') }}"
                            required 
                            autofocus
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('username') border-red-500 @enderror"
                            placeholder="Enter your username"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-lock-line text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="Enter your password"
                        >
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    <span class="flex items-center justify-center">
                        <i class="ri-login-box-line mr-2"></i>
                        Sign In
                    </span>
                </button>
            </form>

            <!-- Additional Links -->
            <div class="mt-6 space-y-3">
                <a href="{{ route('home') }}" class="w-full inline-block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition transform hover:scale-[1.02] active:scale-[0.98]">
                    <i class="ri-home-line mr-2"></i> Back to Home
                </a>
            </div>
        </div>

        <!-- Test Credentials -->
        <div class="mt-6 bg-white/50 backdrop-blur-sm rounded-lg p-6 text-sm text-gray-600">
            <h3 class="font-semibold text-gray-800 mb-4 text-center">Test Accounts - All Roles</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Main Admin -->
                <div class="bg-gradient-to-r from-red-50 to-red-100 p-3 rounded-lg border border-red-200">
                    <div class="flex items-center mb-2">
                        <i class="ri-shield-user-line text-red-600 text-lg mr-2"></i>
                        <span class="font-medium text-red-800">Main Admin</span>
                    </div>
                    <p class="text-xs text-red-700 mb-1">Full system access</p>
                    <p class="text-xs"><span class="font-mono bg-red-200 px-1 rounded">admin</span> / <span class="font-mono bg-red-200 px-1 rounded">password</span></p>
                </div>

                <!-- Institutional Admin -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-3 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-2">
                        <i class="ri-government-line text-blue-600 text-lg mr-2"></i>
                        <span class="font-medium text-blue-800">Institutional Admin</span>
                    </div>
                    <p class="text-xs text-blue-700 mb-1">Institution management</p>
                    <p class="text-xs"><span class="font-mono bg-blue-200 px-1 rounded">institutional_admin</span> / <span class="font-mono bg-blue-200 px-1 rounded">password</span></p>
                </div>

                <!-- Sectoral Admin -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-3 rounded-lg border border-green-200">
                    <div class="flex items-center mb-2">
                        <i class="ri-folder-chart-line text-green-600 text-lg mr-2"></i>
                        <span class="font-medium text-green-800">Sectoral Admin</span>
                    </div>
                    <p class="text-xs text-green-700 mb-1">Sector analysis & reports</p>
                    <p class="text-xs"><span class="font-mono bg-green-200 px-1 rounded">sectoral_admin</span> / <span class="font-mono bg-green-200 px-1 rounded">password</span></p>
                </div>

                <!-- Company -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-3 rounded-lg border border-purple-200">
                    <div class="flex items-center mb-2">
                        <i class="ri-building-line text-purple-600 text-lg mr-2"></i>
                        <span class="font-medium text-purple-800">Company</span>
                    </div>
                    <p class="text-xs text-purple-700 mb-1">Business management</p>
                    <p class="text-xs"><span class="font-mono bg-purple-200 px-1 rounded">testcompany</span> / <span class="font-mono bg-purple-200 px-1 rounded">password</span></p>
                </div>

                <!-- Regular User -->
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-3 rounded-lg border border-indigo-200">
                    <div class="flex items-center mb-2">
                        <i class="ri-user-line text-indigo-600 text-lg mr-2"></i>
                        <span class="font-medium text-indigo-800">Regular User</span>
                    </div>
                    <p class="text-xs text-indigo-700 mb-1">Form submissions & tracking</p>
                    <p class="text-xs"><span class="font-mono bg-indigo-200 px-1 rounded">testuser</span> / <span class="font-mono bg-indigo-200 px-1 rounded">password</span></p>
                </div>

                <!-- Candidate -->
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-3 rounded-lg border border-orange-200">
                    <div class="flex items-center mb-2">
                        <i class="ri-user-star-line text-orange-600 text-lg mr-2"></i>
                        <span class="font-medium text-orange-800">Candidate</span>
                    </div>
                    <p class="text-xs text-orange-700 mb-1">Job applications & profile</p>
                    <p class="text-xs"><span class="font-mono bg-orange-200 px-1 rounded">testcandidate</span> / <span class="font-mono bg-orange-200 px-1 rounded">password</span></p>
                </div>
            </div>

            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                    <i class="ri-information-line text-yellow-600 mr-2"></i>
                    <p class="text-xs text-yellow-800">
                        <strong>Note:</strong> All accounts use the same password: <code class="bg-yellow-200 px-1 rounded">password</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

