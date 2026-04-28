@extends('layouts.dashboard')
@section('title', 'حاسبة الأجور')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; direction: rtl; background: #f5f6f8; }

.topbar {
  background: linear-gradient(135deg, #059669 0%, #185FA5 100%);
  padding: 16px 20px 20px;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.page-title { color: #fff; font-size: 18px; font-weight: 700; }

.nav-tabs { display: flex; gap: 6px; margin-top: 4px; }
.nav-tab {
  color: rgba(255,255,255,0.75); font-size: 12px; font-weight: 600;
  padding: 6px 14px; border-radius: 20px; text-decoration: none;
  border: 1px solid rgba(255,255,255,0.3); transition: all 0.2s;
}
.nav-tab:hover, .nav-tab.active { background: rgba(255,255,255,0.2); color: #fff; }

.content { padding: 16px; }

/* Input card */
.input-card {
  background: #fff; border-radius: 16px; border: 1px solid #f0f0f0;
  padding: 20px; margin-bottom: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.card-title { font-size: 15px; font-weight: 700; color: #222; margin-bottom: 16px; }

.field { margin-bottom: 14px; }
.field label {
  display: block; font-size: 12px; font-weight: 600; color: #555;
  margin-bottom: 6px;
}
.field input {
  width: 100%; border: 1.5px solid #e5e7eb; border-radius: 10px;
  padding: 11px 14px; font-size: 15px; color: #222; outline: none;
  transition: border-color 0.2s; font-family: inherit;
  text-align: right;
}
.field input:focus { border-color: #059669; }
.field .hint { font-size: 10px; color: #aaa; margin-top: 4px; }

/* Result cards */
.results-grid {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 10px; margin-bottom: 16px;
}
.result-card {
  border-radius: 14px; padding: 16px; text-align: center;
  border: 1px solid transparent;
}
.result-card.daily   { background: #EFF6FF; border-color: #BFDBFE; }
.result-card.weekly  { background: #ECFDF5; border-color: #A7F3D0; }
.result-card.monthly { background: #FFF7ED; border-color: #FED7AA; }
.result-card.profit  { background: linear-gradient(135deg, #F0FDF4, #DCFCE7); border-color: #86EFAC; }

.result-card .rc-lbl { font-size: 11px; color: #666; font-weight: 600; margin-bottom: 6px; }
.result-card .rc-val { font-size: 22px; font-weight: 800; color: #222; }
.result-card .rc-sub { font-size: 10px; color: #aaa; margin-top: 3px; }
.result-card.profit .rc-val { color: #059669; font-size: 24px; }

/* Full-width monthly card */
.result-full {
  border-radius: 14px; padding: 18px 20px; margin-bottom: 14px;
  background: linear-gradient(135deg, #F5F3FF, #EDE9FE); border: 1px solid #DDD6FE;
}
.result-full .rc-lbl { font-size: 12px; color: #7C3AED; font-weight: 600; margin-bottom: 6px; }
.result-full .rc-val { font-size: 28px; font-weight: 800; color: #7C3AED; }
.result-full .rc-sub { font-size: 11px; color: #aaa; margin-top: 4px; }

/* Profit breakdown table */
.breakdown-card {
  background: #fff; border-radius: 14px; border: 1px solid #f0f0f0;
  overflow: hidden; margin-bottom: 16px;
}
.breakdown-head { padding: 12px 16px; background: #f9fafb; border-bottom: 1px solid #f0f0f0; }
.breakdown-title { font-size: 13px; font-weight: 700; color: #222; }
.breakdown-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 12px 16px; border-top: 1px solid #f5f5f5; font-size: 13px;
}
.breakdown-row .key { color: #555; }
.breakdown-row .val { font-weight: 700; }
.breakdown-row.profit-row { background: #f0fdf4; }
.breakdown-row.profit-row .val { color: #059669; font-size: 15px; }
.breakdown-row.cost-row .val { color: #D97706; }

/* Days note */
.days-note {
  background: #F3F4F6; border-radius: 10px; padding: 12px 14px;
  font-size: 12px; color: #555; margin-bottom: 14px;
  display: flex; gap: 10px; align-items: flex-start;
}
.days-note .icon { font-size: 18px; flex-shrink: 0; }

/* Reset btn */
.reset-btn {
  background: #EFF6FF; color: #185FA5; border: 1px solid #BFDBFE;
  padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600;
  cursor: pointer; width: 100%; font-family: inherit;
  transition: background 0.2s;
}
.reset-btn:hover { background: #DBEAFE; }

/* Mobile */
@media (max-width: 480px) {
  .results-grid { grid-template-columns: 1fr; }
  .result-card .rc-val { font-size: 20px; }
}
</style>

<div class="topbar">
  <div class="topbar-row">
    <div class="page-title">🧮 حاسبة الأجور</div>
  </div>
  <div class="nav-tabs">
    <a href="{{ route('contractor.profit.daily') }}" class="nav-tab">يومي</a>
    <a href="{{ route('contractor.profit.monthly') }}" class="nav-tab">شهري</a>
    <a href="{{ route('contractor.profit.calculator') }}" class="nav-tab active">🧮 الحاسبة</a>
  </div>
</div>

<div class="content">

  <!-- INPUT CARD -->
  <div class="input-card">
    <div class="card-title">أدخل بيانات الحساب</div>

    <div class="field">
      <label for="workers_count">عدد العمال</label>
      <input type="number" id="workers_count" min="0" placeholder="مثال: 12" oninput="calc()">
    </div>

    <div class="field">
      <label for="company_rate">أجرة العامل من الشركة (ج)</label>
      <input type="number" id="company_rate" min="0" step="0.5" placeholder="مثال: 350" oninput="calc()">
      <div class="hint">ما تتقاضاه من الشركة عن كل عامل في اليوم</div>
    </div>

    <div class="field">
      <label for="worker_rate">أجرة العامل الفعلية (ج) — <span style="color:#059669;">اختياري للربح</span></label>
      <input type="number" id="worker_rate" min="0" step="0.5" placeholder="مثال: 250" oninput="calc()">
      <div class="hint">ما تدفعه للعامل فعلياً — إذا تُركت فارغة يُحسب الإيراد فقط</div>
    </div>

    <div class="days-note">
      <span class="icon">ℹ️</span>
      <span>الأسبوع = 6 أيام عمل ← الشهر = 26 يوم عمل. يمكنك تغيير هذه القيم بالأسفل.</span>
    </div>

    <div style="display:flex; gap:10px;">
      <div class="field" style="flex:1; margin:0;">
        <label for="days_week">أيام الأسبوع</label>
        <input type="number" id="days_week" min="1" max="7" value="6" oninput="calc()">
      </div>
      <div class="field" style="flex:1; margin:0;">
        <label for="days_month">أيام الشهر</label>
        <input type="number" id="days_month" min="1" max="31" value="26" oninput="calc()">
      </div>
    </div>
  </div>

  <!-- REVENUE RESULTS -->
  <div class="results-grid">
    <div class="result-card daily">
      <div class="rc-lbl">إيراد اليوم</div>
      <div class="rc-val" id="rev_day">—</div>
      <div class="rc-sub">جنيه</div>
    </div>
    <div class="result-card weekly">
      <div class="rc-lbl">إيراد الأسبوع</div>
      <div class="rc-val" id="rev_week">—</div>
      <div class="rc-sub">جنيه</div>
    </div>
  </div>

  <div class="result-full">
    <div class="rc-lbl">إجمالي إيراد الشهر من الشركات</div>
    <div class="rc-val" id="rev_month">—</div>
    <div class="rc-sub">جنيه / شهر</div>
  </div>

  <!-- PROFIT BREAKDOWN (shown only when worker_rate is entered) -->
  <div class="breakdown-card" id="profit_section" style="display:none;">
    <div class="breakdown-head">
      <div class="breakdown-title">💰 تحليل الربح (بعد خصم أجور العمال)</div>
    </div>
    <div class="breakdown-row cost-row">
      <span class="key">تكلفة العمال اليومية</span>
      <span class="val" id="cost_day">—</span>
    </div>
    <div class="breakdown-row cost-row">
      <span class="key">تكلفة العمال الشهرية</span>
      <span class="val" id="cost_month">—</span>
    </div>
    <div class="breakdown-row">
      <span class="key">ربح العامل الواحد / يوم</span>
      <span class="val" id="profit_per_worker" style="color:#7C3AED;">—</span>
    </div>

    <div class="breakdown-row profit-row">
      <span class="key" style="font-weight:700;">صافي ربحك اليومي</span>
      <span class="val" id="profit_day">—</span>
    </div>
    <div class="breakdown-row profit-row">
      <span class="key" style="font-weight:700;">صافي ربحك الأسبوعي</span>
      <span class="val" id="profit_week">—</span>
    </div>
    <div class="breakdown-row profit-row" style="background:#dcfce7;">
      <span class="key" style="font-weight:700; font-size:14px;">صافي ربحك الشهري</span>
      <span class="val" id="profit_month" style="color:#16a34a; font-size:18px;">—</span>
    </div>
    <div class="breakdown-row" style="background:#f9fafb;">
      <span class="key">هامش الربح</span>
      <span class="val" id="margin_pct" style="color:#7C3AED;">—</span>
    </div>
  </div>

  <button class="reset-btn" onclick="resetAll()">🔄 إعادة تعيين</button>
</div>

<script>
function fmt(n) {
  if (isNaN(n) || n === null) return '—';
  return new Intl.NumberFormat('ar-EG').format(Math.round(n));
}

function calc() {
  const w  = parseFloat(document.getElementById('workers_count').value) || 0;
  const cr = parseFloat(document.getElementById('company_rate').value)  || 0;
  const wr = parseFloat(document.getElementById('worker_rate').value);
  const dw = parseFloat(document.getElementById('days_week').value)  || 6;
  const dm = parseFloat(document.getElementById('days_month').value) || 26;

  // Revenue
  const revDay   = w * cr;
  const revWeek  = revDay * dw;
  const revMonth = revDay * dm;

  document.getElementById('rev_day').textContent   = w > 0 && cr > 0 ? fmt(revDay)   : '—';
  document.getElementById('rev_week').textContent  = w > 0 && cr > 0 ? fmt(revWeek)  : '—';
  document.getElementById('rev_month').textContent = w > 0 && cr > 0 ? fmt(revMonth) : '—';

  // Profit (only when worker rate entered)
  const hasWorkerRate = !isNaN(wr) && wr > 0;
  document.getElementById('profit_section').style.display = hasWorkerRate ? 'block' : 'none';

  if (hasWorkerRate && w > 0 && cr > 0) {
    const costDay      = w * wr;
    const costMonth    = costDay * dm;
    const profitPerW   = cr - wr;
    const profitDay    = w * profitPerW;
    const profitWeek   = profitDay * dw;
    const profitMonth  = profitDay * dm;
    const marginPct    = cr > 0 ? ((profitPerW / cr) * 100).toFixed(1) : 0;

    document.getElementById('cost_day').textContent        = fmt(costDay)    + ' ج';
    document.getElementById('cost_month').textContent      = fmt(costMonth)  + ' ج';
    document.getElementById('profit_per_worker').textContent = fmt(profitPerW) + ' ج / عامل / يوم';
    document.getElementById('profit_day').textContent      = fmt(profitDay)   + ' ج';
    document.getElementById('profit_week').textContent     = fmt(profitWeek)  + ' ج';
    document.getElementById('profit_month').textContent    = fmt(profitMonth) + ' ج';
    document.getElementById('margin_pct').textContent      = marginPct + '%';
  }

  // Animate values
  document.querySelectorAll('.rc-val, .breakdown-row .val').forEach(el => {
    el.style.transform = 'scale(1.05)';
    setTimeout(() => { el.style.transform = 'scale(1)'; }, 150);
  });
}

function resetAll() {
  ['workers_count','company_rate','worker_rate'].forEach(id => {
    document.getElementById(id).value = '';
  });
  document.getElementById('days_week').value  = 6;
  document.getElementById('days_month').value = 26;
  ['rev_day','rev_week','rev_month'].forEach(id => {
    document.getElementById(id).textContent = '—';
  });
  document.getElementById('profit_section').style.display = 'none';
}
</script>
@endsection
