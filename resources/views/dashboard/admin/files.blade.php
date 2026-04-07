@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'File Management')
@section('profile-route', route('admin.profile'))
@section('settings-route', route('admin.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('admin.analytics') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-2-line text-xl mr-3"></i>
        Analytics
    </a>
    <a href="{{ route('admin.users') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Users
    </a>
    <a href="{{ route('admin.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Companies
    </a>
    <a href="{{ route('admin.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('admin.files.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-folder-line text-xl mr-3"></i>
        Files
    </a>
    <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-box-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('admin.notifications.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    <a href="{{ route('admin.security.audit-log') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shield-check-line text-xl mr-3"></i>
        Security
    </a>
    <a href="{{ route('admin.logs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-text-line text-xl mr-3"></i>
        Logs
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Back to site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Storage</p>
            <h1 class="text-3xl font-bold text-gray-900">File Management</h1>
            <p class="mt-2 text-gray-600">Monitor, download, and manage all uploaded files across the platform.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                {{ $filters['sort'] === 'latest' ? 'Sort: Latest' : 'Sort: ' . ucfirst($filters['sort']) }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                Showing {{ $files->count() }} / {{ number_format($files->total()) }} files
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ri-folder-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total files</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ri-database-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total storage</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        @php
                            $totalSize = $stats['total_size'];
                            if ($totalSize >= 1073741824) {
                                echo number_format($totalSize / 1073741824, 2) . ' GB';
                            } elseif ($totalSize >= 1048576) {
                                echo number_format($totalSize / 1048576, 2) . ' MB';
                            } elseif ($totalSize >= 1024) {
                                echo number_format($totalSize / 1024, 2) . ' KB';
                            } else {
                                echo $totalSize . ' B';
                            }
                        @endphp
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-pdf-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">CV / Resume</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['by_type']['cv'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                    <i class="ri-file-text-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Business Plans</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['by_type']['business_plan'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-2xl p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="search">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="File name, user, or description"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:ring-blue-500 focus:border-blue-500">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="upload_type">Type</label>
                <select id="upload_type" name="upload_type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All types</option>
                    @foreach($uploadTypes as $value => $label)
                        <option value="{{ $value }}" @selected($filters['upload_type'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="sort">Sort by</label>
                <select id="sort" name="sort" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" @selected($filters['sort'] === 'latest')>Latest</option>
                    <option value="name" @selected($filters['sort'] === 'name')>Name</option>
                    <option value="size" @selected($filters['sort'] === 'size')>Size</option>
                    <option value="type" @selected($filters['sort'] === 'type')>Type</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date range</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="md:col-span-3 flex items-end gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    <i class="ri-filter-3-line text-lg mr-2"></i> Apply filters
                </button>
                <a href="{{ route('admin.files.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Files table -->
    <div class="bg-white shadow rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Files</h2>
                <p class="text-sm text-gray-500">Latest {{ $files->count() }} records in this view</p>
            </div>
            <span class="text-sm text-gray-500">Page {{ $files->currentPage() }} of {{ $files->lastPage() }}</span>
        </div>

        @if($files->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @foreach($files as $file)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                            @if(str_contains(strtolower($file->mime_type ?? ''), 'pdf'))
                                                <i class="ri-file-pdf-line text-red-600 text-xl"></i>
                                            @elseif(str_contains(strtolower($file->mime_type ?? ''), 'image'))
                                                <i class="ri-image-line text-blue-600 text-xl"></i>
                                            @elseif(str_contains(strtolower($file->mime_type ?? ''), 'word') || str_contains($file->file_type ?? '', 'doc'))
                                                <i class="ri-file-word-line text-blue-600 text-xl"></i>
                                            @else
                                                <i class="ri-file-line text-gray-600 text-xl"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $file->original_name }}</p>
                                            @if($file->description)
                                                <p class="text-xs text-gray-500">{{ Str::limit($file->description, 40) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $file->user->username ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $file->user->email ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $uploadTypes[$file->upload_type] ?? ucfirst(str_replace('_', ' ', $file->upload_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $file->file_size_human }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <p>{{ $file->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $file->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="inline-flex items-center gap-2">
                                        @if($file->download_url)
                                            <a href="{{ $file->download_url }}" target="_blank" class="text-blue-600 hover:text-blue-900 inline-flex items-center text-xs font-semibold" title="Download file">
                                                <i class="ri-download-line text-lg mr-1"></i> Download
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">File not found</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $files->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-folder-search-line text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No files match your filters</h3>
                <p class="text-gray-500 mb-6">Try broadening the filters or searching with different keywords.</p>
                <a href="{{ route('admin.files.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700">
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

