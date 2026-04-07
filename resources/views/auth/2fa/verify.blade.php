@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-shield-check-line text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Two-Factor Authentication</h2>
                <p class="text-gray-600 dark:text-gray-400">Enter the verification code from your authenticator app</p>
            </div>

            <form action="{{ route('2fa.verify.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Verification Code
                    </label>
                    <input type="text" name="code" id="code" maxlength="6" pattern="[0-9]{6}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-center text-2xl tracking-widest font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="000000" autocomplete="one-time-code">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Verify
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        Use recovery code instead
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-submit on 6 digits
document.getElementById('code')?.addEventListener('input', function(e) {
    if (e.target.value.length === 6) {
        e.target.form?.submit();
    }
});

// Only allow numbers
document.getElementById('code')?.addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
    }
});
</script>
@endsection














