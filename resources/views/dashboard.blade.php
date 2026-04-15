@extends('layouts.dashboard')
@section('title', 'لوحة التحكم')

@section('content')
<div class="dashboard-container">
  <!-- Header -->
  <div class="dashboard-header">
    <h1 class="page-title">لوحة التحكم</h1>
    <p class="subtitle">{{ now()->translatedFormat('l, j F Y') }}</p>
  </div>

  <!-- Quick Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-value" style="color: #185FA5;">{{ $activeCompaniesCount ?? 0 }}</div>
      <div class="stat-label">شركات نشطة</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color: #059669;">{{ $totalWorkersCount ?? 0 }}</div>
      <div class="stat-label">إجمالي العمال</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color: #D97706;">{{ $workersDistributedToday ?? 0 }}</div>
      <div class="stat-label">موزعين اليوم</div>
    </div>
    <div class="stat-card">
      <div class="stat-value" style="color: #059669;">{{ number_format($totalWagesToday ?? 0) }} ج</div>
      <div class="stat-label">الأجور اليوم</div>
    </div>    <div class="stat-card">
      <div class="stat-value" style="color: #7C3AED;">
        {{ collect($workersOvertimeSummary ?? [])->sum(function($w) { return $w['total_hours']; }) }}
      </div>
      <div class="stat-label">ساعات سهر هذا الأسبوع</div>
    </div>  </div>

  <!-- NEW: Bulk Overtime Action Card -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 30px;">
    @include('components.bulk-overtime-card')
  </div>

  <!-- Quick Actions -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
    <a href="{{ route('contractor.distributions.reports') }}" style="text-decoration: none;">
      <div class="quick-action-card">
        <div style="font-size: 28px; margin-bottom: 8px;">📊</div>
        <div class="quick-action-title">تقارير التوزيعات</div>
        <div class="quick-action-desc">شهري وأسبوعي حسب الشركة</div>
      </div>
    </a>
  </div>

  <!-- Today's Distributions -->
  @if(isset($companiesWithDistributions) && $companiesWithDistributions->count() > 0)
  <div class="section-card">
    <h2 class="section-title">التوزيعات اليوم</h2>
    <div class="distributions-list">
      @foreach($companiesWithDistributions as $item)
      <div class="distribution-item">
        <div class="dist-company">
          <strong>{{ $item['company']->name }}</strong>
          <span class="dist-workers">{{ $item['workers_count'] }} عامل</span>
        </div>
        <div class="dist-wage">{{ number_format($item['total_wage']) }} ج</div>
      </div>
      @endforeach
    </div>
  </div>
  @endif
</div>

<style>
.dashboard-container { padding: 20px; max-width: 1200px; margin: 0 auto; }
.dashboard-header { margin-bottom: 30px; }
.page-title { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
.subtitle { color: #999; font-size: 14px; }

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-bottom: 30px;
}

.stat-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-value {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 8px;
}

.stat-label {
  color: #666;
  font-size: 13px;
  font-weight: 500;
}

.section-card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-title {
  font-size: 16px;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f0f0f0;
}

.distributions-list,
.collections-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.distribution-item,
.collection-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  background: #f9fafb;
  border-radius: 8px;
  border-left: 3px solid #185FA5;
}

.collection-item {
  border-left-color: #059669;
}

.dist-company,
.coll-company {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 12px;
}

.dist-company strong,
.coll-company strong {
  color: #1f2937;
  font-weight: 600;
}

.dist-workers,
.coll-period {
  font-size: 12px;
  color: #999;
  background: #fff;
  padding: 2px 8px;
  border-radius: 4px;
}

.dist-wage,
.coll-amount {
  font-weight: 600;
  color: #185FA5;
  font-size: 14px;
  min-width: 100px;
  text-align: right;
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .distribution-item,
  .collection-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .dist-wage,
  .coll-amount {
    align-self: flex-end;
    margin-top: 8px;
  }
}

.quick-action-card {
  background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
  color: #fff;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 2px 8px rgba(13, 99, 27, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.quick-action-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(13, 99, 27, 0.3);
  border-color: rgba(255, 255, 255, 0.4);
}

.quick-action-title {
  font-size: 14px;
  font-weight: 700;
  margin-bottom: 6px;
}

.quick-action-desc {
  font-size: 12px;
  opacity: 0.9;
}

/* RESPONSIVE DESIGN */
@media (max-width: 1024px) {
  .dashboard-container { padding: 16px; }
  .stats-grid { grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
}

@media (max-width: 768px) {
  .dashboard-container { padding: 12px; }
  .page-title { font-size: 22px; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 20px; }
  .stat-card { padding: 16px; }
  .stat-value { font-size: 24px; }
  .stat-label { font-size: 12px; }
  
  .section-card { padding: 16px; margin-bottom: 16px; }
  .section-title { font-size: 14px; margin-bottom: 12px; }
  
  .distribution-item, .collection-item {
    padding: 10px;
    gap: 10px;
  }
}

@media (max-width: 480px) {
  .dashboard-container { padding: 8px; }
  .page-title { font-size: 18px; margin-bottom: 8px; }
  .subtitle { font-size: 12px; }
  
  .stats-grid { 
    grid-template-columns: 1fr;
    gap: 10px;
    margin-bottom: 16px;
  }
  
  .stat-card { 
    padding: 14px;
    border-radius: 10px;
  }
  
  .stat-value { font-size: 20px; margin-bottom: 6px; }
  .stat-label { font-size: 11px; }
  
  .quick-action-card {
    padding: 16px;
    border-radius: 10px;
  }
  
  .quick-action-title { font-size: 13px; }
  .quick-action-desc { font-size: 11px; }
  
  .section-card { 
    padding: 12px;
    margin-bottom: 12px;
    border-radius: 10px;
  }
  
  .section-title { 
    font-size: 13px;
    margin-bottom: 10px;
    padding-bottom: 10px;
  }
  
  .distributions-list,
  .collections-list {
    gap: 8px;
  }
  
  .distribution-item,
  .collection-item {
    padding: 10px;
    border-radius: 8px;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  
  .dist-company, .coll-company {
    flex-direction: column;
    gap: 4px;
    width: 100%;
  }
  
  .dist-workers, .coll-workers {
    font-size: 11px;
  }
  
  .dist-wage,
  .coll-amount {
    align-self: flex-end;
    margin-top: 4px;
  }
}
</style>

<!-- Bulk Overtime Modal -->
@include('components.bulk-overtime-modal', ['companies' => $companies ?? []])

@endsection
