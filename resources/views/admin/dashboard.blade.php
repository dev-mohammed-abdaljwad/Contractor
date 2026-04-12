@extends('layouts.admin-dashboard')

@section('title', 'لوحة المتابعة — نظام حصاد')
@section('topbar-title', 'لوحة المتابعة')

@section('content')

<!-- ══ STATS GRID ══ -->
<div class="stats-grid">

  <div class="stat-card sc-green">
    <div class="stat-icon">👷</div>
    <div class="stat-val">{{ $stats['active_contractors'] ?? 24 }}</div>
    <div class="stat-lbl">مقاول نشط</div>
    <div class="stat-delta delta-up">↑ {{ $stats['contractors_this_month'] ?? 3 }} هذا الشهر</div>
  </div>

  <div class="stat-card sc-blue">
    <div class="stat-icon">👥</div>
    <div class="stat-val">{{ number_format($stats['total_workers'] ?? 1247) }}</div>
    <div class="stat-lbl">إجمالي العمال</div>
    <div class="stat-delta delta-up">↑ {{ $stats['workers_this_week'] ?? 89 }} هذا الأسبوع</div>
  </div>

  <div class="stat-card sc-amber">
    <div class="stat-icon">📦</div>
    <div class="stat-val">{{ $stats['distributions_today'] ?? 342 }}</div>
    <div class="stat-lbl">توزيعات اليوم</div>
    <div class="stat-delta delta-neu">مقارنة بـ {{ $stats['distributions_yesterday'] ?? 318 }} أمس</div>
  </div>

  <div class="stat-card sc-teal">
    <div class="stat-icon">💰</div>
    <div class="stat-val" style="font-size:20px;">{{ number_format($stats['collection_this_month'] ?? 284500) }} ج</div>
    <div class="stat-lbl">تحصيل هذا الشهر</div>
    <div class="stat-delta delta-up">↑ {{ $stats['collection_growth'] ?? 12 }}% عن الشهر الماضي</div>
  </div>

  <div class="stat-card sc-purple">
    <div class="stat-icon">🆕</div>
    <div class="stat-val">{{ $stats['new_registrations'] ?? 3 }}</div>
    <div class="stat-lbl">تسجيلات هذا الأسبوع</div>
    <div class="stat-delta delta-up">↑ نمو جيد</div>
  </div>

  <div class="stat-card sc-red">
    <div class="stat-icon">⚠️</div>
    <div class="stat-val">{{ $stats['inactive_contractors'] ?? 2 }}</div>
    <div class="stat-lbl">مقاولون غير نشطين</div>
    <div class="stat-delta delta-neu">بدون تغيير</div>
  </div>

  <div class="stat-card sc-green">
    <div class="stat-icon">🏢</div>
    <div class="stat-val">{{ $stats['total_companies'] ?? 138 }}</div>
    <div class="stat-lbl">شركات متعاقدة</div>
    <div class="stat-delta delta-up">↑ {{ $stats['companies_this_month'] ?? 14 }} هذا الشهر</div>
  </div>

  <div class="stat-card sc-amber">
    <div class="stat-icon">📊</div>
    <div class="stat-val">{{ $stats['collection_rate'] ?? 96 }}%</div>
    <div class="stat-lbl">معدل التحصيل</div>
    <div class="stat-delta delta-up">↑ ممتاز</div>
  </div>

</div>

<!-- ══ CHART + ACTIVITY ══ -->
<div class="three-col">

  <!-- Chart -->
  <div class="sec-card">
    <div class="sec-head">
      <div class="sec-title">توزيعات يومية — آخر 7 أيام</div>
      <div class="sec-action">تفصيل</div>
    </div>
    <div class="chart-wrap">
      <div class="chart-bars">
        <div class="bar-col"><div class="bar" style="height:45%;background:#1D9E75;opacity:.4;"></div><div class="bar-lbl">إث</div></div>
        <div class="bar-col"><div class="bar" style="height:60%;background:#1D9E75;opacity:.5;"></div><div class="bar-lbl">ث</div></div>
        <div class="bar-col"><div class="bar" style="height:50%;background:#1D9E75;opacity:.6;"></div><div class="bar-lbl">أر</div></div>
        <div class="bar-col"><div class="bar" style="height:80%;background:#1D9E75;opacity:.7;"></div><div class="bar-lbl">خ</div></div>
        <div class="bar-col"><div class="bar" style="height:65%;background:#1D9E75;opacity:.8;"></div><div class="bar-lbl">ج</div></div>
        <div class="bar-col"><div class="bar" style="height:40%;background:#1D9E75;opacity:.6;"></div><div class="bar-lbl">س</div></div>
        <div class="bar-col"><div class="bar" style="height:100%;background:#0a4f14;"></div><div class="bar-lbl" style="color:#0a4f14;font-weight:700;">أح</div></div>
      </div>
      <div style="display:flex;justify-content:space-between;margin-top:10px;font-size:11px;color:var(--text-tertiary);">
        <span>218 توزيع أدنى</span>
        <span>342 توزيع أعلى (اليوم)</span>
      </div>
    </div>
  </div>

  <!-- Activity Feed -->
  <div class="sec-card">
    <div class="sec-head">
      <div class="sec-title">آخر الأحداث</div>
      <div class="sec-action">السجل كامل</div>
    </div>
    <div class="feed-item"><div class="feed-dot fd-green"></div><div class="feed-body"><div class="feed-title">تسجيل مقاول جديد</div><div class="feed-sub">أبو خالد · الدقهلية</div></div><div class="feed-time">منذ 5د</div></div>
    <div class="feed-item"><div class="feed-dot fd-blue"></div><div class="feed-body"><div class="feed-title">ترقية اشتراك</div><div class="feed-sub">مقاول #018 → Pro</div></div><div class="feed-time">منذ 23د</div></div>
    <div class="feed-item"><div class="feed-dot fd-amber"></div><div class="feed-body"><div class="feed-title">تنكر أدمن</div><div class="feed-sub">المدير دخل كـ مقاول #012</div></div><div class="feed-time">منذ 1س</div></div>
    <div class="feed-item"><div class="feed-dot fd-red"></div><div class="feed-body"><div class="feed-title">مقاول موقوف</div><div class="feed-sub">مقاول #007 · انتهاء اشتراك</div></div><div class="feed-time">منذ 3س</div></div>
    <div class="feed-item"><div class="feed-dot fd-purple"></div><div class="feed-body"><div class="feed-title">إشعار مُرسل</div><div class="feed-sub">تحديث النظام → كل المقاولين</div></div><div class="feed-time">أمس</div></div>
  </div>

</div>

<!-- ══ SYSTEM HEALTH + QUICK ACTIONS ══ -->
<div class="two-col">

  <div class="sec-card">
    <div class="sec-head">
      <div class="sec-title">صحة النظام</div>
      <div class="sec-action">تفاصيل</div>
    </div>
    <div class="health-item">
      <div class="health-icon">🖥️</div>
      <div class="health-info">
        <div class="health-title">استخدام السيرفر</div>
        <div class="health-bar"><div class="health-fill" style="width:34%;background:#1D9E75;"></div></div>
      </div>
      <div class="health-val" style="color:#1D9E75;">34%</div>
    </div>
    <div class="health-item">
      <div class="health-icon">💾</div>
      <div class="health-info">
        <div class="health-title">قاعدة البيانات</div>
        <div class="health-bar"><div class="health-fill" style="width:61%;background:#c8961a;"></div></div>
      </div>
      <div class="health-val" style="color:#c8961a;">61%</div>
    </div>
    <div class="health-item">
      <div class="health-icon">📡</div>
      <div class="health-info">
        <div class="health-title">الـ Uptime</div>
        <div class="health-bar"><div class="health-fill" style="width:99.8%;background:#0a4f14;"></div></div>
      </div>
      <div class="health-val" style="color:#0a4f14;">99.8%</div>
    </div>
    <div class="health-item">
      <div class="health-icon">⚡</div>
      <div class="health-info">
        <div class="health-title">متوسط وقت الاستجابة</div>
        <div class="health-bar"><div class="health-fill" style="width:15%;background:#1D9E75;"></div></div>
      </div>
      <div class="health-val" style="color:#1D9E75;">142ms</div>
    </div>
  </div>

  <div class="sec-card">
    <div class="sec-head">
      <div class="sec-title">إجراءات سريعة</div>
    </div>
    <div class="qa-grid">
      <div class="qa-btn">
        <div class="qa-icon">👷</div>
        <div class="qa-label">إضافة مقاول</div>
      </div>
      <div class="qa-btn">
        <div class="qa-icon">🔔</div>
        <div class="qa-label">إرسال إشعار</div>
      </div>
      <div class="qa-btn">
        <div class="qa-icon">📊</div>
        <div class="qa-label">تصدير تقرير</div>
      </div>
      <div class="qa-btn">
        <div class="qa-icon">🔁</div>
        <div class="qa-label">التنكر</div>
      </div>
      <div class="qa-btn">
        <div class="qa-icon">📋</div>
        <div class="qa-label">إدارة الخطط</div>
      </div>
      <div class="qa-btn">
        <div class="qa-icon">🔒</div>
        <div class="qa-label">سجل الأمان</div>
      </div>
    </div>
  </div>

</div>

@endsection
