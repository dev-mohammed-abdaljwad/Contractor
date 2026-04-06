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
        <a href="{{ route('contractor.collections.index') }}" class="mobile-footer-link">
            <span class="ms ms-fill">payments</span>
            <span class="mobile-footer-label">التحصيل</span>
        </a>
    </nav>
</footer>
