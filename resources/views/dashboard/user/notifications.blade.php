@extends('layouts.app')

@section('meta_title', 'Notifications | Boiema Platform')
@section('meta_description', 'View your notifications and alerts from Boiema Platform.')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Notifications</h1>
    
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">All Notifications</h2>
            <div class="flex space-x-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="ri-check-line mr-2"></i>Mark All Read
                </button>
                <button class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="ri-delete-bin-line mr-2"></i>Clear All
                </button>
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="space-y-4">
            <!-- Unread Notification -->
            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="ri-notification-3-line text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-900">Submission Status Update</h3>
                            <p class="text-sm text-blue-700 mt-1">Your Auto-Entrepreneur application has been approved and is now under review.</p>
                            <p class="text-xs text-blue-600 mt-2">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-900" title="Mark as read">
                            <i class="ri-check-line"></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Read Notification -->
            <div class="border-l-4 border-gray-300 bg-gray-50 p-4 rounded-r-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="ri-file-list-3-line text-gray-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">New Form Available</h3>
                            <p class="text-sm text-gray-700 mt-1">A new training form has been added to the platform.</p>
                            <p class="text-xs text-gray-500 mt-2">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-gray-600 hover:text-gray-900" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Success Notification -->
            <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-line text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-900">Profile Updated</h3>
                            <p class="text-sm text-green-700 mt-1">Your profile information has been successfully updated.</p>
                            <p class="text-xs text-green-600 mt-2">3 days ago</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-green-600 hover:text-green-900" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Warning Notification -->
            <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-r-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="ri-alert-line text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-900">Document Required</h3>
                            <p class="text-sm text-yellow-700 mt-1">Please upload your business registration document to complete your application.</p>
                            <p class="text-xs text-yellow-600 mt-2">1 week ago</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-yellow-600 hover:text-yellow-900" title="Mark as read">
                            <i class="ri-check-line"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-yellow-900" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Empty state -->
            <div id="emptyNotificationsState" class="text-center py-8 hidden">
                <i class="ri-notification-off-line text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">No notifications found.</p>
            </div>
        </div>
        
        <!-- Notification Settings -->
        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Settings</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Email Notifications</h4>
                        <p class="text-sm text-gray-500">Receive notifications via email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">SMS Notifications</h4>
                        <p class="text-sm text-gray-500">Receive notifications via SMS</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Push Notifications</h4>
                        <p class="text-sm text-gray-500">Receive push notifications in browser</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






























