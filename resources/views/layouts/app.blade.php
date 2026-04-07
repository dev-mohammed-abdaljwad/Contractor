<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - iDara نظام إدارة العمالة</title>
    
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
    
    <!-- Old Toast Scripts - Can be removed later -->
    <!-- Removed: old toast.js system - using new toast-session.js -->
    <script src="{{ asset('js/delete-manager.js') }}"></script>
    
    <!-- New Session Toast System -->
    <script src="{{ asset('js/toast-session.js') }}"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-blue-600">نظام إدارة العمالة</h1>
            </div>
            
            <nav class="p-6 space-y-4">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                        لوحة التحكم الإدارية
                    </a>
                @else
                    <a href="{{ route('contractor.dashboard') }}" class="block px-4 py-2 rounded {{ request()->routeIs('contractor.dashboard') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                        لوحة التحكم
                    </a>
                    
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase">الإدارة</p>
                        <a href="{{ route('contractor.companies.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('contractor.companies.*') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                            الشركات
                        </a>
                        <a href="{{ route('contractor.workers.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('contractor.workers.*') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                            العمال
                        </a>
                    </div>

                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase">التوزيع والعمل</p>
                        <a href="{{ route('contractor.distributions.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('contractor.distributions.*') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                            التوزيع اليومي
                        </a>
                        <a href="{{ route('contractor.deductions.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('contractor.deductions.*') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                            الخصومات
                        </a>
                        <a href="{{ route('contractor.advances.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('contractor.advances.*') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                            المتقدمات
                        </a>
                    </div>

                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase">التحصيل</p>
                        <a href="{{ route('contractor.collections.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('contractor.collections.*') ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100' }}">
                            الفواتير
                        </a>
                    </div>
                @endif
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-6 border-t bg-gray-50">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Topbar -->
            <div class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page-title')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Flash Messages (Replaced with Toast System) -->
            {{-- Old alert-style messages removed --}}

            <!-- Page Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Session Flash Toast Notifications -->
    <x-toast-session />

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
