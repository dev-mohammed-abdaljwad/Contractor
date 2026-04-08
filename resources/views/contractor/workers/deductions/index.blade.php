@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.topbar {
  background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 100%);
  padding: 20px 24px;
  margin: 0;
  box-shadow: 0 2px 8px rgba(15, 110, 86, 0.15);
}

.topbar-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-title {
  color: #fff;
  font-size: 24px;
  font-weight: 700;
  margin: 0;
  letter-spacing: -0.5px;
}

.breadcrumb {
  color: rgba(255,255,255,0.8);
  font-size: 12px;
  margin-top: 6px;
}

.breadcrumb a {
  color: rgba(255,255,255,0.9);
  text-decoration: none;
}

.topbar-right {
  display: flex;
  gap: 12px;
  align-items: center;
}

.action-btn {
  background: linear-gradient(135deg, #fff 0%, #f8f9f8 100%);
  border: none;
  color: #0d631b;
  font-size: 14px;
  font-weight: 700;
  padding: 10px 20px;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  white-space: nowrap;
}

.action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
  padding: 16px 24px;
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}

.stat-card {
  padding: 16px;
  border-radius: 10px;
  background: #f8f9fa;
  text-align: center;
  border: 1px solid #e8e8e3;
}

.stat-number {
  font-size: 28px;
  font-weight: 700;
  color: #1D9E75;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 12px;
  color: #888;
  font-weight: 500;
}

.filters {
  display: flex;
  gap: 8px;
  padding: 12px 24px;
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
  overflow-x: auto;
}

.filter-chip {
  padding: 8px 16px;
  border-radius: 20px;
  border: 1.5px solid #ddd;
  background: #fff;
  color: #666;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
  font-weight: 500;
}

.filter-chip.active {
  background: #ECFDF5;
  color: #065F46;
  border-color: #6EE7B7;
}

.filter-chip:hover {
  border-color: #bbb;
}

.content {
  padding: 24px;
  background: #f8f9fa;
  min-height: 100vh;
}

.deduction-card {
  background: #fff;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 12px;
  box-shadow: 0 1px 6px rgba(0,0,0,0.06);
  border-left: 4px solid #1D9E75;
  transition: box-shadow 0.2s;
}

.deduction-card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.deduction-card.reversed {
  opacity: 0.7;
  border-left-color: #D1D5DB;
  background: #F9FAFB;
}

.deduction-card.reversed .status-badge {
  background: #F3F4F6;
  color: #9CA3AF;
}

.deduction-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 12px;
}

.deduction-date {
  font-size: 12px;
  color: #888;
  font-weight: 600;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  background: #ECFDF5;
  color: #065F46;
}

.status-badge.reversed {
  background: #F3F4F6;
  color: #9CA3AF;
}

.deduction-amount {
  font-size: 20px;
  font-weight: 700;
  color: #ba1a1a;
}

.deduction-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 12px;
  margin: 12px 0;
  padding: 12px 0;
  border-top: 1px solid #f0f0f0;
  border-bottom: 1px solid #f0f0f0;
}

.detail-item {
  font-size: 11px;
}

.detail-label {
  color: #888;
  margin-bottom: 2px;
  font-weight: 500;
}

.detail-value {
  color: #1a1a1a;
  font-weight: 600;
  font-size: 13px;
}

.deduction-reason {
  font-size: 12px;
  color: #666;
  margin-top: 12px;
  padding: 8px;
  background: #f8f9fa;
  border-radius: 6px;
  border-right: 2px solid #D97706;
}

.deduction-reason-label {
  font-size: 10px;
  color: #D97706;
  font-weight: 600;
  margin-bottom: 4px;
}

.deduction-actions {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid #f0f0f0;
}

.action-icon-btn {
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid #ddd;
  background: #fff;
  color: #666;
  font-size: 11px;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.action-icon-btn:hover {
  background: #f0f0f0;
  border-color: #bbb;
}

.action-icon-btn.danger {
  color: #ba1a1a;
  border-color: #f0d0d0;
}

.action-icon-btn.danger:hover {
  background: #fff5f5;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #aaa;
}

.empty-icon {
  font-size: 48px;
  margin-bottom: 12px;
  opacity: 0.5;
}

.reversal-banner {
  background: #F3F4F6;
  border-radius: 8px;
  padding: 12px;
  margin: 12px 0;
  border-right: 3px solid #9CA3AF;
  font-size: 12px;
}

.reversal-badge {
  background: #9CA3AF;
  color: #fff;
  padding: 2px 8px;
  border-radius: 12px;
  font-weight: 600;
  margin-right: 8px;
  display: inline-block;
  font-size: 10px;
}

.timeline-section {
  margin-top: 24px;
}

.timeline-title {
  font-size: 14px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 16px;
}

.month-divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 24px 0 16px;
  padding-top: 12px;
}

.month-divider-line {
  flex: 1;
  height: 1px;
  background: #e8e8e3;
}

.month-label {
  font-size: 12px;
  font-weight: 700;
  color: #888;
  background: #f8f9fa;
  padding: 0 8px;
}

@media(max-width: 768px) {
  .topbar { padding: 16px; }
  .topbar-content { flex-direction: column; gap: 12px; }
  .topbar-left { width: 100%; }
  .topbar-right { width: 100%; gap: 8px; }
  .page-title { font-size: 20px; }
  .action-btn { flex: 1; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; padding: 12px 16px; }
  .filters { padding: 10px 16px; gap: 6px; }
  .filter-chip { padding: 6px 12px; font-size: 11px; }
  .content { padding: 16px; }
  .deduction-card { padding: 12px; margin-bottom: 8px; }
  .deduction-details { grid-template-columns: repeat(2, 1fr); gap: 8px; }
}

@media(max-width: 480px) {
  .topbar { padding: 12px 16px; }
  .page-title { font-size: 18px; }
  .action-btn { padding: 8px 16px; font-size: 12px; }
  .stats-grid { grid-template-columns: 1fr; }
  .deduction-header { flex-direction: column; }
  .deduction-actions { flex-direction: column-reverse; }
  .action-icon-btn { flex: 1; }
}
</style>

<div class="topbar">
  <div class="topbar-content">
    <div class="topbar-left">
      <div>
        <div class="page-title">سجل الخصومات - {{ $worker->name }}</div>
        <div class="breadcrumb">
          <a href="{{ route('contractor.workers.index') }}">العمال</a> / 
          <a href="{{ route('contractor.workers.show', $worker) }}">{{ $worker->name }}</a> / 
          <span>الخصومات</span>
        </div>
      </div>
    </div>
    <div class="topbar-right">
      <button class="action-btn" onclick="openRecordModal()">+ تسجيل خصم جديد</button>
    </div>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-number">{{ $deductions->count() }}</div>
    <div class="stat-label">إجمالي الخصومات</div>
  </div>
  <div class="stat-card">
    <div class="stat-number">{{ number_format($monthly_total, 0) }} ج</div>
    <div class="stat-label">خصومات هذا الشهر</div>
  </div>
  <div class="stat-card">
    <div class="stat-number">{{ $reversal_count }}</div>
    <div class="stat-label">عدد الإلغاءات</div>
  </div>
  <div class="stat-card">
    <div class="stat-number">{{ number_format($deductions->where('is_reversed', false)->sum('amount'), 0) }} ج</div>
    <div class="stat-label">إجمالي الخصومات النشطة</div>
  </div>
</div>

<!-- Filters -->
<div class="filters">
  <a href="?filter=all" class="filter-chip {{ request('filter', 'month') === 'all' ? 'active' : '' }}">الكل</a>
  <a href="?filter=month" class="filter-chip {{ request('filter', 'month') === 'month' ? 'active' : '' }}">هذا الشهر</a>
  <a href="?filter=week" class="filter-chip {{ request('filter') === 'week' ? 'active' : '' }}">هذا الأسبوع</a>
  <a href="#" class="filter-chip" onclick="openDateRangeModal()">نطاق مخصص</a>
</div>

<!-- Content -->
<div class="content">
  @if($deductions->isEmpty())
    <div class="empty-state">
      <div class="empty-icon">📋</div>
      <p>لا توجد خصومات مسجلة للعامل حالياً</p>
      <p style="font-size: 12px; margin-top: 8px; color: #bbb;">ابدأ بتسجيل أول خصم عند الحاجة</p>
    </div>
  @else
    <div class="timeline-section">
      @php
        $currentMonth = null;
        $deductionsByMonth = $deductions->groupBy(function($d) {
            return $d->created_at->format('Y-m');
        })->sortByDesc(function($items, $month) {
            return $month;
        });
      @endphp

      @foreach($deductionsByMonth as $month => $monthDeductions)
        @php
          $monthName = \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('ar')->monthName;
          $year = \Carbon\Carbon::createFromFormat('Y-m', $month)->year;
        @endphp

        <div class="month-divider">
          <div class="month-divider-line"></div>
          <div class="month-label">{{ $monthName }} {{ $year }}</div>
          <div class="month-divider-line"></div>
        </div>

        @foreach($monthDeductions as $deduction)
          <div class="deduction-card {{ $deduction->is_reversed ? 'reversed' : '' }}">
            <!-- Header with date and amount -->
            <div class="deduction-header">
              <div>
                <div class="deduction-date">
                  {{ $deduction->created_at->locale('ar')->format('l، j F Y') }}
                  <span style="color: #ccc;">•</span>
                  <span style="color: #888;">{{ $deduction->created_at->format('H:i') }}</span>
                </div>
              </div>
              <div style="text-align: left;">
                @if($deduction->is_reversed)
                  <span class="status-badge reversed">ملغى</span>
                @else
                  <span class="status-badge">نشط</span>
                @endif
              </div>
            </div>

            <!-- Amount Display -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
              <div>
                <div style="font-size: 12px; color: #888; margin-bottom: 4px;">مبلغ الخصم</div>
                <div class="deduction-amount">- {{ number_format($deduction->amount, 2) }} ج</div>
              </div>
              <div style="text-align: left;">
                <div style="font-size: 12px; color: #888; margin-bottom: 4px;">نوع الخصم</div>
                <div style="font-size: 14px; font-weight: 700; color: #0d631b;">
                  @switch($deduction->type)
                    @case('quarter')
                      ¼ يوم (25%)
                      @break
                    @case('half')
                      ½ يوم (50%)
                      @break
                    @case('full')
                      يوم كامل (100%)
                      @break
                  @endswitch
                </div>
              </div>
            </div>

            <!-- Details Grid -->
            <div class="deduction-details">
              <div class="detail-item">
                <div class="detail-label">الشركة</div>
                <div class="detail-value">{{ $deduction->distribution->company->name ?? 'N/A' }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">الراتب اليومي</div>
                <div class="detail-value">{{ number_format($deduction->distribution->company->daily_wage ?? 0, 0) }} ج</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">سجل بواسطة</div>
                <div class="detail-value">{{ $deduction->contractor->name ?? 'N/A' }}</div>
              </div>
              @if($deduction->is_reversed)
                <div class="detail-item">
                  <div class="detail-label">تاريخ الإلغاء</div>
                  <div class="detail-value">{{ $deduction->reversed_at->locale('ar')->format('d/m/Y') }}</div>
                </div>
              @endif
            </div>

            <!-- Reversal Info -->
            @if($deduction->is_reversed)
              <div class="reversal-banner">
                <span class="reversal-badge">ملغى</span>
                @if($deduction->reversedBy)
                  تم الإلغاء بواسطة <strong>{{ $deduction->reversedBy->name }}</strong>
                @endif
                @if($deduction->reversal_reason)
                  <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(0,0,0,0.1);">
                    <strong style="font-size: 10px; color: #9CA3AF;">السبب:</strong><br>
                    {{ $deduction->reversal_reason }}
                  </div>
                @endif
              </div>
            @endif

            <!-- Reason if available -->
            @if($deduction->reason && !$deduction->is_reversed)
              <div class="deduction-reason">
                <div class="deduction-reason-label">📝 السبب</div>
                {{ $deduction->reason }}
              </div>
            @endif

            <!-- Actions -->
            @if(!$deduction->is_reversed)
              <div class="deduction-actions">
                <button class="action-icon-btn danger" onclick="openReverseModal({{ $deduction->id }})">
                  ✕ إلغاء الخصم
                </button>
              </div>
            @endif
          </div>
        @endforeach
      @endforeach
    </div>
  @endif
</div>

<!-- Record Deduction Modal -->
<div id="recordModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:600px;max-height:90vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.2)">
        <!-- Modal Header -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:0.5px solid #d0d0c8;position:sticky;top:0;background:#fff;z-index:10">
            <h2 style="font-size:18px;font-weight:700;color:#1a1c19;margin:0">تسجيل خصم جديد</h2>
            <button onclick="closeRecordModal()" style="background:none;border:none;font-size:24px;cursor:pointer;color:#707a6c;padding:0">✕</button>
        </div>

        <!-- Modal Content -->
        <div style="padding:24px">
            <form id="recordDeductionForm" method="POST" action="{{ route('contractor.deductions.store') }}">
                @csrf
                <input type="hidden" name="worker_id" value="{{ $worker->id }}">

                <!-- Date Selection -->
                <div style="margin-bottom:20px">
                    <label style="font-size:12px;font-weight:700;color:#1a1c19;display:block;margin-bottom:8px">اختر التاريخ *</label>
                    <input type="date" name="date" id="deductionDate" required style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;">
                    <div id="dateError" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></div>
                </div>

                <!-- Type Selection -->
                <div style="margin-bottom:20px">
                    <label style="font-size:12px;font-weight:700;color:#1a1c19;display:block;margin-bottom:12px">نوع الخصم *</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                        <label style="cursor:pointer">
                            <input type="radio" name="type" value="quarter" required style="margin-right:6px">
                            <span style="font-size:12px">¼ يوم<br><small style="color:#888">(25%)</small></span>
                        </label>
                        <label style="cursor:pointer">
                            <input type="radio" name="type" value="half" style="margin-right:6px">
                            <span style="font-size:12px">½ يوم<br><small style="color:#888">(50%)</small></span>
                        </label>
                        <label style="cursor:pointer">
                            <input type="radio" name="type" value="full" style="margin-right:6px">
                            <span style="font-size:12px">يوم كامل<br><small style="color:#888">(100%)</small></span>
                        </label>
                    </div>
                </div>

                <!-- Preview -->
                <div id="previewCard" style="background:#f8f9fa;border:1px solid #e8e8e3;border-radius:8px;padding:16px;margin-bottom:20px;display:none">
                    <div style="font-size:12px;color:#888;margin-bottom:12px;font-weight:600">معاينة الخصم</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <div style="font-size:10px;color:#888">الراتب اليومي</div>
                            <div style="font-size:16px;font-weight:700;margin-top:4px" id="previewWage">—</div>
                        </div>
                        <div>
                            <div style="font-size:10px;color:#888">مبلغ الخصم</div>
                            <div style="font-size:16px;font-weight:700;color:#ba1a1a;margin-top:4px" id="previewAmount">—</div>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div style="margin-bottom:20px">
                    <label style="font-size:12px;font-weight:700;color:#1a1c19;display:block;margin-bottom:8px">سبب الخصم <span style="color:#888">(اختياري)</span></label>
                    <textarea name="reason" placeholder="مثال: تأخر عن العمل، عدم الالتزام بالزي..." style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;min-height:80px;font-family:inherit"></textarea>
                </div>

                <!-- Modal Footer -->
                <div style="display:flex;gap:12px;justify-content:flex-end;border-top:0.5px solid #d0d0c8;padding-top:20px">
                    <button type="button" onclick="closeRecordModal()" style="height:40px;padding:0 20px;font-size:13px;font-weight:600;border:0.5px solid #d0d0c8;background:#fff;border-radius:8px;cursor:pointer;color:#707a6c">
                        إلغاء
                    </button>
                    <button type="submit" style="height:40px;padding:0 20px;white-space:nowrap;font-size:13px;font-weight:600;background:#0d631b;color:#fff;border:none;border-radius:8px;cursor:pointer">
                        تسجيل الخصم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reverse Deduction Modal -->
<div id="reverseModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:500px;box-shadow:0 10px 40px rgba(0,0,0,0.2)">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:0.5px solid #d0d0c8">
            <h2 style="font-size:18px;font-weight:700;color:#1a1c19;margin:0">إلغاء الخصم</h2>
            <button onclick="closeReverseModal()" style="background:none;border:none;font-size:24px;cursor:pointer;color:#707a6c;padding:0">✕</button>
        </div>

        <div style="padding:24px">
            <div style="background:#fff5f5;border:1px solid #f0d0d0;border-radius:8px;padding:12px;margin-bottom:20px">
                <p style="font-size:12px;color:#ba1a1a;margin:0">⚠️ هل أنت متأكد من رغبتك في إلغاء هذا الخصم؟</p>
            </div>

            <form id="reverseDeductionForm" method="POST">
                @csrf
                @method('PATCH')

                <label style="font-size:12px;font-weight:700;color:#1a1c19;display:block;margin-bottom:8px">سبب الإلغاء <span style="color:#888">(اختياري)</span></label>
                <textarea name="reversal_reason" placeholder="مثال: تصحيح خطأ، توافق مع العامل..." style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;min-height:80px;font-family:inherit;margin-bottom:20px"></textarea>

                <div style="display:flex;gap:12px;justify-content:flex-end;border-top:0.5px solid #d0d0c8;padding-top:20px">
                    <button type="button" onclick="closeReverseModal()" style="height:40px;padding:0 20px;font-size:13px;font-weight:600;border:0.5px solid #d0d0c8;background:#fff;border-radius:8px;cursor:pointer;color:#707a6c">
                        لا، احتفظ به
                    </button>
                    <button type="submit" style="height:40px;padding:0 20px;white-space:nowrap;font-size:13px;font-weight:600;background:#ba1a1a;color:#fff;border:none;border-radius:8px;cursor:pointer">
                        إلغاء الخصم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRecordModal() {
    document.getElementById('recordModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeRecordModal() {
    document.getElementById('recordModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('recordDeductionForm').reset();
}

function openReverseModal(deductionId) {
    document.getElementById('reverseModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.getElementById('reverseDeductionForm').action = `/contractor/deductions/${deductionId}/reverse`;
}

function closeReverseModal() {
    document.getElementById('reverseModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
document.getElementById('recordModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRecordModal();
});

document.getElementById('reverseModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeReverseModal();
});

// Form submission
document.getElementById('recordDeductionForm').addEventListener('submit', function(e) {
    const date = document.getElementById('deductionDate').value;
    if (!date) {
        e.preventDefault();
        document.getElementById('dateError').textContent = 'التاريخ مطلوب';
        document.getElementById('dateError').style.display = 'block';
    }
});

// Preview calculation
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const date = document.getElementById('deductionDate').value;
        if (date) {
            updatePreview(date, this.value);
        }
    });
});

document.getElementById('deductionDate').addEventListener('change', function() {
    const type = document.querySelector('input[name="type"]:checked')?.value;
    if (type) {
        updatePreview(this.value, type);
    }
});

function updatePreview(date, type) {
    // This will be called to show wage and deduction amount
    fetch(`/contractor/deductions/worker/{{ $worker->id }}/wage-preview?date=${date}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const wage = data.wage;
                const multiplier = {quarter: 0.25, half: 0.5, full: 1.0}[type];
                const deductionAmount = wage * multiplier;
                
                document.getElementById('previewWage').textContent = wage.toFixed(0) + ' ج';
                document.getElementById('previewAmount').textContent = deductionAmount.toFixed(0) + ' ج';
                document.getElementById('previewCard').style.display = 'block';
            }
        })
        .catch(err => console.error(err));
}
</script>

@if(session('success'))
    <script>
        setTimeout(() => {
            if(typeof iDaraToast !== 'undefined') {
                iDaraToast.success("{{ session('success') }}");
            }
        }, 100);
    </script>
@endif

@if(session('error'))
    <script>
        setTimeout(() => {
            if(typeof iDaraToast !== 'undefined') {
                iDaraToast.error("{{ session('error') }}");
            }
        }, 100);
    </script>
@endif

@endsection
