/**
 * Toast Notification System
 * Provides methods to show success, error, warning, and info toasts
 */

const ToastManager = {
    container: null,
    toastTimeout: 5000, // Default 5 seconds

    /**
     * Initialize the toast container
     */
    init() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            console.warn('Toast container not found. Make sure toast.blade.php is included in your layout.');
        }
    },

    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - Type of toast: 'success', 'error', 'warning', 'info'
     * @param {number} duration - How long to show the toast in milliseconds (0 = manual close only)
     */
    show(message, type = 'info', duration = this.toastTimeout) {
        if (!this.container) this.init();

        const toastId = 'toast-' + Date.now();
        const toastElement = document.createElement('div');
        toastElement.id = toastId;
        toastElement.className = `toast ${type}`;
        toastElement.innerHTML = `
            <div class="toast-icon"></div>
            <div class="toast-message">${this.escapeHtml(message)}</div>
            <button class="toast-close" onclick="ToastManager.remove('${toastId}')" title="إغلاق">×</button>
        `;

        this.container.appendChild(toastElement);

        // Auto-remove after duration (if duration > 0)
        if (duration > 0) {
            setTimeout(() => this.remove(toastId), duration);
        }

        return toastId;
    },

    /**
     * Show success toast
     * @param {string} message - The message to display
     * @param {number} duration - How long to show the toast in milliseconds
     */
    success(message, duration = this.toastTimeout) {
        return this.show(message, 'success', duration);
    },

    /**
     * Show error toast
     * @param {string} message - The message to display
     * @param {number} duration - How long to show the toast in milliseconds
     */
    error(message, duration = this.toastTimeout) {
        return this.show(message, 'error', duration);
    },

    /**
     * Show warning toast
     * @param {string} message - The message to display
     * @param {number} duration - How long to show the toast in milliseconds
     */
    warning(message, duration = this.toastTimeout) {
        return this.show(message, 'warning', duration);
    },

    /**
     * Show info toast
     * @param {string} message - The message to display
     * @param {number} duration - How long to show the toast in milliseconds
     */
    info(message, duration = this.toastTimeout) {
        return this.show(message, 'info', duration);
    },

    /**
     * Remove a toast by ID
     * @param {string} toastId - The ID of the toast to remove
     */
    remove(toastId) {
        const toastElement = document.getElementById(toastId);
        if (toastElement) {
            toastElement.classList.add('removing');
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.parentNode.removeChild(toastElement);
                }
            }, 300); // Match animation duration
        }
    },

    /**
     * Remove all toasts
     */
    clearAll() {
        if (this.container) {
            const toasts = this.container.querySelectorAll('.toast');
            toasts.forEach(toast => {
                const id = toast.id;
                if (id) this.remove(id);
            });
        }
    },

    /**
     * Escape HTML to prevent XSS
     * @param {string} text - Text to escape
     * @returns {string} Escaped text
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ToastManager.init());
} else {
    ToastManager.init();
}
