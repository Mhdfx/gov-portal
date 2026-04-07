@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Account Settings')
@section('profile-route', route('candidate.profile'))
@section('settings-route', route('candidate.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('candidate.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('candidate.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    <a href="{{ route('candidate.applications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        My Applications
    </a>
    <a href="{{ route('candidate.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Search
    </a>
    <a href="{{ route('candidate.documents') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-folder-line text-xl mr-3"></i>
        My Documents
    </a>
    <a href="{{ route('candidate.cv') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-file-paper-line text-xl mr-3"></i>
        CV Management
    </a>
    <a href="{{ route('candidate.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    <a href="{{ route('candidate.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
        <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
        <p class="mt-2 text-gray-600">Control your visibility, preferences, and alerts.</p>
    </div>

    <form action="{{ route('candidate.settings.update') }}" method="POST" class="space-y-8">
        @csrf

        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Availability</h2>
                    <p class="text-sm text-gray-500">Update your status so employers know when you can start.</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" class="sr-only peer" @checked($candidate->is_available)>
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-200 rounded-full peer peer-checked:bg-blue-600 transition"></span>
                    <span class="ml-3 text-sm font-medium text-gray-900">Actively looking</span>
                </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Availability window</label>
                    <select name="availability" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="immediate" @selected($candidate->availability === 'immediate')>Immediate</option>
                        <option value="1_month" @selected($candidate->availability === '1_month')>Within 1 month</option>
                        <option value="3_months" @selected($candidate->availability === '3_months')>Within 3 months</option>
                        <option value="6_months" @selected($candidate->availability === '6_months')>Within 6 months</option>
                        <option value="flexible" @selected($candidate->availability === 'flexible')>Flexible</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred contract type</label>
                    <select name="preferred_job_type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="full_time" @selected($candidate->preferred_job_type === 'full_time')>Full time</option>
                        <option value="part_time" @selected($candidate->preferred_job_type === 'part_time')>Part time</option>
                        <option value="contract" @selected($candidate->preferred_job_type === 'contract')>Contract</option>
                        <option value="internship" @selected($candidate->preferred_job_type === 'internship')>Internship</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Job Preferences</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred locations</label>
                    <input type="text" name="preferred_locations_input" value="{{ implode(', ', $candidate->preferred_locations ?? []) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Rabat, Casablanca">
                    <p class="text-xs text-gray-500 mt-1">Separate multiple locations with commas.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred sectors</label>
                    <input type="text" name="preferred_sectors_input" value="{{ implode(', ', $candidate->preferred_sectors ?? []) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Technology, Finance">
                    <p class="text-xs text-gray-500 mt-1">Separate multiple sectors with commas.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected salary (MAD)</label>
                    <input type="number" name="expected_salary" step="0.01" value="{{ $candidate->expected_salary }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. 12000">
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Reminders</h2>
                    <p class="text-sm text-gray-500">Keep your profile up-to-date to stay visible to employers.</p>
                </div>
                <a href="{{ route('candidate.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    <i class="ri-user-settings-line text-lg mr-2"></i>
                    Update profile
                </a>
            </div>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-line text-green-500"></i>
                    Ensure your CV and cover letter are current.
                </li>
                <li class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-line text-green-500"></i>
                    Add new certificates or training in the Documents tab.
                </li>
                <li class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-line text-green-500"></i>
                    Refresh your professional summary every few months.
                </li>
            </ul>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('candidate.dashboard') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="ri-save-3-line text-lg mr-2"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection








