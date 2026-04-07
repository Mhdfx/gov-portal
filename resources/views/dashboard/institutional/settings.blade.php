@extends('layouts.dashboard')

@section('dashboard-name', 'Institutional Admin Dashboard')
@section('dashboard-icon', 'ri-government-line')
@section('page-title', 'Settings')
@section('profile-route', route('institutional.profile'))
@section('settings-route', route('institutional.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('institutional.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('institutional.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('institutional.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('institutional.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    <a href="{{ route('institutional.users') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Users
    </a>
    <a href="{{ route('institutional.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Companies
    </a>
    <a href="{{ route('institutional.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    <a href="{{ route('institutional.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Settings
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
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-gray-500">Institutional Admin • Configuration</p>
            <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
            <p class="mt-2 text-gray-600">Configure institutional preferences and notification settings.</p>
        </div>
        @if(session('success'))
            <div class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded-lg text-sm">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('institutional.settings.update') }}" class="space-y-8">
        @csrf

        <!-- Institution Information -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="ri-government-line text-2xl mr-3 text-blue-600"></i>
                Institution Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Institution Name *</label>
                    <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name']) }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('institution_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email']) }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                    <input type="text" name="region" value="{{ old('region', $settings['region']) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('region')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $settings['city']) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('city')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $settings['phone']) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="ri-notification-3-line text-2xl mr-3 text-yellow-600"></i>
                Notification Settings
            </h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" id="enable_notifications" name="enable_notifications" value="1"
                        {{ old('enable_notifications', $settings['enable_notifications']) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="enable_notifications" class="ml-2 text-sm text-gray-700">
                        Enable System Notifications
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="enable_email_notifications" name="enable_email_notifications" value="1"
                        {{ old('enable_email_notifications', $settings['enable_email_notifications']) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="enable_email_notifications" class="ml-2 text-sm text-gray-700">
                        Enable Email Notifications
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="enable_sms_notifications" name="enable_sms_notifications" value="1"
                        {{ old('enable_sms_notifications', $settings['enable_sms_notifications']) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="enable_sms_notifications" class="ml-2 text-sm text-gray-700">
                        Enable SMS Notifications
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notification Frequency *</label>
                    <select name="notification_frequency" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="immediate" {{ old('notification_frequency', $settings['notification_frequency']) === 'immediate' ? 'selected' : '' }}>Immediate</option>
                        <option value="daily" {{ old('notification_frequency', $settings['notification_frequency']) === 'daily' ? 'selected' : '' }}>Daily Digest</option>
                        <option value="weekly" {{ old('notification_frequency', $settings['notification_frequency']) === 'weekly' ? 'selected' : '' }}>Weekly Digest</option>
                    </select>
                    @error('notification_frequency')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Submission Settings -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="ri-file-list-3-line text-2xl mr-3 text-purple-600"></i>
                Submission Settings
            </h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" id="auto_approve_submissions" name="auto_approve_submissions" value="1"
                        {{ old('auto_approve_submissions', $settings['auto_approve_submissions']) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="auto_approve_submissions" class="ml-2 text-sm text-gray-700">
                        Auto-approve Submissions
                    </label>
                    <p class="text-xs text-gray-500 ml-2">Submissions will be automatically approved without review.</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="require_verification" name="require_verification" value="1"
                        {{ old('require_verification', $settings['require_verification']) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="require_verification" class="ml-2 text-sm text-gray-700">
                        Require User Verification
                    </label>
                    <p class="text-xs text-gray-500 ml-2">Only verified users can submit to your institution.</p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('institutional.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="ri-save-line mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>
@endsection






