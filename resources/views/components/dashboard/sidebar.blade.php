<aside id="sidebar">
    <div class="sidebar-logo">
        <h1>حصاد</h1>
        <p>نظام إدارة العمالة الزراعية</p>
    </div>
    <nav style="padding:12px 0;flex:1">
        <div class="nav-link active" onclick="showPage('dashboard')">
            <span class="ms ms-fill">dashboard</span> لوحة المتابعة
        </div>
        <a href="{{ route('contractor.companies.index') }}" class="nav-link" style="text-decoration:none;color:inherit">
            <span class="ms ms-fill">business</span> إدارة الشركات
        </a>
        <a href="{{ route('contractor.workers.index') }}" class="nav-link" style="text-decoration:none;color:inherit">
            <span class="ms ms-fill">groups</span> العمال
        </a>
        <div class="nav-link" onclick="showPage('distribution')">
            <span class="ms ms-fill">swap_horiz</span> التوزيع اليومي
        </div>
        <div class="nav-link" onclick="showPage('collection')">
            <span class="ms ms-fill">payments</span> التحصيل
        </div>
    </nav>
    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="user-av">{{ auth()->user()->name[0] ?? 'أ' }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'أبو خالد' }}</div>
                <div class="user-role">مقاول عمالة</div>
            </div>
        </div>
    </div>
</aside>
