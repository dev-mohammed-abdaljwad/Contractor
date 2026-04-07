/**
 * Toast Notification System - JavaScript
 * Provides window.showToast(message, type) for programmatic use
 * Works with session-based toasts from Blade component
 */

(function() {
    'use strict';

    // Toast configuration
    const TOAST_CONFIG = {
        duration: 60000, // 60 seconds (1 minute)
        position: 'bottom-left',
        maxToasts: 5
    };

    /**
     * Create and show a toast notification
     * @param {string} message - The toast message
     * @param {string} type - Toast type: 'success', 'error', 'warning', 'info'
     */
    window.showToast = function(message, type = 'info') {
        // Validate type
        if (!['success', 'error', 'warning', 'info'].includes(type)) {
            type = 'info';
        }

        // Ensure message is a string
        message = String(message).trim();

        // Get or create container
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-4 left-4 z-50 flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(container);
        }

        // Limit number of toasts
        const existingToasts = container.querySelectorAll('.toast-item');
        if (existingToasts.length >= TOAST_CONFIG.maxToasts) {
            existingToasts[0].remove();
        }

        // Create toast element using DOM methods
        const toast = createToastElement(message, type);

        // Add to container
        container.appendChild(toast);

        // Trigger animation
        setTimeout(() => {
            toast.style.animation = 'slideInFromLeft 0.4s ease-out';
        }, 0);

        // Auto-dismiss
        const timer = setTimeout(() => {
            removeToast(toast);
        }, TOAST_CONFIG.duration);

        // Pause timer on hover
        toast.addEventListener('mouseenter', () => clearTimeout(timer));
        toast.addEventListener('mouseleave', () => {
            const remainingTime = TOAST_CONFIG.duration - (Date.now() - toast.dataset.createdAt);
            if (remainingTime > 0) {
                const newTimer = setTimeout(() => {
                    removeToast(toast);
                }, remainingTime);
                toast.dataset.timer = newTimer;
            }
        });

        toast.dataset.createdAt = Date.now();
        toast.dataset.timer = timer;
    };

    /**
     * Create a toast element using DOM methods (not innerHTML)
     * @private
     */
    function createToastElement(message, type) {
        const config = getTypeConfig(type);
        const toast = document.createElement('div');
        toast.className = `toast-item toast-${type} pointer-events-auto`;
        toast.dataset.type = type;

        // Main wrapper
        const wrapper = document.createElement('div');
        wrapper.className = `flex items-start gap-3 ${config.bgColor} ${config.borderColor} ${config.textColor} px-4 py-3 rounded-lg shadow-lg`;

        // Icon container
        const iconContainer = document.createElement('div');
        iconContainer.className = 'flex-shrink-0 mt-0.5';
        iconContainer.innerHTML = config.icon;
        wrapper.appendChild(iconContainer);

        // Message container
        const messageContainer = document.createElement('div');
        messageContainer.className = 'flex-1 min-w-0';
        const messagePara = document.createElement('p');
        messagePara.className = 'font-medium text-sm';
        messagePara.textContent = message; // Use textContent to prevent HTML injection
        messageContainer.appendChild(messagePara);
        wrapper.appendChild(messageContainer);

        // Close button
        const closeBtn = document.createElement('button');
        closeBtn.className = `flex-shrink-0 ${config.closeColor} hover:${config.closeHoverColor} focus:outline-none toast-close`;
        closeBtn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            removeToast(toast);
        });
        wrapper.appendChild(closeBtn);

        toast.appendChild(wrapper);

        // Progress bar
        const progressContainer = document.createElement('div');
        progressContainer.className = `h-1 ${config.progressBgColor} rounded-full overflow-hidden`;
        const progressBar = document.createElement('div');
        progressBar.className = `h-full ${config.progressColor} toast-progress`;
        progressBar.style.animation = 'shrink 60s linear forwards';
        progressContainer.appendChild(progressBar);
        toast.appendChild(progressContainer);

        // Pause timer on hover
        toast.addEventListener('mouseenter', () => {
            progressBar.style.animationPlayState = 'paused';
        });

        toast.addEventListener('mouseleave', () => {
            progressBar.style.animationPlayState = 'running';
        });

        return toast;
    }

    /**
     * Get type-specific configuration
     * @private
     */
    function getTypeConfig(type) {
        const configs = {
            success: {
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                bgColor: 'bg-green-50',
                borderColor: 'border-r-4 border-green-500',
                textColor: 'text-green-800',
                closeColor: 'text-green-600',
                closeHoverColor: 'text-green-800',
                progressBgColor: 'bg-green-200',
                progressColor: 'bg-green-500'
            },
            error: {
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
                bgColor: 'bg-red-50',
                borderColor: 'border-r-4 border-red-500',
                textColor: 'text-red-800',
                closeColor: 'text-red-600',
                closeHoverColor: 'text-red-800',
                progressBgColor: 'bg-red-200',
                progressColor: 'bg-red-500'
            },
            warning: {
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
                bgColor: 'bg-amber-50',
                borderColor: 'border-r-4 border-amber-500',
                textColor: 'text-amber-800',
                closeColor: 'text-amber-600',
                closeHoverColor: 'text-amber-800',
                progressBgColor: 'bg-amber-200',
                progressColor: 'bg-amber-500'
            },
            info: {
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
                bgColor: 'bg-blue-50',
                borderColor: 'border-r-4 border-blue-500',
                textColor: 'text-blue-800',
                closeColor: 'text-blue-600',
                closeHoverColor: 'text-blue-800',
                progressBgColor: 'bg-blue-200',
                progressColor: 'bg-blue-500'
            }
        };

        return configs[type] || configs.info;
    }

    /**
     * Remove a toast with animation
     * @private
     */
    function removeToast(toast) {
        if (!toast) return;

        const timer = toast.dataset.timer;
        if (timer) {
            clearTimeout(timer);
        }

        toast.style.animation = 'slideOutToLeft 0.4s ease-in forwards';
        setTimeout(() => {
            toast.remove();
        }, 400);
    }

    /**
     * Clear all toasts
     */
    window.clearAllToasts = function() {
        const container = document.getElementById('toast-container');
        if (container) {
            const toasts = container.querySelectorAll('.toast-item');
            toasts.forEach(toast => removeToast(toast));
        }
    };

})();
