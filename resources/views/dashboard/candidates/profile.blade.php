@extends('layouts.dashboard')

@section('dashboard-name', 'Candidate Dashboard')
@section('dashboard-icon', 'ri-user-line')
@section('page-title', 'Profile Management')
@section('profile-route', route('candidate.profile'))
@section('settings-route', route('candidate.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('candidate.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    
    <a href="{{ route('candidate.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
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
    
    <a href="{{ route('candidate.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
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
        <h1 class="text-3xl font-bold text-gray-900">Profile Management</h1>
        <p class="mt-2 text-gray-600">Manage your candidate profile and personal information</p>
    </div>

    <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Personal Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $candidate->first_name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $candidate->last_name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $candidate->email) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $candidate->phone) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $candidate->date_of_birth) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select name="gender" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $candidate->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $candidate->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $candidate->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nationality *</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $candidate->nationality) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nationality')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @if($candidate->profile_picture_path)
                    <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/' . $candidate->profile_picture_path) }}" target="_blank" class="text-blue-600">View</a></p>
                    @endif
                    @error('profile_picture')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Address Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <input type="text" name="address" value="{{ old('address', $candidate->address) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <input type="text" name="city" value="{{ old('city', $candidate->city) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                    <input type="text" name="region" value="{{ old('region', $candidate->region) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('region')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $candidate->postal_code) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Education & Experience -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Education & Experience</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Education Level *</label>
                    <input type="text" name="education_level" value="{{ old('education_level', $candidate->education_level) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('education_level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Field of Study</label>
                    <input type="text" name="field_of_study" value="{{ old('field_of_study', $candidate->field_of_study) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('field_of_study')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">University</label>
                    <input type="text" name="university" value="{{ old('university', $candidate->university) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('university')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience *</label>
                    <input type="number" name="years_of_experience" value="{{ old('years_of_experience', $candidate->years_of_experience) }}" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('years_of_experience')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Professional Summary</label>
                    <textarea name="professional_summary" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('professional_summary', $candidate->professional_summary) }}</textarea>
                    @error('professional_summary')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Skills & Languages -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Skills & Languages</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Skills (comma-separated)</label>
                    <input type="text" name="skills_input" value="{{ old('skills_input', is_array($candidate->skills) ? implode(', ', $candidate->skills) : '') }}"
                           placeholder="e.g., PHP, Laravel, JavaScript"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Enter skills separated by commas</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Languages (comma-separated)</label>
                    <input type="text" name="languages_input" value="{{ old('languages_input', is_array($candidate->languages) ? implode(', ', $candidate->languages) : '') }}"
                           placeholder="e.g., English, French, Arabic"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Enter languages separated by commas</p>
                </div>
            </div>
        </div>

        <!-- Job Preferences -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Job Preferences</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Availability *</label>
                    <select name="availability" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Availability</option>
                        <option value="immediate" {{ old('availability', $candidate->availability) == 'immediate' ? 'selected' : '' }}>Immediate</option>
                        <option value="1_month" {{ old('availability', $candidate->availability) == '1_month' ? 'selected' : '' }}>1 Month</option>
                        <option value="3_months" {{ old('availability', $candidate->availability) == '3_months' ? 'selected' : '' }}>3 Months</option>
                        <option value="6_months" {{ old('availability', $candidate->availability) == '6_months' ? 'selected' : '' }}>6 Months</option>
                        <option value="flexible" {{ old('availability', $candidate->availability) == 'flexible' ? 'selected' : '' }}>Flexible</option>
                    </select>
                    @error('availability')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Job Type *</label>
                    <select name="preferred_job_type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Job Type</option>
                        <option value="full_time" {{ old('preferred_job_type', $candidate->preferred_job_type) == 'full_time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part_time" {{ old('preferred_job_type', $candidate->preferred_job_type) == 'part_time' ? 'selected' : '' }}>Part-time</option>
                        <option value="contract" {{ old('preferred_job_type', $candidate->preferred_job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="internship" {{ old('preferred_job_type', $candidate->preferred_job_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                    </select>
                    @error('preferred_job_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Salary (MAD)</label>
                    <input type="number" name="expected_salary" value="{{ old('expected_salary', $candidate->expected_salary) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('expected_salary')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Documents</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CV/Resume</label>
                    <input type="file" name="cv_file" accept=".pdf,.doc,.docx"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @if($candidate->cv_file_path)
                    <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/' . $candidate->cv_file_path) }}" target="_blank" class="text-blue-600">View CV</a></p>
                    @endif
                    @error('cv_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter</label>
                    <input type="file" name="cover_letter" accept=".pdf,.doc,.docx"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @if($candidate->cover_letter_path)
                    <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/' . $candidate->cover_letter_path) }}" target="_blank" class="text-blue-600">View Cover Letter</a></p>
                    @endif
                    @error('cover_letter')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('candidate.dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                <i class="ri-save-line mr-2"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Convert comma-separated skills/languages to arrays on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    const skillsInput = document.querySelector('input[name="skills_input"]');
    const languagesInput = document.querySelector('input[name="languages_input"]');
    
    if (skillsInput && skillsInput.value) {
        const skills = skillsInput.value.split(',').map(s => s.trim()).filter(s => s);
        const skillsHidden = document.createElement('input');
        skillsHidden.type = 'hidden';
        skillsHidden.name = 'skills[]';
        skillsHidden.value = JSON.stringify(skills);
        this.appendChild(skillsHidden);
    }
    
    if (languagesInput && languagesInput.value) {
        const languages = languagesInput.value.split(',').map(l => l.trim()).filter(l => l);
        const languagesHidden = document.createElement('input');
        languagesHidden.type = 'hidden';
        languagesHidden.name = 'languages[]';
        languagesHidden.value = JSON.stringify(languages);
        this.appendChild(languagesHidden);
    }
});
</script>
@endpush
@endsection








