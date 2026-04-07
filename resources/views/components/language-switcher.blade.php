<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" type="button" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span>{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
        <form method="POST" action="{{ route('language.switch') }}">
            @csrf
            <input type="hidden" name="locale" value="fr">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'fr' ? 'bg-gray-100 font-medium' : '' }}">
                🇫🇷 Français
            </button>
        </form>
        
        <form method="POST" action="{{ route('language.switch') }}">
            @csrf
            <input type="hidden" name="locale" value="ar">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'ar' ? 'bg-gray-100 font-medium' : '' }}">
                🇲🇦 العربية
            </button>
        </form>
        
        <form method="POST" action="{{ route('language.switch') }}">
            @csrf
            <input type="hidden" name="locale" value="en">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'en' ? 'bg-gray-100 font-medium' : '' }}">
                🇺🇸 English
            </button>
        </form>
    </div>
</div>






























