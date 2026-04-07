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

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
