@extends('layouts.dashboard')
@section('title', 'تفاصيل التوزيع')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.topbar {
  background: linear-gradient(135deg, #185FA5 0%, #1D9E75 100%);
  padding: 16px 20px;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; }
.back-btn { color: #fff; font-size: 13px; cursor: pointer; }
.back-btn:hover { opacity: 0.8; }
.top-title { color: #fff; font-size: 16px; font-weight: 700; }

.company-card {
  background: linear-gradient(135deg, #185FA5, #1D9E75);
  border-radius: 16px;
  padding: 20px;
  margin: 16px;
  color: #fff;
}
.comp-name { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
.comp-meta { font-size: 12px; opacity: 0.85; margin-bottom: 14px; }

.info-grid {
  display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px;
}
.info-box {
  background: rgba(255,255,255,0.15);
  border-radius: 12px; padding: 12px;
  text-align: center;
}
.info-val { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
.info-lbl { font-size: 10px; opacity: 0.8; }

.section-title {
  font-size: 13px; font-weight: 700; color: #222;
  padding: 14px 16px 8px; text-transform: uppercase; background: #fff;
  border-bottom: 1px solid #f0f0f0;
}

.worker-list-item {
  background: #fff;
  padding: 12px 16px;
  border-bottom: 1px solid #f5f5f5;
  display: flex; align-items: center; gap: 12px;
}
.worker-list-item:last-child { border-bottom: none; }

.w-avatar {
  width: 40px; height: 40px;
  border-radius: 50%; display: flex;
  align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700;
  background: #EFF6FF; color: #1D4ED8; flex-shrink: 0;
}

.w-details {
  flex: 1; min-width: 0;
}
.w-name { font-size: 13px; font-weight: 600; color: #222; }
.w-sub { font-size: 11px; color: #aaa; margin-top: 2px; }
.w-wage { text-align: left; }
.w-wage-val { font-size: 13px; font-weight: 700; color: #059669; }

.action-buttons {
  display: flex; gap: 8px; padding: 16px;
  background: #fff; border-top: 1px solid #f0f0f0;
}
.btn {
  flex: 1; padding: 12px; border: none; border-radius: 12px;
  font-size: 13px; font-weight: 600; cursor: pointer;
  text-decoration: none; display: flex; align-items: center; 
  justify-content: center; gap: 6px; transition: all 0.2s;
}
.btn-primary { background: #1D9E75; color: #fff; }
.btn-primary:hover { background: #0F6E56; }
.btn-secondary { background: #EFF6FF; color: #185FA5; }
.btn-secondary:hover { background: #D9E9F7; }
.btn-danger { background: #FEF2F2; color: #991B1B; }
.btn-danger:hover { background: #fee2e2; }

.history-item {
  background: #fff; padding: 14px 16px;
  border-bottom: 1px solid #f5f5f5;
  border-left: 3px solid #1D9E75;
}
.history-item:nth-child(even) { border-left-color: #185FA5; }
.his-action { font-size: 12px; font-weight: 600; color: #222; margin-bottom: 4px; }
.his-time { font-size: 11px; color: #aaa; }

.empty-section {
  padding: 20px 16px; text-align: center; color: #aaa; font-size: 12px;
}

/* MOBILE */
@media (max-width: 640px) {
  .topbar { padding: 12px 12px; }
  .top-title { font-size: 14px; }
  .company-card { margin: 12px; padding: 16px; }
  .comp-name { font-size: 16px; }
  .comp-meta { font-size: 11px; }
  .info-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
  .info-box { padding: 10px; }
  .info-val { font-size: 16px; }
  
  .worker-list-item { padding: 10px 12px; }
  .w-avatar { width: 36px; height: 36px; font-size: 12px; }
  .w-name { font-size: 12px; }
  .w-sub { font-size: 10px; }
  
  .action-buttons { gap: 6px; padding: 12px; }
  .btn { padding: 10px; font-size: 12px; }
}
</style>

<!-- TOPBAR -->
<div class="topbar">
  <div class="topbar-row">
    <div class="back-btn" onclick="window.history.back()">← رجوع</div>
    <div class="top-title">تفاصيل التوزيع</div>
    <div style="width:40px;"></div>
  </div>
</div>

<!-- COMPANY CARD -->
<div class="company-card">
  <div class="comp-name">{{ $distribution->company->name }}</div>
  <div class="comp-meta">{{ $distribution->created_at->format('l، j F Y') }}</div>
  <div class="info-grid">
    <div class="info-box">
      <div class="info-val">{{ $distribution->workers->count() }}</div>
      <div class="info-lbl">عدد العمال</div>
    </div>
    <div class="info-box">
      <div class="info-val">{{ $distribution->company->daily_wage }} ج</div>
      <div class="info-lbl">الأجر/عامل</div>
    </div>
    <div class="info-box">
      <div class="info-val">{{ $distribution->total_amount ?? 0 }} ج</div>
      <div class="info-lbl">الإجمالي</div>
    </div>
  </div>
</div>

<!-- WORKERS SECTION -->
<div class="section-title">العمال المعينون</div>
<div style="background:#fff;">
  @forelse($distribution->workers as $worker)
  <div class="worker-list-item">
    <div class="w-avatar">{{ substr($worker->name, 0, 1) }}</div>
    <div class="w-details">
      <div class="w-name">{{ $worker->name }}</div>
      <div class="w-sub">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }}</div>
    </div>
    <div class="w-wage">
      <div class="w-wage-val">{{ $distribution->company->daily_wage }} ج</div>
    </div>
  </div>
  @empty
  <div class="empty-section">لا يوجد عمال معينين</div>
  @endforelse
</div>

<!-- HISTORY SECTION -->
@if($distribution->actionLogs->count() > 0)
<div class="section-title" style="margin-top:16px;">سجل التعديلات</div>
<div style="background:#fff;border-radius:12px;margin:0 16px 16px;overflow:hidden;border:1px solid #f0f0f0;">
  @foreach($distribution->actionLogs as $log)
  <div class="history-item">
    <div class="his-action">{{ $log->action === 'created' ? 'تم الإنشاء' : ($log->action === 'updated' ? 'تم التعديل' : 'تم الإلغاء') }}</div>
    <div class="his-time">{{ $log->created_at->format('H:i · j F') }}</div>
  </div>
  @endforeach
</div>
@endif

<!-- ACTION BUTTONS -->
<div class="action-buttons">
  @if($distribution->canEdit())
  <a href="{{ route('contractor.distributions.edit', $distribution->id) }}" class="btn btn-secondary">✎ تعديل</a>
  <form action="{{ route('contractor.distributions.destroy', $distribution->id) }}" method="POST" style="flex:1;" onsubmit="return confirm('هل تريد إلغاء هذا التوزيع؟')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-danger" style="width:100%;">✕ إلغاء</button>
  </form>
  @else
  <div style="flex:1;text-align:center;padding:12px;background:#f0f0f0;border-radius:12px;color:#aaa;font-size:12px;">
    انتهت فترة التعديل (أكثر من 7 أيام)
  </div>
  @endif
</div>

<script>
document.querySelectorAll('.btn-danger').forEach(btn => {
  if (btn.parentElement.tagName === 'FORM') {
    btn.parentElement.style.flex = '1';
  }
});
</script>
@endsection
