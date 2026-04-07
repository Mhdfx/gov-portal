/**
 * Real-time Features using Laravel Echo and Pusher
 * Handles WebSocket connections for live updates
 */

class RealtimeManager {
    constructor() {
        this.echo = null;
        this.initialized = false;
    }

    init() {
        if (this.initialized) return;

        // Check if Echo is available (requires Laravel Echo and Pusher)
        if (typeof Echo !== 'undefined') {
            this.echo = Echo;
            this.setupListeners();
            this.initialized = true;
        } else {
            console.warn('Laravel Echo not available. Real-time features disabled.');
            // Fallback to polling
            this.setupPolling();
        }
    }

    setupListeners() {
        const userId = window.userId || null;
        
        if (!userId) return;

        // Listen to user-specific channel
        this.echo.private(`user.${userId}`)
            .listen('.submission.status.updated', (e) => {
                this.handleStatusUpdate(e);
            })
            .listen('.submission.created', (e) => {
                this.handleNewSubmission(e);
            });

        // Listen to admin channel (if admin)
        if (window.userRole === 'main_admin' || window.userRole === 'institutional_admin') {
            this.echo.private('admin.submissions')
                .listen('.submission.status.updated', (e) => {
                    this.handleStatusUpdate(e);
                })
                .listen('.submission.created', (e) => {
                    this.handleNewSubmission(e);
                });
        }
    }

    handleStatusUpdate(event) {
        // Show notification
        this.showNotification({
            title: 'Status Updated',
            message: event.message,
            type: 'info'
        });

        // Update UI if submission is visible
        const submissionElement = document.querySelector(`[data-submission-id="${event.submission_id}"]`);
        if (submissionElement) {
            const statusBadge = submissionElement.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.textContent = event.new_status;
                statusBadge.className = `status-badge status-${event.new_status}`;
            }
        }

        // Refresh dashboard stats if on dashboard
        if (window.location.pathname.includes('/dashboard')) {
            this.refreshDashboardStats();
        }
    }

    handleNewSubmission(event) {
        // Show notification
        this.showNotification({
            title: 'New Submission',
            message: event.message,
            type: 'success'
        });

        // Refresh dashboard if on admin dashboard
        if (window.location.pathname.includes('/admin/dashboard')) {
            this.refreshDashboardStats();
        }
    }

    showNotification({ title, message, type = 'info' }) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm animate-fade-in`;
        
        const colors = {
            info: 'bg-blue-500 text-white',
            success: 'bg-green-500 text-white',
            warning: 'bg-yellow-500 text-white',
            error: 'bg-red-500 text-white'
        };
        
        notification.className += ` ${colors[type] || colors.info}`;
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-1">
                    <p class="font-semibold">${title}</p>
                    <p class="text-sm mt-1">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    refreshDashboardStats() {
        // Trigger dashboard refresh
        if (typeof loadDashboardStats === 'function') {
            loadDashboardStats();
        } else {
            // Fallback: reload page after delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    }

    setupPolling() {
        // Fallback polling for real-time updates
        setInterval(() => {
            if (window.location.pathname.includes('/dashboard')) {
                this.refreshDashboardStats();
            }
        }, 30000); // Poll every 30 seconds
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const realtime = new RealtimeManager();
        realtime.init();
        window.realtimeManager = realtime;
    });
} else {
    const realtime = new RealtimeManager();
    realtime.init();
    window.realtimeManager = realtime;
}














