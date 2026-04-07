@extends('layouts.app')

@section('meta_title', 'Newsletter Subscription | I.M System')
@section('meta_description', 'Subscribe to our newsletter to stay updated with the latest news and updates from I.M System.')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Newsletter Subscription</h1>
            <p class="text-gray-600">Stay updated with the latest news, updates, and opportunities from the I.M System</p>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('newsletter.subscribe') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}" placeholder="your.email@example.com">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name (Optional)</label>
                    <input type="text" name="name" id="name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}" placeholder="Your Name">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <div class="flex items-start">
                        <input type="checkbox" name="privacy_policy" id="privacy_policy" required
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="privacy_policy" class="ml-2 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-900 underline">Privacy Policy</a> 
                            and consent to receiving newsletter emails from I.M System.
                        </label>
                    </div>
                    @error('privacy_policy')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 font-medium transition-colors">
                        <i class="ri-mail-line mr-2"></i>Subscribe to Newsletter
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">What you'll receive:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="ri-check-line text-green-600 mr-2"></i>
                            Latest government opportunities
                        </div>
                        <div class="flex items-center">
                            <i class="ri-check-line text-green-600 mr-2"></i>
                            Job openings and career updates
                        </div>
                        <div class="flex items-center">
                            <i class="ri-check-line text-green-600 mr-2"></i>
                            Platform feature announcements
                        </div>
                        <div class="flex items-center">
                            <i class="ri-check-line text-green-600 mr-2"></i>
                            Success stories and testimonials
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                Already subscribed? 
                <a href="{{ route('newsletter.unsubscribe') }}" class="text-blue-600 hover:text-blue-900 underline">
                    Unsubscribe here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection






























