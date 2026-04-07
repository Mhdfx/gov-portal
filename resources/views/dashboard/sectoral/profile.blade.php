@extends('layouts.dashboard')

@section('dashboard-name', 'Sectoral Admin Dashboard')
@section('dashboard-icon', 'ri-folder-chart-line')
@section('page-title', 'Profile')
@section('profile-route', route('sectoral.profile'))
@section('settings-route', route('sectoral.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('sectoral.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('sectoral.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Submissions
    </a>
    <a href="{{ route('sectoral.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Reports
    </a>
    <a href="{{ route('sectoral.analysis') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-line-chart-line text-xl mr-3"></i>
        Analysis
    </a>
    <a href="{{ route('sectoral.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    <a href="{{ route('sectoral.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Companies
    </a>
    <a href="{{ route('sectoral.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Settings
    </a>
    <a href="{{ route('sectoral.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
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
            <p class="text-sm uppercase tracking-wide text-gray-500">Sectoral Admin • Account</p>
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="mt-2 text-gray-600">Manage your sectoral admin account information and preferences.</p>
        </div>
        @if(session('success'))
            <div class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded-lg text-sm flex items-center">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white shadow rounded-2xl p-6 text-center">
                <div class="w-32 h-32 mx-auto mb-4">
                    @if(isset($user->avatar_path) && $user->avatar_path)
                        <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="Avatar" 
                            class="w-32 h-32 rounded-full object-cover border-4 border-blue-100">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-4xl font-bold text-white shadow-lg">
                            {{ strtoupper(substr($user->username, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $user->username }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                <div class="mt-4">
                    <span class="inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                        <i class="ri-folder-chart-line mr-1"></i>Sectoral Administrator
                    </span>
                </div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-900">Role</dt>
                        <dd class="text-gray-600 capitalize">{{ str_replace('_', ' ', $user->role) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Member Since</dt>
                        <dd class="text-gray-600">{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                    @if($user->last_login_at)
                        <div>
                            <dt class="font-medium text-gray-900">Last Login</dt>
                            <dd class="text-gray-600">{{ $user->last_login_at->diffForHumans() }}</dd>
                        </div>
                    @endif
                    @if($user->region)
                        <div>
                            <dt class="font-medium text-gray-900">Region</dt>
                            <dd class="text-gray-600">{{ $user->region }}</dd>
                        </div>
                    @endif
                    @if($user->city)
                        <div>
                            <dt class="font-medium text-gray-900">City</dt>
                            <dd class="text-gray-600">{{ $user->city }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="ri-user-settings-line text-2xl mr-3 text-blue-600"></i>
                    Profile Information
                </h2>
                <form method="POST" action="{{ route('sectoral.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('username')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                            <input type="text" name="region" value="{{ old('region', $user->region) }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('region')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('city')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                            <input type="file" name="avatar" accept="image/*"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @error('avatar')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Formats: JPEG, PNG, JPG</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea name="bio" rows="3" 
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Tell us about yourself...">{{ old('bio', $user->bio ?? '') }}</textarea>
                        @error('bio')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            <i class="ri-save-line mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="ri-lock-password-line text-2xl mr-3 text-red-600"></i>
                    Change Password
                </h2>
                <form method="POST" action="{{ route('sectoral.profile.password') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                        <input type="password" name="current_password" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                            <input type="password" name="password" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            <i class="ri-lock-line mr-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tips -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="ri-shield-check-line text-2xl mr-3 text-green-600"></i>
                    Security Tips
                </h2>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-3">
                        <i class="ri-shield-check-line text-blue-600 text-xl mt-0.5"></i>
                        <span>Use a strong, unique password that you don't reuse elsewhere.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-mail-line text-blue-600 text-xl mt-0.5"></i>
                        <span>Keep your email address up to date to receive important notifications.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-lock-line text-blue-600 text-xl mt-0.5"></i>
                        <span>Never share your sectoral admin credentials with anyone.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="ri-eye-off-line text-blue-600 text-xl mt-0.5"></i>
                        <span>Enable two-factor authentication for additional security (if available).</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection






