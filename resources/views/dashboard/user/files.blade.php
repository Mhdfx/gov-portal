@extends('layouts.dashboard')

@section('dashboard-name', 'User Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'My Files')
@section('profile-route', route('user.profile'))
@section('settings-route', route('user.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('user.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    
    <a href="{{ route('user.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        My Submissions
    </a>
    
    <a href="{{ route('user.files') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-folder-line text-xl mr-3"></i>
        My Files
    </a>
    
    <a href="{{ route('user.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    
    <a href="{{ route('user.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Files</h1>
            <p class="mt-2 text-gray-600">Manage your uploaded documents and files</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-folder-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $files->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-pdf-line text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">PDF Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $files->filter(fn($f) => str_ends_with(strtolower($f->file_path ?? ''), '.pdf'))->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-word-line text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Document Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $files->filter(fn($f) => in_array(strtolower(pathinfo($f->file_path ?? '', PATHINFO_EXTENSION)), ['doc', 'docx']))->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-image-line text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Image Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $files->filter(fn($f) => in_array(strtolower(pathinfo($f->file_path ?? '', PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Files List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">My Files ({{ $files->count() }})</h3>
        </div>
        
        @if($files->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($files as $file)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                @php
                                    $extension = strtolower(pathinfo($file->file_path ?? '', PATHINFO_EXTENSION));
                                    $icon = 'ri-file-line';
                                    $color = 'text-gray-600';
                                    if (in_array($extension, ['pdf'])) {
                                        $icon = 'ri-file-pdf-line';
                                        $color = 'text-red-600';
                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                        $icon = 'ri-file-word-line';
                                        $color = 'text-blue-600';
                                    } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        $icon = 'ri-file-image-line';
                                        $color = 'text-green-600';
                                    } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                        $icon = 'ri-file-excel-line';
                                        $color = 'text-green-600';
                                    }
                                @endphp
                                <i class="{{ $icon }} {{ $color }} text-2xl"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $file->original_name ?? basename($file->file_path ?? 'Unknown') }}
                                    </h4>
                                    @if($file->file_size)
                                    <span class="text-xs text-gray-500">
                                        ({{ number_format($file->file_size / 1024, 2) }} KB)
                                    </span>
                                    @endif
                                </div>
                                <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                    <span>
                                        <i class="ri-calendar-line mr-1"></i>
                                        Uploaded {{ $file->created_at->diffForHumans() }}
                                    </span>
                                    @if($file->file_type)
                                    <span>
                                        <i class="ri-file-info-line mr-1"></i>
                                        {{ ucfirst($file->file_type) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 ml-4">
                            @if($file->file_path)
                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50" 
                               title="View">
                                <i class="ri-eye-line text-xl"></i>
                            </a>
                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                               download
                               class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50" 
                               title="Download">
                                <i class="ri-download-line text-xl"></i>
                            </a>
                            @endif
                            <button onclick="confirmDelete({{ $file->id }})" 
                                    class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50" 
                                    title="Delete">
                                <i class="ri-delete-bin-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-folder-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No files uploaded</h3>
                <p class="text-gray-500 mb-6">
                    You haven't uploaded any files yet. Files will appear here once you upload them through form submissions.
                </p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(fileId) {
    if (confirm('Are you sure you want to delete this file? This action cannot be undone.')) {
        // TODO: Implement delete functionality
        console.log('Delete file:', fileId);
    }
}
</script>
@endpush
@endsection
