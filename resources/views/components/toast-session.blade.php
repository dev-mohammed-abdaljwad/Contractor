<div id="toast-container" class="fixed bottom-4 left-4 z-50 flex flex-col gap-3 pointer-events-none">
    @if (session('success'))
        <div class="toast-item toast-success pointer-events-auto" data-type="success">
            <div class="flex items-start gap-3 bg-green-50 border-r-4 border-green-500 text-green-800 px-4 py-3 rounded-lg shadow-lg">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
                
                <button onclick="this.closest('.toast-item').remove()" class="flex-shrink-0 text-green-600 hover:text-green-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-green-200 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 animate-shrink" style="animation: shrink 60s linear forwards;"></div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast-item toast-error pointer-events-auto" data-type="error">
            <div class="flex items-start gap-3 bg-red-50 border-r-4 border-red-500 text-red-800 px-4 py-3 rounded-lg shadow-lg">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm">{{ session('error') }}</p>
                </div>
                
                <button onclick="this.closest('.toast-item').remove()" class="flex-shrink-0 text-red-600 hover:text-red-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-red-200 rounded-full overflow-hidden">
                <div class="h-full bg-red-500 animate-shrink" style="animation: shrink 60s linear forwards;"></div>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="toast-item toast-warning pointer-events-auto" data-type="warning">
            <div class="flex items-start gap-3 bg-amber-50 border-r-4 border-amber-500 text-amber-800 px-4 py-3 rounded-lg shadow-lg">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm">{{ session('warning') }}</p>
                </div>
                
                <button onclick="this.closest('.toast-item').remove()" class="flex-shrink-0 text-amber-600 hover:text-amber-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-amber-200 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 animate-shrink" style="animation: shrink 60s linear forwards;"></div>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="toast-item toast-info pointer-events-auto" data-type="info">
            <div class="flex items-start gap-3 bg-blue-50 border-r-4 border-blue-500 text-blue-800 px-4 py-3 rounded-lg shadow-lg">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm">{{ session('info') }}</p>
                </div>
                
                <button onclick="this.closest('.toast-item').remove()" class="flex-shrink-0 text-blue-600 hover:text-blue-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-blue-200 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 animate-shrink" style="animation: shrink 60s linear forwards;"></div>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-400px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutToLeft {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-400px);
        }
    }

    @keyframes shrink {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    .toast-item {
        animation: slideInFromLeft 0.4s ease-out;
    }

    .toast-item.removing {
        animation: slideOutToLeft 0.4s ease-in;
    }

    #toast-container:has(.toast-item:hover) .toast-item:not(:hover) .animate-shrink {
        animation-play-state: paused;
    }

    .toast-item:hover .animate-shrink {
        animation-play-state: paused;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-item');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 400);
            }, 60000);
        });
    });
</script>
