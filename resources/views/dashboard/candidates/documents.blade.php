@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'My Documents')
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
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        My Applications
    </a>
    <a href="{{ route('candidate.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Search
    </a>
    <a href="{{ route('candidate.documents') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-folder-line text-xl mr-3"></i>
        My Documents
    </a>
    <a href="{{ route('candidate.cv') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-paper-line text-xl mr-3"></i>
        CV Management
    </a>
    <a href="{{ route('candidate.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Settings
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
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Documents</h1>
            <p class="mt-2 text-gray-600">Keep your portfolio organized and ready for quick applications.</p>
        </div>
        <a href="#upload-form" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="ri-upload-cloud-2-line text-lg mr-2"></i>
            Upload Document
        </a>
    </div>

    <!-- Upload Form -->
    <div id="upload-form" class="bg-white shadow rounded-2xl p-6 mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Add a new document</h2>
                <p class="text-sm text-gray-500">Upload certificates, cover letters, portfolios, and more.</p>
            </div>
        </div>
        <form action="{{ route('candidate.documents.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document</label>
                <input type="file" name="document" required class="w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-xs text-gray-500">Accepted: PDF, DOC, DOCX, JPG, PNG (max 10MB)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="document_type" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select type</option>
                    @foreach($documentTypes as $type => $label)
                        <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional context for this file"></textarea>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    <i class="ri-upload-2-line text-lg mr-2"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-folder-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Documents</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        @foreach($documentTypes as $type => $label)
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="ri-file-line text-gray-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ $label }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['by_type'][$type] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Filename or description">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                <select name="type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    @foreach($documentTypes as $type => $label)
                        <option value="{{ $type }}" @selected(request('type') === $type)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="ri-search-line text-lg mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('candidate.documents') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Documents List -->
    @if($documents->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($documents as $document)
                <div class="bg-white shadow rounded-lg p-5 flex flex-col space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $document->original_name ?? $document->file_name }}</p>
                            <p class="text-xs text-gray-500">{{ $documentTypes[$document->file_type] ?? ucfirst($document->file_type ?? 'document') }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ strtoupper(pathinfo($document->original_name ?? $document->file_name, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 line-clamp-2">{{ $document->description ?? 'No description provided.' }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $document->file_size_human }}</span>
                        <span>{{ optional($document->created_at)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ $document->download_url }}" target="_blank" class="flex-1 inline-flex items-center justify-center border border-gray-200 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 py-2">
                            <i class="ri-eye-line text-lg mr-2"></i>
                            View
                        </a>
                        <form method="POST" action="{{ route('candidate.documents.destroy', $document) }}" class="flex-1" onsubmit="return confirm('Delete this document?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center border border-red-100 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 py-2">
                                <i class="ri-delete-bin-2-line text-lg mr-2"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $documents->links() }}
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                <i class="ri-folder-info-line text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No documents yet</h3>
            <p class="text-gray-600 mb-6">Upload your certificates, cover letters, portfolios and more to speed up future applications.</p>
            <a href="#upload-form" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Upload your first document
            </a>
        </div>
    @endif
</div>
@endsection

