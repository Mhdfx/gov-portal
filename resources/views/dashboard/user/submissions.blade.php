@extends('layouts.dashboard')

@section('dashboard-name', 'User Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'My Submissions')
@section('profile-route', route('user.profile'))
@section('settings-route', route('user.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('user.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    
    <a href="{{ route('user.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        My Submissions
    </a>
    
    <a href="{{ route('user.files') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Submissions</h1>
        <p class="mt-2 text-gray-600">View and track all your submitted forms and applications</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-file-list-3-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Submissions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_submissions'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-time-line text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_submissions'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-eye-line text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">In Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['in_review_submissions'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-checkbox-circle-line text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_submissions'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="ri-close-circle-line text-red-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected_submissions'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('user.submissions') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Submission Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="auto_entrepreneur" {{ request('type') == 'auto_entrepreneur' ? 'selected' : '' }}>Auto-Entrepreneur</option>
                    <option value="idea_carrier" {{ request('type') == 'idea_carrier' ? 'selected' : '' }}>Idea Carrier</option>
                    <option value="project_carrier" {{ request('type') == 'project_carrier' ? 'selected' : '' }}>Project Carrier</option>
                    <option value="investment" {{ request('type') == 'investment' ? 'selected' : '' }}>Investment</option>
                    <option value="indh" {{ request('type') == 'indh' ? 'selected' : '' }}>INDH</option>
                    <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="ri-search-line mr-2"></i>
                    Filter
                </button>
                @if(request()->hasAny(['type', 'status', 'date_from', 'date_to']))
                <a href="{{ route('user.submissions') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="ri-close-line"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Submissions List ({{ $submissions->total() }})</h3>
        </div>
        
        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title/Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    #{{ strtoupper(substr($submission->submission_type ?? 'SUB', 0, 2)) }}-{{ str_pad($submission->id, 3, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $submission->submission_type_label ?? ucfirst(str_replace('_', ' ', $submission->submission_type ?? 'Unknown')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    @if(isset($submission->project_name))
                                        {{ $submission->project_name }}
                                    @elseif(isset($submission->business_name))
                                        {{ $submission->business_name }}
                                    @elseif(isset($submission->idea_title))
                                        {{ $submission->idea_title }}
                                    @else
                                        Submission #{{ $submission->id }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_review' => 'bg-blue-100 text-blue-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusColor = $statusColors[$submission->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $submission->status ?? 'pending')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $submission->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-900" title="View Details" onclick="alert('Submission details page coming soon!'); return false;">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-900" title="Download" onclick="alert('Download feature coming soon!'); return false;">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-file-list-3-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No submissions found</h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->hasAny(['type', 'status', 'date_from', 'date_to']))
                        No submissions match your filter criteria. Try adjusting your filters.
                    @else
                        You haven't submitted any forms yet. Submissions will appear here once you complete and submit a form.
                    @endif
                </p>
                @if(request()->hasAny(['type', 'status', 'date_from', 'date_to']))
                <a href="{{ route('user.submissions') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 font-medium transition-colors">
                    Clear Filters
                </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
