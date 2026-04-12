@extends('layouts.dashboard')

@section('title', 'ساعات السهر - ' . $worker->name)

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Tajawal', sans-serif; direction: rtl; background: #f5f6f0; }

.topbar {
    background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%);
    padding: 18px 20px 54px;
}
.top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.back-btn {
    color: rgba(255, 255, 255, .8);
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
}
.top-title {
    color: #fff;
    font-size: 17px;
    font-weight: 900;
}
.top-sub {
    color: rgba(255, 255, 255, .65);
    font-size: 11px;
    margin-top: 2px;
}

/* worker recap */
.worker-recap {
    background: #fff;
    border-radius: 16px;
    margin: 0 14px;
    margin-top: -36px;
    position: relative;
    z-index: 10;
    padding: 14px 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, .09);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.w-av-sm {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0a4f14, #1D9E75);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 900;
    color: #fff;
    flex-shrink: 0;
}
.w-name {
    font-size: 14px;
    font-weight: 800;
    color: #1a1a1a;
}
.w-meta {
    font-size: 11px;
    color: #aaa;
    margin-top: 2px;
}
.w-wage {
    text-align: left;
    margin-right: auto;
}
.w-wage-val {
    font-size: 15px;
    font-weight: 800;
    color: #0a4f14;
}
.w-wage-lbl {
    font-size: 10px;
    color: #aaa;
    text-align: left;
}

/* tabs */
.tabs-bar {
    display: flex;
    background: #fff;
    border-bottom: 1px solid #f0f0e8;
    padding: 0 14px;
    margin-bottom: 12px;
}
.tab {
    flex: 1;
    text-align: center;
    padding: 11px 4px;
    font-size: 12px;
    color: #aaa;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all .2s;
}
.tab.active {
    color: #0a4f14;
    font-weight: 700;
    border-bottom-color: #1D9E75;
}

.body {
    padding: 0 14px 80px;
}
.sec-label {
    font-size: 10px;
    font-weight: 700;
    color: #bbb;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 10px;
    margin-top: 14px;
}

/* daily row */
.day-row {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #f0f0e8;
    padding: 12px 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: border-color .2s;
}
.day-row.has-ot {
    border-color: #1D9E75;
    background: #fcfffe;
}
.day-row.no-dist {
    opacity: .45;
    pointer-events: none;
}
.day-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-green { background: #1D9E75; }
.dot-amber { background: #f59e0b; }
.dot-gray { background: #d1d5db; }

.day-info {
    flex: 1;
    min-width: 0;
}
.day-name {
    font-size: 13px;
    font-weight: 700;
    color: #1a1a1a;
}
.day-co {
    font-size: 11px;
    color: #aaa;
    margin-top: 1px;
}
.day-wage {
    font-size: 12px;
    font-weight: 700;
    color: #0a4f14;
    flex-shrink: 0;
}

/* overtime stepper */
.ot-stepper {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.ot-btn {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    border: 1.5px solid #e0e0d8;
    background: #f8f9f0;
    color: #555;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
    font-family: 'Tajawal', sans-serif;
}
.ot-btn:hover {
    border-color: #1D9E75;
    color: #0a4f14;
    background: #ecfdf5;
}
.ot-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.ot-val {
    font-size: 13px;
    font-weight: 800;
    color: #0a4f14;
    min-width: 28px;
    text-align: center;
}
.ot-lbl {
    font-size: 9px;
    color: #aaa;
    text-align: center;
    margin-top: 1px;
}
.ot-badge {
    font-size: 10px;
    font-weight: 700;
    background: #ecfdf5;
    color: #065f46;
    padding: 2px 7px;
    border-radius: 20px;
    border: 1px solid #6ee7b7;
}

/* weekly summary card */
.summary-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 12px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, .06);
}
.sum-head {
    background: linear-gradient(135deg, #0a4f14, #1D9E75);
    padding: 14px 16px;
    color: #fff;
}
.sum-head-title {
    font-size: 14px;
    font-weight: 700;
}
.sum-head-sub {
    font-size: 11px;
    opacity: .7;
    margin-top: 2px;
}
.sum-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1px;
    background: #f0f0e8;
}
.sum-stat {
    background: #fff;
    padding: 14px 12px;
    text-align: center;
}
.sum-val {
    font-size: 20px;
    font-weight: 800;
}
.sum-lbl {
    font-size: 10px;
    color: #aaa;
    margin-top: 2px;
}
.sv-green { color: #0a4f14; }
.sv-teal { color: #1D9E75; }
.sv-amber { color: #c8961a; }
.sv-blue { color: #185fa5; }

.calc-rows {
    padding: 14px 16px;
}
.calc-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    font-size: 13px;
    border-bottom: 1px solid #f5f5f0;
}
.calc-row:last-child {
    border-bottom: none;
}
.calc-key {
    color: #888;
}
.calc-val {
    font-weight: 700;
    color: #222;
}
.calc-divider {
    border-top: 1.5px solid #e8e8e0;
    margin: 8px 0;
}
.calc-total {
    display: flex;
    justify-content: space-between;
    padding: 10px 16px;
    background: #ecfdf5;
    border-top: 1px solid #bbf7d0;
}
.calc-total-key {
    font-size: 14px;
    font-weight: 700;
    color: #065f46;
}
.calc-total-val {
    font-size: 18px;
    font-weight: 900;
    color: #0a4f14;
}

/* deductions section */
.deduct-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 16px;
    font-size: 12px;
}
.deduct-key {
    color: #888;
}
.deduct-val {
    font-weight: 700;
}
.dv-red { color: #dc2626; }
.dv-amber { color: #c8961a; }
.dv-green { color: #0a4f14; }

/* save bar */
.save-bar {
    position: sticky;
    bottom: 0;
    background: #fff;
    border-top: 1px solid #f0f0e8;
    padding: 12px 14px;
    display: flex;
    gap: 8px;
}
.save-btn {
    flex: 2;
    background: #0a4f14;
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Tajawal', sans-serif;
    transition: all .2s;
}
.save-btn:hover {
    background: #1D9E75;
}
.save-btn:disabled {
    background: #aaa;
    cursor: not-allowed;
}
.cancel-btn {
    flex: 1;
    background: #f0f0e8;
    color: #666;
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Tajawal', sans-serif;
}

/* toast */
.toast {
    position: fixed;
    bottom: 80px;
    right: 50%;
    transform: translateX(50%);
    background: #0a4f14;
    color: #fff;
    padding: 10px 22px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    opacity: 0;
    transition: opacity .3s;
    pointer-events: none;
    white-space: nowrap;
    z-index: 100;
}
.toast.show {
    opacity: 1;
}

/* error state */
.error-message {
    color: #dc2626;
    font-size: 12px;
    margin-top: 4px;
}

/* responsive */
@media (max-width: 640px) {
    .topbar {
        padding: 12px 12px 14px;
    }
    .top-title {
        font-size: 16px;
    }
    .worker-recap {
        margin: 0 12px;
        margin-top: -24px;
        padding: 12px;
    }
    .tabs-bar {
        padding: 0 12px;
    }
    .body {
        padding: 0 12px 80px;
    }
    .save-bar {
        padding: 10px 12px;
    }
}
</style>

<div>
    <!-- TOPBAR -->
    <div class="topbar">
        <div class="top-row">
            <a href="{{ route('contractor.workers.show', $worker->id) }}" class="back-btn" style="text-decoration:none;color:inherit;">← رجوع</a>
            <div class="top-title">ساعات السهر</div>
            <div style="width:40px;"></div>
        </div>
        <div class="top-sub">تسجيل ساعات العمل الإضافي</div>
    </div>

    <!-- Worker Recap -->
    <div class="worker-recap">
        <div class="w-av-sm">{{ strtoupper(substr($worker->name, 0, 2)) }}</div>
        <div>
            <div class="w-name">{{ $worker->name }}</div>
            <div class="w-meta">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }} · هذا الأسبوع: {{ $summary['days_worked'] }} أيام</div>
        </div>
        <div class="w-wage">
            <div class="w-wage-val">{{ number_format($worker->daily_wage ?? 0) }} ج</div>
            <div class="w-wage-lbl">سعر اليوم</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs-bar">
        <div class="tab active" onclick="switchTab(0, this)" style="cursor:pointer;">تسجيل الساعات</div>
        <div class="tab" onclick="switchTab(1, this)" style="cursor:pointer;">ملخص الأسبوع</div>
    </div>

    <!-- ══ TAB 0: Record Overtime ══ -->
    <div id="tab0" class="body">
        <div class="sec-label">أيام هذا الأسبوع — اضغط + لإضافة ساعات</div>

        @forelse($distributions as $dist)
            <div class="day-row {{ $dist->overtime_hours > 0 ? 'has-ot' : '' }}">
                <div class="day-dot dot-green"></div>
                <div class="day-info">
                    <div class="day-name">{{ $dist->distribution_date->locale('ar')->translatedFormat('l j F') }}</div>
                    <div class="day-co">
                        {{ $dist->company->name }} · {{ number_format($dist->total_amount ?? 0) }} ج
                        @if($dist->overtime_hours > 0)
                            <span class="ot-badge">{{ (int)$dist->overtime_hours }} ساعة{{ $dist->overtime_hours != 1 ? 'ات' : '' }} سهر</span>
                        @endif
                    </div>
                </div>
                <div class="ot-stepper">
                    <button class="ot-btn" onclick="changeOT(this,-1)" data-dist-id="{{ $dist->id }}">−</button>
                    <div>
                        <div class="ot-val" style="color:{{ $dist->overtime_hours > 0 ? '#0a4f14' : '#aaa' }};">{{ (int)$dist->overtime_hours }}</div>
                        <div class="ot-lbl">ساعة</div>
                    </div>
                    <button class="ot-btn" onclick="changeOT(this,1)" data-dist-id="{{ $dist->id }}">+</button>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:40px 20px;color:#aaa;">
                <div style="font-size:48px;margin-bottom:12px;">📭</div>
                <div style="font-size:16px;font-weight:700;color:#222;margin-bottom:6px;">لا توجد توزيعات لهذا الأسبوع</div>
                <div style="font-size:12px;">لا يمكن تسجيل سهر بدون توزيع</div>
            </div>
        @endforelse

        @if($distributions->count() > 0)
            <!-- Live mini summary -->
            <div style="background:#fff;border-radius:14px;border:1px solid #e8e8e0;padding:12px 14px;margin-top:4px;">
                <div style="font-size:11px;color:#aaa;margin-bottom:8px;">ملخص الأسبوع الحالي</div>
                <div style="display:flex;justify-content:space-around;text-align:center;">
                    <div><div style="font-size:18px;font-weight:800;color:#0a4f14;" id="livedays">{{ $summary['days_worked'] }}</div><div style="font-size:10px;color:#aaa;">أيام</div></div>
                    <div style="font-size:20px;color:#e0e0d8;">+</div>
                    <div><div style="font-size:18px;font-weight:800;color:#1D9E75;" id="livehours">{{ (int)$summary['overtime_hours'] }}</div><div style="font-size:10px;color:#aaa;">ساعة سهر</div></div>
                    <div style="font-size:20px;color:#e0e0d8;">=</div>
                    <div><div style="font-size:18px;font-weight:800;color:#c8961a;" id="liveot">{{ number_format($summary['overtime_earnings']) }}</div><div style="font-size:10px;color:#aaa;">ج أجر سهر</div></div>
                </div>
            </div>
        @endif
    </div>

    <!-- ══ TAB 1: Weekly Summary ══ -->
    <div id="tab1" class="body" style="display:none;">
        <div class="summary-card">
            <div class="sum-head">
                <div class="sum-head-title">ملخص أسبوع {{ $week_start->locale('ar')->translatedFormat('j F') }} — {{ $week_end->locale('ar')->translatedFormat('j F') }}</div>
                <div class="sum-head-sub">{{ $worker->name }} · #{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="sum-grid">
                <div class="sum-stat"><div class="sum-val sv-green">{{ $summary['days_worked'] }}</div><div class="sum-lbl">أيام عمل</div></div>
                <div class="sum-stat"><div class="sum-val sv-teal">{{ (int)$summary['overtime_hours'] }}</div><div class="sum-lbl">ساعات سهر</div></div>
                <div class="sum-stat"><div class="sum-val sv-amber" style="font-size:16px;">{{ number_format($summary['regular_earnings']) }} ج</div><div class="sum-lbl">أجر الأيام</div></div>
                <div class="sum-stat"><div class="sum-val sv-blue">{{ number_format($summary['overtime_earnings']) }} ج</div><div class="sum-lbl">أجر السهر</div></div>
            </div>

            <div class="calc-rows">
                <div class="calc-row">
                    <span class="calc-key">أيام العمل</span>
                    <span class="calc-val">{{ $summary['days_worked'] }} يوم = {{ number_format($summary['regular_earnings']) }} ج</span>
                </div>
                <div class="calc-row">
                    <span class="calc-key">ساعات السهر</span>
                    <span class="calc-val">{{ (int)$summary['overtime_hours'] }} ساعة × {{ number_format($distributions->first()?->overtime_rate ?? 0) }} ج = {{ number_format($summary['overtime_earnings']) }} ج</span>
                </div>
                <div style="height:1px;background:#f0f0e8;margin:4px 0;"></div>
                <div class="calc-row">
                    <span class="calc-key" style="font-weight:700;color:#222;">الإجمالي قبل الخصم</span>
                    <span class="calc-val" style="color:#0a4f14;">{{ number_format($summary['regular_earnings'] + $summary['overtime_earnings']) }} ج</span>
                </div>
            </div>

            @if($summary['total_deductions'] > 0)
                <div class="deduct-row"><span class="deduct-key">خصومات</span><span class="deduct-val dv-red">− {{ number_format($summary['total_deductions']) }} ج</span></div>
            @endif

            @if($summary['pending_advances'] > 0)
                <div class="deduct-row"><span class="deduct-key">سلف معلقة</span><span class="deduct-val dv-amber">− {{ number_format($summary['pending_advances']) }} ج</span></div>
            @endif

            <div class="calc-total">
                <span class="calc-total-key">صافي مستحق العامل</span>
                <span class="calc-total-val">{{ number_format($summary['grand_total']) }} ج</span>
            </div>
        </div>
    </div>

    <!-- Save bar -->
    <div class="save-bar" id="saveBar">
        <button class="cancel-btn" onclick="window.history.back()">إلغاء</button>
        <button class="save-btn" onclick="saveOT()" id="saveBtn">حفظ ساعات السهر</button>
    </div>

    <div class="toast" id="toast"></div>
</div>

<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

function switchTab(i, el) {
    ['tab0', 'tab1'].forEach((id, j) => {
        const elem = document.getElementById(id);
        if (elem) elem.style.display = j === i ? 'block' : 'none';
    });
    document.querySelectorAll('.tab').forEach((t, j) => {
        t.classList.toggle('active', j === i);
    });
    document.getElementById('saveBar').style.display = i === 0 ? 'flex' : 'none';
}

function changeOT(btn, delta) {
    const stepper = btn.parentElement;
    const valEl = stepper.querySelector('.ot-val');
    const distId = btn.getAttribute('data-dist-id');
    
    let val = parseInt(valEl.textContent) + delta;
    if (val < 0) val = 0;
    if (val > 12) val = 12;

    valEl.textContent = val;
    valEl.style.color = val > 0 ? '#0a4f14' : '#aaa';

    const row = stepper.closest('.day-row');
    let badge = row.querySelector('.ot-badge');
    const dayco = row.querySelector('.day-co');

    if (val > 0) {
        row.classList.add('has-ot');
        if (!badge) {
            const b = document.createElement('span');
            b.className = 'ot-badge';
            b.textContent = val + (val === 1 ? ' ساعة سهر' : ' ساعات سهر');
            dayco.appendChild(b);
        } else {
            badge.textContent = val + (val === 1 ? ' ساعة سهر' : ' ساعات سهر');
        }
    } else {
        row.classList.remove('has-ot');
        if (badge) badge.remove();
    }

    updateLive();
    
    // Auto-save individual changes
    saveOvertimeEntry(distId, val);
}

function updateLive() {
    const vals = document.querySelectorAll('#tab0 .ot-val');
    let total = 0;
    vals.forEach(v => total += parseInt(v.textContent) || 0);
    const otRate = {{ $distributions->first()?->overtime_rate ?? 20 }};
    document.getElementById('livehours').textContent = total;
    document.getElementById('liveot').textContent = (total * otRate).toLocaleString('ar-EG') + ' ج';
}

function saveOvertimeEntry(distributionId, hours) {
    fetch('/contractor/overtime', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify({
            distribution_id: distributionId,
            overtime_hours: hours
        })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            showToast(data.message || 'حدث خطأ', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('خطأ في الاتصال', 'error');
    });
}

function saveOT() {
    const btn = document.querySelector('.save-btn');
    btn.disabled = true;
    btn.textContent = 'جاري الحفظ...';

    // Collect all overtime entries
    const entries = [];
    document.querySelectorAll('#tab0 .day-row').forEach(row => {
        const stepper = row.querySelector('.ot-stepper');
        const val = parseInt(stepper.querySelector('.ot-val').textContent);
        const distId = stepper.querySelector('.ot-btn').getAttribute('data-dist-id');
        if (val > 0) {
            entries.push({
                distribution_id: distId,
                overtime_hours: val
            });
        }
    });

    if (entries.length === 0) {
        showToast('يجب تحديد ساعات سهر على الأقل');
        btn.disabled = false;
        btn.textContent = 'حفظ ساعات السهر';
        return;
    }

    fetch('/contractor/overtime/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify({ entries })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = 'حفظ ساعات السهر';
        
        if (data.success) {
            showToast('تم حفظ ساعات السهر بنجاح ✓', 'success');
            setTimeout(() => window.history.back(), 1500);
        } else {
            showToast(data.message || 'حدث خطأ', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        btn.disabled = false;
        btn.textContent = 'حفظ ساعات السهر';
        showToast('خطأ في الاتصال', 'error');
    });
}

function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.background = type === 'error' ? '#dc2626' : '#0a4f14';
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
@endsection
