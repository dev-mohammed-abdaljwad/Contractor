@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; background: #f5f6f8; min-height: 100vh; }

.topbar {
  background: linear-gradient(135deg, #0C447C 0%, #185FA5 100%);
  padding: 16px 20px 20px;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.page-title { color: #fff; font-size: 18px; font-weight: 700; }
.add-btn {
  background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);
  color: #fff; font-size: 12px; font-weight: 600;
  padding: 7px 14px; border-radius: 20px; cursor: pointer;
  display: flex; align-items: center; gap: 5px;
}
.search-wrap {
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 12px; padding: 9px 14px;
  display: flex; align-items: center; gap: 8px;
}
.search-icon { color: rgba(255,255,255,0.7); font-size: 14px; }
.search-fake { color: rgba(255,255,255,0.6); font-size: 13px; flex: 1; }

.stats-strip {
  display: grid; grid-template-columns: repeat(4, minmax(0,1fr));
  gap: 0; background: #fff;
  border-bottom: 1px solid #f0f0f0;
}
.strip-stat {
  text-align: center; padding: 12px 6px;
  border-left: 1px solid #f0f0f0;
}
.strip-stat:last-child { border-left: none; }
.strip-val { font-size: 18px; font-weight: 700; }
.strip-lbl { font-size: 10px; color: #aaa; margin-top: 2px; line-height: 1.3; }
.sv-blue { color: #185FA5; }
.sv-green { color: #059669; }
.sv-amber { color: #D97706; }
.sv-red { color: #DC2626; }

.filter-row {
  display: flex; gap: 7px; padding: 12px 16px;
  background: #fff; border-bottom: 1px solid #f0f0f0;
  overflow-x: auto;
}
.chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 13px; border-radius: 20px; font-size: 12px;
  white-space: nowrap; cursor: pointer; font-weight: 500;
  border: 1.5px solid transparent; flex-shrink: 0;
}
.chip-all { background: #EFF6FF; color: #1D4ED8; border-color: #93C5FD; }
.chip-daily { background: #ECFDF5; color: #065F46; border-color: #6EE7B7; }
.chip-weekly { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
.chip-bi { background: #F5F3FF; color: #5B21B6; border-color: #DDD6FE; }
.chip-overdue { background: #FEF2F2; color: #991B1B; border-color: #FCA5A5; }
.chip-inactive { background: #F3F4F6; color: #6B7280; border-color: #E5E7EB; }
.chip-count { background: rgba(0,0,0,0.08); border-radius: 20px; padding: 1px 6px; font-size: 10px; font-weight: 700; }

.sort-bar {
  display: flex; justify-content: space-between; align-items: center;
  padding: 8px 16px; background: #f5f6f8;
}
.sort-label { font-size: 11px; color: #bbb; }
.sort-select { font-size: 11px; color: #185FA5; font-weight: 600; cursor: pointer; background: none; border: none; }

.list-body { padding: 10px 12px 100px; }

.co-card {
  background: #fff; border-radius: 16px;
  margin-bottom: 12px;
  box-shadow: 0 1px 6px rgba(0,0,0,0.06);
  overflow: hidden; cursor: pointer;
  transition: box-shadow 0.2s;
  border-right: 4px solid transparent;
}
.co-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
.border-green { border-right-color: #10B981; }
.border-amber { border-right-color: #F59E0B; }
.border-red { border-right-color: #EF4444; }
.border-gray { border-right-color: #D1D5DB; }

.co-card-top { display: flex; align-items: flex-start; gap: 12px; padding: 13px 14px 8px; }

.co-av {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; font-weight: 700; flex-shrink: 0;
}
.av-teal { background: #ECFDF5; color: #065F46; }
.av-blue { background: #EFF6FF; color: #1D4ED8; }
.av-purple { background: #F5F3FF; color: #5B21B6; }
.av-coral { background: #FFF1EE; color: #9A3412; }
.av-amber { background: #FFFBEB; color: #92400E; }

.co-info { flex: 1; min-width: 0; }
.co-name { font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
.co-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

.cycle-tag {
  font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.cycle-daily { background: #ECFDF5; color: #065F46; }
.cycle-weekly { background: #EFF6FF; color: #1D4ED8; }
.cycle-bi { background: #F5F3FF; color: #5B21B6; }

.status-tag {
  font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.st-paid { background: #ECFDF5; color: #065F46; }
.st-due { background: #FEF3C7; color: #92400E; }
.st-overdue { background: #FEF2F2; color: #991B1B; }
.st-upcoming { background: #EFF6FF; color: #1D4ED8; }

.co-right { text-align: left; flex-shrink: 0; }
.co-amount { font-size: 16px; font-weight: 700; }
.am-green { color: #059669; }
.am-amber { color: #D97706; }
.am-red { color: #DC2626; }
.am-gray { color: #D1D5DB; }
.co-amount-lbl { font-size: 10px; color: #bbb; margin-top: 2px; text-align: left; }

.co-stats {
  display: grid; grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 6px; padding: 0 14px 8px;
}
.mini-stat { background: #f8f9fa; border-radius: 8px; padding: 7px 8px; }
.mini-val { font-size: 13px; font-weight: 700; color: #222; }
.mini-lbl { font-size: 10px; color: #aaa; margin-top: 1px; }

.urgency-row { padding: 0 14px 12px; }
.urgency-label-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
.urgency-lbl { font-size: 10px; color: #aaa; }
.urgency-days { font-size: 10px; font-weight: 600; }
.urgency-bar { height: 5px; background: #f0f0f0; border-radius: 3px; overflow: hidden; }
.urgency-fill { height: 100%; border-radius: 3px; }
.uf-green { background: #10B981; }
.uf-amber { background: #F59E0B; }
.uf-red { background: #EF4444; }
.uf-gray { background: #D1D5DB; }

.co-card-actions {
  display: flex; gap: 6px; padding: 0 14px 10px; flex-wrap: wrap;
}
.btn-view, .btn-deactivate {
  flex: 1; padding: 7px 10px; border: none; border-radius: 8px;
  font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s;
  min-width: 70px;
}
.btn-view {
  background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;
}
.btn-view:hover { background: #DBEAFE; }
.btn-deactivate {
  background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5;
}
.btn-deactivate:hover { background: #FEE2E2; }

.inactive-header {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 4px 6px; cursor: pointer;
}
.inactive-label { font-size: 12px; font-weight: 600; color: #9CA3AF; }
.inactive-count { font-size: 11px; background: #F3F4F6; color: #9CA3AF; padding: 2px 8px; border-radius: 20px; }
.inactive-toggle { font-size: 11px; color: #bbb; margin-left: auto; }
.co-card-inactive { opacity: 0.55; }

.total-bar {
  background: #fff; border-top: 1px solid #f0f0f0;
  padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;
  position: fixed; bottom: 0; left: 0; right: 0;
}
.total-bar-label { font-size: 12px; color: #aaa; }
.total-bar-val { font-size: 17px; font-weight: 700; color: #D97706; }
.total-bar-sub { font-size: 10px; color: #bbb; margin-top: 1px; }

/* Responsive: Tablet (481px - 768px) */
@media(max-width: 768px) {
  .topbar { padding: 12px 16px 16px; }
  .page-title { font-size: 16px; }
  .add-btn { font-size: 11px; padding: 6px 12px; }
  .search-fake { font-size: 12px; }
  
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); }
  .strip-stat { padding: 10px 4px; }
  .strip-val { font-size: 16px; }
  .strip-lbl { font-size: 9px; }
  
  .filter-row { gap: 5px; padding: 10px 12px; }
  .chip { padding: 5px 11px; font-size: 11px; }
  .chip-count { font-size: 9px; }
  
  .sort-bar { padding: 6px 12px; }
  .sort-label, .sort-select { font-size: 10px; }
  
  .co-card { margin-bottom: 10px; }
  .co-card-top { gap: 10px; padding: 11px 12px 6px; }
  .co-av { width: 40px; height: 40px; font-size: 16px; }
  .co-name { font-size: 13px; margin-bottom: 3px; }
  .co-meta { font-size: 9px; }
  .cycle-tag, .status-tag { font-size: 9px; padding: 2px 7px; }
  .co-meta > div { font-size: 10px; }
  .co-amount { font-size: 14px; }
  .co-amount-lbl { font-size: 9px; margin-top: 1px; }
  
  .co-stats { gap: 5px; padding: 0 12px 6px; }
  .mini-stat { padding: 6px 7px; }
  .mini-val { font-size: 12px; }
  .mini-lbl { font-size: 9px; margin-top: 0px; }
  
  .urgency-row { padding: 0 12px 10px; }
  .urgency-lbl, .urgency-days { font-size: 9px; }
  .urgency-bar { height: 4px; }
  
  .co-card-actions { padding: 0 12px 8px; gap: 5px; }
  .btn-view, .btn-deactivate { padding: 6px 8px; font-size: 10px; }
  
  .inactive-header { padding: 10px 2px 4px; }
  .inactive-label, .inactive-count { font-size: 11px; }
  
  .total-bar { padding: 10px 12px; }
  .total-bar-label { font-size: 11px; }
  .total-bar-val { font-size: 15px; }
  .total-bar-sub { font-size: 9px; }
  
  .list-body { padding: 10px 10px 90px; }
}

/* Responsive: Mobile (< 480px) */
@media(max-width: 480px) {
  .topbar { padding: 10px 14px 12px; }
  .topbar-row { margin-bottom: 10px; }
  .page-title { font-size: 14px; }
  .add-btn { font-size: 10px; padding: 5px 10px; }
  .search-wrap { padding: 8px 10px; margin-top: 8px; }
  .search-fake { font-size: 11px; }
  
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 1px; }
  .strip-stat { padding: 8px 3px; border-width: 0.5px; }
  .strip-val { font-size: 14px; }
  .strip-lbl { font-size: 8px; }
  
  .filter-row { gap: 5px; padding: 8px 10px; }
  .chip { padding: 4px 10px; font-size: 10px; }
  .chip-count { font-size: 8px; }
  
  .sort-bar { padding: 5px 10px; }
  .sort-label, .sort-select { font-size: 9px; }
  
  .co-card { margin-bottom: 8px; }
  .co-card-top { gap: 9px; padding: 9px 10px 5px; }
  .co-av { width: 36px; height: 36px; font-size: 14px; }
  .co-name { font-size: 12px; margin-bottom: 2px; }
  .co-meta { font-size: 8px; gap: 4px; }
  .cycle-tag, .status-tag { font-size: 8px; padding: 1px 6px; }
  .co-meta > div { font-size: 9px; }
  .co-right { }
  .co-amount { font-size: 13px; }
  .co-amount-lbl { font-size: 8px; }
  
  .co-stats { gap: 4px; padding: 0 10px 5px; }
  .mini-stat { padding: 5px 6px; }
  .mini-val { font-size: 11px; }
  .mini-lbl { font-size: 8px; }
  
  .urgency-row { padding: 0 10px 8px; }
  .urgency-lbl, .urgency-days { font-size: 8px; }
  .urgency-bar { height: 4px; }
  
  .co-card-actions { padding: 0 10px 7px; gap: 4px; }
  .btn-view, .btn-deactivate { padding: 5px 7px; font-size: 9px; height: 28px; }
  
  .inactive-header { padding: 8px 2px 3px; }
  .inactive-label, .inactive-count, .inactive-toggle { font-size: 10px; }
  
  .total-bar { padding: 8px 10px; flex-direction: column; align-items: flex-start; gap: 4px; }
  .total-bar-label { font-size: 10px; }
  .total-bar-val { font-size: 14px; }
  .total-bar-sub { font-size: 8px; margin-top: 0px; }
  
  .list-body { padding: 8px 8px 80px; }
}
</style>

<div>
  <!-- Topbar -->
  <div class="topbar">
    <div class="topbar-row">
      <div class="page-title">الشركات</div>
      <div class="add-btn" onclick="openCompanyModal(false)">+ إضافة شركة</div>
    </div>
    <div class="search-wrap">
      <span class="search-icon">🔍</span>
      <div class="search-fake">ابحث باسم الشركة أو المسؤول...</div>
    </div>
  </div>

  <!-- Stats strip -->
  <div class="stats-strip">
    <div class="strip-stat"><div class="strip-val sv-blue">{{ $active_count }}</div><div class="strip-lbl">شركات نشطة</div></div>
    <div class="strip-stat"><div class="strip-val sv-green">{{ $today_count }}</div><div class="strip-lbl">عامل اليوم</div></div>
    <div class="strip-stat"><div class="strip-val sv-amber" style="font-size:14px;">{{ number_format($total_due, 0) }}</div><div class="strip-lbl">مستحق (ج)</div></div>
    <div class="strip-stat"><div class="strip-val sv-red">{{ $overdue_count }}</div><div class="strip-lbl">متأخرة</div></div>
  </div>

  <!-- Filters -->
  <div class="filter-row">
    <div class="chip chip-all">الكل <span class="chip-count">{{ $active_count }}</span></div>
    @if($activeCompanies->where('payment_cycle', 'يومي')->count() > 0)
      <div class="chip chip-daily">يومي <span class="chip-count">{{ $activeCompanies->where('payment_cycle', 'يومي')->count() }}</span></div>
    @endif
    @if($activeCompanies->where('payment_cycle', 'أسبوعي')->count() > 0)
      <div class="chip chip-weekly">أسبوعي <span class="chip-count">{{ $activeCompanies->where('payment_cycle', 'أسبوعي')->count() }}</span></div>
    @endif
    @if($activeCompanies->where('payment_cycle', 'نص شهري')->count() > 0)
      <div class="chip chip-bi">نص شهري <span class="chip-count">{{ $activeCompanies->where('payment_cycle', 'نص شهري')->count() }}</span></div>
    @endif
    @if($overdue_count > 0)
      <div class="chip chip-overdue">متأخرة <span class="chip-count">{{ $overdue_count }}</span></div>
    @endif
    @if($inactiveCompanies->count() > 0)
      <div class="chip chip-inactive">غير نشطة <span class="chip-count">{{ $inactiveCompanies->count() }}</span></div>
    @endif
  </div>

  <!-- Sort -->
  <div class="sort-bar">
    <span class="sort-label">{{ $active_count }} شركات نشطة</span>
    <select class="sort-select">
      <option>ترتيب: المستحق أولاً</option>
      <option>ترتيب: الاسم</option>
      <option>ترتيب: عدد العمال</option>
      <option>ترتيب: موعد الدفع</option>
    </select>
  </div>

  <div class="list-body">
    @forelse($activeCompanies as $company)
      @php
        $borderClass = match($company->payment_status) {
          'overdue' => 'border-red',
          'due' => 'border-amber',
          'paid' => 'border-green',
          default => 'border-amber'
        };
        
        $avatarClass = 'av-teal';
        $initials = substr($company->name, 0, 1);
        
        $urgencyFillColor = match($company->payment_status) {
          'overdue' => 'uf-red',
          'due' => 'uf-amber',
          'paid' => 'uf-green',
          default => ''
        };
        
        $urgencyFillWidth = match($company->payment_status) {
          'overdue' => '100%',
          'due' => '80%',
          'paid' => '100%',
          default => '35%'
        };
        
        $statusClass = match($company->payment_status) {
          'overdue' => 'st-overdue',
          'due' => 'st-due',
          'paid' => 'st-paid',
          default => 'st-upcoming'
        };
        
        $amountClass = match($company->payment_status) {
          'overdue' => 'am-red',
          'due' => 'am-amber',
          'paid' => 'am-green',
          default => 'am-amber'
        };
      @endphp
      
      <div class="co-card {{ $borderClass }}">
        <div class="co-card-top">
          <div class="co-av {{ $avatarClass }}">{{ $initials }}</div>
          <div class="co-info">
            <div class="co-name">{{ $company->name }}</div>
            <div class="co-meta">
              <span class="cycle-tag cycle-weekly">{{ $company->payment_cycle ?? 'لا محدد' }}</span>
              <span class="status-tag {{ $statusClass }}">{{ $company->payment_status_label }}</span>
            </div>
            <div style="font-size:11px;color:#aaa;margin-top:4px;">{{ $company->contact_person }} · آخر دفعة: {{ $company->last_payment_date }}</div>
          </div>
          <div class="co-right">
            <div class="co-amount {{ $amountClass }}">{{ number_format($company->amount_due, 0) }} ج</div>
            <div class="co-amount-lbl">{{ $company->amount_due > 0 ? 'مستحق' : 'متسدد' }}</div>
          </div>
        </div>
        <div class="co-stats">
          <div class="mini-stat"><div class="mini-val" style="color:{{ $company->workers_today > 0 ? '#059669' : '#D1D5DB' }};">{{ $company->workers_today }}</div><div class="mini-lbl">عمال اليوم</div></div>
          <div class="mini-stat"><div class="mini-val">{{ number_format($company->daily_wage, 0) }} ج</div><div class="mini-lbl">أجر/عامل</div></div>
          <div class="mini-stat"><div class="mini-val">{{ number_format($company->total_month, 0) }} ج</div><div class="mini-lbl">إجمالي الشهر</div></div>
        </div>
        <div class="urgency-row">
          <div class="urgency-label-row">
            <span class="urgency-lbl">{{ $company->urgency_label }}</span>
            <span class="urgency-days" style="color:{{ $company->payment_status === 'overdue' ? '#DC2626' : ($company->payment_status === 'paid' ? '#059669' : '#D97706') }};">
              @if($company->payment_status === 'overdue')
                متأخر {{ $company->urgency_days }} يوم!
              @elseif($company->payment_status === 'due')
                يستحق اليوم
              @elseif($company->payment_status === 'paid')
                تم التحصيل
              @else
                متبقي {{ $company->urgency_days }} أيام
              @endif
            </span>
          </div>
          <div class="urgency-bar"><div class="urgency-fill {{ $urgencyFillColor }}" style="width:{{ $urgencyFillWidth }};"></div></div>
        </div>
        <div class="co-card-actions" onclick="event.stopPropagation()">
          <button class="btn-view" onclick="viewCompany({{ $company->id }})">عرض</button>
          <button class="btn-deactivate" onclick="deactivateCompany({{ $company->id }})">إيقاف</button>
        </div>
      </div>
    @empty
      <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:12px;border:0.5px solid #d0d0c8">
        <span class="ms" style="font-size:48px;color:#c0c0b8">business</span>
        <p style="margin-top:16px;font-size:16px;color:#707a6c">لا توجد شركات مسجلة حتى الآن</p>
        <p style="font-size:12px;color:#999;margin-top:8px">ابدأ بإضافة شركة جديدة للبدء</p>
      </div>
    @endforelse

    <!-- Inactive section -->
    @if($inactiveCompanies->count() > 0)
      <div class="inactive-header" onclick="document.querySelector('.inactive-companies').style.display = document.querySelector('.inactive-companies').style.display === 'none' ? 'block' : 'none'; this.querySelector('.inactive-toggle').textContent = document.querySelector('.inactive-companies').style.display === 'none' ? 'عرض ▾' : 'إخفاء ▲';">
        <div class="inactive-label">شركات غير نشطة</div>
        <div class="inactive-count">{{ $inactiveCompanies->count() }}</div>
        <div class="inactive-toggle" style="margin-left:auto;">عرض ▾</div>
      </div>

      <div class="inactive-companies" style="display:none;">
        @foreach($inactiveCompanies as $company)
          <div class="co-card co-card-inactive border-gray">
            <div class="co-card-top">
              <div class="co-av" style="background:#F3F4F6;color:#9CA3AF;">{{ substr($company->name, 0, 1) }}</div>
              <div class="co-info">
                <div class="co-name" style="color:#9CA3AF;">{{ $company->name }}</div>
                <div class="co-meta">
                  <span class="cycle-tag" style="background:#F3F4F6;color:#9CA3AF;">موقوفة</span>
                </div>
                <div style="font-size:11px;color:#ccc;margin-top:4px;">آخر توزيع: {{ $company->distributions()->orderByDesc('distribution_date')->first()?->distribution_date?->format('d M Y') ?? 'لا يوجد' }}</div>
              </div>
              <div class="co-right">
                <div style="font-size:11px;font-weight:600;color:#D1D5DB;">موقوفة</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  <!-- Fixed total bar -->
  <div class="total-bar">
    <div>
      <div class="total-bar-label">إجمالي المستحق من كل الشركات</div>
      <div class="total-bar-sub">{{ $active_count }} شركات نشطة · اليوم {{ now()->format('l d M') }}</div>
    </div>
    <div class="total-bar-val">{{ number_format($total_due, 0) }} ج</div>
  </div>

</div>

<script>
function viewCompany(companyId) {
  window.location.href = `/contractor/companies/${companyId}`;
}

function deactivateCompany(companyId) {
  if (!confirm('هل تريد إيقاف هذه الشركة؟\nسيتم إخفاؤها من التوزيعات ولكن سيبقى سجلها محفوظاً')) {
    return;
  }

  fetch(`/contractor/companies/${companyId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({ is_active: false })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      window.location.reload();
    } else {
      alert(data.message || 'فشل إيقاف الشركة');
    }
  })
  .catch(error => alert('حدث خطأ أثناء إيقاف الشركة'));
}

function openCompanyModal(isEdit, companyId) {
  // Placeholder for modal implementation
  alert('سيتم فتح نموذج الشركة');
}
</script>

@include('components.company-form-modal')
@endsection

