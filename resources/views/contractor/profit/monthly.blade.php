@extends('layouts.dashboard')
@section('title', 'تقرير الربح الشهري')

@section('content')
@php
  $arabicMonths = ['','يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
  $summary = $report['summary'];
  $gp = $summary['gross_profit'];
  $maxWeekProfit = $summary['by_week']->max('gross_profit') ?: 1;
@endphp
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; background: #f5f6f8; }
.topbar { background: linear-gradient(135deg,#7C3AED 0%,#185FA5 100%); padding:16px 20px 20px; }
.topbar-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
.page-title { color:#fff; font-size:18px; font-weight:700; }
.month-nav { display:flex; align-items:center; gap:10px; background:rgba(255,255,255,0.15); border-radius:12px; padding:8px 14px; }
.month-nav a { color:#fff; text-decoration:none; font-size:20px; opacity:0.8; transition:opacity 0.2s; }
.month-nav a:hover { opacity:1; }
.month-label { color:#fff; font-size:14px; font-weight:600; }
.nav-tabs { display:flex; gap:6px; margin-top:12px; }
.nav-tab { color:rgba(255,255,255,0.75); font-size:12px; font-weight:600; padding:6px 14px; border-radius:20px; text-decoration:none; border:1px solid rgba(255,255,255,0.3); transition:all 0.2s; }
.nav-tab:hover,.nav-tab.active { background:rgba(255,255,255,0.2); color:#fff; }
.kpi-strip { display:grid; grid-template-columns:repeat(3,1fr); background:#fff; border-bottom:1px solid #f0f0f0; }
.kpi-stat { text-align:center; padding:14px 8px; border-left:1px solid #f0f0f0; }
.kpi-stat:last-child { border-left:none; }
.kpi-val { font-size:18px; font-weight:700; }
.kpi-lbl { font-size:10px; color:#aaa; margin-top:3px; line-height:1.3; }
.content { padding:16px; }
.section-card { background:#fff; border-radius:14px; border:1px solid #f0f0f0; overflow:hidden; margin-bottom:14px; }
.section-head { padding:14px 16px; border-bottom:1px solid #f5f5f5; display:flex; align-items:center; justify-content:space-between; }
.section-title { font-size:14px; font-weight:700; color:#222; }
.section-badge { font-size:11px; font-weight:600; padding:3px 10px; border-radius:10px; background:#EDE9FE; color:#7C3AED; }
.info-row { display:flex; justify-content:space-between; padding:10px 14px; border-top:1px solid #f5f5f5; font-size:12px; }
.info-row .lbl { color:#666; }
.info-row .val { font-weight:700; color:#222; }
.top-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:10px; padding:14px; }
.top-card { border-radius:12px; padding:14px; text-align:center; border:1px solid #bae6fd; background:linear-gradient(135deg,#f0f9ff,#e0f2fe); position:relative; }
.top-card.r1 { background:linear-gradient(135deg,#fefce8,#fef9c3); border-color:#fde68a; }
.top-card.r2 { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#bbf7d0; }
.top-card.r3 { background:linear-gradient(135deg,#fff7ed,#ffedd5); border-color:#fed7aa; }
.rank-badge { position:absolute; top:8px; right:8px; font-size:16px; }
.top-name { font-size:12px; font-weight:700; color:#222; margin:4px 0 6px; }
.top-profit { font-size:15px; font-weight:700; color:#059669; }
.top-days { font-size:10px; color:#aaa; margin-top:3px; }
.weekly-chart { padding:14px 16px; }
.week-row { margin-bottom:12px; }
.week-label { font-size:11px; color:#666; margin-bottom:4px; display:flex; justify-content:space-between; }
.bar-track { background:#f0f0f0; border-radius:6px; height:10px; overflow:hidden; }
.bar-fill { height:100%; border-radius:6px; background:linear-gradient(90deg,#7C3AED,#185FA5); transition:width 0.6s ease; }
.profit-table { width:100%; border-collapse:collapse; font-size:12px; }
.profit-table th { text-align:right; padding:10px 12px; background:#f8f9fa; color:#666; font-weight:600; font-size:11px; }
.profit-table td { padding:11px 12px; border-top:1px solid #f5f5f5; color:#333; vertical-align:middle; }
.profit-table tr:hover td { background:#fafafa; }
.totals-row td { background:#f5f3ff; font-weight:700; border-top:2px solid #ddd6fe; }
.pp { color:#059669; font-weight:700; } .pn { color:#DC2626; font-weight:700; }
.mb { display:inline-block; padding:3px 9px; border-radius:10px; font-size:11px; font-weight:700; }
.mg { background:#dcfce7; color:#16a34a; } .mo { background:#fef9c3; color:#ca8a04; } .ml { background:#fee2e2; color:#dc2626; }
.empty-state { text-align:center; padding:40px 20px; }
.empty-icon { font-size:48px; margin-bottom:12px; }
.empty-title { font-size:15px; font-weight:700; color:#222; margin-bottom:6px; }
.empty-sub { font-size:12px; color:#aaa; }
@media(max-width:640px){
  .kpi-strip{grid-template-columns:repeat(2,1fr);}
  .top-grid{grid-template-columns:repeat(2,1fr);}
  .content{padding:12px;}
  .profit-table{font-size:11px;}
  .profit-table th,.profit-table td{padding:8px 8px;}
}
</style>

<div class="topbar">
  <div class="topbar-row">
    <div class="page-title">📅 تقرير الربح الشهري</div>
  </div>
  <div class="month-nav">
    <a href="{{ route('contractor.profit.monthly',['month'=>$prevMonth->month,'year'=>$prevMonth->year]) }}">‹</a>
    <div class="month-label">{{ $arabicMonths[$month] }} {{ $year }}</div>
    @if(!($month==now()->month&&$year==now()->year))
    <a href="{{ route('contractor.profit.monthly',['month'=>$nextMonth->month,'year'=>$nextMonth->year]) }}">›</a>
    @endif
  </div>
  <div class="nav-tabs">
    <a href="{{ route('contractor.profit.daily') }}" class="nav-tab">يومي</a>
    <a href="{{ route('contractor.profit.monthly') }}" class="nav-tab active">شهري</a>
    <a href="{{ route('contractor.profit.calculator') }}" class="nav-tab">🧮 الحاسبة</a>
  </div>
</div>

<div class="kpi-strip">
  <div class="kpi-stat">
    <div class="kpi-val" style="color:#185FA5;">{{ number_format($summary['total_revenue']) }}</div>
    <div class="kpi-lbl">الإيراد الكلي (ج)</div>
  </div>
  <div class="kpi-stat">
    <div class="kpi-val" style="color:{{ $gp>=0?'#059669':'#DC2626' }};">{{ number_format(abs($gp)) }}</div>
    <div class="kpi-lbl">صافي الربح (ج)</div>
  </div>
  <div class="kpi-stat">
    <div class="kpi-val" style="color:#7C3AED;">{{ $summary['profit_margin_pct'] }}%</div>
    <div class="kpi-lbl">هامش الربح</div>
  </div>
</div>

<div class="content">
@if($summary['by_company']->isEmpty())
  <div class="section-card">
    <div class="empty-state">
      <div class="empty-icon">📅</div>
      <div class="empty-title">لا توجد بيانات لهذا الشهر</div>
      <div class="empty-sub">{{ $arabicMonths[$month] }} {{ $year }}</div>
    </div>
  </div>
@else

  {{-- Summary --}}
  <div class="section-card">
    <div class="section-head">
      <div class="section-title">ملخص الشهر</div>
      @php $m=$summary['profit_margin_pct']; @endphp
      <span class="mb {{ $m>=20?'mg':($m>=10?'mo':'ml') }}">{{ $m }}% هامش</span>
    </div>
    <div class="info-row"><span class="lbl">إيراد الشركات</span><span class="val">{{ number_format($summary['total_revenue']) }} ج</span></div>
    <div class="info-row"><span class="lbl">تكلفة العمال</span><span class="val pn">{{ number_format($summary['total_workers_cost']) }} ج</span></div>
    <div class="info-row"><span class="lbl">الخصومات (ترفع ربحك)</span><span class="val pp">+{{ number_format($summary['total_deductions']) }} ج</span></div>
    <div class="info-row"><span class="lbl">السهر (يخفض ربحك)</span><span class="val pn">-{{ number_format($summary['total_overtime']) }} ج</span></div>
    <div class="info-row" style="border-top:2px solid #ede9fe;background:#faf5ff;padding:12px 14px;">
      <span class="lbl" style="font-weight:700;font-size:13px;">صافي الربح</span>
      <span class="{{ $gp>=0?'pp':'pn' }}" style="font-size:15px;">{{ number_format(abs($gp)) }} ج</span>
    </div>
  </div>

  {{-- Top companies --}}
  @if($report['top_companies']->isNotEmpty())
  <div class="section-card">
    <div class="section-head"><div class="section-title">🏆 أفضل الشركات ربحاً</div><div class="section-badge">هذا الشهر</div></div>
    <div class="top-grid">
      @foreach($report['top_companies'] as $i=>$co)
      @php $rc=$i===0?'r1':($i===1?'r2':($i===2?'r3':'')); $re=$i===0?'🥇':($i===1?'🥈':($i===2?'🥉':($i+1))); @endphp
      <div class="top-card {{ $rc }}">
        <div class="rank-badge">{{ $re }}</div>
        <div class="top-name">{{ $co->company_name }}</div>
        <div class="top-profit">{{ number_format($co->gross_profit) }} ج</div>
        <div class="top-days">{{ $co->days_worked }} يوم</div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Weekly bars --}}
  @if($summary['by_week']->isNotEmpty())
  <div class="section-card">
    <div class="section-head"><div class="section-title">الربح الأسبوعي</div></div>
    <div class="weekly-chart">
      @foreach($summary['by_week'] as $week)
      @php $bp=$maxWeekProfit>0?max(0,min(100,($week->gross_profit/$maxWeekProfit)*100)):0; @endphp
      <div class="week-row">
        <div class="week-label">
          <span>{{ \Carbon\Carbon::parse($week->date_from)->format('d/m') }} — {{ \Carbon\Carbon::parse($week->date_to)->format('d/m') }}</span>
          <span style="color:{{ $week->gross_profit>=0?'#059669':'#DC2626' }};font-weight:700;">{{ number_format($week->gross_profit) }} ج</span>
        </div>
        <div class="bar-track"><div class="bar-fill" style="width:{{ $bp }}%;"></div></div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Per-company table --}}
  <div class="section-card">
    <div class="section-head">
      <div class="section-title">تفصيل الشركات</div>
      <div class="section-badge">{{ $summary['by_company']->count() }} شركة</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="profit-table">
        <thead>
          <tr>
            <th>الشركة</th>
            <th style="text-align:center;">أيام</th>
            <th style="text-align:center;">إيراد</th>
            <th style="text-align:center;">تكلفة</th>
            <th style="text-align:center;">خصومات</th>
            <th style="text-align:center;">سهر</th>
            <th style="text-align:center;">الربح</th>
            <th style="text-align:center;">هامش</th>
          </tr>
        </thead>
        <tbody>
          @foreach($summary['by_company']->sortByDesc('gross_profit') as $co)
          @php $cm=$co->total_revenue>0?round(($co->gross_profit/$co->total_revenue)*100,0):0; @endphp
          <tr>
            <td style="font-weight:600;">{{ $co->company_name }}</td>
            <td style="text-align:center;">{{ $co->days_worked }}</td>
            <td style="text-align:center;color:#185FA5;font-weight:600;">{{ number_format($co->total_revenue) }} ج</td>
            <td style="text-align:center;color:#D97706;">{{ number_format($co->total_worker_cost) }} ج</td>
            <td style="text-align:center;color:#059669;">+{{ number_format($co->total_deductions) }} ج</td>
            <td style="text-align:center;color:#DC2626;">-{{ number_format($co->overtime_cost) }} ج</td>
            <td style="text-align:center;" class="{{ $co->gross_profit>=0?'pp':'pn' }}">{{ number_format(abs($co->gross_profit)) }} ج</td>
            <td style="text-align:center;"><span class="mb {{ $cm>=20?'mg':($cm>=10?'mo':'ml') }}">{{ $cm }}%</span></td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="totals-row">
            <td>الإجمالي</td>
            <td style="text-align:center;">—</td>
            <td style="text-align:center;">{{ number_format($summary['total_revenue']) }} ج</td>
            <td style="text-align:center;">{{ number_format($summary['total_workers_cost']) }} ج</td>
            <td style="text-align:center;">+{{ number_format($summary['total_deductions']) }} ج</td>
            <td style="text-align:center;">-{{ number_format($summary['total_overtime']) }} ج</td>
            <td style="text-align:center;font-size:14px;" class="{{ $gp>=0?'pp':'pn' }}">{{ number_format(abs($gp)) }} ج</td>
            <td style="text-align:center;"><span class="mb {{ $m>=20?'mg':($m>=10?'mo':'ml') }}">{{ $m }}%</span></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
@endif
</div>

<script>
window.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('.bar-fill').forEach(b=>{
    const t=b.style.width; b.style.width='0';
    setTimeout(()=>{ b.style.width=t; },200);
  });
});
</script>
@endsection
