<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'iDara - نظام إدارة العمالة')</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1D9E75">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="iDara">
    <meta name="msapplication-TileColor" content="#1D9E75">
    <meta name="description" content="iDara - نظام إدارة العمالة الذكي للمقاولين والشركات">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Icons -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #fafaf5;
        }
    </style>
</head>
<body>
    @yield('content')

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(reg => {
                    console.log('[PWA] Service Worker registered successfully:', reg.scope);
                    // Check for updates every minute
                    setInterval(() => {
                        reg.update();
                    }, 60000);
                })
                .catch(err => console.error('[PWA] Service Worker registration failed:', err));
        } else {
            console.warn('[PWA] Service Worker not supported in this browser');
        }

        // Listen for updates to the Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('controller', () => {
                console.log('[PWA] Service Worker controller changed');
                // Optionally notify user about update
                if (confirm('تحديث جديد متاح. هل تريد إعادة تحميل الصفحة؟')) {
                    window.location.reload();
                }
            });
        }
    </script>
</body>
</html>
