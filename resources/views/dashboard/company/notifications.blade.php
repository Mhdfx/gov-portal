@extends('layouts.dashboard')

@section('dashboard-name', 'Company Dashboard')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Notifications')
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
    <a href="{{ route('company.documents') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-shield-2-line text-xl mr-3"></i>
        Documents
    </a>
    <a href="{{ route('company.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
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
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
        <p class="mt-2 text-gray-600">Stay informed about approvals, alerts, and platform updates.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Total Notifications</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Unread</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['unread'] }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">Read</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total'] - $stats['unread'] }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('company.notifications') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Title or content"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="unread" @selected(request('status') === 'unread')>Unread</option>
                    <option value="read" @selected(request('status') === 'read')>Read</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">Filter</button>
                <a href="{{ route('company.notifications') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 w-full text-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Notifications ({{ $notifications->total() }})</h3>
        </div>
        @if($notifications->count())
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                    <div>
                        <div class="flex items-center gap-3">
                            <h4 class="text-base font-semibold text-gray-900">{{ $notification->title ?? 'Notification' }}</h4>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">{{ $notification->message ?? $notification->body }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if(!$notification->is_read)
                        <form action="{{ route('company.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                Mark as Read
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-green-600 font-medium">Read</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-notification-off-line text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-500">No notifications yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection








