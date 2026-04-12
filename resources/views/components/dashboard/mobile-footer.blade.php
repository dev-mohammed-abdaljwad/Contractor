<footer id="mobile-footer">
    <nav id="mobile-footer-nav">
        <a href="{{ route('contractor.dashboard') }}" class="mobile-footer-link active">
            <span class="ms ms-fill">dashboard</span>
            <span class="mobile-footer-label">الرئيسية</span>
        </a>
        <a href="{{ route('contractor.companies.index') }}" class="mobile-footer-link" data-page="companies">
            <span class="ms ms-fill">business</span>
            <span class="mobile-footer-label">الشركات</span>
        </a>
        <a href="{{ route('contractor.distributions.index') }}" class="mobile-footer-link">
            <span class="ms ms-fill">swap_horiz</span>
            <span class="mobile-footer-label">التوزيع</span>
        </a>
        <a href="{{ route('contractor.workers.index') }}" class="mobile-footer-link">
            <span class="ms ms-fill">groups</span>
            <span class="mobile-footer-label">العمال</span>
        </a>
        <a href="{{ route('settings.index') }}" class="mobile-footer-link">
            <span class="ms ms-fill">settings</span>
            <span class="mobile-footer-label">الإعدادات</span>
        </a>
        
        <!-- LOGOUT BUTTON FOR MOBILE -->
        <button 
            type="button"
            onclick="handleLogout()"
            style="
                flex: 1;
                background: none;
                border: none;
                padding: 8px 4px;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                color: #666;
                font-size: 12px;
                transition: color 0.2s;
            "
            onmouseover="this.style.color='#dc2626'"
            onmouseout="this.style.color='#666'"
        >
            <span class="ms ms-fill">logout</span>
            <span class="mobile-footer-label">الخروج</span>
        </button>
    </nav>
</footer>
