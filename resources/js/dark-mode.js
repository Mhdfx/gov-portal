/**
 * Dark Mode Toggle
 * Handles dark mode switching and persistence
 */

class DarkMode {
    constructor() {
        this.init();
    }

    init() {
        // Check for saved preference or default to light mode
        const darkMode = this.getDarkMode();
        this.setDarkMode(darkMode);
        
        // Add toggle button if it doesn't exist
        this.createToggleButton();
        
        // Listen for system preference changes
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', (e) => {
                if (!this.getDarkMode()) {
                    // Only auto-switch if user hasn't manually set preference
                    this.setDarkMode(e.matches);
                }
            });
        }
    }

    getDarkMode() {
        // Check cookie first
        const cookie = this.getCookie('dark_mode');
        if (cookie === 'true') return true;
        if (cookie === 'false') return false;
        
        // Check localStorage
        const stored = localStorage.getItem('dark_mode');
        if (stored === 'true') return true;
        if (stored === 'false') return false;
        
        // Check system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return true;
        }
        
        return false;
    }

    setDarkMode(enabled) {
        const html = document.documentElement;
        
        if (enabled) {
            html.classList.add('dark');
            html.setAttribute('data-theme', 'dark');
        } else {
            html.classList.remove('dark');
            html.setAttribute('data-theme', 'light');
        }
        
        // Save to localStorage
        localStorage.setItem('dark_mode', enabled ? 'true' : 'false');
        
        // Save to cookie via API
        this.savePreference(enabled);
    }

    toggle() {
        const current = this.getDarkMode();
        this.setDarkMode(!current);
    }

    createToggleButton() {
        // Check if button already exists
        if (document.getElementById('dark-mode-toggle')) {
            return;
        }
        
        const button = document.createElement('button');
        button.id = 'dark-mode-toggle';
        button.className = 'fixed bottom-4 right-4 z-50 p-3 bg-blue-600 dark:bg-blue-800 text-white rounded-full shadow-lg hover:bg-blue-700 dark:hover:bg-blue-900 transition-colors';
        button.setAttribute('aria-label', 'Toggle dark mode');
        button.innerHTML = `
            <i class="ri-moon-line dark:hidden"></i>
            <i class="ri-sun-line hidden dark:inline"></i>
        `;
        
        button.addEventListener('click', () => {
            this.toggle();
            this.updateButtonIcon(button);
        });
        
        document.body.appendChild(button);
        this.updateButtonIcon(button);
    }

    updateButtonIcon(button) {
        const isDark = this.getDarkMode();
        const moonIcon = button.querySelector('.ri-moon-line');
        const sunIcon = button.querySelector('.ri-sun-line');
        
        if (isDark) {
            moonIcon?.classList.add('hidden');
            sunIcon?.classList.remove('hidden');
        } else {
            moonIcon?.classList.remove('hidden');
            sunIcon?.classList.add('hidden');
        }
    }

    savePreference(enabled) {
        // Save via API
        fetch('/api/settings/dark-mode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ enabled })
        }).catch(err => {
            console.error('Failed to save dark mode preference:', err);
        });
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new DarkMode();
    });
} else {
    new DarkMode();
}

// Export for use in other scripts
window.DarkMode = DarkMode;














