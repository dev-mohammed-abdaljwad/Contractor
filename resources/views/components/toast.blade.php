<!-- Toast Container -->
<div id="toast-container" 
     style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
</div>

<style>
    #toast-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        pointer-events: none;
    }

    .toast {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        font-size: 13px;
        font-weight: 500;
        animation: slideInRight 0.3s ease-out;
        pointer-events: auto;
        max-width: 100%;
        word-wrap: break-word;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(400px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    .toast.removing {
        animation: slideOutRight 0.3s ease-in forwards;
    }

    /* Success Toast */
    .toast.success {
        background-color: #f0fdf4;
        color: #166534;
        border-left: 4px solid #16a34a;
    }

    .toast.success .toast-icon::before {
        content: '✓';
        font-weight: bold;
        font-size: 18px;
    }

    /* Error Toast */
    .toast.error {
        background-color: #fef2f2;
        color: #7f1d1d;
        border-left: 4px solid #dc2626;
    }

    .toast.error .toast-icon::before {
        content: '✕';
        font-weight: bold;
        font-size: 18px;
    }

    /* Warning Toast */
    .toast.warning {
        background-color: #fffbeb;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .toast.warning .toast-icon::before {
        content: '!';
        font-weight: bold;
        font-size: 18px;
    }

    /* Info Toast */
    .toast.info {
        background-color: #eff6ff;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    .toast.info .toast-icon::before {
        content: 'i';
        font-weight: bold;
        font-size: 18px;
    }

    .toast-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .toast-message {
        flex: 1;
    }

    .toast-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 0;
        font-size: 18px;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .toast-close:hover {
        opacity: 1;
    }

    /* Mobile responsive */
    @media (max-width: 480px) {
        #toast-container {
            left: 12px;
            right: 12px;
            top: 12px;
            max-width: calc(100% - 24px);
        }

        .toast {
            padding: 12px 14px;
            font-size: 12px;
        }
    }
</style>
