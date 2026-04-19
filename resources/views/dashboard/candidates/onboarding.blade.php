@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Portal')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Welcome to Boiema')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] px-4 py-12 text-center">
    <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-8 animate-bounce">
        <i class="ri-user-add-line text-5xl"></i>
    </div>
    
    <h1 class="text-4xl font-extrabold text-gray-900 mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
        Ready to start your journey?
    </h1>
    
    <p class="text-xl text-gray-600 max-w-2xl mb-10 leading-relaxed">
        Welcome to the Boiema Platform! To start applying for jobs and connecting with top companies, you first need to complete your candidate profile.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mb-12 text-left">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                <i class="ri-file-user-line text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Build Your CV</h3>
            <p class="text-sm text-gray-500">Showcase your skills, experience, and education to potential employers.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                <i class="ri-search-eye-line text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Get Noticed</h3>
            <p class="text-sm text-gray-500">Our smart matching system connects your profile with relevant job listings.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-4">
                <i class="ri-rocket-line text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Apply Fast</h3>
            <p class="text-sm text-gray-500">Apply to multiple jobs with one click once your profile is set up.</p>
        </div>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('candidate.register') }}" class="px-8 py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transform hover:-translate-y-1 transition-all shadow-lg shadow-blue-200">
            Complete My Profile Now
        </a>
        <a href="{{ route('home') }}" class="px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-xl font-bold hover:bg-gray-50 transition-all">
            Back to Home
        </a>
    </div>
    
    <p class="mt-8 text-sm text-gray-400">
        Takes less than 5 minutes to complete.
    </p>
</div>
@endsection
