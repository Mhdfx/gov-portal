@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'CV Management')
@section('profile-route', route('candidate.profile'))
@section('settings-route', route('candidate.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('candidate.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('candidate.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    <a href="{{ route('candidate.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Search
    </a>
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Applications
    </a>
    <a href="{{ route('candidate.cv') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-paper-line text-xl mr-3"></i>
        CV Management
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Back to Site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">CV & Documents</h1>
            <p class="mt-2 text-gray-600">Upload your latest CV and supporting documents to apply faster.</p>
        </div>
        @if(session('success'))
            <div class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Current CV</h2>
            @if($candidate->cv_file_path)
                <p class="text-sm text-gray-600 mb-4">Uploaded on {{ optional($candidate->updated_at)->format('M d, Y') }}</p>
                <a href="{{ asset('storage/' . $candidate->cv_file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="ri-file-text-line mr-2"></i>
                    View CV
                </a>
            @else
                <p class="text-sm text-gray-500">You haven't uploaded a CV yet.</p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Cover Letter</h2>
            @if($candidate->cover_letter_path)
                <p class="text-sm text-gray-600 mb-4">Latest version uploaded.</p>
                <a href="{{ asset('storage/' . $candidate->cover_letter_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="ri-file-list-3-line mr-2"></i>
                    View Cover Letter
                </a>
            @else
                <p class="text-sm text-gray-500">Upload a cover letter to speed up your job applications.</p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Summary</h2>
            <p class="text-sm text-gray-600">{{ $candidate->professional_summary ?? 'Add a summary to highlight your strengths.' }}</p>
        </div>
    </div>

    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Update Documents</h2>
        <form method="POST" action="{{ route('candidate.cv.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload New CV</label>
                <input type="file" name="cv_file" accept=".pdf,.doc,.docx" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">PDF, DOC up to 10MB</p>
                @error('cv_file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Cover Letter</label>
                <input type="file" name="cover_letter" accept=".pdf,.doc,.docx" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('cover_letter')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Professional Summary</label>
                <textarea name="professional_summary" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('professional_summary', $candidate->professional_summary) }}</textarea>
                @error('professional_summary')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('candidate.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Updates</button>
            </div>
        </form>
    </div>
</div>
@endsection








