@extends('layouts.admin')

@section('meta_title', 'Newsletter Management | Admin Dashboard')
@section('meta_description', 'Manage newsletter subscriptions and send newsletters.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Newsletter Management</h1>
        <div class="flex space-x-4">
            <a href="{{ route('newsletter.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                <i class="ri-mail-send-line mr-2"></i>Send Newsletter
            </a>
            <a href="{{ route('newsletter.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="ri-download-line mr-2"></i>Export CSV
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="ri-user-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Subscribers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="ri-checkbox-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="ri-pause-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="ri-close-circle-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unsubscribed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['unsubscribed'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="ri-calendar-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Recent (30 days)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['recent'] }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('newsletter.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                       placeholder="Email or name...">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
            
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
            </div>
            
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="ri-search-line mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Subscribers Table -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Newsletter Subscribers</h2>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Name</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Subscribed At</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Unsubscribed At</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscriptions as $subscription)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $subscription->email }}</td>
                            <td class="py-2 px-4 border-b">{{ $subscription->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($subscription->status === 'active') bg-green-100 text-green-800
                                    @elseif($subscription->status === 'inactive') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">{{ $subscription->subscribed_at->format('M d, Y H:i') }}</td>
                            <td class="py-2 px-4 border-b">
                                {{ $subscription->unsubscribed_at ? $subscription->unsubscribed_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td class="py-2 px-4 border-b">
                                @if($subscription->status === 'active')
                                    <form action="{{ route('newsletter.unsubscribe') }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $subscription->email }}">
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm" 
                                                onclick="return confirm('Are you sure you want to unsubscribe this user?')">
                                            <i class="ri-mail-close-line"></i> Unsubscribe
                                        </button>
                                    </form>
                                @elseif($subscription->status === 'unsubscribed')
                                    <form action="{{ route('newsletter.reactivate') }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $subscription->email }}">
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-sm">
                                            <i class="ri-mail-check-line"></i> Reactivate
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-4 text-center text-gray-500">No subscribers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection






























