@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; background: #f5f6f8; min-height: 100vh; }

.topbar {
  background: linear-gradient(135deg, #0E5A43 0%, #1D9E75 100%);
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

/* Search input styling */
.search-wrap input {
  background: transparent !important;
  border: none !important;
  outline: none !important;
  color: rgba(255,255,255,0.8) !important;
  font-size: 13px !important;
  flex: 1 !important;
  font-family: inherit !important;
}

.search-wrap input::placeholder {
  color: rgba(255,255,255,0.6) !important;
}

.search-wrap input::-webkit-input-placeholder {
  color: rgba(255,255,255,0.6) !important;
}

.search-wrap input:-moz-placeholder {
  color: rgba(255,255,255,0.6) !important;
}

.search-wrap input::-moz-placeholder {
  color: rgba(255,255,255,0.6) !important;
}

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
  display: flex; gap: 8px; padding: 12px 16px;
  background: #fff; border-bottom: 1px solid #f0f0f0;
  overflow-x: auto; align-items: center;
}
.filter-chips {
  display: flex; gap: 7px; overflow-x: auto; flex: 1;
}
.filter-search {
  display: flex; align-items: center; gap: 8px; width: 220px; flex-shrink: 0;
  background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 8px;
  padding: 6px 10px; margin: 0 6px;
}
.filter-search-icon { color: #999; font-size: 13px; }
.filter-search-input {
  background: transparent; border: none; flex: 1; font-size: 12px;
  font-family: inherit; outline: none; color: #666;
}
.filter-search-input::placeholder { color: #bbb; }
.filter-btn {
  background: #1D9E75; color: #fff; border: none;
  padding: 6px 14px; border-radius: 20px; font-size: 12px;
  font-weight: 600; cursor: pointer; display: flex; align-items: center;
  gap: 5px; white-space: nowrap; flex-shrink: 0;
  transition: background 0.15s;
}
.filter-btn:hover { background: #167A5B; }
.filter-actions {
  display: flex; gap: 8px; align-items: center; flex-shrink: 0;
}
.chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 13px; border-radius: 20px; font-size: 12px;
  white-space: nowrap; cursor: pointer; font-weight: 500;
  border: 1.5px solid transparent; flex-shrink: 0;
  text-decoration: none; color: inherit;
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

@media(max-width: 768px) {
  .list-body { padding: 10px 10px 90px; }
}

@media(max-width: 480px) {
  .list-body { 
    padding: 10px 12px 120px;
    background: #f9f9f7;
  }
}

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

.co-card-top { display: flex; align-items: flex-start; gap: 12px; padding: 13px 14px 8px; position: relative; }
@media(max-width: 480px) {
  .co-card-top { 
    flex-wrap: wrap;
    padding: 12px 12px 0; 
  }
}

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
.co-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 3px; }

@media(max-width: 480px) {
  .co-meta { 
    gap: 4px; 
    flex-wrap: wrap;
    font-size: 10px;
  }
}

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
.btn-view, .btn-deactivate, .btn-add-workers {
  flex: 1; padding: 8px 10px; border: none; border-radius: 8px;
  font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;
  min-width: 70px; min-height: 40px; display: flex; align-items: center; justify-content: center;
}
.btn-add-workers {
  background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;
}
.btn-add-workers:hover { background: #D1FAE5; }
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
  .topbar { padding: 12px 16px 12px; margin: 0; }
  .page-title { font-size: 16px; }
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); }
  .strip-stat { padding: 10px 4px; }
  .strip-val { font-size: 16px; }
  .strip-lbl { font-size: 9px; }
  .filter-row { gap: 6px; padding: 10px 8px; flex-wrap: wrap; }
  .filter-chips { gap: 6px; }
  .filter-search { width: 180px; padding: 5px 8px; }
  .filter-search-input { font-size: 11px; }
  .filter-btn { padding: 5px 11px; font-size: 11px; }
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
  body { background: #f9f9f7; }
  .topbar { padding: 12px 14px 8px; margin: 0; }
  .page-title { font-size: 16px; font-weight: 800; }
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 0.5px; background: #fff; }
  .strip-stat { padding: 12px 8px; border: 0.5px solid #f0f0f0; text-align: center; }
  .strip-val { font-size: 16px; font-weight: 700; }
  .strip-lbl { font-size: 10px; }
  .filter-row { gap: 4px; padding: 8px 6px; flex-wrap: wrap; }
  .filter-chips { gap: 4px; }
  .filter-search { width: 100%; padding: 5px 8px; margin: 0; }
  .filter-search-input { font-size: 11px; }
  .filter-actions { width: 100%; }
  .filter-btn { padding: 5px 10px; font-size: 10px; width: 100%; justify-content: center; }
  .chip { padding: 6px 12px; font-size: 11px; border-radius: 20px; }
  .chip-count { font-size: 9px; padding: 2px 6px; }
  .sort-bar { padding: 8px 14px; background: #fff; gap: 10px; }
  .sort-label { font-size: 11px; }
  .sort-select { font-size: 11px; }
  .co-card { margin-bottom: 10px; border-radius: 12px; background: #fff; }
  .co-card-top { gap: 10px; padding: 12px 12px 8px; display: flex; align-items: flex-start; }
  .co-av { width: 40px; height: 40px; font-size: 16px; flex-shrink: 0; }
  .co-name { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
  .co-meta { font-size: 9px; gap: 5px; flex-wrap: wrap; }
  .cycle-tag, .status-tag { font-size: 9px; padding: 2px 7px; border-radius: 12px; }
  .co-meta > div { font-size: 10px; color: #999; }
  .co-right { text-align: left; flex-shrink: 0; min-width: 60px; }
  .co-amount { font-size: 14px; font-weight: 700; }
  .co-amount-lbl { font-size: 9px; color: #999; margin-top: 2px; }
  .co-stats { gap: 6px; padding: 0 12px 8px; grid-template-columns: repeat(3, minmax(0,1fr)); margin: 0; }
  .mini-stat { padding: 8px 6px; background: #f8f9fa; border-radius: 8px; text-align: center; }
  .mini-val { font-size: 12px; font-weight: 700; }
  .mini-lbl { font-size: 9px; color: #999; margin-top: 2px; }
  .urgency-row { padding: 0 12px 10px; }
  .urgency-label-row { gap: 8px; }
  .urgency-lbl { font-size: 10px; color: #999; }
  .urgency-days { font-size: 10px; font-weight: 600; }
  .urgency-bar { height: 5px; border-radius: 3px; }
  .co-card-actions { padding: 0 12px 10px; gap: 6px; display: flex; flex-wrap: wrap; }
  .btn-view, .btn-deactivate, .btn-add-workers { flex: 1; min-width: 70px; padding: 8px 10px; font-size: 11px; height: auto; min-height: 36px; border-radius: 8px; font-weight: 600; transition: all 0.2s; }
  .btn-add-workers { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
  .btn-view { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
  .btn-deactivate { background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5; }
  .inactive-header { padding: 12px 14px 8px; display: flex; align-items: center; gap: 8px; }
  .inactive-label { font-size: 12px; font-weight: 600; }
  .inactive-count { font-size: 11px; background: #F3F4F6; padding: 3px 8px; border-radius: 12px; }
  .inactive-toggle { font-size: 11px; margin-left: auto; }
  .total-bar { padding: 12px 14px; flex-direction: row; gap: 8px; background: #fff; }
  .total-bar-label { font-size: 11px; color: #999; }
  .total-bar-val { font-size: 16px; font-weight: 700; }
  .total-bar-sub { font-size: 9px; color: #999; }
  .list-body { padding: 10px 12px 100px; }
  button { min-height: 44px; padding: 10px 12px; }
  @media(max-width: 360px) {
    .topbar { padding: 10px 12px 12px; }
    .page-title { font-size: 15px; }
    .co-card-top { padding: 10px 10px 6px; gap: 8px; }
    .co-stats { padding: 0 10px 6px; }
    .co-card-actions { padding: 0 10px 8px; gap: 4px; }
    .btn-view, .btn-deactivate, .btn-add-workers { font-size: 10px; }
    .strip-val { font-size: 14px; }
    .co-amount { font-size: 13px; }
  }
}
</style>

<div>
  <!-- Topbar -->
  <div class="topbar">
    <div class="topbar-row">
      <div class="page-title">الشركات</div>
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
    <div class="filter-chips">
      @php
        $companiesToCount = isset($originalActiveCompanies) ? $originalActiveCompanies : $activeCompanies;
      @endphp
      <a href="{{ route('contractor.companies.index', ['search' => $searchQuery ?? '']) }}" class="chip {{ !request('cycle') && !request('status') ? 'chip-all' : 'chip-neutral' }}">الكل <span class="chip-count">{{ $companiesToCount->count() }}</span></a>
      @if($companiesToCount->where('payment_cycle', 'يومي')->count() > 0)
        <a href="{{ route('contractor.companies.index', ['cycle' => 'يومي', 'search' => $searchQuery ?? '']) }}" class="chip {{ request('cycle') === 'يومي' ? 'chip-daily' : 'chip-neutral' }}">يومي <span class="chip-count">{{ $companiesToCount->where('payment_cycle', 'يومي')->count() }}</span></a>
      @endif
      @if($companiesToCount->where('payment_cycle', 'أسبوعي')->count() > 0)
        <a href="{{ route('contractor.companies.index', ['cycle' => 'أسبوعي', 'search' => $searchQuery ?? '']) }}" class="chip {{ request('cycle') === 'أسبوعي' ? 'chip-weekly' : 'chip-neutral' }}">أسبوعي <span class="chip-count">{{ $companiesToCount->where('payment_cycle', 'أسبوعي')->count() }}</span></a>
      @endif
      @if($companiesToCount->where('payment_cycle', 'نص شهري')->count() > 0)
        <a href="{{ route('contractor.companies.index', ['cycle' => 'نص شهري', 'search' => $searchQuery ?? '']) }}" class="chip {{ request('cycle') === 'نص شهري' ? 'chip-bi' : 'chip-neutral' }}">نص شهري <span class="chip-count">{{ $companiesToCount->where('payment_cycle', 'نص شهري')->count() }}</span></a>
      @endif
      @if($companiesToCount->where('payment_status', 'overdue')->count() > 0)
        <a href="{{ route('contractor.companies.index', ['status' => 'overdue', 'search' => $searchQuery ?? '']) }}" class="chip {{ request('status') === 'overdue' ? 'chip-overdue' : 'chip-neutral' }}">متأخرة <span class="chip-count">{{ $companiesToCount->where('payment_status', 'overdue')->count() }}</span></a>
      @endif
      @if($inactiveCompanies->count() > 0)
        <a href="{{ route('contractor.companies.index', ['status' => 'inactive', 'search' => $searchQuery ?? '']) }}" class="chip {{ request('status') === 'inactive' ? 'chip-inactive' : 'chip-neutral' }}">غير نشطة <span class="chip-count">{{ $inactiveCompanies->count() }}</span></a>
      @endif
    </div>
    <form method="GET" action="{{ route('contractor.companies.index') }}" id="searchForm" class="filter-search">
      <span class="filter-search-icon">🔍</span>
      <input type="text" name="search" placeholder="بحث..." 
             value="{{ $searchQuery ?? '' }}" 
             id="searchInput"
             class="filter-search-input">
      <input type="hidden" name="cycle" value="{{ request('cycle', '') }}">
      <input type="hidden" name="status" value="{{ request('status', '') }}">
    </form>
    <div class="filter-actions">
      <button class="filter-btn" onclick="openCompanyModal(false)">+ إضافة شركة</button>
    </div>
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
          <div class="mini-stat"><div class="mini-val">{{ number_format($company->contractor_rate ?? $company->daily_wage, 0) }} ج</div><div class="mini-lbl">أجر الشركة</div></div>
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
          <button class="btn-add-workers" onclick="openAddWorkersModal({{ $company->id }}, '{{ $company->name }}', {{ $company->daily_wage }})">+ اضافة عمالة</button>
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
  showConfirmModal(
    'إيقاف الشركة',
    'هل تريد فعلاً إيقاف هذه الشركة؟\nسيتم إخفاؤها من التوزيعات ولكن سيبقى سجلها محفوظاً',
    'تحذير',
    function() {
      fetch(`/contractor/companies/${companyId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ is_active: false })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          window.showToast(data.message || 'تم إيقاف الشركة بنجاح', 'success');
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          window.showToast(data.message || 'فشل إيقاف الشركة', 'error');
        }
      })
      .catch(error => {
        window.showToast('حدث خطأ: ' + error.message, 'error');
      });
    }
  );
}

function openCompanyModal(isEdit, companyId) {
  const modal = document.getElementById('company-form-modal');
  const form = document.getElementById('company-form');
  const title = document.getElementById('modal-title');
  const submitText = document.getElementById('submit-btn-text');
  
  if (!modal || !form || !title || !submitText) {
    console.error('Required modal elements not found');
    return;
  }
  
  if (isEdit && companyId) {
    // Load company data for editing
    fetch(`/contractor/companies/${companyId}/edit`, {
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      }
    })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          const company = data.company;
          
          // Set form values
          const formFields = {
            'form-name': company.name || '',
            'form-daily_wage': company.daily_wage || '',
            'form-contractor_rate': company.contractor_rate || '',
            'form-overtime_rate': company.overtime_rate || '',
            'form-payment_cycle': company.payment_cycle || '',
            'form-contract_start_date': company.contract_start_date || '',
            'form-is_active': company.is_active ? '1' : '0'
          };
          
          Object.keys(formFields).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
              field.value = formFields[fieldId];
            }
          });
          
          const statusField = document.getElementById('status-field');
          if (statusField) {
            statusField.style.display = 'block';
          }
          
          // Update form action and method
          form.action = `/contractor/companies/${companyId}`;
          const methodField = document.getElementById('form-method');
          if (methodField) {
            methodField.value = 'PUT';
          }
          
          title.textContent = 'تعديل: ' + company.name;
          submitText.textContent = 'حفظ التعديلات';
          
          // Show modal
          modal.classList.add('show');
          document.body.style.overflow = 'hidden';
          
          // Focus first input
          setTimeout(() => {
            const nameField = document.getElementById('form-name');
            if (nameField) nameField.focus();
          }, 100);
        } else {
          showAlertModal('خطأ', data.message || 'فشل تحميل بيانات الشركة', 'error');
        }
      })
      .catch(error => {
        showAlertModal('خطأ', 'حدث خطأ: ' + error.message, 'error');
        console.error(error);
      });
  } else {
    // Reset form for new company
    form.reset();
    const statusField = document.getElementById('status-field');
    if (statusField) {
      statusField.style.display = 'none';
    }
    form.action = '/contractor/companies';
    const methodField = document.getElementById('form-method');
    if (methodField) {
      methodField.value = 'POST';
    }
    
    title.textContent = 'شركة جديدة';
    submitText.textContent = 'حفظ الشركة';
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Focus first input
    setTimeout(() => {
      const nameField = document.getElementById('form-name');
      if (nameField) nameField.focus();
    }, 100);
  }
}

function closeCompanyModal() {
  const modal = document.getElementById('company-form-modal');
  modal.classList.remove('show');
  document.body.style.overflow = 'auto';
}

// Company form modal will be initialized after component loads

// Add workers modal functions
let currentCompanyData = {
  id: null,
  name: '',
  dailyWage: 0
};

function openAddWorkersModal(companyId, companyName, dailyWage) {
  // Verify all required elements exist before proceeding
  const requiredElements = {
    'modalCompanyId': 'input',
    'modalCompanyName': 'div',
    'previewCompanyName': 'div',
    'previewDailyWage': 'div',
    'addWorkersModal': 'div'
  };
  
  for (const [id, type] of Object.entries(requiredElements)) {
    const el = document.getElementById(id);
    if (!el) {
      console.error(`Element #${id} not found`);
      return;
    }
  }
  
  currentCompanyData = {
    id: companyId,
    name: companyName,
    dailyWage: dailyWage
  };

  // Set company info
  document.getElementById('modalCompanyId').value = companyId;
  document.getElementById('modalCompanyName').textContent = companyName + ' - ' + number_format(dailyWage) + ' ج/عامل';
  document.getElementById('previewCompanyName').textContent = companyName;
  document.getElementById('previewDailyWage').textContent = number_format(dailyWage) + ' ج';

  // Load available workers
  loadCompanyWorkers();

  // Show modal
  document.getElementById('addWorkersModal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeAddWorkersModal() {
  const modal = document.getElementById('addWorkersModal');
  if (!modal) return;
  
  modal.classList.add('hidden');
  document.body.style.overflow = 'auto';
  
  const form = document.getElementById('addWorkersForm');
  if (form) form.reset();
  
  updateAddWorkersPreview();
}

async function loadCompanyWorkers() {
  const today = new Date().toISOString().split('T')[0];
  const modalWorkersList = document.getElementById('modalWorkersList');
  
  if (!modalWorkersList) {
    console.error('modalWorkersList element not found');
    return;
  }
  
  try {
    const response = await fetch(`/contractor/distributions/available-workers?date=${today}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      }
    });
    
    // Check if HTTP response is successful
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await response.json();
    
    if (result.success && result.data) {
      populateCompanyWorkersList(result.data);
    } else {
      modalWorkersList.innerHTML = 
        '<div style="text-align: center; padding: 20px; color: #dc2626;">فشل تحميل قائمة العمال</div>';
    }
  } catch (error) {
    console.error('Error loading workers:', error);
    modalWorkersList.innerHTML = 
      '<div style="text-align: center; padding: 20px; color: #dc2626;">خطأ في تحميل قائمة العمال: ' + error.message + '</div>';
  }
}

function populateCompanyWorkersList(workers) {
  const workersList = document.getElementById('modalWorkersList');
  
  if (workers.length === 0) {
    workersList.innerHTML = 
      '<div style="text-align: center; padding: 20px; color: #999;">جميع العمال مسجلين بالفعل لهذا اليوم</div>';
    return;
  }

  workersList.innerHTML = workers.map(worker => `
    <label class="worker-checkbox">
      <input type="checkbox" name="worker_ids[]" value="${worker.id}" 
             onchange="updateAddWorkersPreview()">
      <span>${worker.name}</span>
    </label>
  `).join('');
}

function updateAddWorkersPreview() {
  const checkedWorkers = document.querySelectorAll('#addWorkersForm input[name="worker_ids[]"]:checked');
  const workersCount = checkedWorkers.length;
  const totalAmount = workersCount * currentCompanyData.dailyWage;

  document.getElementById('previewWorkerCount').textContent = workersCount;
  document.getElementById('previewTotal').textContent = number_format(totalAmount) + ' ج';
}

function number_format(num) {
  return new Intl.NumberFormat('ar-EG').format(num);
}

// Handle form submission
document.getElementById('addWorkersForm')?.addEventListener('submit', function(e) {
  const companyId = document.getElementById('modalCompanyId').value;
  const checkedWorkers = document.querySelectorAll('input[name="worker_ids[]"]:checked');

  let hasErrors = false;

  // Validate workers
  if (checkedWorkers.length === 0) {
    document.getElementById('modal-worker_ids-error').textContent = 'اختر عاملاً واحداً على الأقل';
    document.getElementById('modal-worker_ids-error').classList.add('show');
    hasErrors = true;
  } else {
    document.getElementById('modal-worker_ids-error').classList.remove('show');
  }

  if (hasErrors) {
    e.preventDefault();
  }
});
</script>

<!-- Alert Modal -->
<div id="alertModal" class="alert-modal">
  <div class="alert-overlay" onclick="closeAlertModal()"></div>
  <div class="alert-content">
    <div class="alert-header">
      <h3 id="alertTitle" class="alert-title">إشعار</h3>
      <button type="button" class="alert-close" onclick="closeAlertModal()">&times;</button>
    </div>
    <div class="alert-body">
      <div id="alertMessage" class="alert-message">هناك رسالة بانتظارك</div>
    </div>
    <div class="alert-footer">
      <button type="button" class="alert-btn" onclick="closeAlertModal()">حسناً</button>
    </div>
  </div>
</div>

<style>
.alert-modal {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 9999;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.2s ease-in-out;
}

.alert-modal.show {
  display: flex;
}

.alert-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  cursor: pointer;
}

.alert-content {
  position: relative;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  width: 90%;
  max-width: 400px;
  max-height: 80vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  animation: slideUp 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    transform: translateY(30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.alert-header {
  padding: 20px 24px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.alert-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
}

.alert-close {
  background: none;
  border: none;
  font-size: 28px;
  color: #999;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.2s;
}

.alert-close:hover {
  background: #f5f5f5;
  color: #333;
}

.alert-body {
  padding: 24px;
  overflow-y: auto;
  flex: 1;
}

.alert-message {
  font-size: 14px;
  color: #666;
  line-height: 1.6;
  word-wrap: break-word;
}

.alert-footer {
  padding: 16px 24px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.alert-btn {
  padding: 10px 24px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  background: #0d631b;
  color: #fff;
  min-height: 44px;
}

.alert-btn:hover {
  background: #0a5216;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(13, 99, 27, 0.2);
}

.alert-btn:active {
  transform: translateY(0);
}

/* Responsive */
@media(max-width: 480px) {
  .alert-content {
    width: 95%;
    max-width: 100%;
    border-radius: 12px;
  }

  .alert-header {
    padding: 16px 16px;
  }

  .alert-title {
    font-size: 16px;
  }

  .alert-body {
    padding: 16px;
  }

  .alert-message {
    font-size: 13px;
  }

  .alert-footer {
    padding: 12px 16px;
  }

  .alert-btn {
    padding: 10px 16px;
    font-size: 13px;
    min-height: 40px;
    flex: 1;
  }
}
</style>

<script>
function showAlertModal(title, message, type = 'info') {
  const modal = document.getElementById('alertModal');
  const titleEl = document.getElementById('alertTitle');
  const messageEl = document.getElementById('alertMessage');
  const btnEl = document.querySelector('.alert-btn');

  titleEl.textContent = title;
  messageEl.textContent = message;

  // Style button based on type
  if (type === 'error') {
    btnEl.style.background = '#ba1a1a';
    btnEl.onmouseover = function() {
      this.style.background = '#991b1b';
    };
    btnEl.onmouseout = function() {
      this.style.background = '#ba1a1a';
    };
  } else if (type === 'success') {
    btnEl.style.background = '#059669';
    btnEl.onmouseover = function() {
      this.style.background = '#047857';
    };
    btnEl.onmouseout = function() {
      this.style.background = '#059669';
    };
  } else {
    btnEl.style.background = '#0d631b';
    btnEl.onmouseover = function() {
      this.style.background = '#0a5216';
    };
    btnEl.onmouseout = function() {
      this.style.background = '#0d631b';
    };
  }

  modal.classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeAlertModal() {
  const modal = document.getElementById('alertModal');
  modal.classList.remove('show');
  document.body.style.overflow = 'auto';
}

// Close modal when clicking overlay
document.addEventListener('DOMContentLoaded', function() {
  const alertModal = document.getElementById('alertModal');
  alertModal?.addEventListener('click', function(e) {
    if (e.target === this) {
      closeAlertModal();
    }
  });

  const confirmModal = document.getElementById('confirmModal');
  confirmModal?.addEventListener('click', function(e) {
    if (e.target === this) {
      closeConfirmModal();
    }
  });
});

// Confirmation Modal
function showConfirmModal(title, message, type = 'warning', onConfirm = null) {
  const modal = document.getElementById('confirmModal');
  const titleEl = document.getElementById('confirmTitle');
  const messageEl = document.getElementById('confirmMessage');
  const confirmBtn = document.getElementById('confirmBtn');
  const cancelBtn = document.getElementById('cancelBtn');

  titleEl.textContent = title;
  messageEl.textContent = message;

  // Store the callback
  window.confirmCallback = onConfirm;

  // Style buttons based on type
  if (type === 'warning' || type === 'error') {
    confirmBtn.style.background = '#ba1a1a';
    confirmBtn.onmouseover = function() {
      this.style.background = '#991b1b';
    };
    confirmBtn.onmouseout = function() {
      this.style.background = '#ba1a1a';
    };
  } else {
    confirmBtn.style.background = '#0d631b';
    confirmBtn.onmouseover = function() {
      this.style.background = '#0a5216';
    };
    confirmBtn.onmouseout = function() {
      this.style.background = '#0d631b';
    };
  }

  modal.classList.add('show');
  document.body.style.overflow = 'hidden';
  setTimeout(() => confirmBtn.focus(), 100);
}

function closeConfirmModal() {
  const modal = document.getElementById('confirmModal');
  modal.classList.remove('show');
  document.body.style.overflow = 'auto';
  window.confirmCallback = null;
}

function confirmAction() {
  if (window.confirmCallback && typeof window.confirmCallback === 'function') {
    window.confirmCallback();
  }
  closeConfirmModal();
}
</script>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
  <div class="confirm-overlay" onclick="closeConfirmModal()"></div>
  <div class="confirm-content">
    <div class="confirm-header">
      <h3 id="confirmTitle" class="confirm-title">تأكيد</h3>
      <button type="button" class="confirm-close" onclick="closeConfirmModal()">&times;</button>
    </div>
    <div class="confirm-body">
      <div id="confirmMessage" class="confirm-message">هل أنت متأكد؟</div>
    </div>
    <div class="confirm-footer">
      <button type="button" class="confirm-btn-cancel" onclick="closeConfirmModal()">إلغاء</button>
      <button type="button" id="confirmBtn" class="confirm-btn-action" onclick="confirmAction()">موافق</button>
    </div>
  </div>
</div>

<style>
.confirm-modal {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 9999;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.2s ease-in-out;
}

.confirm-modal.show {
  display: flex;
}

.confirm-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  cursor: pointer;
}

.confirm-content {
  position: relative;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  width: 90%;
  max-width: 420px;
  max-height: 80vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  animation: slideUp 0.3s ease-in-out;
}

.confirm-header {
  padding: 24px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.confirm-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
}

.confirm-close {
  background: none;
  border: none;
  font-size: 28px;
  color: #999;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.2s;
}

.confirm-close:hover {
  background: #f5f5f5;
  color: #333;
}

.confirm-body {
  padding: 24px;
  overflow-y: auto;
  flex: 1;
}

.confirm-message {
  font-size: 14px;
  color: #666;
  line-height: 1.6;
  word-wrap: break-word;
}

.confirm-footer {
  padding: 16px 24px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.confirm-btn-cancel,
.confirm-btn-action {
  padding: 10px 24px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  min-height: 44px;
}

.confirm-btn-cancel {
  background: #f3f4f6;
  color: #666;
  border: 1px solid #e5e7eb;
}

.confirm-btn-cancel:hover {
  background: #e5e7eb;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.confirm-btn-action {
  background: #0d631b;
  color: #fff;
}

.confirm-btn-action:hover {
  background: #0a5216;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(13, 99, 27, 0.2);
}

.confirm-btn-action:focus {
  outline: 2px solid #0d631b;
  outline-offset: 2px;
}

/* Responsive */
@media(max-width: 480px) {
  .confirm-content {
    width: 95%;
    max-width: 100%;
    border-radius: 12px;
  }

  .confirm-header {
    padding: 16px;
  }

  .confirm-title {
    font-size: 16px;
  }

  .confirm-body {
    padding: 16px;
  }

  .confirm-message {
    font-size: 13px;
  }

  .confirm-footer {
    padding: 12px 16px;
    gap: 8px;
  }

  .confirm-btn-cancel,
  .confirm-btn-action {
    padding: 10px 16px;
    font-size: 13px;
    min-height: 40px;
    flex: 1;
  }
}
</style>


<div id="addWorkersModal" class="add-workers-modal hidden">
  <div class="modal-overlay" onclick="closeAddWorkersModal()"></div>
  <div class="modal-content">
    <div class="modal-header">
      <div>
        <h2 class="modal-title">اضافة عمالة اليوم</h2>
        <p class="modal-company" id="modalCompanyName"></p>
      </div>
      <button type="button" class="modal-close" onclick="closeAddWorkersModal()">&times;</button>
    </div>
    
    <form id="addWorkersForm" method="POST" action="{{ route('contractor.distributions.store') }}">
      @csrf
      
      <div class="modal-body">
        <!-- Hidden company ID -->
        <input type="hidden" name="company_id" id="modalCompanyId">

        <!-- Workers Selection -->
        <div class="form-group">
          <label class="form-label">اختر العمال المتاحين <span class="required">*</span></label>
          <div class="workers-list" id="modalWorkersList">
            <div style="text-align: center; padding: 20px; color: #999;">
              جاري تحميل قائمة العمال...
            </div>
          </div>
          <div class="error-message" id="modal-worker_ids-error"></div>
        </div>

        <!-- Real-time Earnings Calculation Preview -->
        <div class="earnings-preview">
          <h3 class="preview-title">معاينة الاجمالي اليوم</h3>
          <div class="preview-row">
            <span class="preview-label">الشركة:</span>
            <span class="preview-value" id="previewCompanyName"></span>
          </div>
          <div class="preview-row">
            <span class="preview-label">عدد العمال:</span>
            <span class="preview-value" id="previewWorkerCount">0</span>
          </div>
          <div class="preview-row">
            <span class="preview-label">الأجر لكل عامل:</span>
            <span class="preview-value" id="previewDailyWage">0 ج</span>
          </div>
          <div class="preview-row preview-total">
            <span class="preview-label">الإجمالي:</span>
            <span class="preview-value" id="previewTotal">0 ج</span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeAddWorkersModal()">إلغاء</button>
        <button type="submit" class="btn-primary">تأكيد التوزيع</button>
      </div>
    </form>
  </div>
</div>

<style>
.add-workers-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.add-workers-modal.hidden {
  display: none;
}

.add-workers-modal .modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  cursor: pointer;
}

.add-workers-modal .modal-content {
  position: relative;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  direction: rtl;
}

.add-workers-modal .modal-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px;
  border-bottom: 1px solid #f0f0f0;
}

.add-workers-modal .modal-title {
  font-size: 18px;
  font-weight: 700;
  color: #333;
  margin: 0;
}

.add-workers-modal .modal-company {
  font-size: 13px;
  color: #666;
  margin-top: 6px;
  margin: 6px 0 0 0;
}

.add-workers-modal .modal-close {
  background: none;
  border: none;
  font-size: 28px;
  color: #aaa;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s;
}

.add-workers-modal .modal-close:hover {
  background: #f0f0f0;
  color: #333;
}

.add-workers-modal .modal-body {
  padding: 20px;
}

.add-workers-modal .modal-footer {
  padding: 16px 20px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.add-workers-modal .form-group {
  margin-bottom: 20px;
}

.add-workers-modal .form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.add-workers-modal .required {
  color: #dc2626;
}

.add-workers-modal .workers-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 10px;
}

.add-workers-modal .worker-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 14px;
  user-select: none;
}

.add-workers-modal .worker-checkbox:hover {
  border-color: #10B981;
  background: rgba(16, 185, 129, 0.05);
}

.add-workers-modal .worker-checkbox input[type="checkbox"] {
  cursor: pointer;
}

.add-workers-modal .worker-checkbox input[type="checkbox"]:checked ~ span {
  color: #059669;
  font-weight: 600;
}

.add-workers-modal .error-message {
  color: #dc2626;
  font-size: 12px;
  margin-top: 4px;
  display: none;
}

.add-workers-modal .error-message.show {
  display: block;
}

.earnings-preview {
  background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 100%);
  border: 1px solid #A7F3D0;
  border-radius: 12px;
  padding: 16px;
  margin-top: 20px;
}

.preview-title {
  font-size: 14px;
  font-weight: 700;
  color: #065F46;
  margin: 0 0 12px 0;
}

.preview-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  font-size: 13px;
  border-bottom: 1px solid rgba(167, 243, 208, 0.5);
}

.preview-row:last-child {
  border-bottom: none;
}

.preview-label {
  color: #047857;
  font-weight: 500;
}

.preview-value {
  font-weight: 600;
  color: #065F46;
}

.preview-total {
  border-top: 2px solid #A7F3D0;
  padding-top: 12px;
  margin-top: 8px;
  font-size: 15px;
}

.preview-total .preview-value {
  color: #059669;
  font-size: 16px;
}

.add-workers-modal .btn-primary, .add-workers-modal .btn-secondary {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.add-workers-modal .btn-primary {
  background: linear-gradient(135deg, #10B981 0%, #059669 100%);
  color: white;
}

.add-workers-modal .btn-primary:hover {
  opacity: 0.9;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.add-workers-modal .btn-secondary {
  background: #f3f4f6;
  color: #333;
}

.add-workers-modal .btn-secondary:hover {
  background: #e5e7eb;
}

@media (max-width: 640px) {
  .add-workers-modal .modal-content {
    width: 95%;
    max-height: 95vh;
  }
  
  .add-workers-modal .workers-list {
    grid-template-columns: 1fr;
  }
}
</style>

@include('components.company-form-modal')

<script>
// Initialize company form modal after component loads
function initCompanyFormModal() {
  const modal = document.getElementById('company-form-modal');
  const form = document.getElementById('company-form');
  
  if (!modal || !form) {
    console.error('Company form modal or form elements not found');
    return;
  }
  
  // Close modal on background click
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeCompanyModal();
    }
  });
  
  // Handle form submission
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Disable submit button to prevent double submission
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = 'جاري الحفظ...';
    }
    
    const formData = new FormData(form);
    const method = document.getElementById('form-method').value;
    const action = form.action;
    
    fetch(action, {
      method: method,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json',
      },
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Show toast notification
        window.showToast(data.message || 'تم حفظ البيانات بنجاح', 'success');
        
        // Close modal
        closeCompanyModal();
        
        // Reload after toast displays
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        // Re-enable submit button
        if (submitBtn) {
          submitBtn.disabled = false;
          const isEdit = document.getElementById('form-method').value === 'PUT';
          submitBtn.textContent = isEdit ? 'حفظ التعديلات' : 'حفظ الشركة';
        }
        
        // Display validation errors
        if (data.errors) {
          Object.keys(data.errors).forEach(field => {
            const errorEl = document.getElementById(`error-${field}`);
            if (errorEl) {
              errorEl.textContent = '❌ ' + data.errors[field][0];
              errorEl.style.display = 'block';
            }
          });
        } else {
          window.showToast(data.message || 'فشل حفظ البيانات', 'error');
        }
      }
    })
    .catch(error => {
      // Re-enable submit button
      if (submitBtn) {
        submitBtn.disabled = false;
        const isEdit = document.getElementById('form-method').value === 'PUT';
        submitBtn.textContent = isEdit ? 'حفظ التعديلات' : 'حفظ الشركة';
      }
      
      window.showToast('حدث خطأ: ' + error.message, 'error');
      console.error(error);
    });
  });
}

// Initialize when document is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCompanyFormModal);
} else {
  initCompanyFormModal();
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const searchForm = document.getElementById('searchForm');
  
  if (searchInput) {
    // Debounce search to avoid too many requests
    let searchTimeout;
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        if (searchForm) {
          searchForm.submit();
        }
      }, 500);
    });
  }
});
</script>
@endsection

