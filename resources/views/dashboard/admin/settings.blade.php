@extends('layouts.dashboard')

@section('dashboard-name', 'Main Admin Dashboard')
@section('dashboard-icon', 'ri-shield-star-line')
@section('page-title', 'Settings')
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
    <a href="{{ route('admin.files.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
    <a href="{{ route('admin.newsletter.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-mail-line text-xl mr-3"></i>
        Newsletter
    </a>
    <a href="{{ route('admin.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Administration • Configuration</p>
            <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
            <p class="mt-2 text-gray-600">Configure system settings, security policies, and platform preferences.</p>
        </div>
        @if(session('success'))
            <div class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded-lg text-sm">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
        @csrf

        <!-- General Settings -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="ri-global-line text-2xl mr-3 text-blue-600"></i>
                General Settings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Name *</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('site_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Email *</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('site_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" 
                            {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="maintenance_mode" class="ml-2 text-sm text-gray-700">
                            Enable Maintenance Mode
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">When enabled, only administrators can access the platform.</p>
                </div>
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="registration_enabled" name="registration_enabled" value="1"
                            {{ old('registration_enabled', $settings['registration_enabled']) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="registration_enabled" class="ml-2 text-sm text-gray-700">
                            Enable User Registration
                        </label>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="email_verification_required" name="email_verification_required" value="1"
                            {{ old('email_verification_required', $settings['email_verification_required']) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="email_verification_required" class="ml-2 text-sm text-gray-700">
                            Require Email Verification
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Users must verify their email address before accessing the platform.</p>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="ri-shield-check-line text-2xl mr-3 text-red-600"></i>
                Security Settings
            </h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Session Management</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Session Timeout (minutes) *</label>
                            <input type="number" name="session_timeout" value="{{ old('session_timeout', $settings['session_timeout']) }}" 
                                min="15" max="1440" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('session_timeout')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">User sessions will expire after this duration of inactivity.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Password Policy</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Password Length *</label>
                            <input type="number" name="password_min_length" value="{{ old('password_min_length', $settings['password_min_length']) }}" 
                                min="6" max="32" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('password_min_length')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="password_require_uppercase" name="password_require_uppercase" value="1"
                                    {{ old('password_require_uppercase', $settings['password_require_uppercase']) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="password_require_uppercase" class="ml-2 text-sm text-gray-700">
                                    Require Uppercase Letters
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="password_require_lowercase" name="password_require_lowercase" value="1"
                                    {{ old('password_require_lowercase', $settings['password_require_lowercase']) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="password_require_lowercase" class="ml-2 text-sm text-gray-700">
                                    Require Lowercase Letters
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="password_require_numbers" name="password_require_numbers" value="1"
                                    {{ old('password_require_numbers', $settings['password_require_numbers']) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="password_require_numbers" class="ml-2 text-sm text-gray-700">
                                    Require Numbers
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="password_require_symbols" name="password_require_symbols" value="1"
                                    {{ old('password_require_symbols', $settings['password_require_symbols']) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="password_require_symbols" class="ml-2 text-sm text-gray-700">
                                    Require Special Characters
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Login Security</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Login Attempts *</label>
                            <input type="number" name="max_login_attempts" value="{{ old('max_login_attempts', $settings['max_login_attempts']) }}" 
                                min="3" max="10" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('max_login_attempts')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Number of failed login attempts before account lockout.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lockout Duration (minutes) *</label>
                            <input type="number" name="lockout_duration" value="{{ old('lockout_duration', $settings['lockout_duration']) }}" 
                                min="5" max="1440" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('lockout_duration')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Duration of account lockout after max attempts reached.</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="enable_2fa" name="enable_2fa" value="1"
                                    {{ old('enable_2fa', $settings['enable_2fa']) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="enable_2fa" class="ml-2 text-sm text-gray-700">
                                    Enable Two-Factor Authentication (2FA)
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Require users to use 2FA for additional security.</p>
                        </div>
                    </div>
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
            </div>
        </div>

        <!-- System Maintenance -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="ri-tools-line text-2xl mr-3 text-purple-600"></i>
                System Maintenance
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Log Retention (days) *</label>
                    <input type="number" name="log_retention_days" value="{{ old('log_retention_days', $settings['log_retention_days']) }}" 
                        min="7" max="365" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('log_retention_days')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">How long to keep system logs before automatic deletion.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Backup Frequency *</label>
                    <select name="backup_frequency" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="daily" {{ old('backup_frequency', $settings['backup_frequency']) === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('backup_frequency', $settings['backup_frequency']) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('backup_frequency', $settings['backup_frequency']) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('backup_frequency')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="ri-save-line mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>
@endsection






