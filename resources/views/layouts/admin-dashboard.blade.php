<!DOCTYPE html>
<html lang="ar" dir="rtl" @if(auth()->check() && auth()->user()->preferences && auth()->user()->preferences->dark_mode)data-dark-mode="1"@else data-dark-mode="0" @endif>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes"/>
    <title>@yield('title', 'لوحة الإدارة — نظام حصاد')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a4f14">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"/>
    <!-- Cache busted CSS with version -->
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css?v=1.4') }}">
    
    <!-- Toast System -->
    <script src="{{ asset('js/toast-session.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js?v=1.1') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Ensure media queries evaluate on load -->
    <script>
        // Force media query evaluation on page load
        window.addEventListener('load', function() {
            // Trigger layout recalculation
            document.body.offsetHeight;
        });
        
        // Handle viewport changes
        window.addEventListener('orientationchange', function() {
            window.location.reload();
        });
    </script>
</head>
<body>
    <div class="layout">
        
        <!-- ══ SIDEBAR ══ -->
        @include('components.admin.sidebar')

        <!-- ══ MAIN ══ -->
        <div class="main">
            
            <!-- Topbar -->
            @include('components.admin.topbar')

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Session Toast Notifications -->
    <x-toast-session />

    <!-- Mobile Footer Navigation -->
    @include('components.admin.mobile-footer')

    <!-- Hidden Logout Form -->
    <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal-overlay" class="logout-modal-overlay" style="display: none;">
        <div class="logout-modal">
            <div class="logout-modal-header">
                <h2>تسجيل الخروج</h2>
                <button type="button" class="logout-modal-close" onclick="closeLogoutModal()">&times;</button>
            </div>
            <div class="logout-modal-body">
                <p style="margin: 0; font-size: 15px; color: #666; line-height: 1.6;">
                    هل تريد فعلاً تسجيل الخروج من نظام حصاد؟
                </p>
            </div>
            <div class="logout-modal-footer">
                <button type="button" class="logout-modal-btn logout-modal-btn-cancel" onclick="closeLogoutModal()">
                    إلغاء
                </button>
                <button type="button" class="logout-modal-btn logout-modal-btn-logout" onclick="confirmLogout()">
                    تسجيل الخروج
                </button>
            </div>
        </div>
    </div>

    <style>
        .logout-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .logout-modal {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .logout-modal-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .logout-modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #999;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background 0.2s, color 0.2s;
        }

        .logout-modal-close:hover {
            background: #f5f5f5;
            color: #666;
        }

        .logout-modal-body {
            padding: 20px;
        }

        .logout-modal-footer {
            display: flex;
            gap: 12px;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            justify-content: flex-end;
        }

        .logout-modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-modal-btn-cancel {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .logout-modal-btn-cancel:hover {
            background: #e5e7eb;
            border-color: #9ca3af;
        }

        .logout-modal-btn-logout {
            background: #dc2626;
            color: white;
        }

        .logout-modal-btn-logout:hover {
            background: #b91c1c;
        }

        .logout-modal-btn-logout:active {
            transform: scale(0.98);
        }

        /* Dark mode support */
        html[data-dark-mode="1"] .logout-modal {
            background: #1f2937;
        }

        html[data-dark-mode="1"] .logout-modal-header {
            border-bottom-color: #374151;
        }

        html[data-dark-mode="1"] .logout-modal-header h2 {
            color: #f3f4f6;
        }

        html[data-dark-mode="1"] .logout-modal-body p {
            color: #d1d5db !important;
        }

        html[data-dark-mode="1"] .logout-modal-footer {
            border-top-color: #374151;
        }

        html[data-dark-mode="1"] .logout-modal-close {
            color: #9ca3af;
        }

        html[data-dark-mode="1"] .logout-modal-close:hover {
            background: #374151;
            color: #d1d5db;
        }

        html[data-dark-mode="1"] .logout-modal-btn-cancel {
            background: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
        }

        html[data-dark-mode="1"] .logout-modal-btn-cancel:hover {
            background: #4b5563;
            border-color: #6b7280;
        }
    </style>

    <script>
        /**
         * Show logout confirmation modal
         */
        function handleAdminLogout() {
            document.getElementById('logout-modal-overlay').style.display = 'flex';
        }

        /**
         * Close logout confirmation modal
         */
        function closeLogoutModal() {
            document.getElementById('logout-modal-overlay').style.display = 'none';
        }

        /**
         * Confirm logout and clear cache
         */
        async function confirmLogout() {
            try {
                // 1. Clear all caches
                if ('caches' in window) {
                    const cacheNames = await caches.keys();
                    await Promise.all(cacheNames.map(n => caches.delete(n)));
                    console.log('[Admin Logout] Caches cleared');
                }

                // 2. Tell Service Worker to clear its cache
                if (navigator.serviceWorker?.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'CLEAR_CACHE'
                    });
                }

                // 3. Clear storage
                localStorage.clear();
                sessionStorage.clear();

                // 4. Submit logout form
                document.getElementById('admin-logout-form').submit();

            } catch (error) {
                console.error('[Admin Logout] Error:', error);
                // Still logout even if cache clearing fails
                document.getElementById('admin-logout-form').submit();
            }
        }

        /**
         * Close modal when clicking outside of it or pressing ESC
         */
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('logout-modal-overlay');
            if (event.target === modal) {
                closeLogoutModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLogoutModal();
            }
        });

        /**
         * Close modal with Escape key
         */
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLogoutModal();
            }
        });

        // Dark Mode Toggle for Admin
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeCheckbox = document.querySelector('input[name="dark_mode"]');
            if (darkModeCheckbox) {
                darkModeCheckbox.addEventListener('change', function() {
                    window.toggleDarkMode(this.checked);
                });
            }
        });
    </script>
</body>
</html>
