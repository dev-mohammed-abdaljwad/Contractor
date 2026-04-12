<div class="sidebar">
  <div class="sb-logo">
    <div class="sb-logo-text"><span>i</span>Dara</div>
    <div class="sb-logo-sub">لوحة الإدارة</div>
  </div>

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
  </div>
</div>
