<footer id="admin-mobile-footer">
    <nav id="admin-mobile-footer-nav">
        <a href="{{ route('admin.dashboard') }}" class="admin-mobile-footer-link active">
            <span>⊞</span>
            <span class="admin-mobile-footer-label">متابعة</span>
        </a>
        <a href="{{ route('admin.contractors.index') }}" class="admin-mobile-footer-link">
            <span>👷</span>
            <span class="admin-mobile-footer-label">مقاولون</span>
        </a>
        <a href="#" class="admin-mobile-footer-link">
            <span>📊</span>
            <span class="admin-mobile-footer-label">تقارير</span>
        </a>
        <a href="{{ route('settings.index') }}" class="admin-mobile-footer-link">
            <span>⚙️</span>
            <span class="admin-mobile-footer-label">إعدادات</span>
        </a>
        <button 
            type="button"
            onclick="handleAdminLogout()"
            class="admin-mobile-footer-link admin-logout-btn"
        >
            <span>🚪</span>
            <span class="admin-mobile-footer-label">خروج</span>
        </button>
    </nav>
</footer>

<script>
function handleAdminLogout() {
    if (confirm('هل تريد تسجيل الخروج؟')) {
        // Use the logout form if it exists, or redirect to logout route
        document.querySelector('form[action*="logout"]')?.submit() || 
        window.location.href = "{{ route('logout') }}";
    }
}

// Highlight current page link
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.admin-mobile-footer-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            link.classList.add('active');
        }
    });
});
</script>
