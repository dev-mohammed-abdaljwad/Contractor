@extends('layouts.dashboard')
@section('title', 'تقارير التوزيعات')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Tajawal', sans-serif; direction: rtl; background: #f5f6f8; }

.topbar {
  background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
  padding: 16px 20px;
}
.topbar-title { color: #fff; font-size: 18px; font-weight: 700; }

.filters-section {
  background: #fff; padding: 16px; margin: 16px;
  border-radius: 12px; display: flex; gap: 12px; flex-wrap: wrap;
  align-items: flex-end;
}
.filter-group { display: flex; flex-direction: column; gap: 4px; }
.filter-label { font-size: 12px; font-weight: 600; color: #666; }
.filter-input, .filter-select {
  padding: 8px 12px; border: 1px solid #d0d0c8; border-radius: 8px;
  font-size: 13px; font-family: 'Tajawal', sans-serif;
}
.filter-input:focus, .filter-select:focus {
  outline: none; border-color: #0d631b; background: #f9f9f7;
}
.filter-btn {
  background: #0d631b; color: #fff; border: none;
  padding: 8px 20px; border-radius: 8px; cursor: pointer;
  font-size: 13px; font-weight: 600; transition: all 0.2s;
}
.filter-btn:hover { background: #0a5216; }

.stats-header {
  background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
  color: #fff; padding: 20px; margin: 0 16px;
  border-radius: 12px; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px; margin-bottom: 16px;
}
.stat-box { text-align: center; }
.stat-number { font-size: 28px; font-weight: 900; }
.stat-label { font-size: 12px; color: rgba(255,255,255,0.8); margin-top: 4px; }

.report-container { padding: 16px; }

.company-card {
  background: #fff; border-radius: 14px; overflow: hidden;
  border: 1px solid #f0f0f0; margin-bottom: 16px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.company-header {
  background: linear-gradient(135deg, rgba(13, 99, 27, 0.08) 0%, rgba(29, 158, 117, 0.08) 100%);
  padding: 16px; border-bottom: 2px solid #0d631b;
  display: flex; justify-content: space-between; align-items: center;
}
.company-name { font-size: 16px; font-weight: 700; color: #1a1c19; }
.company-stats { display: flex; gap: 16px; }
.company-stat { text-align: center; }
.company-stat-val { font-size: 18px; font-weight: 900; color: #0d631b; }
.company-stat-lbl { font-size: 10px; color: #999; margin-top: 2px; }

.weekly-breakdown {
  padding: 16px;
}
.weekly-row {
  display: grid; grid-template-columns: 1fr 1.5fr 1.5fr 1.5fr;
  gap: 12px; padding: 12px; border-bottom: 1px solid #f5f5f5;
  align-items: center;
}
.weekly-row:last-child { border-bottom: none; }
.weekly-label { font-weight: 600; color: #666; }
.weekly-value { text-align: center; font-weight: 600; color: #1a1c19; }
.weekly-amount { color: #059669; font-weight: 700; }

.empty-state {
  text-align: center; padding: 40px;
  background: #fff; border-radius: 12px; margin: 16px;
}
.empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-title { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 8px; }
.empty-text { font-size: 13px; color: #999; }

@media (max-width: 1024px) {
  .filters-section { flex-direction: column; gap: 10px; }
  .filter-group { width: 100%; }
  .filter-input, .filter-select { width: 100%; }
  .filter-btn { width: 100%; }
  .stats-header { grid-template-columns: repeat(2, 1fr); gap: 12px; }
  .company-stats { gap: 10px; }
  .company-stat-val { font-size: 16px; }
}

@media (max-width: 768px) {
  .topbar { padding: 12px 16px; }
  .topbar-title { font-size: 16px; }
  
  .filters-section { 
    margin: 12px; 
    flex-direction: column; 
    gap: 10px;
  }
  
  .filter-group { width: 100%; }
  .filter-label { font-size: 11px; }
  .filter-input, .filter-select { 
    width: 100%;
    padding: 10px;
    font-size: 13px;
  }
  .filter-btn { 
    width: 100%;
    padding: 10px 16px;
  }
  
  .stats-header { 
    margin: 12px; 
    grid-template-columns: repeat(2, 1fr); 
    gap: 10px;
    padding: 12px;
  }
  .stat-number { font-size: 22px; }
  .stat-label { font-size: 11px; }
  
  .report-container { padding: 12px; }
  
  .company-card { margin-bottom: 12px; }
  
  .company-header { 
    flex-direction: column; 
    align-items: flex-start; 
    gap: 12px;
    padding: 12px;
  }
  .company-name { font-size: 14px; }
  
  .company-stats { 
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
  }
  .company-stat { font-size: 12px; }
  .company-stat-val { font-size: 16px; }
  .company-stat-lbl { font-size: 9px; }
  
  .weekly-breakdown { padding: 12px; }
  
  .weekly-row {
    grid-template-columns: 1fr;
    gap: 6px;
    padding: 10px;
    margin-bottom: 8px;
    background: #f9f9f7;
    border-radius: 8px;
    border: none;
  }
  .weekly-row:last-child { border-bottom: none; }
}

@media (max-width: 480px) {
  .topbar { padding: 10px 12px; }
  .topbar-title { font-size: 14px; }
  
  .filters-section { margin: 8px; gap: 8px; }
  .filter-input, .filter-select { 
    font-size: 12px;
    padding: 8px;
  }
  .filter-btn { font-size: 12px; padding: 8px 12px; }
  
  .stats-header { 
    margin: 8px; 
    grid-template-columns: 1fr 1fr; 
    gap: 8px;
    padding: 10px;
  }
  .stat-number { font-size: 18px; }
  .stat-label { font-size: 10px; }
  
  .report-container { padding: 8px; }
  
  .company-card { 
    margin-bottom: 10px; 
    border-radius: 10px;
  }
  
  .company-header { 
    padding: 10px;
    gap: 10px;
    border-radius: 10px 10px 0 0;
  }
  .company-name { font-size: 13px; }
  
  .company-stats { 
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
  }
  .company-stat-val { font-size: 14px; }
  .company-stat-lbl { font-size: 8px; }
  
  .weekly-breakdown { padding: 10px; }
  
  .weekly-row {
    padding: 8px;
    margin-bottom: 6px;
    font-size: 12px;
  }
  
  .empty-state { 
    padding: 20px; 
    margin: 8px;
  }
  .empty-icon { font-size: 36px; }
  .empty-title { font-size: 14px; }
  .empty-text { font-size: 12px; }
}
</style>

<div class="topbar">
  <div class="topbar-title">تقارير التوزيعات الشهرية والأسبوعية</div>
</div>

<!-- FILTERS -->
<div class="filters-section">
  <form method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; flex: 1;">
    <div class="filter-group">
      <label class="filter-label">الشهر</label>
      <select name="month" class="filter-select">
        @for ($m = 1; $m <= 12; $m++)
          <option value="{{ $m }}" @selected($m == $month)>
            {{ Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}
          </option>
        @endfor
      </select>
    </div>
    
    <div class="filter-group">
      <label class="filter-label">السنة</label>
      <select name="year" class="filter-select">
        @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
          <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
        @endfor
      </select>
    </div>
    
    <div class="filter-group">
      <label class="filter-label">الشركة</label>
      <select name="company_id" class="filter-select">
        <option value="">-- كل الشركات --</option>
        @foreach($companies as $company)
          <option value="{{ $company->id }}" @selected($company->id == $selectedCompanyId)>
            {{ $company->name }}
          </option>
        @endforeach
      </select>
    </div>
    
    <button type="submit" class="filter-btn">تطبيق الفلتر</button>
  </form>
</div>

<!-- STATS HEADER -->
<div class="stats-header">
  <div class="stat-box">
    <div class="stat-number">{{ $monthName }}</div>
    <div class="stat-label">الفترة</div>
  </div>
  <div class="stat-box">
    <div class="stat-number">{{ $report->count() }}</div>
    <div class="stat-label">عدد الشركات</div>
  </div>
  <div class="stat-box">
    <div class="stat-number">{{ $totalWorkers }}</div>
    <div class="stat-label">إجمالي العمال</div>
  </div>
  <div class="stat-box">
    <div class="stat-number">{{ number_format($totalAmount) }}</div>
    <div class="stat-label">الأجور الكلية</div>
  </div>
</div>

<!-- REPORTS -->
<div class="report-container">
  @if($report->isEmpty())
    <div class="empty-state">
      <div class="empty-icon">📋</div>
      <div class="empty-title">لا توجد بيانات</div>
      <div class="empty-text">لم يتم العثور على توزيعات للفترة المحددة</div>
    </div>
  @else
    @foreach($report as $companyId => $data)
      <div class="company-card">
        <!-- COMPANY HEADER -->
        <div class="company-header">
          <div>
            <div class="company-name">{{ $data['company']->name }}</div>
          </div>
          <div class="company-stats">
            <div class="company-stat">
              <div class="company-stat-val">{{ $data['total_workers'] }}</div>
              <div class="company-stat-lbl">عامل</div>
            </div>
            <div class="company-stat">
              <div class="company-stat-val">{{ $data['distributions_count'] }}</div>
              <div class="company-stat-lbl">توزيعة</div>
            </div>
            <div class="company-stat">
              <div class="company-stat-val" style="color: #059669;">{{ number_format($data['total_amount']) }}</div>
              <div class="company-stat-lbl">ج</div>
            </div>
          </div>
        </div>

        <!-- WEEKLY BREAKDOWN -->
        <div class="weekly-breakdown">
          <div class="weekly-row" style="background: #f9f9f7; font-weight: 700; border-radius: 8px;">
            <div class="weekly-label">الأسبوع</div>
            <div class="weekly-value">التوزيعات</div>
            <div class="weekly-value">عدد العمال</div>
            <div class="weekly-value weekly-amount">الإجمالي</div>
          </div>

          @foreach($data['weekly_breakdown'] as $week)
            <div class="weekly-row">
              <div class="weekly-label">أسبوع #{{ $week['week'] }}</div>
              <div class="weekly-value">{{ $week['count'] }}</div>
              <div class="weekly-value">{{ $week['workers_count'] }}</div>
              <div class="weekly-value weekly-amount">{{ number_format($week['amount']) }} ج</div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  @endif
</div>

@endsection
