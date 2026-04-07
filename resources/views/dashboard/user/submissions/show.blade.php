@extends('layouts.dashboard')

@section('dashboard-name', 'User Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Submission Details')
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
    <a href="{{ route('user.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('user.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-arrow-left-line text-xl mr-3"></i>
        Back to submissions
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">{{ $submission->submission_type_label }}</p>
            <h1 class








