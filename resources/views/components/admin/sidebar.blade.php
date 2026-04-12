<div class="sidebar">
  <div class="sb-logo">
    <div class="sb-logo-text"><span>i</span>Dara</div>
    <div class="sb-logo-sub">لوحة الإدارة</div>
  </div>

  <!-- Mobile Menu Toggle -->
  <button class="sb-mobile-toggle" id="mobileMenuToggle" onclick="toggleMobileMenu()">☰</button>

  <!-- Navigation Container -->
  <div class="sb-nav-container" id="sbNavContainer">
    <div class="sb-section">الرئيسية</div>
    <a href="{{ route('admin.dashboard') }}" class="sb-link @if(request()->routeIs('admin.dashboard')) active @endif">
      <span class="sb-icon">⊞</span> لوحة المتابعة
    </a>
    <a href="#" class="sb-link">
      <span class="sb-icon">◷</span> سجل الأحداث
    </a>

    <div class="sb-section">المقاولون</div>
    <a href="{{ route('admin.contractors.index') }}" class="sb-link @if(request()->routeIs('admin.contractors.*')) active @endif">
      <span class="sb-icon">👷</span> المقاولون <span class="sb-badge">{{ \App\Models\User::where('role', 'contractor')->where('status', 'active')->count() }}</span>
    </a>
    <a href="#" class="sb-link">
      <span class="sb-icon">📋</span> الاشتراكات
    </a>
    <a href="#" class="sb-link">
      <span class="sb-icon">🔁</span> التنكر
    </a>

    <div class="sb-section">الإدارة</div>
    <a href="#" class="sb-link">
      <span class="sb-icon">🔔</span> الإشعارات
    </a>
    <a href="{{ route('settings.index') }}" class="sb-link @if(request()->routeIs('settings.*')) active @endif">
      <span class="sb-icon">⚙️</span> الإعدادات
    </a>
  </div>

  <div class="sb-bottom">
    <div class="sb-user">
      @php
        $names = explode(' ', auth()->user()->name);
        $initials = '';
        foreach(array_slice($names, 0, 1) as $name) {
          $initials .= substr($name, 0, 1);
        }
      @endphp
      <div class="sb-av">{{ $initials }}</div>
      <div>
        <div class="sb-uname">{{ auth()->user()->name }}</div>
        <div class="sb-urole">Admin</div>
      </div>
    </div>
    
    <!-- Logout Button -->
    <button 
      type="button"
      onclick="handleAdminLogout()"
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
        margin-top: 12px;
      "
      onmouseover="this.style.background='#b91c1c'"
      onmouseout="this.style.background='#dc2626'"
    >
      <span style="margin-left: 8px;">🚪</span>
      تسجيل الخروج
    </button>
  </div>
</div>

<script>
function toggleMobileMenu() {
  const container = document.getElementById('sbNavContainer');
  const toggle = document.getElementById('mobileMenuToggle');
  
  if (!container) return;
  
  if (container.style.display === 'none' || container.style.display === '') {
    container.style.display = 'flex';
    toggle.textContent = '✕';
  } else {
    container.style.display = 'none';
    toggle.textContent = '☰';
  }
}

// Initialize menu state on page load
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('sbNavContainer');
  if (container) {
    // Hide nav by default on mobile/tablet
    if (window.innerWidth <= 1024) {
      container.style.display = 'none';
    } else {
      container.style.display = 'flex';
    }
  }
  
  // Close menu when a link is clicked
  document.querySelectorAll('.sb-link').forEach(link => {
    link.addEventListener('click', () => {
      const container = document.getElementById('sbNavContainer');
      const toggle = document.getElementById('mobileMenuToggle');
      if (window.innerWidth <= 1024) {
        container.style.display = 'none';
        toggle.textContent = '☰';
      }
    });
  });
});

// Handle window resize
window.addEventListener('resize', function() {
  const container = document.getElementById('sbNavContainer');
  const toggle = document.getElementById('mobileMenuToggle');
  if (!container) return;
  
  if (window.innerWidth <= 1024) {
    container.style.display = 'none';
    toggle.style.display = 'block';
  } else {
    container.style.display = 'flex';
    toggle.style.display = 'none';
  }
});
</script>
