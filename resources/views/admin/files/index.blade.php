@extends('layouts.app')

@section('meta_title', 'File Management | Admin Dashboard')
@section('meta_description', 'Manage all uploaded files and documents on the I.M System.')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">File Management</h1>
        <div class="flex space-x-4">
            <button onclick="showStatistics()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                <i class="ri-bar-chart-line mr-2"></i>Statistics
            </button>
            <button onclick="bulkDownload()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="ri-download-line mr-2"></i>Bulk Download
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="ri-file-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $files->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="ri-folder-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">File Types</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fileTypes->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="ri-user-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="ri-database-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Submissions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $submissionTypes->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.files.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by filename..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach($fileTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ strtoupper($type) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Submission Type</label>
                <select name="submission_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Submissions</option>
                    @foreach($submissionTypes as $type)
                        <option value="{{ $type }}" {{ request('submission_type') == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-6 flex justify-end space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    <i class="ri-search-line mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.files.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                    <i class="ri-refresh-line mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Files Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Uploaded Files</h3>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Select All</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAllHeader" class="form-checkbox h-4 w-4 text-blue-600">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($files as $file)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="file_ids[]" value="{{ $file->id }}" class="file-checkbox form-checkbox h-4 w-4 text-blue-600">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($file->file_type === 'pdf')
                                            <div class="h-10 w-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-file-pdf-line text-red-600 text-xl"></i>
                                            </div>
                                        @elseif(in_array($file->file_type, ['jpg', 'jpeg', 'png', 'gif']))
                                            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-image-line text-green-600 text-xl"></i>
                                            </div>
                                        @elseif(in_array($file->file_type, ['doc', 'docx']))
                                            <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-file-word-line text-blue-600 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-file-line text-gray-600 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $file->original_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $file->file_path }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ strtoupper($file->file_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($file->file_size / 1024, 2) }} KB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $file->user->username ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst(str_replace('_', ' ', $file->submission_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $file->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.files.preview', $file->id) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Preview">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.files.download', $file->id) }}" 
                                       class="text-green-600 hover:text-green-900" title="Download">
                                        <i class="ri-download-line"></i>
                                    </a>
                                    <button onclick="deleteFile({{ $file->id }})" 
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No files found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $files->links() }}
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div id="statisticsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-96 overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">File Statistics</h3>
                <button onclick="closeStatistics()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <div class="p-6" id="statisticsContent">
                <!-- Statistics content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Delete File</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600">Are you sure you want to delete this file? This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-4">
                <button onclick="closeDeleteModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Select all functionality
document.getElementById('selectAllHeader').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.file-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    document.getElementById('selectAll').checked = this.checked;
});

document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.file-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    document.getElementById('selectAllHeader').checked = this.checked;
});

// Statistics modal
function showStatistics() {
    fetch('{{ route("admin.files.statistics") }}')
        .then(response => response.json())
        .then(data => {
            let content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Files by Type</h4>
                        <div class="space-y-2">
            `;
            
            Object.entries(data.files_by_type).forEach(([type, count]) => {
                content += `<div class="flex justify-between"><span>${type.toUpperCase()}</span><span class="font-medium">${count}</span></div>`;
            });
            
            content += `
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Files by Submission</h4>
                        <div class="space-y-2">
            `;
            
            Object.entries(data.files_by_submission).forEach(([type, count]) => {
                content += `<div class="flex justify-between"><span>${type.replace('_', ' ')}</span><span class="font-medium">${count}</span></div>`;
            });
            
            content += `
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('statisticsContent').innerHTML = content;
            document.getElementById('statisticsModal').classList.remove('hidden');
        });
}

function closeStatistics() {
    document.getElementById('statisticsModal').classList.add('hidden');
}

// Delete functionality
function deleteFile(fileId) {
    document.getElementById('deleteForm').action = `{{ route('admin.files.index') }}/${fileId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Bulk download
function bulkDownload() {
    const selectedFiles = Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.value);
    
    if (selectedFiles.length === 0) {
        alert('Please select files to download.');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.files.bulk-download") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    selectedFiles.forEach(fileId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'file_ids[]';
        input.value = fileId;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection






























