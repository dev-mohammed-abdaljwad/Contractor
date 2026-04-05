@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; }

.page { background: #f5f6f8; min-height: 100vh; }

.hero {
  background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 60%, #2DC98A 100%);
  padding: 28px 20px 60px;
  position: relative;
}
.hero-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.back { color: rgba(255,255,255,0.85); font-size: 13px; cursor: pointer; text-decoration: none; }
.back:hover { color: #fff; }
.more { color: rgba(255,255,255,0.85); font-size: 20px; cursor: pointer; letter-spacing: 2px; }

.worker-info { display: flex; align-items: center; gap: 16px; }
.avatar-lg {
  width: 68px; height: 68px; border-radius: 50%;
  background: rgba(255,255,255,0.22);
  border: 2.5px solid rgba(255,255,255,0.5);
  display: flex; align-items: center; justify-content: center;
  font-size: 24px; font-weight: 600; color: #fff;
  flex-shrink: 0;
}
.worker-name { font-size: 20px; font-weight: 600; color: #fff; margin-bottom: 4px; }
.worker-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.id-badge { background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
.status-badge { background: #4ade80; color: #14532d; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; }
.status-badge.inactive { background: #ef4444; color: #fff; }
.join-date { color: rgba(255,255,255,0.75); font-size: 12px; }

.actions { display: flex; gap: 8px; margin-top: 16px; }
.act-btn {
  flex: 1; text-align: center; padding: 8px 6px;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 10px; color: #fff; font-size: 12px; cursor: pointer;
  transition: background 0.2s; text-decoration: none;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
}
.act-btn:hover { background: rgba(255,255,255,0.25); }
.act-icon { font-size: 16px; display: block; margin-bottom: 2px; }

.stats-row { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 10px; padding: 0 16px; margin-top: -36px; position: relative; z-index: 10; margin-bottom: 16px; }
.stat-card { background: #fff; border-radius: 14px; padding: 12px 10px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
.stat-val { font-size: 15px; font-weight: 700; margin-bottom: 3px; }
.stat-label { font-size: 10px; color: #888; line-height: 1.3; }
.val-green { color: #0F6E56; }
.val-red { color: #DC2626; }
.val-amber { color: #D97706; }
.val-blue { color: #2563EB; }

.tabs-wrap { background: #fff; border-radius: 14px 14px 0 0; margin: 0 12px; overflow: hidden; box-shadow: 0 -2px 12px rgba(0,0,0,0.04); }
.tabs-bar { display: flex; border-bottom: 1px solid #f0f0f0; padding: 0 4px; }
.tab-btn { flex: 1; text-align: center; padding: 12px 4px; font-size: 12px; color: #999; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; }
.tab-btn.active { color: #1D9E75; font-weight: 600; border-bottom-color: #1D9E75; }

.tab-content { padding: 16px; display: none; }
.tab-content.active { display: block; }

.week-row { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
.week-row:last-child { border-bottom: none; }
.week-date { font-size: 11px; color: #aaa; min-width: 36px; text-align: center; }
.week-day { font-size: 10px; color: #ccc; }
.week-co { flex: 1; font-size: 13px; font-weight: 500; color: #222; }
.week-co-sub { font-size: 11px; color: #999; margin-top: 1px; }
.earn { font-size: 13px; font-weight: 600; color: #0F6E56; }
.absent-pill { font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 20px; background: #FEE2E2; color: #991B1B; }

.sec-title { font-size: 12px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 10px; margin-top: 4px; }

.co-freq { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
.co-freq:last-child { border-bottom: none; }
.co-dot { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; flex-shrink: 0; }
.co-blue { background: #EFF6FF; color: #1D4ED8; }
.co-teal { background: #ECFDF5; color: #065F46; }
.co-purple { background: #F5F3FF; color: #5B21B6; }
.co-name { flex: 1; font-size: 13px; font-weight: 500; color: #222; }
.co-days { font-size: 12px; color: #aaa; }
.co-bar-wrap { height: 4px; background: #f0f0f0; border-radius: 2px; margin-top: 4px; }
.co-bar { height: 100%; border-radius: 2px; background: #1D9E75; }

.cal-header { display: grid; grid-template-columns: repeat(7,1fr); gap: 3px; margin-bottom: 4px; }
.cal-day-name { text-align: center; font-size: 10px; color: #bbb; padding: 3px 0; }
.cal-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 3px; }
.cal-cell { aspect-ratio: 1; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 500; }
.c-present { background: #ECFDF5; color: #065F46; }
.c-partial { background: #FFFBEB; color: #92400E; }
.c-absent { background: #FEF2F2; color: #991B1B; }
.c-today { background: #1D9E75; color: #fff; font-weight: 700; }
.c-empty { background: #fafafa; color: #ddd; }

.cal-legend { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 12px; margin-bottom: 16px; }
.leg { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #888; }
.leg-box { width: 10px; height: 10px; border-radius: 3px; }

.cal-summary { display: grid; grid-template-columns: repeat(4,1fr); gap: 8px; margin-top: 4px; }
.cal-sum-card { background: #f8f9fa; border-radius: 10px; padding: 10px 8px; text-align: center; }
.cal-sum-val { font-size: 18px; font-weight: 700; }
.cal-sum-lbl { font-size: 10px; color: #aaa; margin-top: 2px; }

.tl-item { display: flex; gap: 12px; padding-bottom: 16px; }
.tl-left { display: flex; flex-direction: column; align-items: center; }
.tl-dot { width: 11px; height: 11px; border-radius: 50%; flex-shrink: 0; margin-top: 3px; }
.tl-line { width: 1px; flex: 1; background: #e8e8e8; margin-top: 4px; }
.tl-body { flex: 1; }
.tl-title { font-size: 13px; font-weight: 600; color: #222; }
.tl-sub { font-size: 11px; color: #aaa; margin-top: 2px; }
.tl-reason { font-size: 11px; color: #777; margin-top: 4px; font-style: italic; }
.tl-amt { font-size: 13px; font-weight: 700; margin-top: 4px; }
.amt-red { color: #DC2626; }
.amt-green { color: #059669; }
.dot-red { background: #EF4444; }
.dot-amber { background: #F59E0B; }
.dot-green { background: #10B981; }

.disc-total { background: #FEF2F2; border-radius: 12px; padding: 12px 14px; margin-top: 8px; }
.disc-total-row { display: flex; justify-content: space-between; padding: 3px 0; font-size: 12px; }
.disc-total-row.final { border-top: 1px solid #FECACA; margin-top: 6px; padding-top: 8px; font-weight: 700; font-size: 13px; }

.formula-card { background: #ECFDF5; border-radius: 14px; padding: 14px 16px; margin-bottom: 16px; }
.formula-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 13px; }
.formula-row.total { border-top: 1.5px solid #A7F3D0; margin-top: 6px; padding-top: 10px; font-weight: 700; font-size: 15px; color: #065F46; }
.formula-label { color: #555; }
.formula-minus { color: #DC2626; }
.formula-val { font-weight: 600; }

.adv-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
.adv-item:last-child { border-bottom: none; }
.adv-amt-box { width: 44px; height: 44px; border-radius: 10px; background: #FFFBEB; color: #92400E; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; }
.adv-body { flex: 1; }
.adv-title { font-size: 13px; font-weight: 500; color: #222; }
.adv-sub { font-size: 11px; color: #aaa; margin-top: 2px; }
.adv-badge { font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
.adv-pending { background: #FEF3C7; color: #92400E; }
.adv-done { background: #ECFDF5; color: #065F46; }

.action-buttons-group { display: flex; gap: 8px; margin-top: 12px; }
.action-btn { flex: 1; padding: 10px; background: #1D9E75; color: #fff; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
.action-btn:hover { background: #0F6E56; }
.action-btn.secondary { background: #ef4444; }
.action-btn.secondary:hover { background: #dc2626; }

@media (max-width: 768px) {
  .stats-row { grid-template-columns: repeat(2, minmax(0,1fr)); }
  .cal-summary { grid-template-columns: repeat(2,1fr); }
}
</style>

<div class="page">
  <div class="hero">
    <div class="hero-top">
      <a href="{{ route('contractor.workers.index') }}" class="back">← رجوع</a>
      <div class="more">···</div>
    </div>
    <div class="worker-info">
      <div class="avatar-lg">{{ substr($worker->name, 0, 1) }}{{ substr(explode(' ', $worker->name)[1] ?? '', 0, 1) }}</div>
      <div>
        <div class="worker-name">{{ $worker->name }}</div>
        <div class="worker-meta">
          <span class="id-badge">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }}</span>
          <span class="status-badge {{ !$worker->is_active ? 'inactive' : '' }}">{{ $worker->is_active ? 'نشط' : 'غير نشط' }}</span>
          <span class="join-date">منذ {{ $worker->created_at->format('d M Y') }}</span>
        </div>
      </div>
    </div>
    <div class="actions">
      <a href="{{ route('contractor.workers.edit', $worker->id) }}" class="act-btn"><span class="act-icon">✎</span>تعديل</a>
      <button class="act-btn" onclick="alert('قابل للتطوير');"><span class="act-icon">−</span>خصم</button>
      <button class="act-btn" onclick="alert('قابل للتطوير');"><span class="act-icon">↑</span>سلفة</button>
    </div>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-val val-green">{{ number_format($ledger['gross'] ?? 0, 0) }}</div>
      <div class="stat-label">أجر الشهر (ج)</div>
    </div>
    <div class="stat-card">
      <div class="stat-val val-red">{{ number_format($ledger['deductions'] ?? 0, 0) }}</div>
      <div class="stat-label">خصومات (ج)</div>
    </div>
    <div class="stat-card">
      <div class="stat-val val-amber">{{ number_format($ledger['advances'] ?? 0, 0) }}</div>
      <div class="stat-label">سلف معلقة</div>
    </div>
    <div class="stat-card">
      <div class="stat-val val-blue">{{ $ledger['attendance_rate'] ?? 0 }}%</div>
      <div class="stat-label">نسبة الحضور</div>
    </div>
  </div>

  <div class="tabs-wrap">
    <div class="tabs-bar">
      <div class="tab-btn active" onclick="switchTab(0)">نظرة عامة</div>
      <div class="tab-btn" onclick="switchTab(1)">الحضور</div>
      <div class="tab-btn" onclick="switchTab(2)">الخصومات</div>
      <div class="tab-btn" onclick="switchTab(3)">الحساب</div>
    </div>

    <!-- TAB 1: Overview -->
    <div class="tab-content active" id="tab0">
      <div class="sec-title">الشركات المعتادة هذا الشهر</div>
      @forelse($frequentCompanies ?? [] as $index => $company)
        @php
          $colors = ['blue', 'teal', 'purple'];
          $colorClass = $colors[$index % 3] ?? 'blue';
          $initials = substr($company['name'], 0, 1);
        @endphp
        <div class="co-freq">
          <div class="co-dot co-{{ $colorClass }}">{{ $initials }}</div>
          <div style="flex:1;">
            <div class="co-name">{{ $company['name'] }}</div>
            <div class="co-bar-wrap"><div class="co-bar" style="width:{{ min($company['percentage'] ?? 50, 100) }}%;"></div></div>
          </div>
          <div class="co-days">{{ $company['days'] ?? 0 }} يوم</div>
        </div>
      @empty
        <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد بيانات</div>
      @endforelse

      <div class="sec-title" style="margin-top:20px;">نشاط الأسبوع الحالي</div>
      @forelse($thisWeekActivity ?? [] as $activity)
        <div class="week-row">
          <div style="min-width:38px;text-align:center;">
            <div class="week-date">{{ $activity['day'] ?? '-' }}</div>
            <div class="week-day">{{ $activity['day_name'] ?? '-' }}</div>
          </div>
          <div class="week-co">
            <div>{{ $activity['company_name'] ?? 'غير موزع' }}</div>
            <div class="week-co-sub">{{ $activity['rate_label'] ?? '-' }}</div>
          </div>
          <div>
            @if(($activity['status'] ?? '') === 'absent')
              <span class="absent-pill">غياب</span>
            @elseif(($activity['status'] ?? '') === 'partial')
              <div class="earn" style="color:#D97706;">{{ $activity['amount'] ?? 0 }} ج</div>
            @else
              <div class="earn">{{ $activity['amount'] ?? 0 }} ج</div>
            @endif
          </div>
        </div>
      @empty
        <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد بيانات</div>
      @endforelse
    </div>

    <!-- TAB 2: Attendance -->
    <div class="tab-content" id="tab1">
      <div class="cal-header">
        <div class="cal-day-name">أح</div><div class="cal-day-name">إث</div>
        <div class="cal-day-name">ث</div><div class="cal-day-name">أر</div>
        <div class="cal-day-name">خ</div><div class="cal-day-name">ج</div>
        <div class="cal-day-name">س</div>
      </div>
      <div class="cal-grid">
        @foreach($calendar ?? [] as $day)
          <div class="cal-cell {{ $day['class'] ?? 'c-empty' }}">
            @if(isset($day['day'])){{ $day['day'] }}@endif
          </div>
        @endforeach
      </div>

      <div class="cal-legend">
        <div class="leg"><div class="leg-box" style="background:#ECFDF5;border:1px solid #6EE7B7;"></div>حضور كامل</div>
        <div class="leg"><div class="leg-box" style="background:#FFFBEB;border:1px solid #FCD34D;"></div>خصم جزئي</div>
        <div class="leg"><div class="leg-box" style="background:#FEF2F2;border:1px solid #FCA5A5;"></div>غياب</div>
        <div class="leg"><div class="leg-box" style="background:#1D9E75;"></div>اليوم</div>
      </div>

      <div class="cal-summary">
        <div class="cal-sum-card"><div class="cal-sum-val" style="color:#059669;">{{ $ledger['attendance_days'] ?? 0 }}</div><div class="cal-sum-lbl">حضور كامل</div></div>
        <div class="cal-sum-card"><div class="cal-sum-val" style="color:#D97706;">{{ $ledger['partial_days'] ?? 0 }}</div><div class="cal-sum-lbl">خصم جزئي</div></div>
        <div class="cal-sum-card"><div class="cal-sum-val" style="color:#DC2626;">{{ $ledger['absent_days'] ?? 0 }}</div><div class="cal-sum-lbl">غياب</div></div>
        <div class="cal-sum-card"><div class="cal-sum-val" style="color:#2563EB;">{{ $ledger['attendance_rate'] ?? 0 }}%</div><div class="cal-sum-lbl">نسبة الحضور</div></div>
      </div>
    </div>

    <!-- TAB 3: Deductions -->
    <div class="tab-content" id="tab2">
      <div style="display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap;">
        <span style="background:#ECFDF5;color:#065F46;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600;">الكل</span>
        <span style="background:#f5f5f5;color:#888;border-radius:20px;padding:4px 12px;font-size:12px;">هذا الأسبوع</span>
        <span style="background:#f5f5f5;color:#888;border-radius:20px;padding:4px 12px;font-size:12px;">هذا الشهر</span>
      </div>

      @forelse($deductionsTimeline ?? [] as $deduction)
        @php
          $isDotRed = ($deduction['type'] === 'full');
          $isDotGreen = ($deduction['type'] === 'reversal');
          $isDotAmber = !$isDotRed && !$isDotGreen;
        @endphp
        <div class="tl-item">
          <div class="tl-left">
            @if($isDotRed)
              <div class="tl-dot dot-red"></div>
            @elseif($isDotGreen)
              <div class="tl-dot dot-green"></div>
            @else
              <div class="tl-dot dot-amber"></div>
            @endif
            <div class="tl-line"></div>
          </div>
          <div class="tl-body">
            <div class="tl-title">{{ $deduction['title'] ?? 'خصم' }}</div>
            <div class="tl-sub">{{ $deduction['date'] ?? '-' }} · {{ $deduction['company_name'] ?? '-' }}</div>
            <div class="tl-reason">"{{ $deduction['reason'] ?? '-' }}"</div>
            @php
              $amtClass = (($deduction['type'] ?? '') === 'reversal') ? 'amt-green' : 'amt-red';
              $amtSign = (($deduction['type'] ?? '') === 'reversal') ? '+' : '−';
            @endphp
            <div class="tl-amt {{ $amtClass }}">
              {{ $amtSign }} {{ abs($deduction['amount'] ?? 0) }} ج
              @if(isset($deduction['original_amount']))
                <span style="font-size:11px;color:#aaa;">(من {{ $deduction['original_amount'] }} ج)</span>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد خصومات</div>
      @endforelse

      <div class="disc-total">
        <div class="disc-total-row"><span style="color:#777;">إجمالي الخصومات</span><span style="color:#DC2626;font-weight:600;">− {{ $ledger['deductions'] ?? 0 }} ج</span></div>
        <div class="disc-total-row"><span style="color:#777;">استردادات</span><span style="color:#059669;font-weight:600;">+ {{ $ledger['reversals'] ?? 0 }} ج</span></div>
        <div class="disc-total-row final"><span>صافي الخصم</span><span style="color:#DC2626;">− {{ ($ledger['deductions'] ?? 0) - ($ledger['reversals'] ?? 0) }} ج</span></div>
      </div>
    </div>

    <!-- TAB 4: Account -->
    <div class="tab-content" id="tab3">
      <div class="formula-card">
        <div style="font-size:12px;color:#065F46;font-weight:600;margin-bottom:10px;">صافي الأجر — {{ now()->format('B Y') }}</div>
        <div class="formula-row"><span class="formula-label">الأجر الإجمالي</span><span class="formula-val" style="color:#059669;">{{ $ledger['gross'] ?? 0 }} ج</span></div>
        <div class="formula-row"><span class="formula-label formula-minus">− خصومات</span><span class="formula-val formula-minus">{{ $ledger['deductions'] ?? 0 }} ج</span></div>
        <div class="formula-row"><span class="formula-label formula-minus">− سلف محصلة</span><span class="formula-val formula-minus">{{ $ledger['collected_advances'] ?? 0 }} ج</span></div>
        <div class="formula-row total"><span>صافي المستحق</span><span>{{ ($ledger['gross'] ?? 0) - ($ledger['deductions'] ?? 0) - ($ledger['collected_advances'] ?? 0) }} ج</span></div>
      </div>

      <div class="sec-title">السلف المعلقة</div>
      @forelse($pendingAdvances ?? [] as $advance)
        <div class="adv-item">
          <div class="adv-amt-box">{{ $advance['amount'] ?? 0 }}</div>
          <div class="adv-body">
            <div class="adv-title">سلفة — {{ $advance['date'] ?? '-' }}</div>
            <div class="adv-sub">{{ $advance['recovery_method'] ?? 'طريقة محددة' }}</div>
          </div>
          <span class="adv-badge adv-pending">معلق</span>
        </div>
      @empty
        <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد سلف معلقة</div>
      @endforelse

      <div class="sec-title" style="margin-top:16px;">سلف محصلة سابقاً</div>
      @forelse($collectedAdvances ?? [] as $advance)
        <div class="adv-item">
          <div class="adv-amt-box" style="background:#ECFDF5;color:#065F46;">{{ $advance['amount'] ?? 0 }}</div>
          <div class="adv-body">
            <div class="adv-title">سلفة — {{ $advance['date'] ?? '-' }}</div>
            <div class="adv-sub">تم خصمها بتاريخ {{ $advance['collected_date'] ?? '-' }}</div>
          </div>
          <span class="adv-badge adv-done">تم</span>
        </div>
      @empty
        <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد سلف محصلة</div>
      @endforelse

      <div class="action-buttons-group">
        <button class="action-btn">تسجيل سلفة</button>
        <button class="action-btn">تسجيل دفع</button>
        @if($worker->is_active)
          <button class="action-btn secondary" onclick="deactivateWorker({{ $worker->id }})">إيقاف العامل</button>
        @else
          <button class="action-btn" onclick="reactivateWorker({{ $worker->id }})">تفعيل العامل</button>
        @endif
      </div>
    </div>

  </div>
  <div style="height:24px;"></div>
</div>

<script>
function switchTab(i) {
  document.querySelectorAll('.tab-btn').forEach((b,j) => b.classList.toggle('active', i===j));
  document.querySelectorAll('.tab-content').forEach((c,j) => c.classList.toggle('active', i===j));
}

function deactivateWorker(workerId) {
  if(confirm('هل تريد بالفعل إيقاف هذا العامل؟')) {
    fetch('/contractor/workers/' + workerId, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ is_active: false })
    })
    .then(r => r.json())
    .then(data => {
      alert('تم إيقاف العامل بنجاح');
      location.reload();
    })
    .catch(e => alert('حدث خطأ: ' + e));
  }
}

function reactivateWorker(workerId) {
  if(confirm('هل تريد بالفعل تفعيل هذا العامل؟')) {
    fetch('/contractor/workers/' + workerId, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ is_active: true })
    })
    .then(r => r.json())
    .then(data => {
      alert('تم تفعيل العامل بنجاح');
      location.reload();
    })
    .catch(e => alert('حدث خطأ: ' + e));
  }
}
</script>
@endsection
