@extends('layouts.app')

@section('meta_title', 'Inscription | I.M System')
@section('meta_description', 'Créez votre compte I.M System pour accéder à tous les services de la plateforme.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-indigo-100">
                <i class="ri-user-add-line text-indigo-600 text-2xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Créer un compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    connectez-vous à votre compte existant
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="space-y-4">
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de compte</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="role" value="user" class="sr-only" {{ old('role') == 'user' ? 'checked' : '' }}>
                            <div class="flex items-center">
                                <i class="ri-user-line text-2xl text-indigo-600 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Particulier</div>
                                    <div class="text-sm text-gray-500">Porteur de projet, auto-entrepreneur</div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="role" value="company" class="sr-only" {{ old('role') == 'company' ? 'checked' : '' }}>
                            <div class="flex items-center">
                                <i class="ri-building-line text-2xl text-indigo-600 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Entreprise</div>
                                    <div class="text-sm text-gray-500">Investisseur, partenaire</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input id="first_name" name="first_name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('first_name') border-red-500 @enderror" 
                               placeholder="Prénom" value="{{ old('first_name') }}">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input id="last_name" name="last_name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('last_name') border-red-500 @enderror" 
                               placeholder="Nom" value="{{ old('last_name') }}">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror" 
                           placeholder="Adresse email" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                    <input id="username" name="username" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('username') border-red-500 @enderror" 
                           placeholder="Nom d'utilisateur" value="{{ old('username') }}">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input id="phone" name="phone" type="tel" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror" 
                           placeholder="+212 6XX XXX XXX" value="{{ old('phone') }}">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror" 
                           placeholder="Mot de passe">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="Confirmer le mot de passe">
                </div>

                <!-- Address Information -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                    <input id="address" name="address" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('address') border-red-500 @enderror" 
                           placeholder="Adresse complète" value="{{ old('address') }}">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                        <input id="city" name="city" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('city') border-red-500 @enderror" 
                               placeholder="Ville" value="{{ old('city') }}">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700">Région</label>
                        <select id="region" name="region" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('region') border-red-500 @enderror">
                            <option value="">Sélectionner une région</option>
                            <option value="Casablanca-Settat" {{ old('region') == 'Casablanca-Settat' ? 'selected' : '' }}>Casablanca-Settat</option>
                            <option value="Rabat-Salé-Kénitra" {{ old('region') == 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>Rabat-Salé-Kénitra</option>
                            <option value="Fès-Meknès" {{ old('region') == 'Fès-Meknès' ? 'selected' : '' }}>Fès-Meknès</option>
                            <option value="Marrakech-Safi" {{ old('region') == 'Marrakech-Safi' ? 'selected' : '' }}>Marrakech-Safi</option>
                            <option value="Tanger-Tétouan-Al Hoceïma" {{ old('region') == 'Tanger-Tétouan-Al Hoceïma' ? 'selected' : '' }}>Tanger-Tétouan-Al Hoceïma</option>
                            <option value="Oriental" {{ old('region') == 'Oriental' ? 'selected' : '' }}>Oriental</option>
                            <option value="Béni Mellal-Khénifra" {{ old('region') == 'Béni Mellal-Khénifra' ? 'selected' : '' }}>Béni Mellal-Khénifra</option>
                            <option value="Souss-Massa" {{ old('region') == 'Souss-Massa' ? 'selected' : '' }}>Souss-Massa</option>
                            <option value="Guelmim-Oued Noun" {{ old('region') == 'Guelmim-Oued Noun' ? 'selected' : '' }}>Guelmim-Oued Noun</option>
                            <option value="Laâyoune-Sakia El Hamra" {{ old('region') == 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>Laâyoune-Sakia El Hamra</option>
                            <option value="Dakhla-Oued Ed-Dahab" {{ old('region') == 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>Dakhla-Oued Ed-Dahab</option>
                            <option value="Drâa-Tafilalet" {{ old('region') == 'Drâa-Tafilalet' ? 'selected' : '' }}>Drâa-Tafilalet</option>
                        </select>
                        @error('region')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="ri-user-add-line text-indigo-500 group-hover:text-indigo-400" aria-hidden="true"></i>
                    </span>
                    Créer le compte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection






























