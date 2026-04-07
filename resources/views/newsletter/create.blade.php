@extends('layouts.admin')

@section('meta_title', 'Create Newsletter | Admin Dashboard')
@section('meta_description', 'Create and send a new newsletter to subscribers.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Create Newsletter</h1>
            <a href="{{ route('newsletter.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                <i class="ri-arrow-left-line mr-2"></i>Back to Newsletter Management
            </a>
        </div>
        
        <div class="bg-white shadow-md rounded-lg p-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('newsletter.send') }}">
                @csrf
                
                <!-- Newsletter Content -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Newsletter Content</h2>
                    
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject Line *</label>
                        <input type="text" name="subject" id="subject" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror"
                               value="{{ old('subject') }}" placeholder="Enter newsletter subject...">
                        @error('subject')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Newsletter Content *</label>
                        <textarea name="content" id="content" rows="15" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                  placeholder="Write your newsletter content here...">{{ old('content') }}</textarea>
                        @error('content')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500 mt-2">You can use HTML tags for formatting. Basic HTML is supported.</p>
                    </div>
                </div>
                
                <!-- Recipients Selection -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recipients</h2>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Recipients *</label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" name="recipients" id="recipients_all" value="all" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                       {{ old('recipients', 'all') == 'all' ? 'checked' : '' }}>
                                <label for="recipients_all" class="ml-2 text-sm text-gray-700">
                                    All Active Subscribers
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="radio" name="recipients" id="recipients_recent" value="recent" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                       {{ old('recipients') == 'recent' ? 'checked' : '' }}>
                                <label for="recipients_recent" class="ml-2 text-sm text-gray-700">
                                    Recent Subscribers (Last 30 days)
                                </label>
                            </div>
                        </div>
                        @error('recipients')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Test Email -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Test Email (Optional)</h2>
                    
                    <div class="mb-6">
                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                        <input type="email" name="test_email" id="test_email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('test_email') border-red-500 @enderror"
                               value="{{ old('test_email') }}" placeholder="test@example.com">
                        @error('test_email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500 mt-2">Send a test email to preview the newsletter before sending to all subscribers.</p>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" name="action" value="test" class="bg-yellow-600 text-white px-6 py-2 rounded-md hover:bg-yellow-700">
                            <i class="ri-send-plane-line mr-2"></i>Send Test Email
                        </button>
                    </div>
                </div>
                
                <!-- Send Newsletter -->
                <div class="border-t pt-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Once you send the newsletter, it cannot be undone. Make sure to:</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Review the content carefully</li>
                                        <li>Send a test email first</li>
                                        <li>Check the recipient count</li>
                                        <li>Ensure the subject line is clear and engaging</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('newsletter.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="send" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700"
                                onclick="return confirm('Are you sure you want to send this newsletter to all selected recipients?')">
                            <i class="ri-mail-send-line mr-2"></i>Send Newsletter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Simple content editor enhancement
document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    
    // Add some basic formatting buttons
    const editorToolbar = document.createElement('div');
    editorToolbar.className = 'mb-2 flex space-x-2';
    
    const boldBtn = document.createElement('button');
    boldBtn.type = 'button';
    boldBtn.className = 'px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded';
    boldBtn.innerHTML = '<i class="ri-bold"></i>';
    boldBtn.onclick = () => insertText('<strong></strong>');
    
    const italicBtn = document.createElement('button');
    italicBtn.type = 'button';
    italicBtn.className = 'px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded';
    italicBtn.innerHTML = '<i class="ri-italic"></i>';
    italicBtn.onclick = () => insertText('<em></em>');
    
    const linkBtn = document.createElement('button');
    linkBtn.type = 'button';
    linkBtn.className = 'px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 rounded';
    linkBtn.innerHTML = '<i class="ri-link"></i>';
    linkBtn.onclick = () => insertText('<a href=""></a>');
    
    editorToolbar.appendChild(boldBtn);
    editorToolbar.appendChild(italicBtn);
    editorToolbar.appendChild(linkBtn);
    
    contentTextarea.parentNode.insertBefore(editorToolbar, contentTextarea);
    
    function insertText(tag) {
        const start = contentTextarea.selectionStart;
        const end = contentTextarea.selectionEnd;
        const selectedText = contentTextarea.value.substring(start, end);
        const replacement = tag.replace('></>', '>' + selectedText + '</>');
        
        contentTextarea.value = contentTextarea.value.substring(0, start) + replacement + contentTextarea.value.substring(end);
        contentTextarea.focus();
    }
});
</script>
@endsection






























