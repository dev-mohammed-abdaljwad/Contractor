@extends('layouts.dashboard')
@section('title', 'تقرير الربح اليومي')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; background: #f5f6f8; }

.topbar {
  background: linear-gradient(135deg, #185FA5 0%, #1D9E75 100%);
  padding: 16px 20px 20px;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.page-title { color: #fff; font-size: 18px; font-weight: 700; }

.date-nav {
  display: flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,0.15); border-radius: 12px;
  padding: 8px 12px;
}
.date-nav a {
  color: #fff; text-decoration: none; font-size: 20px; line-height: 1;
  opacity: 0.8; transition: opacity 0.2s;
}
.date-nav a:hover { opacity: 1; }
.date-label { color: #fff; font-size: 14px; font-weight: 600; }
.date-form input[type=date] {
  background: transparent; border: none; color: #fff; font-size: 13px;
  cursor: pointer; outline: none; width: 130px;
}

/* Nav tabs */
.nav-tabs {
  display: flex; gap: 6px; padding: 0 16px; margin-top: 12px;
}
.nav-tab {
  color: rgba(255,255,255,0.75); font-size: 12px; font-weight: 600;
  padding: 6px 14px; border-radius: 20px; text-decoration: none;
  border: 1px solid rgba(255,255,255,0.3); transition: all 0.2s;
}
.nav-tab:hover, .nav-tab.active {
  background: rgba(255,255,255,0.2); color: #fff;
}

/* Summary strip */
.summary-strip {
  display: grid; grid-template-columns: repeat(4, 1fr);
  background: #fff; border-bottom: 1px solid #f0f0f0;
}
.strip-stat { text-align: center; padding: 12px 6px; border-left: 1px solid #f0f0f0; }
.strip-stat:last-child { border-left: none; }
.strip-val { font-size: 17px; font-weight: 700; }
.strip-lbl { font-size: 10px; color: #aaa; margin-top: 2px; line-height: 1.3; }
.sv-blue { color: #185FA5; } .sv-green { color: #059669; }
.sv-amber { color: #D97706; } .sv-red { color: #DC2626; }
.sv-purple { color: #7C3AED; }

/* Main content */
.content { padding: 16px; }

.section-card {
  background: #fff; border-radius: 14px; border: 1px solid #f0f0f0;
  overflow: hidden; margin-bottom: 14px;
}
.section-head {
  padding: 14px 16px; border-bottom: 1px solid #f5f5f5;
  display: flex; align-items: center; justify-content: space-between;
}
.section-title { font-size: 14px; font-weight: 700; color: #222; }
.section-badge {
  font-size: 11px; font-weight: 600; padding: 3px 10px;
  border-radius: 10px; background: #EFF6FF; color: #185FA5;
}

/* Table */
.profit-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.profit-table th {
  text-align: right; padding: 10px 12px;
  background: #f8f9fa; color: #666; font-weight: 600; font-size: 11px;
}
.profit-table td {
  padding: 12px 12px; border-top: 1px solid #f5f5f5;
  color: #333; vertical-align: middle;
}
.profit-table tr:hover td { background: #fafafa; }

.company-name { font-weight: 600; color: #222; font-size: 13px; }
.workers-pill {
  background: #EFF6FF; color: #185FA5; font-size: 10px;
  font-weight: 600; padding: 2px 8px; border-radius: 10px;
  display: inline-block; margin-top: 3px;
}

.amount-green { color: #059669; font-weight: 700; }
.amount-red   { color: #DC2626; font-weight: 700; }
.amount-amber { color: #D97706; font-weight: 700; }

.profit-positive { color: #059669; font-weight: 700; font-size: 13px; }
.profit-negative { color: #DC2626; font-weight: 700; font-size: 13px; }
.profit-zero     { color: #6B7280; font-weight: 600; font-size: 13px; }

/* Totals row */
.totals-row td { background: #f0fdf4; font-weight: 700; border-top: 2px solid #bbf7d0; }

/* Profit margin badge */
.margin-badge {
  display: inline-block; padding: 4px 10px; border-radius: 12px;
  font-size: 12px; font-weight: 700;
}
.margin-good  { background: #dcfce7; color: #16a34a; }
.margin-ok    { background: #fef9c3; color: #ca8a04; }
.margin-low   { background: #fee2e2; color: #dc2626; }

/* Empty state */
.empty-state { text-align: center; padding: 40px 20px; }
.empty-icon  { font-size: 48px; margin-bottom: 12px; }
.empty-title { font-size: 15px; font-weight: 700; color: #222; margin-bottom: 6px; }
.empty-sub   { font-size: 12px; color: #aaa; }

/* Worker breakdown */
.worker-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 10px 16px; border-top: 1px solid #f5f5f5;
}
.worker-info .name { font-size: 13px; font-weight: 600; color: #222; }
.worker-info .co   { font-size: 11px; color: #aaa; margin-top: 2px; }
.worker-amounts { text-align: left; }
.worker-amounts .profit { font-size: 13px; font-weight: 700; }
.worker-amounts .detail { font-size: 10px; color: #aaa; }

/* Mobile */
@media (max-width: 640px) {
  .topbar { padding: 12px 12px 14px; }
  .page-title { font-size: 16px; }
  .date-label { font-size: 12px; }
  .summary-strip { grid-template-columns: repeat(2, 1fr); }
  .strip-val { font-size: 15px; }
  .strip-lbl { font-size: 9px; }
  .profit-table { font-size: 11px; }
  .profit-table th, .profit-table td { padding: 8px 8px; }
  .company-name { font-size: 12px; }
  .content { padding: 12px; }
}
</style>

<!-- TOPBAR -->
<div class="topbar">
  <div class="topbar-row">
    <div class="page-title">💰 تقرير الربح اليومي</div>
  </div>

  <!-- Date navigation -->
  <div class="date-nav">
    <a href="{{ route('contractor.profit.daily', ['date' => $prevDate]) }}">‹</a>
    <div class="date-label">{{ $date->format('d/m/Y') }}</div>
    @if(!$isToday)
    <a href="{{ route('contractor.profit.daily', ['date' => $nextDate]) }}">›</a>
    @else
    <span style="color:rgba(255,255,255,0.3); font-size:20px;">›</span>
    @endif
    <form method="GET" class="date-form" style="margin-right:4px;">
      <input type="date" name="date" value="{{ $report['date'] }}"
             onchange="this.form.submit()" max="{{ now()->format('Y-m-d') }}">
    </form>
  </div>

  <!-- Nav tabs -->
  <div class="nav-tabs">
    <a href="{{ route('contractor.profit.daily') }}" class="nav-tab active">يومي</a>
    <a href="{{ route('contractor.profit.monthly') }}" class="nav-tab">شهري</a>
    <a href="{{ route('contractor.profit.calculator') }}" class="nav-tab">🧮 الحاسبة</a>
  </div>
</div>

<!-- SUMMARY STRIP -->
<div class="summary-strip">
  <div class="strip-stat">
    <div class="strip-val sv-blue">{{ number_format($report['totals']['total_revenue']) }}</div>
    <div class="strip-lbl">إيراد<br>الشركات</div>
  </div>
  <div class="strip-stat">
    <div class="strip-val sv-amber">{{ number_format($report['totals']['total_worker_cost']) }}</div>
    <div class="strip-lbl">تكلفة<br>العمال</div>
  </div>
  <div class="strip-stat">
    @php $profit = $report['totals']['gross_profit']; @endphp
    <div class="strip-val {{ $profit >= 0 ? 'sv-green' : 'sv-red' }}">{{ number_format(abs($profit)) }}</div>
    <div class="strip-lbl">صافي<br>الربح</div>
  </div>
  <div class="strip-stat">
    @php $margin = $report['totals']['profit_margin_pct']; @endphp
    <div class="strip-val sv-purple">{{ $margin }}%</div>
    <div class="strip-lbl">هامش<br>الربح</div>
  </div>
</div>

<div class="content">

  @if($report['by_company']->isEmpty())
  <!-- Empty state -->
  <div class="section-card">
    <div class="empty-state">
      <div class="empty-icon">📊</div>
      <div class="empty-title">لا توجد توزيعات لهذا اليوم</div>
      <div class="empty-sub">{{ $date->format('d/m/Y') }} — جرّب يوماً آخر أو أنشئ توزيعاً جديداً</div>
    </div>
  </div>
  @else

  <!-- PER-COMPANY PROFIT TABLE -->
  <div class="section-card">
    <div class="section-head">
      <div class="section-title">ربح الشركات</div>
      <div class="section-badge">{{ $report['totals']['companies_count'] }} شركة</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="profit-table">
        <thead>
          <tr>
            <th>الشركة</th>
            <th style="text-align:center;">العمال</th>
            <th style="text-align:center;">إيراد الشركة</th>
            <th style="text-align:center;">تكلفة العمال</th>
            <th style="text-align:center;">خصومات</th>
            <th style="text-align:center;">سهر</th>
            <th style="text-align:center;">الربح</th>
          </tr>
        </thead>
        <tbody>
          @foreach($report['by_company'] as $row)
          @php
            $rowProfit = $row->gross_profit;
          @endphp
          <tr>
            <td>
              <div class="company-name">{{ $row->company_name }}</div>
              <span class="workers-pill">{{ $row->workers_count }} عامل</span>
            </td>
            <td style="text-align:center;">{{ $row->workers_count }}</td>
            <td style="text-align:center;" class="amount-blue">{{ number_format($row->total_revenue) }} ج</td>
            <td style="text-align:center;" class="amount-amber">{{ number_format($row->total_worker_cost) }} ج</td>
            <td style="text-align:center;" class="amount-green">
              @if($row->total_deductions > 0)
                +{{ number_format($row->total_deductions) }} ج
              @else
                —
              @endif
            </td>
            <td style="text-align:center;" class="amount-red">
              @if($row->overtime_cost > 0)
                -{{ number_format($row->overtime_cost) }} ج
              @else
                —
              @endif
            </td>
            <td style="text-align:center;">
              <span class="{{ $rowProfit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                {{ number_format(abs($rowProfit)) }} ج
              </span>
              @if($row->total_revenue > 0)
              @php $rowMargin = round(($rowProfit / $row->total_revenue) * 100, 0); @endphp
              <br><span class="margin-badge {{ $rowMargin >= 20 ? 'margin-good' : ($rowMargin >= 10 ? 'margin-ok' : 'margin-low') }}">
                {{ $rowMargin }}%
              </span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="totals-row">
            <td><strong>الإجمالي</strong></td>
            <td style="text-align:center;"><strong>{{ $report['totals']['workers_count'] }}</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($report['totals']['total_revenue']) }} ج</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($report['totals']['total_worker_cost']) }} ج</strong></td>
            <td style="text-align:center;"><strong>+{{ number_format($report['totals']['total_deductions']) }} ج</strong></td>
            <td style="text-align:center;"><strong>-{{ number_format($report['totals']['total_overtime']) }} ج</strong></td>
            <td style="text-align:center;">
              @php $gp = $report['totals']['gross_profit']; @endphp
              <span class="{{ $gp >= 0 ? 'profit-positive' : 'profit-negative' }}" style="font-size:15px;">
                {{ number_format(abs($gp)) }} ج
              </span>
              <br>
              <span class="margin-badge {{ $report['totals']['profit_margin_pct'] >= 20 ? 'margin-good' : ($report['totals']['profit_margin_pct'] >= 10 ? 'margin-ok' : 'margin-low') }}">
                {{ $report['totals']['profit_margin_pct'] }}%
              </span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- PER-WORKER BREAKDOWN -->
  @if($report['workers']->isNotEmpty())
  <div class="section-card">
    <div class="section-head">
      <div class="section-title">تفصيل ربح العمال</div>
      <div class="section-badge">{{ $report['totals']['workers_count'] }} عامل</div>
    </div>
    @foreach($report['workers'] as $w)
    @php $wProfit = $w->profit; @endphp
    <div class="worker-row">
      <div class="worker-info">
        <div class="name">{{ $w->worker_name }}</div>
        <div class="co">{{ $w->company_name }}</div>
      </div>
      <div class="worker-amounts">
        <div class="profit {{ $wProfit >= 0 ? 'amount-green' : 'amount-red' }}">
          {{ number_format(abs($wProfit)) }} ج
        </div>
        <div class="detail">
          {{ number_format($w->company_wage) }} - {{ number_format($w->net_worker_cost) }}
          @if($w->deduction > 0) (+{{ number_format($w->deduction) }})@endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Animate numbers on load
  document.querySelectorAll('.strip-val').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(8px)';
    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    setTimeout(() => {
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    }, 100);
  });
});
</script>
@endsection
