@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Enable Two-Factor Authentication</h2>
                <p class="text-gray-600 dark:text-gray-400">Scan the QR code with your authenticator app</p>
            </div>

            <div class="mt-8">
                <!-- QR Code -->
                <div class="flex justify-center mb-6">
                    <div class="bg-white p-4 rounded-lg border-2 border-gray-200">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}" alt="2FA QR Code" class="w-48 h-48">
                    </div>
                </div>

                <!-- Secret Key -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Or enter this code manually:
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" value="{{ $secret }}" readonly class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-mono text-sm" id="secret-key">
                        <button onclick="copySecret()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Verification Form -->
                <form action="{{ route('2fa.enable') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter verification code
                        </label>
                        <input type="text" name="code" id="code" maxlength="6" pattern="[0-9]{6}" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-center text-2xl tracking-widest font-mono"
                            placeholder="000000" autocomplete="one-time-code">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Enable 2FA
                    </button>
                </form>

                <!-- Recovery Codes -->
                @if(count($recoveryCodes) > 0)
                <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">
                        <i class="ri-alert-line"></i> Save these recovery codes
                    </p>
                    <div class="space-y-1">
                        @foreach($recoveryCodes as $code)
                        <code class="block text-xs font-mono text-yellow-900 dark:text-yellow-100">{{ $code }}</code>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-6 text-center">
                    <a href="{{ route('user.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copySecret() {
    const secretInput = document.getElementById('secret-key');
    secretInput.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="ri-check-line"></i>';
    button.classList.add('bg-green-600');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600');
    }, 2000);
}

// Auto-focus code input
document.getElementById('code')?.focus();

// Auto-submit on 6 digits
document.getElementById('code')?.addEventListener('input', function(e) {
    if (e.target.value.length === 6) {
        e.target.form?.submit();
    }
});
</script>
@endsection














