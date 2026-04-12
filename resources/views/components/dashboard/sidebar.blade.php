<aside id="sidebar">
    <div class="sidebar-logo">
        <h1>تحصيل</h1>
        <p>نظام إدارة العمالة الزراعية</p>
    </div>
    <nav style="padding:12px 0;flex:1">
        <a href="{{ route('contractor.dashboard') }}" class="nav-link active" style="text-decoration:none;color:inherit">
            <span class="ms ms-fill">dashboard</span>  المتابعة
        </a>
        <a href="{{ route('contractor.companies.index') }}" class="nav-link" style="text-decoration:none;color:inherit">
            <span class="ms ms-fill">business</span> إدارة الشركات
        </a>
        <a href="{{ route('contractor.workers.index') }}" class="nav-link" style="text-decoration:none;color:inherit">
            <span class="ms ms-fill">groups</span> العمال
        </a>
        <a href="{{ route('contractor.distributions.index') }}" class="nav-link" style="text-decoration:none;color:inherit">
            <span class="ms ms-fill">swap_horiz</span> التوزيع اليومي
        </a>
    </nav>
    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="user-av">{{ auth()->user()->name[0] ?? 'أ' }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'محمد عبد الجواد' }}</div>
                <div class="user-role">مقاول  </div>
            </div>
        </div>

        <!-- SETTINGS LINK -->
        @if(auth()->user()->isContractor())
            <a href="{{ route('settings.index') }}" class="nav-link" style="text-decoration:none;color:inherit;margin-top:8px;">
                <span class="ms ms-fill">settings</span> الإعدادات
            </a>
        @elseif(auth()->user()->isAdmin())
            <a href="{{ route('admin.settings.show') }}" class="nav-link" style="text-decoration:none;color:inherit;margin-top:8px;">
                <span class="ms ms-fill">settings</span> الإعدادات
            </a>
        @endif
        
        <!-- LOGOUT BUTTON -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin-top: 12px;">
            @csrf
            <button 
                type="button"
                onclick="handleLogout()"
                style="
                    width: 100%;
                    padding: 10px;
                    background: #dc2626;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 500;
                    transition: background 0.2s;
                "
                onmouseover="this.style.background='#b91c1c'"
                onmouseout="this.style.background='#dc2626'"
            >
                <span class="ms ms-fill" style="margin-left: 8px;">logout</span>
                تسجيل الخروج
            </button>
        </form>
    </div>
</aside>
