/**
 * Accessibility Enhancements
 * WCAG 2.1 AA Compliance Improvements
 */

class Accessibility {
    constructor() {
        this.init();
    }

    init() {
        // Skip to main content link
        this.addSkipLink();
        
        // Keyboard navigation enhancements
        this.enhanceKeyboardNavigation();
        
        // Focus management
        this.manageFocus();
        
        // ARIA labels for icons
        this.addAriaLabels();
        
        // Screen reader announcements
        this.setupAnnouncements();
        
        // High contrast mode detection
        this.detectHighContrast();
    }

    addSkipLink() {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded';
        skipLink.textContent = 'Skip to main content';
        document.body.insertBefore(skipLink, document.body.firstChild);
    }

    enhanceKeyboardNavigation() {
        // Trap focus in modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                const modals = document.querySelectorAll('[role="dialog"]');
                modals.forEach(modal => {
                    if (modal.classList.contains('open')) {
                        this.trapFocus(modal, e);
                    }
                });
            }

            // Close modals with Escape
            if (e.key === 'Escape') {
                const openModal = document.querySelector('[role="dialog"].open');
                if (openModal) {
                    const closeButton = openModal.querySelector('[data-dismiss="modal"]');
                    if (closeButton) {
                        closeButton.click();
                    }
                }
            }
        });
    }

    trapFocus(container, event) {
        const focusableElements = container.querySelectorAll(
            'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault();
            lastElement.focus();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault();
            firstElement.focus();
        }
    }

    manageFocus() {
        // Announce page changes to screen readers
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Check if main content changed
                    const mainContent = document.querySelector('main, [role="main"]');
                    if (mainContent && mutation.target.contains(mainContent)) {
                        this.announce('Page content updated');
                    }
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    addAriaLabels() {
        // Add aria-labels to icon-only buttons
        document.querySelectorAll('button:not([aria-label]):has(i.ri-), a:not([aria-label]):has(i.ri-)').forEach(element => {
            const icon = element.querySelector('i');
            if (icon) {
                const iconClass = Array.from(icon.classList).find(cls => cls.startsWith('ri-'));
                if (iconClass) {
                    const label = iconClass.replace('ri-', '').replace(/-/g, ' ');
                    element.setAttribute('aria-label', label);
                }
            }
        });
    }

    setupAnnouncements() {
        // Create live region for announcements
        const liveRegion = document.createElement('div');
        liveRegion.id = 'a11y-announcements';
        liveRegion.setAttribute('role', 'status');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        document.body.appendChild(liveRegion);
    }

    announce(message) {
        const liveRegion = document.getElementById('a11y-announcements');
        if (liveRegion) {
            liveRegion.textContent = message;
            // Clear after announcement
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }

    detectHighContrast() {
        // Check for high contrast mode
        if (window.matchMedia('(prefers-contrast: high)').matches) {
            document.documentElement.classList.add('high-contrast');
        }

        // Listen for changes
        window.matchMedia('(prefers-contrast: high)').addEventListener('change', (e) => {
            if (e.matches) {
                document.documentElement.classList.add('high-contrast');
            } else {
                document.documentElement.classList.remove('high-contrast');
            }
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new Accessibility();
    });
} else {
    new Accessibility();
}

// Export for use in other scripts
window.Accessibility = Accessibility;














