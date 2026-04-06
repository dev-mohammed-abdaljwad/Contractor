@extends('layouts.dashboard')
@section('title', 'التوزيع اليومي')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

/* ── STEP INDICATOR ── */
.steps-bar {
  background: #fff;
  padding: 14px 20px;
  display: flex;
  align-items: center;
  gap: 0;
  border-bottom: 1px solid #f0f0f0;
  position: sticky;
  top: 0;
  z-index: 50;
}
.step {
  display: flex;
  align-items: center;
  gap: 7px;
  flex: 1;
}
.step-circle {
  width: 30px; height: 30px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700;
  flex-shrink: 0;
  transition: all 0.3s;
}
.step-done   { background: #1D9E75; color: #fff; }
.step-active { background: #185FA5; color: #fff; }
.step-idle   { background: #f0f0f0; color: #bbb; }
.step-label  { font-size: 11px; font-weight: 600; }
.step-label-done   { color: #1D9E75; }
.step-label-active { color: #185FA5; }
.step-label-idle   { color: #ccc; }
.step-connector { flex: 1; height: 2px; background: #f0f0f0; margin: 0 4px; }
.step-connector.done { background: #1D9E75; }

/* ── PAGE SECTIONS ── */
.page-section { display: none; padding-bottom: 20px; }
.page-section.active { display: block; }

/* ── TOPBAR ── */
.topbar {
  background: linear-gradient(135deg, #185FA5 0%, #1D9E75 100%);
  padding: 18px 20px 22px;
  margin: 0 0 0 0;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; }
.back-btn { color: rgba(255,255,255,0.85); font-size: 13px; cursor: pointer; user-select: none; }
.back-btn:hover { color: #fff; }
.top-title { color: #fff; font-size: 16px; font-weight: 700; }
.top-date  { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 4px; }

/* ── COMPANY CARDS ── */
.section-body { padding: 16px; }
.sec-label { font-size: 11px; font-weight: 600; color: #bbb; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 10px; }

.co-card {
  background: #fff;
  border-radius: 16px;
  border: 2px solid transparent;
  padding: 14px;
  margin-bottom: 10px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 12px;
}
.co-card:hover { border-color: #93C5FD; box-shadow: 0 2px 12px rgba(24,95,165,0.1); }
.co-card.selected { border-color: #185FA5; background: #EFF6FF; }

.co-av {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; font-weight: 700; flex-shrink: 0;
}
.av-teal   { background: #ECFDF5; color: #065F46; }
.av-blue   { background: #EFF6FF; color: #1D4ED8; }
.av-purple { background: #F5F3FF; color: #5B21B6; }
.av-coral  { background: #FFF1EE; color: #9A3412; }
.av-amber  { background: #FFFBEB; color: #92400E; }

.co-name  { font-size: 14px; font-weight: 700; color: #1a1a1a; }
.co-meta  { font-size: 12px; color: #aaa; margin-top: 2px; }
.co-wage  { font-size: 15px; font-weight: 700; color: #185FA5; margin-right: auto; flex-shrink: 0; }
.co-wage-lbl { font-size: 10px; color: #aaa; text-align: left; }

/* ── WORKER CARDS ── */
.w-card {
  background: #fff;
  border-radius: 14px;
  border: 2px solid #f0f0f0;
  padding: 12px 14px;
  margin-bottom: 8px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 11px;
}
.w-card:hover:not(.already-assigned) { border-color: #6EE7B7; }
.w-card.selected { border-color: #1D9E75; background: #ECFDF5; }
.w-card.already-assigned {
  opacity: 0.45;
  cursor: not-allowed;
  background: #fafafa;
}

.w-av {
  width: 40px; height: 40px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; flex-shrink: 0;
}

.check-circle {
  width: 22px; height: 22px; border-radius: 50%;
  border: 2px solid #d0d0c8;
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; color: transparent;
  transition: all 0.2s;
}
.w-card.selected .check-circle {
  background: #1D9E75;
  border-color: #1D9E75;
  color: #fff;
}
.w-info { flex: 1; min-width: 0; }
.w-name { font-size: 13px; font-weight: 600; color: #1a1a1a; }
.w-sub  { font-size: 11px; color: #aaa; margin-top: 2px; }
.already-tag { font-size: 10px; font-weight: 600; background: #F3F4F6; color: #9CA3AF; padding: 2px 7px; border-radius: 20px; }
.advance-tag { font-size: 10px; font-weight: 600; background: #FFFBEB; color: #92400E; padding: 2px 7px; border-radius: 20px; border: 1px dashed #FCD34D; }

/* ── STICKY SUMMARY ── */
.sticky-summary {
  position: sticky;
  bottom: 0;
  background: #fff;
  border-top: 1px solid #f0f0f0;
  padding: 12px 16px;
  z-index: 40;
}
.summary-mini {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}
.sum-stat { text-align: center; }
.sum-val  { font-size: 18px; font-weight: 700; color: #185FA5; }
.sum-lbl  { font-size: 10px; color: #aaa; margin-top: 1px; }
.sum-x    { font-size: 14px; color: #d0d0c8; font-weight: 700; }
.sum-eq   { font-size: 20px; font-weight: 800; color: #1D9E75; }
.next-btn {
  width: 100%; padding: 13px;
  background: #1D9E75; color: #fff;
  border: none; border-radius: 12px;
  font-size: 14px; font-weight: 700;
  cursor: pointer; transition: all 0.2s;
  font-family: 'Segoe UI', sans-serif;
}
.next-btn:disabled { background: #d0d0c8; cursor: not-allowed; }
.next-btn:not(:disabled):hover { background: #0F6E56; }

/* ── REVIEW PAGE ── */
.review-company-card {
  background: linear-gradient(135deg, #185FA5, #1D9E75);
  border-radius: 16px;
  padding: 16px;
  margin-bottom: 16px;
  color: #fff;
}
.rev-co-name { font-size: 17px; font-weight: 700; margin-bottom: 4px; }
.rev-co-sub  { font-size: 12px; opacity: 0.8; }

.review-stats {
  display: grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 10px;
  margin: 16px 0;
}
.rev-stat {
  background: #fff;
  border-radius: 12px;
  padding: 12px 10px;
  text-align: center;
  border: 1px solid #f0f0f0;
}
.rev-val { font-size: 20px; font-weight: 700; }
.rev-lbl { font-size: 10px; color: #aaa; margin-top: 2px; }
.rv-blue  { color: #185FA5; }
.rv-green { color: #059669; }
.rv-amber { color: #D97706; }

.rev-worker-row {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 0;
  border-bottom: 1px solid #f5f5f5;
}
.rev-worker-row:last-child { border-bottom: none; }
.rev-w-av {
  width: 34px; height: 34px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.rev-w-name { font-size: 13px; font-weight: 500; color: #222; flex: 1; }
.rev-w-amt  { font-size: 13px; font-weight: 700; color: #059669; }

.confirm-btn {
  width: 100%; padding: 14px;
  background: #1D9E75; color: #fff;
  border: none; border-radius: 14px;
  font-size: 15px; font-weight: 700;
  cursor: pointer; margin-top: 4px;
  font-family: 'Segoe UI', sans-serif;
}
.confirm-btn:hover { background: #0F6E56; }
.edit-btn {
  width: 100%; padding: 11px;
  background: transparent; color: #185FA5;
  border: 1.5px solid #185FA5; border-radius: 14px;
  font-size: 14px; font-weight: 600;
  cursor: pointer; margin-top: 8px;
  font-family: 'Segoe UI', sans-serif;
}

/* ── SUCCESS ── */
.success-screen { text-align: center; padding: 40px 20px; }
.success-icon {
  width: 80px; height: 80px; border-radius: 50%;
  background: #ECFDF5;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
  font-size: 36px; color: #1D9E75;
}
.success-title { font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 6px; }
.success-sub   { font-size: 13px; color: #aaa; margin-bottom: 28px; line-height: 1.6; }
.success-cards {
  display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 24px;
}
.sc { background: #fff; border-radius: 12px; padding: 12px 8px; text-align: center; border: 1px solid #f0f0f0; }
.sc-val { font-size: 18px; font-weight: 700; color: #1D9E75; }
.sc-lbl { font-size: 10px; color: #aaa; margin-top: 2px; }
.done-btn {
  width: 100%; padding: 13px; background: #185FA5; color: #fff;
  border: none; border-radius: 14px; font-size: 14px; font-weight: 700; cursor: pointer;
}
.new-btn {
  width: 100%; padding: 11px; background: transparent; color: #1D9E75;
  border: 1.5px solid #1D9E75; border-radius: 14px; font-size: 14px; font-weight: 600;
  cursor: pointer; margin-top: 8px;
}

/* ── SEARCH ── */
.w-search {
  background: #f8f9fa;
  border: 1px solid #f0f0f0;
  border-radius: 12px;
  padding: 9px 14px;
  display: flex; align-items: center; gap: 8px;
  margin-bottom: 12px;
}
.w-search-fake { font-size: 12px; color: #bbb; }

.select-all-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 8px 4px; margin-bottom: 6px;
}
.sel-all-btn {
  font-size: 12px; font-weight: 600; color: #185FA5; cursor: pointer;
}
.sel-count { font-size: 12px; color: #aaa; }

/* ── MOBILE RESPONSIVE ── */
@media (max-width: 640px) {
  .steps-bar {
    padding: 10px 12px;
  }
  .step-label { display: none; }
  .step {
    flex: 0 0 auto;
  }
  .section-body { padding: 12px; }
  .co-card { padding: 10px; margin-bottom: 8px; }
  .co-av { width: 40px; height: 40px; font-size: 16px; }
  .co-name { font-size: 13px; }
  .co-meta { font-size: 11px; }
  .co-wage { font-size: 13px; }
  .co-wage-lbl { font-size: 9px; }
  
  .w-card { padding: 10px 12px; margin-bottom: 6px; gap: 9px; }
  .w-av { width: 36px; height: 36px; font-size: 12px; }
  .w-name { font-size: 12px; }
  .w-sub { font-size: 10px; }
  .check-circle { width: 20px; height: 20px; }
  
  .topbar { padding: 14px 12px 16px; }
  .topbar-row { margin-bottom: 8px; }
  .top-title { font-size: 14px; }
  .back-btn { font-size: 12px; }
  
  .sticky-summary { padding: 10px 12px; }
  .sum-val { font-size: 16px; }
  .sum-lbl { font-size: 9px; }
  .sum-x { font-size: 12px; }
  .sum-eq { font-size: 18px; }
  .next-btn { padding: 11px; font-size: 13px; }
  
  .review-stats {
    grid-template-columns: 1fr;
    gap: 8px;
    margin: 12px 0;
  }
  .rev-stat { padding: 10px 8px; }
  .rev-val { font-size: 18px; }
  
  .success-screen { padding: 30px 12px; }
  .success-icon { width: 70px; height: 70px; font-size: 32px; margin-bottom: 12px; }
  .success-title { font-size: 18px; margin-bottom: 4px; }
  .success-sub { font-size: 12px; margin-bottom: 20px; }
  .success-cards {
    grid-template-columns: repeat(2,1fr);
    gap: 8px;
    margin-bottom: 16px;
  }
  .sc { padding: 10px 6px; }
  .sc-val { font-size: 16px; }
  .sc-lbl { font-size: 9px; }
}
</style>

<!-- ══════════════ TOPBAR ══════════════ -->
<div class="topbar">
  <div class="topbar-row">
    <div class="back-btn" onclick="goBack()">← رجوع</div>
    <div class="top-title" id="topTitle">التوزيع اليومي</div>
    <div style="width:40px;"></div>
  </div>
  <div class="top-date" id="topDate"></div>
</div>

<!-- ══════════════ STEPS ══════════════ -->
<div class="steps-bar" id="stepsBar">
  <div class="step">
    <div class="step-circle step-active" id="s1">1</div>
    <div class="step-label step-label-active" id="sl1">اختر شركة</div>
  </div>
  <div class="step-connector" id="sc1"></div>
  <div class="step">
    <div class="step-circle step-idle" id="s2">2</div>
    <div class="step-label step-label-idle" id="sl2">اختر عمال</div>
  </div>
  <div class="step-connector" id="sc2"></div>
  <div class="step">
    <div class="step-circle step-idle" id="s3">3</div>
    <div class="step-label step-label-idle" id="sl3">مراجعة</div>
  </div>
</div>

<!-- ══════════════ STEP 1: SELECT COMPANY ══════════════ -->
<div class="page-section active" id="step1">
  <div class="section-body">
    <p class="sec-label">اختار الشركة اللي هتبعت إليها عمال النهارده</p>

    @foreach($companies as $company)
    <div class="co-card" data-company-id="{{ $company->id }}" onclick="selectCompany(this, '{{ $company->name }}', {{ $company->daily_wage }}, '{{ substr($company->name, 0, 1) }}', 'av-teal')">
      <div class="co-av av-teal">{{ substr($company->name, 0, 1) }}</div>
      <div style="flex:1;">
        <div class="co-name">{{ $company->name }}</div>
        <div class="co-meta">{{ $company->payment_type ?? 'يومي' }} · {{ $company->manager_name ?? 'مدير الشركة' }}</div>
      </div>
      <div style="text-align:left;">
        <div class="co-wage">{{ $company->daily_wage }} ج</div>
        <div class="co-wage-lbl">لكل عامل</div>
      </div>
    </div>
    @endforeach
  </div>

  <div style="padding:0 16px 16px;">
    <button class="next-btn" id="nextStep1" disabled onclick="goToStep2()">
      التالي — اختار العمال ←
    </button>
  </div>
</div>

<!-- ══════════════ STEP 2: SELECT WORKERS ══════════════ -->
<div class="page-section" id="step2">
  <div class="section-body" style="padding-bottom:0;">

    <!-- Selected company recap -->
    <div style="background:#EFF6FF;border-radius:12px;padding:12px 14px;margin-bottom:14px;display:flex;align-items:center;gap:10px;">
      <div class="co-av" id="recapAv" style="width:38px;height:38px;border-radius:10px;font-size:15px;"></div>
      <div style="flex:1;">
        <div style="font-size:13px;font-weight:700;color:#1D4ED8;" id="recapName"></div>
        <div style="font-size:11px;color:#93C5FD;">الأجر: <span id="recapWage" style="font-weight:700;"></span> ج لكل عامل</div>
      </div>
      <div style="font-size:11px;color:#185FA5;cursor:pointer;" onclick="goToStep1()">تغيير</div>
    </div>

    <!-- Search -->
    <div class="w-search">
      <span style="color:#bbb;font-size:14px;">🔍</span>
      <div class="w-search-fake">ابحث باسم العامل...</div>
    </div>

    <!-- Select all -->
    <div class="select-all-row">
      <div class="sel-all-btn" onclick="selectAll()">تحديد الكل</div>
      <div class="sel-count" id="selCount">0 محدد</div>
    </div>

  </div>

  <!-- Workers list -->
  <div style="padding:0 16px;">
    <p style="font-size:11px;color:#bbb;margin-bottom:6px;">متاحون للتوزيع</p>

    @foreach($workers as $worker)
    <div class="w-card" data-worker-id="{{ $worker->id }}" data-worker-name="{{ $worker->name }}" onclick="toggleWorker(this, '{{ $worker->name }}', '{{ substr($worker->name, 0, 1) }}', 'av-teal', {{ $worker->id }})">
      <div class="check-circle">✓</div>
      <div class="w-av av-teal">{{ substr($worker->name, 0, 1) }}</div>
      <div class="w-info">
        <div class="w-name">{{ $worker->name }}</div>
        <div class="w-sub">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }} · حضر أمس</div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Sticky summary -->
  <div class="sticky-summary">
    <div class="summary-mini">
      <div class="sum-stat">
        <div class="sum-val" id="sumCount">0</div>
        <div class="sum-lbl">عمال</div>
      </div>
      <div class="sum-x">×</div>
      <div class="sum-stat">
        <div class="sum-val" id="sumWage" style="color:#185FA5;">—</div>
        <div class="sum-lbl">أجر/عامل</div>
      </div>
      <div class="sum-x">=</div>
      <div class="sum-stat">
        <div class="sum-eq" id="sumTotal">0 ج</div>
        <div class="sum-lbl">إجمالي</div>
      </div>
    </div>
    <button class="next-btn" id="nextStep2" disabled onclick="goToStep3()">
      مراجعة وتأكيد ←
    </button>
  </div>
</div>

<!-- ══════════════ STEP 3: REVIEW ══════════════ -->
<div class="page-section" id="step3">
  <div class="section-body">

    <div class="review-company-card">
      <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;" id="revCoAv"></div>
        <div>
          <div class="rev-co-name" id="revCoName"></div>
          <div class="rev-co-sub">الأجر اليومي: <span id="revCoWage"></span> ج لكل عامل</div>
        </div>
      </div>
    </div>

    <div class="review-stats">
      <div class="rev-stat">
        <div class="rev-val rv-blue" id="revCount">0</div>
        <div class="rev-lbl">عدد العمال</div>
      </div>
      <div class="rev-stat">
        <div class="rev-val rv-blue" id="revWage2">0 ج</div>
        <div class="rev-lbl">أجر العامل</div>
      </div>
      <div class="rev-stat">
        <div class="rev-val rv-green" id="revTotal">0 ج</div>
        <div class="rev-lbl">الإجمالي</div>
      </div>
    </div>

    <p class="sec-label">العمال المحددون</p>
    <div id="revWorkersList" style="background:#fff;border-radius:14px;border:1px solid #f0f0f0;overflow:hidden;padding:0 14px;margin-bottom:16px;"></div>

    <div style="background:#ECFDF5;border-radius:12px;padding:12px 14px;margin-bottom:16px;display:flex;gap:8px;align-items:flex-start;">
      <span style="color:#1D9E75;font-size:16px;flex-shrink:0;">✓</span>
      <div style="font-size:12px;color:#065F46;line-height:1.6;">
        بعد التأكيد هيتسجل الحضور تلقائياً وهيتحسب الأجر لكل عامل من الأرقام دي.
      </div>
    </div>

    <button class="confirm-btn" onclick="submitDistribution()">تأكيد التوزيع</button>
    <button class="edit-btn" onclick="goToStep2()">← تعديل العمال</button>
  </div>
</div>

<!-- ══════════════ SUCCESS ══════════════ -->
<div class="page-section" id="stepSuccess">
  <div class="success-screen">
    <div class="success-icon">✓</div>
    <div class="success-title">تم التوزيع بنجاح!</div>
    <div class="success-sub" id="successMsg"></div>
    <div class="success-cards" id="successCards"></div>
    <a href="{{ route('contractor.distributions.index') }}" class="done-btn" style="display:block;text-decoration:none;text-align:center;">العودة للقائمة</a>
    <button class="new-btn" onclick="resetAll()">+ توزيع شركة أخرى</button>
  </div>
</div>

</div>

<script>
let selectedCompany = null;
let selectedWorkers = [];
let currentStep = 1;

const avatarColors = { 'av-teal':'#ECFDF5','av-blue':'#EFF6FF','av-amber':'#FFFBEB','av-purple':'#F5F3FF','av-coral':'#FFF1EE' };
const textColors   = { 'av-teal':'#065F46','av-blue':'#1D4ED8','av-amber':'#92400E','av-purple':'#5B21B6','av-coral':'#9A3412' };

function selectCompany(el, name, wage, initial, avClass) {
  document.querySelectorAll('.co-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  selectedCompany = { name, wage, initial, avClass };
  document.getElementById('nextStep1').disabled = false;
}

function goToStep1() {
  showSection('step1');
  setStep(1);
}

function goToStep2() {
  if (!selectedCompany) return;
  document.getElementById('recapName').textContent = selectedCompany.name;
  document.getElementById('recapWage').textContent = selectedCompany.wage;
  const av = document.getElementById('recapAv');
  av.textContent = selectedCompany.initial;
  av.style.background = avatarColors[selectedCompany.avClass] || '#EFF6FF';
  av.style.color = textColors[selectedCompany.avClass] || '#1D4ED8';
  document.getElementById('sumWage').textContent = selectedCompany.wage;
  showSection('step2');
  setStep(2);
  updateSummary();
  document.getElementById('stepsBar').style.display = 'flex';
}

function goToStep3() {
  if (selectedWorkers.length === 0) return;
  document.getElementById('revCoAv').textContent = selectedCompany.initial;
  document.getElementById('revCoName').textContent = selectedCompany.name;
  document.getElementById('revCoWage').textContent = selectedCompany.wage;
  document.getElementById('revCount').textContent = selectedWorkers.length;
  document.getElementById('revWage2').textContent = selectedCompany.wage + ' ج';
  document.getElementById('revTotal').textContent = (selectedWorkers.length * selectedCompany.wage).toLocaleString('ar-EG') + ' ج';

  const list = document.getElementById('revWorkersList');
  list.innerHTML = selectedWorkers.map(w => `
    <div class="rev-worker-row">
      <div class="rev-w-av" style="background:${avatarColors[w.av]||'#ECFDF5'};color:${textColors[w.av]||'#065F46'};">${w.initial}</div>
      <div class="rev-w-name">${w.name}</div>
      <div class="rev-w-amt">${selectedCompany.wage} ج</div>
    </div>
  `).join('');

  showSection('step3');
  setStep(3);
}

function submitDistribution() {
  // Find company ID from selected company
  const selectedCard = document.querySelector('.co-card.selected');
  if (!selectedCard) {
    alert('الرجاء اختيار شركة');
    return;
  }
  
  const companyId = selectedCard.dataset.companyId;

  if (!companyId || selectedWorkers.length === 0) {
    alert('الرجاء اختيار شركة وعمال');
    return;
  }

  // Create form and submit
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ route("contractor.distributions.store") }}';
  form.innerHTML = `
    @csrf
    <input type="hidden" name="company_id" value="${companyId}">
    <input type="hidden" name="worker_ids" value="${selectedWorkers.map(w => w.id).join(',')}">
  `;
  
  // Show success screen while submitting
  const total = selectedWorkers.length * selectedCompany.wage;
  document.getElementById('successMsg').textContent =
    `تم إرسال ${selectedWorkers.length} عمال إلى ${selectedCompany.name} · الأجر الكلي ${total.toLocaleString('ar-EG')} جنيه`;
  document.getElementById('successCards').innerHTML = `
    <div class="sc"><div class="sc-val">${selectedWorkers.length}</div><div class="sc-lbl">عامل</div></div>
    <div class="sc"><div class="sc-val">${selectedCompany.wage}</div><div class="sc-lbl">أجر/عامل</div></div>
    <div class="sc"><div class="sc-val" style="font-size:14px;">${total.toLocaleString()}</div><div class="sc-lbl">إجمالي (ج)</div></div>
  `;
  showSection('stepSuccess');
  document.getElementById('stepsBar').style.display = 'none';
  
  // Submit form
  document.body.appendChild(form);
  form.submit();
}

function resetAll() {
  selectedCompany = null;
  selectedWorkers = [];
  document.querySelectorAll('.co-card').forEach(c => c.classList.remove('selected'));
  document.querySelectorAll('.w-card:not(.already-assigned)').forEach(c => c.classList.remove('selected'));
  document.getElementById('nextStep1').disabled = true;
  document.getElementById('nextStep2').disabled = true;
  showSection('step1');
  setStep(1);
  document.getElementById('stepsBar').style.display = 'flex';
  updateSummary();
}

function toggleWorker(el, name, initial, av, id) {
  if (el.classList.contains('already-assigned')) return;
  const idx = selectedWorkers.findIndex(w => w.id === id);
  if (idx > -1) {
    selectedWorkers.splice(idx, 1);
    el.classList.remove('selected');
  } else {
    selectedWorkers.push({ name, initial, av, id });
    el.classList.add('selected');
  }
  updateSummary();
}

function selectAll() {
  const cards = document.querySelectorAll('.w-card:not(.already-assigned)');
  const allSelected = selectedWorkers.length === cards.length;
  selectedWorkers = [];
  cards.forEach(card => {
    card.classList.remove('selected');
    if (!allSelected) {
      card.classList.add('selected');
      const name = card.dataset.workerName;
      const initial = card.dataset.workerName.charAt(0);
      const id = parseInt(card.dataset.workerId);
      selectedWorkers.push({ name, initial, av: 'av-teal', id });
    }
  });
  updateSummary();
}

function updateSummary() {
  const count = selectedWorkers.length;
  const wage  = selectedCompany?.wage || 0;
  const total = count * wage;
  document.getElementById('sumCount').textContent = count;
  document.getElementById('sumTotal').textContent = total.toLocaleString('ar-EG') + ' ج';
  document.getElementById('selCount').textContent = count + ' محدد';
  document.getElementById('nextStep2').disabled = count === 0;
}

function setStep(n) {
  currentStep = n;
  [1,2,3].forEach(i => {
    const circle = document.getElementById('s'+i);
    const label  = document.getElementById('sl'+i);
    if (i < n) {
      circle.className = 'step-circle step-done';
      label.className  = 'step-label step-label-done';
      circle.textContent = '✓';
    } else if (i === n) {
      circle.className = 'step-circle step-active';
      label.className  = 'step-label step-label-active';
      circle.textContent = i;
    } else {
      circle.className = 'step-circle step-idle';
      label.className  = 'step-label step-label-idle';
      circle.textContent = i;
    }
    if (i < 3) {
      document.getElementById('sc'+i).className = 'step-connector' + (i < n ? ' done' : '');
    }
  });
}

function showSection(id) {
  document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}

function goBack() {
  if (currentStep === 2) goToStep1();
  else if (currentStep === 3) goToStep2();
  else window.history.back();
}

// Set current date
document.getElementById('topDate').textContent = new Date().toLocaleDateString('ar-EG', {
  weekday: 'long',
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});
</script>
@endsection
