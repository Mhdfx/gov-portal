@extends('layouts.dashboard')

@section('dashboard-name', 'Company Dashboard')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Company Documents')
@section('profile-route', route('company.profile'))
@section('settings-route', route('company.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>

    <a href="{{ route('company.products') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-bag-line text-xl mr-3"></i>
        Products
    </a>

    <a href="{{ route('company.orders') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-cart-line text-xl mr-3"></i>
        Orders
    </a>

    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Listings
    </a>

    <a href="{{ route('company.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Applications
    </a>

    <a href="{{ route('company.documents') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-folder-shield-2-line text-xl mr-3"></i>
        Documents
    </a>

    <a href="{{ route('company.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Company Documents</h1>
            <p class="mt-2 text-gray-600">Upload and manage the official documents required to operate on the platform.</p>
        </div>
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Total Documents</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_documents'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved_documents'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_documents'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Rejected</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['rejected_documents'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Storage Used</p>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['storage_used'] / 1048576, 2) }} MB</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('company.documents') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Document name"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach($documentTypes as $key => $label)
                        <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">
                    Filter
                </button>
                <a href="{{ route('company.documents') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 w-full text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Documents ({{ $documents->total() }})</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($documents as $document)
                    <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h4 class="text-base font-semibold text-gray-900">{{ $document->document_name }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $documentTypes[$document->document_type] ?? ucfirst($document->document_type) }}
                                </span>
                            </div>
                            <div class="mt-2 text-sm text-gray-500 flex flex-wrap gap-4">
                                <span><i class="ri-file-line mr-1"></i>{{ strtoupper($document->file_type) }} • {{ $document->file_size_human ?? number_format($document->file_size / 1024, 2) . ' KB' }}</span>
                                <span><i class="ri-calendar-line mr-1"></i>Uploaded {{ $document->created_at->format('M d, Y') }}</span>
                                <span><i class="ri-shield-check-line mr-1"></i>{{ ucfirst($document->status) }}</span>
                            </div>
                            @if($document->admin_notes)
                                <p class="mt-2 text-xs text-yellow-700 bg-yellow-50 border border-yellow-100 rounded px-3 py-2">
                                    Admin Notes: {{ $document->admin_notes }}
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                <i class="ri-eye-line"></i> View
                            </a>
                            <form action="{{ route('company.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 flex items-center gap-2">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="ri-folder-close-line text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">No documents uploaded yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $documents->links() }}
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Document</h3>
            <form method="POST" action="{{ route('company.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Type *</label>
                    <select name="document_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select type</option>
                        @foreach($documentTypes as $key => $label)
                            <option value="{{ $key }}" @selected(old('document_type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('document_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Name *</label>
                    <input type="text" name="document_name" value="{{ old('document_name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('document_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File *</label>
                    <input type="file" name="file" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, PNG, DOC (max 10MB)</p>
                    @error('file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Upload Document
                </button>
            </form>
        </div>
    </div>
</div>
@endsection








