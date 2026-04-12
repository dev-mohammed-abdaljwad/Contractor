<div class="topbar">
  <div>
    <div class="topbar-title">@yield('topbar-title', 'لوحة المتابعة')</div>
    <div class="topbar-sub">{{ now()->locale('ar')->translatedFormat('l، j F Y') }} · آخر تحديث: منذ دقائق</div>
  </div>
  <div class="topbar-actions">
    <div class="search-bar">
      🔍 <span>بحث...</span>
    </div>
    <div class="notif-btn">
      🔔
      <div class="notif-dot"></div>
    </div>
    <div style="background:#0a4f14;color:#fff;font-size:12px;font-weight:700;padding:7px 14px;border-radius:10px;cursor:pointer;">+ مقاول جديد</div>
  </div>
</div>
