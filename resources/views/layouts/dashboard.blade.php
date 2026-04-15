<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>نظام حصاد — إدارة العمالة الزراعية</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@24,400,0&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <!-- Old Toast & Delete Manager Scripts (Can be removed later) -->
    <!-- Removed: old toast.js system - using new toast-session.js -->
    <script src="{{ asset('js/delete-manager.js') }}"></script>
    
    <!-- New Session Toast System -->
    <script src="{{ asset('js/toast-session.js') }}"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- MOBILE OVERLAY -->
    <div id="mobile-overlay" onclick="closeSidebar()"></div>

    <!-- SIDEBAR -->
    @include('components.dashboard.sidebar')

    <!-- MAIN CONTENT -->
    <main id="main">
        <div class="content">
            @yield('content')
        </div>
    </main>

    <!-- MOBILE FOOTER NAV -->
    @include('components.dashboard.mobile-footer')

    <!-- Session Flash Toast Notifications -->
    <x-toast-session />

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
    </style>

    <script src="{{ asset('js/dashboard.js') }}"></script>

    <script>
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
    </script>
</body>
</html>
