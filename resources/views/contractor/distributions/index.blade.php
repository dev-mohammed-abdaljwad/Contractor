@extends('layouts.dashboard')
@section('title', 'قائمة التوزيعات')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; background: #f5f6f8; }

.topbar {
  background: linear-gradient(135deg, #185FA5 0%, #1D9E75 100%);
  padding: 16px 20px 20px;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.page-title { color: #fff; font-size: 18px; font-weight: 700; }
.create-btn {
  background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);
  color: #fff; font-size: 12px; font-weight: 600;
  padding: 7px 14px; border-radius: 20px; cursor: pointer;
  display: flex; align-items: center; gap: 5px; text-decoration: none;
}
.create-btn:hover { background: rgba(255,255,255,0.3); }

.search-wrap {
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 12px; padding: 9px 14px;
  display: flex; align-items: center; gap: 8px;
}
.search-icon { color: rgba(255,255,255,0.7); font-size: 14px; }
.search-input { 
  background: transparent; border: none; color: #fff; font-size: 13px;
  font-family: inherit; flex: 1; outline: none;
}
.search-input::placeholder { color: rgba(255,255,255,0.6); }

.stats-strip {
  display: grid; grid-template-columns: repeat(4, minmax(0,1fr));
  gap: 0; background: #fff;
  border-bottom: 1px solid #f0f0f0;
}
.strip-stat { text-align: center; padding: 12px 6px; border-left: 1px solid #f0f0f0; }
.strip-stat:last-child { border-left: none; }
.strip-val { font-size: 18px; font-weight: 700; }
.strip-lbl { font-size: 10px; color: #aaa; margin-top: 2px; line-height: 1.3; }
.sv-blue { color: #185FA5; } .sv-green { color: #059669; }
.sv-amber { color: #D97706; } .sv-red { color: #DC2626; }

.dist-card {
  background: #fff; border-radius: 14px; border: 1px solid #f0f0f0;
  overflow: hidden; margin-bottom: 12px;
}
.dist-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 16px; border-bottom: 1px solid #f5f5f5;
}
.dist-date { font-size: 13px; font-weight: 600; color: #222; }
.dist-co { font-size: 11px; color: #aaa; margin-top: 2px; }
.dist-amt { font-size: 14px; font-weight: 700; color: #059669; }
.dist-actions { display: flex; gap: 6px; padding: 10px 14px; }
.action-btn {
  flex: 1; text-align: center; padding: 8px;
  border-radius: 10px; font-size: 12px; font-weight: 600; cursor: pointer;
  border: none; transition: all 0.2s;
}
.view-btn { background: #EFF6FF; color: #185FA5; }
.view-btn:hover { background: #D9E9F7; }
.edit-btn { background: #Fef3c7; color: #92400E; }
.edit-btn:hover { background: #fde68a; }
.delete-btn { background: #FEF2F2; color: #991B1B; }
.delete-btn:hover { background: #fee2e2; }

.empty-state {
  text-align: center; padding: 40px 20px;
}
.empty-icon { font-size: 48px; margin-bottom: 12px; }
.empty-title { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 6px; }
.empty-sub { font-size: 12px; color: #aaa; margin-bottom: 20px; }
.empty-btn {
  background: #1D9E75; color: #fff; border: none;
  padding: 11px 24px; border-radius: 12px;
  font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none;
  display: inline-block;
}
.empty-btn:hover { background: #0F6E56; }

/* MOBILE RESPONSIVE */
@media (max-width: 640px) {
  .topbar { padding: 12px 12px 14px; }
  .topbar-row { margin-bottom: 10px; flex-wrap: wrap; }
  .page-title { font-size: 16px; }
  .create-btn { font-size: 11px; padding: 6px 12px; }
  .search-wrap { padding: 8px 12px; }
  .search-input { font-size: 12px; }
  
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); }
  .strip-stat { padding: 10px 4px; }
  .strip-val { font-size: 16px; }
  .strip-lbl { font-size: 9px; }
  
  .dist-card { margin-bottom: 10px; }
  .dist-head { padding: 12px; }
  .dist-date { font-size: 12px; }
  .dist-co { font-size: 10px; }
  .dist-amt { font-size: 13px; }
  .dist-actions { gap: 4px; padding: 8px 12px; }
  .action-btn { padding: 6px; font-size: 11px; }
  
  .empty-state { padding: 30px 12px; }
  .empty-icon { font-size: 40px; }
  .empty-title { font-size: 14px; }
  .empty-sub { font-size: 11px; }
}
</style>

<!-- TOPBAR -->
<div class="topbar">
  <div class="topbar-row">
    <div class="page-title">التحليلات اليومية</div>
  
  </div>
</div>

<!-- STATS STRIP -->
<div class="stats-strip">
  <div class="strip-stat">
    <div class="strip-val sv-blue">{{ $totalDistributions }}</div>
    <div class="strip-lbl">إجمالي<br>التوزيعات</div>
  </div>
  <div class="strip-stat">
    <div class="strip-val sv-green">{{ $totalWorkers }}</div>
    <div class="strip-lbl">عدد<br>العمال اليوم</div>
  </div>
  <div class="strip-stat">
    <div class="strip-val sv-amber">{{ $totalWages }}</div>
    <div class="strip-lbl">إجمالي<br>الأجور</div>
  </div>
  <div class="strip-stat">
    <div class="strip-val sv-amber">{{ $editableCount }}</div>
    <div class="strip-lbl">قابل<br>للتعديل</div>
  </div>
</div>

<!-- ANALYTICS SECTION -->
<div style="padding: 16px;">
  <div style="background: #fff; border-radius: 14px; padding: 24px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
    <div style="margin-bottom: 16px;">
      <h2 style="font-size: 24px; font-weight: 700; color: #059669; margin: 0;">{{ $totalWages }} ج</h2>
      <p style="font-size: 13px; color: #666; margin-top: 6px;">إجمالي الأجور المتوقعة اليوم</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-top: 20px;">
      <div style="background: #EFF6FF; padding: 16px; border-radius: 12px;">
        <div style="font-size: 18px; font-weight: 700; color: #185FA5;">{{ $totalDistributions }}</div>
        <p style="font-size: 12px; color: #666; margin-top: 4px;">عدد التوزيعات</p>
      </div>
      <div style="background: #ECFDF5; padding: 16px; border-radius: 12px;">
        <div style="font-size: 18px; font-weight: 700; color: #059669;">{{ $totalWorkers }}</div>
        <p style="font-size: 12px; color: #666; margin-top: 4px;">عدد العمال</p>
      </div>
      <div style="background: #FEF3C7; padding: 16px; border-radius: 12px;">
        <div style="font-size: 18px; font-weight: 700; color: #D97706;">
          @php
            $companyCount = $distributions->pluck('company_id')->unique()->count();
          @endphp
          {{ $companyCount }}
        </div>
        <p style="font-size: 12px; color: #666; margin-top: 4px;">عدد الشركات النشطة</p>
      </div>
    </div>

    <div style="margin-top: 20px; padding: 16px; background: #F3F4F6; border-radius: 10px;">
      <p style="font-size: 13px; color: #555; margin: 0;">
        <strong>نصيحة:</strong> اذهب إلى صفحة <a href="{{ route('contractor.companies.index') }}" style="color: #185FA5; text-decoration: none; font-weight: 600;">الشركات</a> لإضافة عمالة جديدة لأي شركة اليوم
      </p>
    </div>
  </div>

  @if($distributions->count() > 0)
  <div style="background: #fff; border-radius: 14px; padding: 20px; margin-top: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
    <h3 style="font-size: 16px; font-weight: 700; color: #222; margin: 0 0 16px 0;">تفاصيل التوزيعات اليوم</h3>
    
    <div style="display: grid; gap: 10px;">
      @foreach($distributions as $distribution)
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border: 1px solid #f0f0f0; border-radius: 8px;">
        <div>
          <div style="font-size: 13px; font-weight: 600; color: #222;">{{ $distribution->company->name }}</div>
          <div style="font-size: 11px; color: #999; margin-top: 4px;">{{ $distribution->workers_count ?? 0 }} عامل</div>
        </div>
        <div style="text-align: left;">
          <div style="font-size: 14px; font-weight: 700; color: #059669;">{{ number_format($distribution->total_amount ?? 0) }} ج</div>
          <div style="font-size: 10px; color: #999; margin-top: 2px;">{{ $distribution->created_at->format('H:i') }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif
</div>

<script>
// Simple sorting by date
document.addEventListener('DOMContentLoaded', function() {
  console.log('Daily Analytics page loaded');
});
</script>

<!-- Modals removed - use company page to add workers -->
@endsection
