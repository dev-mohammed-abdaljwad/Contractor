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
    <!-- <script src="{{ asset('js/dark-mode.js?v=1.1') }}"></script>
     -->
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

    <script>
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
