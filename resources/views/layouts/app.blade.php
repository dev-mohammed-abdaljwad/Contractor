<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - نظام إدارة مقاول العمالة</title>
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

            <!-- Flash Messages -->
            @if ($errors->any())
                <div class="mx-8 mt-4 p-4 bg-red-100 text-red-700 rounded">
                    <strong>خطأ:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mx-8 mt-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Page Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
