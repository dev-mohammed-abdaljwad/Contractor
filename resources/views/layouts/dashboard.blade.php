<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>نظام حصاد — إدارة العمالة الزراعية</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@24,400,0&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d631b',
                        'pri-mid': '#1D9E75',
                        'pri-lt': '#66BB6A',
                        'pri-bg': '#E1F5EE',
                        surface: '#fafaf5',
                        'sur-2': '#f1f1ec',
                        outline: '#d0d0c8',
                        muted: '#707a6c',
                        danger: '#ba1a1a',
                        'dan-bg': '#fff5f5',
                        amber: '#BA7517',
                        'amb-bg': '#FAEEDA',
                        blue: '#185FA5',
                        'blu-bg': '#E6F1FB',
                    },
                    fontFamily: { arabic: ['Tajawal','sans-serif'] },
                }
            }
        }
    </script>
</head>
<body>
    <!-- MOBILE OVERLAY -->
    <div id="mobile-overlay" onclick="closeSidebar()"></div>

    <!-- MOBILE TOPBAR -->
    {{-- @include('components.dashboard.mobile-topbar') --}}

    <!-- SIDEBAR -->
    @include('components.dashboard.sidebar')

    <!-- MAIN CONTENT -->
    <main id="main">
        {{-- @include('components.dashboard.topbar') --}}
        
        <div class="content">
            @yield('content')
        </div>
    </main>

    <!-- MOBILE FOOTER NAV -->
    @include('components.dashboard.mobile-footer')

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
