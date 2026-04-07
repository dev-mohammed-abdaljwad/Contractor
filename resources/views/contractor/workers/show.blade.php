
@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

/* Header */
.hero {
  background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 60%, #2DC98A 100%);
  padding: 28px 20px 60px;
  position: relative;
  margin: -28px -28px 0 -28px;
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
.id-badge {
  background: rgba(255,255,255,0.2); color: #fff;
  font-size: 11px; padding: 2px 8px; border-radius: 20px;
}
.status-badge {
  background: #4ade80; color: #14532d;
  font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.status-badge.inactive { background: #ef4444; color: #fff; }
.join-date { color: rgba(255,255,255,0.75); font-size: 12px; }

/* Action buttons */
.actions {
  display: flex; gap: 8px; margin-top: 16px;
}
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

/* Stats cards */
.stats-row {
  display: grid; grid-template-columns: repeat(4, minmax(0,1fr));
  gap: 10px; padding: 0 16px;
  margin-top: -36px; position: relative; z-index: 10;
  margin-bottom: 16px;
}
.stat-card {
  background: #fff; border-radius: 14px;
  padding: 12px 10px; text-align: center;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.stat-val { font-size: 15px; font-weight: 700; margin-bottom: 3px; }
.stat-label { font-size: 10px; color: #888; line-height: 1.3; }
.val-green { color: #0F6E56; }
.val-red { color: #DC2626; }
.val-amber { color: #D97706; }
.val-blue { color: #2563EB; }

/* Tabs */
.tabs-wrap {
  background: #fff;
  border-radius: 14px 14px 0 0;
  margin: 0 16px 0 16px;
  overflow: hidden;
  box-shadow: 0 -2px 12px rgba(0,0,0,0.04);
}
.tabs-bar {
  display: flex; border-bottom: 1px solid #f0f0f0;
  padding: 0 4px;
}
.tab-btn {
  flex: 1; text-align: center; padding: 12px 4px;
  font-size: 12px; color: #999; cursor: pointer;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}
.tab-btn.active { color: #1D9E75; font-weight: 600; border-bottom-color: #1D9E75; }

/* Tab content */
.tab-content { padding: 16px; display: none; }
.tab-content.active { display: block; }

/* Week activity */
.week-row {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 0; border-bottom: 1px solid #f5f5f5;
}
.week-row:last-child { border-bottom: none; }
.week-date { font-size: 11px; color: #aaa; min-width: 36px; text-align: center; }
.week-day { font-size: 10px; color: #ccc; }
.week-co {
  flex: 1; font-size: 13px; font-weight: 500; color: #222;
}
.week-co-sub { font-size: 11px; color: #999; margin-top: 1px; }
.earn { font-size: 13px; font-weight: 600; color: #0F6E56; }
.absent-pill {
  font-size: 10px; font-weight: 600; padding: 2px 6px;
  border-radius: 20px; background: #FEE2E2; color: #991B1B;
}

/* Section title */
.sec-title {
  font-size: 12px; font-weight: 600; color: #999;
  text-transform: uppercase; letter-spacing: 0.06em;
  margin-bottom: 10px; margin-top: 4px;
}

/* Company frequency */
.co-freq {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 0; border-bottom: 1px solid #f5f5f5;
}
.co-freq:last-child { border-bottom: none; }
.co-dot {
  width: 36px; height: 36px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 600; flex-shrink: 0;
}
.co-blue { background: #EFF6FF; color: #1D4ED8; }
.co-teal { background: #ECFDF5; color: #065F46; }
.co-purple { background: #F5F3FF; color: #5B21B6; }
.co-name { flex: 1; font-size: 13px; font-weight: 500; color: #222; }
.co-days { font-size: 12px; color: #aaa; }
.co-bar-wrap { height: 4px; background: #f0f0f0; border-radius: 2px; margin-top: 4px; }
.co-bar { height: 100%; border-radius: 2px; background: #1D9E75; }

/* Calendar */
.cal-header { display: grid; grid-template-columns: repeat(7,1fr); gap: 3px; margin-bottom: 4px; }
.cal-day-name { text-align: center; font-size: 10px; color: #bbb; padding: 3px 0; }
.cal-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 3px; }
.cal-cell {
  aspect-ratio: 1; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 500;
}
.c-present { background: #ECFDF5; color: #065F46; }
.c-partial { background: #FFFBEB; color: #92400E; }
.c-absent { background: #FEF2F2; color: #991B1B; }
.c-today { background: #1D9E75; color: #fff; font-weight: 700; }
.c-empty { background: #fafafa; color: #ddd; }

.cal-legend {
  display: flex; gap: 12px; flex-wrap: wrap;
  margin-top: 12px; margin-bottom: 16px;
}
.leg { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #888; }
.leg-box { width: 10px; height: 10px; border-radius: 3px; }

.cal-summary {
  display: grid; grid-template-columns: repeat(4,1fr); gap: 8px; margin-top: 4px;
}
.cal-sum-card {
  background: #f8f9fa; border-radius: 10px;
  padding: 10px 8px; text-align: center;
}
.cal-sum-val { font-size: 18px; font-weight: 700; }
.cal-sum-lbl { font-size: 10px; color: #aaa; margin-top: 2px; }

/* Timeline */
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

.disc-total {
  background: #FEF2F2; border-radius: 12px; padding: 12px 14px; margin-top: 8px;
}
.disc-total-row { display: flex; justify-content: space-between; padding: 3px 0; font-size: 12px; }
.disc-total-row.final { border-top: 1px solid #FECACA; margin-top: 6px; padding-top: 8px; font-weight: 700; font-size: 13px; }

/* Account tab */
.formula-card {
  background: #ECFDF5; border-radius: 14px; padding: 14px 16px; margin-bottom: 16px;
}
.formula-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 13px; }
.formula-row.total {
  border-top: 1.5px solid #A7F3D0; margin-top: 6px; padding-top: 10px;
  font-weight: 700; font-size: 15px; color: #065F46;
}
.formula-label { color: #555; }
.formula-minus { color: #DC2626; }
.formula-val { font-weight: 600; }

.adv-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 0; border-bottom: 1px solid #f5f5f5;
}
.adv-item:last-child { border-bottom: none; }
.adv-amt-box {
  width: 44px; height: 44px; border-radius: 10px;
  background: #FFFBEB; color: #92400E;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; flex-shrink: 0;
}
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

/* ============ MODALS ============ */
.modal-overlay {
  display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5); z-index: 999; align-items: center; justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal-box {
  background: #fff; border-radius: 16px; width: 90%; max-width: 500px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); max-height: 90vh; overflow-y: auto;
  animation: slideUp 0.3s ease;
}
@keyframes slideUp {
  from { transform: translateY(40px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.modal-header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 20px 24px; border-bottom: 1px solid #f0f0f0;
}
.modal-title { font-size: 16px; font-weight: 700; color: #222; }
.modal-close {
  background: none; border: none; font-size: 24px; cursor: pointer;
  color: #999; transition: color 0.2s;
}
.modal-close:hover { color: #333; }
.modal-body { padding: 24px; }
.modal-footer {
  display: flex; gap: 12px; padding: 16px 24px;
  border-top: 1px solid #f0f0f0;
}
.modal-btn {
  flex: 1; padding: 12px; border-radius: 10px; border: none;
  font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s;
}
.modal-btn-primary { background: #1D9E75; color: #fff; }
.modal-btn-primary:hover { background: #0F6E56; }
.modal-btn-secondary { background: #f5f5f5; color: #333; }
.modal-btn-secondary:hover { background: #e8e8e8; }

.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
.form-input {
  width: 100%; padding: 10px 12px; border: 1px solid #e0e0e0;
  border-radius: 8px; font-size: 14px; font-family: inherit;
}
.form-input:focus { outline: none; border-color: #1D9E75; box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1); }
.form-input:disabled { background: #f5f5f5; color: #999; }
.form-textarea {
  width: 100%; padding: 10px 12px; border: 1px solid #e0e0e0;
  border-radius: 8px; font-size: 13px; font-family: inherit; resize: vertical; min-height: 80px;
}
.form-textarea:focus { outline: none; border-color: #1D9E75; box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1); }

.form-alert { background: #FEF2F2; border-left: 4px solid #EF4444; padding: 12px; border-radius: 6px; font-size: 13px; color: #7F1D1D; margin-bottom: 16px; }
.form-alert.success { background: #ECFDF5; border-color: #10B981; color: #065F46; }

</style>

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
    <button class="act-btn" onclick="openModal('editModal')"><span class="act-icon">✎</span>تعديل</button>
    <button class="act-btn" onclick="openModal('deductionModal')"><span class="act-icon">−</span>خصم</button>
    <button class="act-btn" onclick="openModal('advanceModal')"><span class="act-icon">↑</span>سلفة</button>
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
  <div class="tab-btn active" onclick="switchTab(this, 'tab0')">نظرة عامة</div>
  <div class="tab-btn" onclick="switchTab(this, 'tab1')">الحضور</div>
  <div class="tab-btn" onclick="switchTab(this, 'tab2')">الخصومات</div>
  <div class="tab-btn" onclick="switchTab(this, 'tab3')">الحساب</div>
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
      <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد نشاط</div>
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
      <div class="tl-item">
        <div class="tl-left"><div class="tl-dot {{ (($deduction['type'] ?? '') === 'reversal') ? 'dot-green' : 'dot-amber' }}"></div><div class="tl-line"></div></div>
        <div class="tl-body">
          <div class="tl-title">{{ $deduction['title'] ?? 'خصم' }}</div>
          <div class="tl-sub">{{ $deduction['date'] ?? '-' }} · {{ $deduction['company_name'] ?? '-' }}</div>
          <div class="tl-reason">"{{ $deduction['reason'] ?? '-' }}"</div>
          <div class="tl-amt {{ (($deduction['type'] ?? '') === 'reversal') ? 'amt-green' : 'amt-red' }}">
            {{ (($deduction['type'] ?? '') === 'reversal') ? '+' : '−' }} {{ $deduction['amount'] ?? 0 }} ج
            @if($deduction['original_amount'] ?? 0)
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
      <div class="disc-total-row final"><span>صافي الخصم</span><span style="color:#DC2626;">− {{ $ledger['deductions'] ?? 0 }} ج</span></div>
    </div>
  </div>

  <!-- TAB 4: Account -->
  <div class="tab-content" id="tab3">
    <div class="formula-card">
      <div style="font-size:12px;color:#065F46;font-weight:600;margin-bottom:10px;">صافي الأجر</div>
      <div class="formula-row"><span class="formula-label">الأجر الإجمالي</span><span class="formula-val" style="color:#059669;">{{ number_format($ledger['gross'] ?? 0, 0) }} ج</span></div>
      <div class="formula-row"><span class="formula-label formula-minus">− خصومات</span><span class="formula-val formula-minus">{{ number_format($ledger['deductions'] ?? 0, 0) }} ج</span></div>
      <div class="formula-row"><span class="formula-label formula-minus">− سلف محصلة</span><span class="formula-val formula-minus">{{ number_format($ledger['advances_collected'] ?? 0, 0) }} ج</span></div>
      <div class="formula-row total"><span>صافي المستحق</span><span>{{ number_format(($ledger['gross'] ?? 0) - ($ledger['deductions'] ?? 0) - ($ledger['advances_collected'] ?? 0), 0) }} ج</span></div>
    </div>

    <div class="sec-title">السلف المعلقة</div>
    @forelse($pendingAdvances ?? [] as $advance)
      <div class="adv-item">
        <div class="adv-amt-box">{{ number_format($advance['amount'] ?? 0, 0) }}</div>
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
        <div class="adv-amt-box" style="background:#ECFDF5;color:#065F46;">{{ number_format($advance['amount'] ?? 0, 0) }}</div>
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

<!-- ============ MODALS ============ -->

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">تعديل بيانات العامل</div>
      <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="editForm">
        <div class="form-group">
          <label class="form-label">اسم العامل</label>
          <input type="text" class="form-input" id="workerName" value="{{ $worker->name }}" disabled>
        </div>
        <div class="form-group">
          <label class="form-label">الحالة</label>
          <select class="form-input" id="workerStatus">
            <option value="1" {{ $worker->is_active ? 'selected' : '' }}>نشط</option>
            <option value="0" {{ !$worker->is_active ? 'selected' : '' }}>غير نشط</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">ملاحظات</label>
          <textarea class="form-textarea" id="workerNotes" placeholder="أضف ملاحظات..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="modal-btn modal-btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
      <button class="modal-btn modal-btn-primary" onclick="saveWorkerEdit({{ $worker->id }})">حفظ التغييرات</button>
    </div>
  </div>
</div>

<!-- DEDUCTION MODAL -->
<div class="modal-overlay" id="deductionModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">تسجيل خصم</div>
      <button class="modal-close" onclick="closeModal('deductionModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="deductionForm">
        <div class="form-group">
          <label class="form-label">المبلغ (جنيه)</label>
          <input type="number" class="form-input" id="deductionAmount" placeholder="0.00" min="0" step="0.01">
        </div>
        <div class="form-group">
          <label class="form-label">النوع</label>
          <select class="form-input" id="deductionType">
            <option value="absence">غياب</option>
            <option value="damage">تلف</option>
            <option value="other">أخرى</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">السبب</label>
          <textarea class="form-textarea" id="deductionReason" placeholder="اشرح سبب الخصم..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">الشركة</label>
          <select class="form-input" id="deductionCompany">
            <option value="">اختر الشركة</option>
            @foreach($frequentCompanies ?? [] as $company)
              <option value="{{ $company['name'] }}">{{ $company['name'] }}</option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="modal-btn modal-btn-secondary" onclick="closeModal('deductionModal')">إلغاء</button>
      <button class="modal-btn modal-btn-primary" onclick="saveDeduction({{ $worker->id }})">تسجيل الخصم</button>
    </div>
  </div>
</div>

<!-- ADVANCE MODAL -->
<div class="modal-overlay" id="advanceModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">تسجيل سلفة</div>
      <button class="modal-close" onclick="closeModal('advanceModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="advanceForm">
        <div class="form-group">
          <label class="form-label">المبلغ (جنيه)</label>
          <input type="number" class="form-input" id="advanceAmount" placeholder="0.00" min="0" step="0.01">
        </div>
        <div class="form-group">
          <label class="form-label">طريقة الاسترجاع</label>
          <select class="form-input" id="advanceRecovery">
            <option value="salary_deduction">خصم من الراتب</option>
            <option value="lump_sum">دفعة واحدة</option>
            <option value="installments">أقساط</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">ملاحظات</label>
          <textarea class="form-textarea" id="advanceNotes" placeholder="أضف ملاحظات..."></textarea>
        </div>
        <div class="form-alert">
          ⚠️ سيتم خصم هذا المبلغ من راتب العامل وفقاً لطريقة الاسترجاع المختارة
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="modal-btn modal-btn-secondary" onclick="closeModal('advanceModal')">إلغاء</button>
      <button class="modal-btn modal-btn-primary" onclick="saveAdvance({{ $worker->id }})">تسجيل السلفة</button>
    </div>
  </div>
</div>

<script>
// ============ MODAL FUNCTIONS ============
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add('active');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.remove('active');
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {
      overlay.classList.remove('active');
    }
  });
});

// ============ EDIT WORKER ============
function saveWorkerEdit(workerId) {
  const status = document.getElementById('workerStatus').value;
  const notes = document.getElementById('workerNotes').value;

  fetch(`/contractor/workers/${workerId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      is_active: parseInt(status),
      notes: notes
    })
  })
  .then(r => {
    if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
    return r.json();
  })
  .then(data => {
    if (data.success) {
      alert('تم تحديث البيانات بنجاح');
      closeModal('editModal');
      location.reload();
    } else {
      alert('خطأ: ' + (data.message || 'حدث خطأ'));
    }
  })
  .catch(e => alert('خطأ: ' + e));
}

// ============ DEDUCTION ============
function saveDeduction(workerId) {
  const amount = document.getElementById('deductionAmount').value;
  const type = document.getElementById('deductionType').value;
  const reason = document.getElementById('deductionReason').value;
  const company = document.getElementById('deductionCompany').value;

  if (!amount) {
    alert('الرجاء إدخال المبلغ');
    return;
  }

  fetch(`/contractor/workers/${workerId}/deductions`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      amount: parseFloat(amount),
      type: type,
      reason: reason,
      company_name: company
    })
  })
  .then(r => {
    if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
    return r.json();
  })
  .then(data => {
    if (data.success) {
      alert('تم تسجيل الخصم بنجاح');
      closeModal('deductionModal');
      document.getElementById('deductionForm').reset();
      location.reload();
    } else {
      alert('خطأ: ' + (data.message || 'حدث خطأ'));
    }
  })
  .catch(e => alert('خطأ: ' + e.message));
}

// ============ ADVANCE ============
function saveAdvance(workerId) {
  const amount = document.getElementById('advanceAmount').value;
  const recovery = document.getElementById('advanceRecovery').value;
  const notes = document.getElementById('advanceNotes').value;

  if (!amount) {
    alert('الرجاء إدخال المبلغ');
    return;
  }

  fetch(`/contractor/workers/${workerId}/advances`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      amount: parseFloat(amount),
      recovery_method: recovery,
      notes: notes
    })
  })
  .then(r => {
    if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
    return r.json();
  })
  .then(data => {
    if (data.success) {
      alert('تم تسجيل السلفة بنجاح');
      closeModal('advanceModal');
      document.getElementById('advanceForm').reset();
      location.reload();
    } else {
      alert('خطأ: ' + (data.message || 'حدث خطأ'));
    }
  })
  .catch(e => alert('خطأ: ' + e.message));
}


function switchTab(el, tabId) {
  // Remove active class from all buttons
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
  // Add active class to clicked button
  if (el && el.classList) {
    el.classList.add('active');
  }
  
  // Hide all tab contents
  document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
  // Show selected tab content
  const tabContent = document.getElementById(tabId);
  if (tabContent) tabContent.classList.add('active');
  
  console.log('Switched to tab', tabId);
}


function deactivateWorker(workerId) {
  if(confirm('هل تريد بالفعل إيقاف هذا العامل؟')) {
    fetch('/contractor/workers/' + workerId, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: JSON.stringify({ is_active: false })
    })
    .then(r => {
      if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
      return r.json();
    })
    .then(data => {
      if (data.success) {
        window.showToast(data.message || 'تم إيقاف العامل بنجاح', 'success');
        setTimeout(() => {
          location.reload();
        }, 1500);
      } else {
        window.showToast(data.message || 'فشل إيقاف العامل', 'error');
      }
    })
    .catch(e => window.showToast('حدث خطأ: ' + e.message, 'error'));
  }
}

function reactivateWorker(workerId) {
  if(confirm('هل تريد بالفعل تفعيل هذا العامل؟')) {
    fetch('/contractor/workers/' + workerId, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: JSON.stringify({ is_active: true })
    })
    .then(r => {
      if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
      return r.json();
    })
    .then(data => {
      alert('تم تفعيل العامل بنجاح');
      location.reload();
    })
    .catch(e => alert('حدث خطأ: ' + e.message));
  }
}
</script>
@endsection
