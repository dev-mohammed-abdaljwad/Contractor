@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

/* Header */
.hero {
  background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 60%, #2DC98A 100%);
  padding: 28px 20px 60px;
  position: relative;
  margin: -28px -28px 0 -28px;
}
.hero-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.back { color: rgba(255,255,255,0.85); font-size: 13px; cursor: pointer; text-decoration: none; }
.back:hover { color: #fff; }
.more-btn { color: rgba(255,255,255,0.85); font-size: 14px; cursor: pointer; }

.co-info { display: flex; align-items: center; gap: 16px; }
.co-avatar-lg {
  width: 68px; height: 68px; border-radius: 50%;
  background: rgba(255,255,255,0.22);
  border: 2.5px solid rgba(255,255,255,0.5);
  display: flex; align-items: center; justify-content: center;
  font-size: 24px; font-weight: 600; color: #fff;
  flex-shrink: 0;
}
.co-name { font-size: 20px; font-weight: 600; color: #fff; margin-bottom: 4px; }
.co-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.cycle-badge {
  background: rgba(255,255,255,0.2); color: #fff;
  font-size: 11px; padding: 2px 8px; border-radius: 20px;
}
.status-badge {
  background: #4ade80; color: #14532d;
  font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.status-badge.inactive { background: #ef4444; color: #fff; }
.co-sub { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 3px; }

/* Action buttons */
.actions {
  display: flex; gap: 8px; margin-top: 16px;
}
.act-btn {
  flex: 1; text-align: center; padding: 8px 6px;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 10px; color: #fff; font-size: 12px; cursor: pointer;
  transition: background 0.2s; text-decoration: none;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
}
.act-btn:hover { background: rgba(255,255,255,0.25); }
.act-icon { font-size: 16px; display: block; margin-bottom: 2px; }

/* Stats cards */
.stats-row {
  display: grid; grid-template-columns: repeat(4, minmax(0,1fr));
  gap: 10px; padding: 0 16px;
  margin-top: -36px; position: relative; z-index: 10;
  margin-bottom: 16px;
}
.stat-card {
  background: #fff; border-radius: 14px;
  padding: 12px 10px; text-align: center;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.stat-val { font-size: 15px; font-weight: 700; margin-bottom: 3px; }
.stat-label { font-size: 10px; color: #888; line-height: 1.3; }
.v-green { color: #0F6E56; }
.v-amber { color: #D97706; }

/* Tabs */
.tabs-wrap {
  background: #fff;
  border-radius: 14px 14px 0 0;
  margin: 0 16px 0 16px;
  overflow: hidden;
  box-shadow: 0 -2px 12px rgba(0,0,0,0.04);
}
.tabs-bar {
  display: flex; border-bottom: 1px solid #f0f0f0;
  padding: 0 4px;
}
.tab-btn {
  flex: 1; text-align: center; padding: 12px 4px;
  font-size: 12px; color: #999; cursor: pointer;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}
.tab-btn.active { color: #1D9E75; font-weight: 600; border-bottom-color: #1D9E75; }

/* Tab content */
.tab-content { padding: 16px; display: none; }
.tab-content.active { display: block; }

/* Section title */
.sec-title {
  font-size: 12px; font-weight: 600; color: #999;
  text-transform: uppercase; letter-spacing: 0.06em;
  margin-bottom: 10px; margin-top: 4px;
}

/* Info rows */
.info-row { display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
.info-row:last-child { border-bottom: none; }
.info-key { font-size: 12px; color: #aaa; min-width: 100px; flex-shrink: 0; }
.info-val { font-size: 13px; font-weight: 500; color: #222; flex: 1; }

/* Deactivate banner */
.deact-banner {
  margin: 16px; background: #FEF2F2;
  border: 1px solid #FECACA; border-radius: 12px; padding: 14px;
  display: none;
}
.deact-banner.show { display: block; }
.deact-title { font-size: 13px; font-weight: 600; color: #991B1B; margin-bottom: 6px; }
.deact-text { font-size: 12px; color: #7f1d1d; line-height: 1.6; margin-bottom: 12px; }
.deact-checks { margin-bottom: 12px; }
.deact-check { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; color: #555; margin-bottom: 6px; line-height: 1.5; }
.check-icon { color: #10B981; font-weight: 700; flex-shrink: 0; margin-top: 1px; }
.deact-actions { display: flex; gap: 8px; }
.btn-cancel { flex: 1; padding: 9px; background: #fff; border: 1px solid #ddd; border-radius: 10px; font-size: 13px; font-weight: 500; cursor: pointer; text-align: center; color: #555; }
.btn-confirm { flex: 1; padding: 9px; background: #DC2626; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; text-align: center; color: #fff; }

/* Modals */
.modal-overlay {
  display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5); z-index: 999; align-items: center; justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal-box {
  background: #fff; border-radius: 16px; width: 90%; max-width: 500px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); max-height: 90vh; overflow-y: auto;
  animation: slideUp 0.3s ease;
}
@keyframes slideUp {
  from { transform: translateY(40px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.modal-header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 20px 24px; border-bottom: 1px solid #f0f0f0;
}
.modal-title { font-size: 16px; font-weight: 700; color: #222; }
.modal-close {
  background: none; border: none; font-size: 24px; cursor: pointer;
  color: #999; transition: color 0.2s;
}
.modal-close:hover { color: #333; }
.modal-body { padding: 24px; }
.modal-footer {
  display: flex; gap: 12px; padding: 16px 24px;
  border-top: 1px solid #f0f0f0;
}
.modal-btn {
  flex: 1; padding: 12px; border-radius: 10px; border: none;
  font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s;
}
.modal-btn-primary { background: #1D9E75; color: #fff; }
.modal-btn-primary:hover { background: #0F6E56; }
.modal-btn-secondary { background: #f5f5f5; color: #333; }
.modal-btn-secondary:hover { background: #e8e8e8; }

.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
.form-input {
  width: 100%; padding: 10px 12px; border: 1px solid #e0e0e0;
  border-radius: 8px; font-size: 14px; font-family: inherit;
}
.form-input:focus { outline: none; border-color: #1D9E75; box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1); }
.form-textarea {
  width: 100%; padding: 10px 12px; border: 1px solid #e0e0e0;
  border-radius: 8px; font-size: 13px; font-family: inherit; resize: vertical; min-height: 80px;
}
.form-textarea:focus { outline: none; border-color: #1D9E75; box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1); }
.form-alert { background: #ECFDF5; border-left: 4px solid #1D9E75; padding: 12px; border-radius: 6px; font-size: 13px; color: #065F46; margin-bottom: 16px; }

@media (max-width: 768px) {
  .stats-row { grid-template-columns: repeat(2, minmax(0,1fr)); }
}
</style>

<div class="hero">
  <div class="hero-top">
    <a href="{{ route('contractor.companies.index') }}" class="back">← رجوع</a>
    <div class="more-btn" onclick="toggleDeact()">⏸ إيقاف</div>
  </div>
  <div class="co-info">
    <div class="co-avatar-lg">{{ substr($company->name, 0, 1) }}</div>
    <div>
      <div class="co-name">{{ $company->name }}</div>
      <div class="co-meta">
        <span class="cycle-badge">
          @if($company->payment_cycle === 'daily') يومي
          @elseif($company->payment_cycle === 'weekly') أسبوعي
          @elseif($company->payment_cycle === 'bimonthly') نصف شهري
          @else شهري
          @endif
        </span>
        <span class="status-badge {{ !$company->is_active ? 'inactive' : '' }}">{{ $company->is_active ? 'نشطة' : 'متوقفة' }}</span>
      </div>
      <div class="co-sub">{{ $company->contact_person }} · {{ $company->phone }} · منذ {{ $company->contract_start_date->format('M Y') }}</div>
    </div>
  </div>
  <div class="actions">
    <button class="act-btn" onclick="openModal('editModal')"><span class="act-icon">✎</span>تعديل</button>
    <button class="act-btn" onclick="goToCollection()"><span class="act-icon">💰</span>تحصيل</button>
    <button class="act-btn" onclick="openModal('paymentModal')"><span class="act-icon">↓</span>دفعة</button>
    <button class="act-btn" onclick="openModal('distributionModal')"><span class="act-icon">+</span>توزيع</button>
  </div>
</div>

<!-- Deactivate Banner -->
<div class="deact-banner" id="deactBanner">
  <div class="deact-title">إيقاف {{ $company->name }}</div>
  <div class="deact-text">هتختفي الشركة من شاشة التوزيع اليومي، لكن كل السجلات هتفضل محفوظة.</div>
  <div class="deact-checks">
    <div class="deact-check"><span class="check-icon">✓</span>سجل التوزيع والحضور محفوظ</div>
    <div class="deact-check"><span class="check-icon">✓</span>سجل التحصيل والدفعات محفوظ</div>
    <div class="deact-check"><span class="check-icon">✓</span>ممكن إعادة التفعيل في أي وقت</div>
    <div class="deact-check"><span class="check-icon">✓</span>الشركة هتظهر تحت فلتر "غير نشطة"</div>
  </div>
  <div class="deact-actions">
    <button class="btn-cancel" onclick="toggleDeact()">إلغاء</button>
    <button class="btn-confirm" onclick="deactivateCompany({{ $company->id }})">تأكيد الإيقاف</button>
  </div>
</div>

<div class="stats-row">
  <div class="stat-card">
    <div class="stat-val v-green">{{ number_format($company->daily_wage, 0) }}</div>
    <div class="stat-label">أجر/عامل (ج)</div>
  </div>
  <div class="stat-card">
    <div class="stat-val v-green">{{ $workersToday->count() }}</div>
    <div class="stat-label">عمال اليوم</div>
  </div>
  <div class="stat-card">
    <div class="stat-val v-green" style="font-size:12px;">{{ number_format($monthlyTotal, 0) }}</div>
    <div class="stat-label">إجمالي الشهر</div>
  </div>
  <div class="stat-card">
    <div class="stat-val v-amber" style="font-size:12px;">{{ number_format($pendingAmount, 0) }}</div>
    <div class="stat-label">مستحق الآن</div>
  </div>
</div>

<div class="tabs-wrap">
  <div class="tabs-bar">
    <div class="tab-btn active" onclick="switchTab(this, 'tab0')">نظرة عامة</div>
    <div class="tab-btn" onclick="switchTab(this, 'tab1')">العمال</div>
    <div class="tab-btn" onclick="switchTab(this, 'tab2')">السجل</div>
    <div class="tab-btn" onclick="switchTab(this, 'tab3')">التحصيل</div>
  </div>

  <!-- TAB 0: Overview -->
  <div class="tab-content active" id="tab0">
    <div class="sec-title">بيانات الشركة</div>
    <div class="info-row">
      <div class="info-key">الاسم</div>
      <div class="info-val">{{ $company->name }}</div>
    </div>
    <div class="info-row">
      <div class="info-key">المسؤول</div>
      <div class="info-val">{{ $company->contact_person }}</div>
    </div>
    <div class="info-row">
      <div class="info-key">الجوال</div>
      <div class="info-val" style="color:#1D9E75;direction:ltr;unicode-bidi:plaintext;">{{ $company->phone }}</div>
    </div>
    <div class="info-row">
      <div class="info-key">الأجر اليومي</div>
      <div class="info-val">{{ number_format($company->daily_wage, 0) }} جنيه</div>
    </div>
    <div class="info-row">
      <div class="info-key">دورة الدفع</div>
      <div class="info-val">
        @if($company->payment_cycle === 'daily') دفع يومي
        @elseif($company->payment_cycle === 'weekly') أسبوعي
        @elseif($company->payment_cycle === 'bimonthly') نصف شهري
        @else شهري
        @endif
      </div>
    </div>
    <div class="info-row">
      <div class="info-key">تاريخ التعاقد</div>
      <div class="info-val">{{ $company->contract_start_date->format('d M Y') }}</div>
    </div>
    
    @if($company->notes)
      <div style="margin-top:16px;padding:12px;background:#ECFDF5;border-radius:8px;border:1px solid #A7F3D0">
        <div class="sec-title" style="color:#065F46;margin-bottom:8px;">ملاحظات:</div>
        <p style="font-size:13px;color:#065F46;line-height:1.5">{{ $company->notes }}</p>
      </div>
    @endif
  </div>

  <!-- TAB 1: Workers Today -->
  <div class="tab-content" id="tab1">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
      <div class="sec-title" style="margin:0;">عمال اليوم</div>
      <span style="background:#ECFDF5;color:#065F46;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">{{ count($workersToday) }} عامل</span>
    </div>
    @if($workersToday->count() > 0)
      <div style="display:grid;gap:8px;">
        @foreach($workersToday as $worker)
          <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:#f8f9fa;border-radius:10px;border-left:3px solid #1D9E75;">
            <div style="font-size:13px;font-weight:500;color:#222;">{{ $worker->name }}</div>
            <div style="font-size:12px;color:#aaa;">{{ $company->daily_wage }} ج</div>
          </div>
        @endforeach
      </div>
    @else
      <div style="color: #aaa; text-align: center; padding: 20px;">لا يوجد عمال موزعين اليوم</div>
    @endif
  </div>

  <!-- TAB 2: History -->
  <div class="tab-content" id="tab2">
    <div style="display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap;">
      <span style="background:#ECFDF5;color:#065F46;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:600;cursor:pointer;" onclick="filterDistributions('week')">هذا الأسبوع</span>
      <span style="background:#f5f5f5;color:#888;border-radius:20px;padding:4px 12px;font-size:12px;cursor:pointer;" onclick="filterDistributions('month')">هذا الشهر</span>
    </div>
    @if($distributionHistory->count() > 0)
      <div style="display:grid;gap:10px;">
        @foreach($distributionHistory as $dist)
          <div style="padding:12px;background:#f8f9fa;border-radius:10px;border-right:3px solid #1D9E75;">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
              <div style="font-size:13px;font-weight:600;color:#222;">
                {{ $dist->distribution_date->format('d/m') }}
              </div>
              <div style="font-size:14px;font-weight:700;color:#1D9E75;">
                {{ number_format($dist->total_amount ?? 0) }} ج
              </div>
            </div>
            <div style="font-size:12px;color:#666;">{{ $dist->workers->count() }} عامل</div>
          </div>
        @endforeach
      </div>
    @else
      <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد سجلات توزيع</div>
    @endif
  </div>

  <!-- TAB 3: Collection -->
  <div class="tab-content" id="tab3">
    @if($paymentsHistory->count() > 0)
      <div style="display:grid;gap:10px;margin-bottom:16px;">
        @foreach($paymentsHistory as $payment)
          <div style="padding:14px;background:#ECFDF5;border-radius:10px;border-right:3px solid #1D9E75;">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
              <div>
                <div style="font-size:13px;font-weight:600;color:#222;">
                  {{ $payment->date->format('d/m/Y') }}
                </div>
                <div style="font-size:11px;color:#999;margin-top:2px;">
                  {{ $payment->payment_type === 'salary' ? 'الراتب' : ($payment->payment_type === 'advance_repayment' ? 'سداد مقدم' : ($payment->payment_type === 'bonus' ? 'حافز' : 'أخرى')) }}
                  · {{ $payment->payment_method === 'cash' ? 'كاش' : ($payment->payment_method === 'transfer' ? 'تحويل' : ($payment->payment_method === 'check' ? 'شيك' : 'أخرى')) }}
                </div>
              </div>
              <div style="text-align:left;">
                <div style="font-size:14px;font-weight:700;color:#1D9E75;">
                  {{ number_format($payment->amount, 0) }} ج
                </div>
              </div>
            </div>
            @if($payment->notes)
              <div style="font-size:11px;color:#666;margin-top:8px;padding-top:8px;border-top:1px solid rgba(29,158,117,0.1);">
                {{ $payment->notes }}
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <div style="color: #aaa; text-align: center; padding: 20px;">لا توجد دفعات مسجلة</div>
    @endif
    <button style="width: 100%; padding: 13px; background: #1D9E75; color: #fff; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 4px;" onclick="openModal('paymentModal')">+ تسجيل دفعة جديدة</button>
  </div>

</div>

<!-- ============ MODALS ============ -->

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">تعديل بيانات الشركة</div>
      <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">اسم الشركة</label>
        <input type="text" class="form-input" id="companyName" value="{{ $company->name }}">
      </div>
      <div class="form-group">
        <label class="form-label">المسؤول</label>
        <input type="text" class="form-input" id="contactPerson" value="{{ $company->contact_person }}">
      </div>
      <div class="form-group">
        <label class="form-label">الجوال</label>
        <input type="tel" class="form-input" id="phone" value="{{ $company->phone }}">
      </div>
      <div class="form-group">
        <label class="form-label">الأجر اليومي (ج)</label>
        <input type="number" class="form-input" id="dailyWage" value="{{ $company->daily_wage }}" min="0" step="0.01">
      </div>
      <div class="form-group">
        <label class="form-label">دورة الدفع</label>
        <select class="form-input" id="paymentCycle">
          <option value="daily" {{ $company->payment_cycle === 'daily' ? 'selected' : '' }}>يومي</option>
          <option value="weekly" {{ $company->payment_cycle === 'weekly' ? 'selected' : '' }}>أسبوعي</option>
          <option value="bimonthly" {{ $company->payment_cycle === 'bimonthly' ? 'selected' : '' }}>نصف شهري</option>
          <option value="monthly" {{ $company->payment_cycle === 'monthly' ? 'selected' : '' }}>شهري</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">ملاحظات</label>
        <textarea class="form-textarea" id="notes" placeholder="أضف ملاحظات...">{{ $company->notes ?? '' }}</textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="modal-btn modal-btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
      <button class="modal-btn modal-btn-primary" onclick="saveCompanyEdit({{ $company->id }})">حفظ التغييرات</button>
    </div>
  </div>
</div>

<!-- PAYMENT MODAL -->
<div class="modal-overlay" id="paymentModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">تسجيل دفعة</div>
      <button class="modal-close" onclick="closeModal('paymentModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="paymentForm">
        <div class="form-group">
          <label class="form-label">التاريخ</label>
          <input type="date" class="form-input" id="paymentDate">
        </div>
        <div class="form-group">
          <label class="form-label">المبلغ (جنيه)</label>
          <input type="number" class="form-input" id="paymentAmount" placeholder="0.00" min="0" step="0.01">
        </div>
        <div class="form-group">
          <label class="form-label">طريقة الدفع</label>
          <select class="form-input" id="paymentMethod">
            <option value="">اختر طريقة الدفع</option>
            <option value="cash">كاش</option>
            <option value="bank_transfer">تحويل بنكي</option>
            <option value="cheque">شيك</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">نوع الدفعة</label>
          <select class="form-input" id="paymentType">
            <option value="">اختر نوع الدفعة</option>
            <option value="advance">دفعة مقدمة</option>
            <option value="salary">راتب</option>
            <option value="bonus">حافز</option>
            <option value="settlement">تسوية</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">ملاحظات</label>
          <textarea class="form-textarea" id="paymentNotes" placeholder="اضف ملاحظات..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="modal-btn modal-btn-secondary" onclick="closeModal('paymentModal')">إلغاء</button>
      <button class="modal-btn modal-btn-primary" onclick="savePayment({{ $company->id }})">تسجيل الدفعة</button>
    </div>
  </div>
</div>

<!-- DISTRIBUTION MODAL -->
<div class="modal-overlay" id="distributionModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">توزيع عمال</div>
      <button class="modal-close" onclick="closeModal('distributionModal')">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-alert">
        ℹ️ اختر العمال الموزعين اليوم على هذه الشركة
      </div>
      <div class="form-group">
        <label class="form-label">العمال</label>
        <input type="text" class="form-input" placeholder="ابحث عن عامل..." id="workerSearch">
        <div id="workersList" style="margin-top:8px;"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="modal-btn modal-btn-secondary" onclick="closeModal('distributionModal')">إلغاء</button>
      <button class="modal-btn modal-btn-primary" onclick="saveDistribution({{ $company->id }})">حفظ التوزيع</button>
    </div>
  </div>
</div>

<script>
// ============ MODAL FUNCTIONS ============
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add('active');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.remove('active');
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {
      overlay.classList.remove('active');
    }
  });
});

// ============ TAB SWITCHING ============
function switchTab(el, tabId) {
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
  if (el && el.classList) el.classList.add('active');
  
  document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
  const tabContent = document.getElementById(tabId);
  if (tabContent) tabContent.classList.add('active');
}

// Go to collection tab
function goToCollection() {
  const tabBtns = document.querySelectorAll('.tab-btn');
  if (tabBtns.length >= 4) {
    switchTab(tabBtns[3], 'tab3');
  }
}

// ============ DEACTIVATE ============
function toggleDeact() {
  document.getElementById('deactBanner').classList.toggle('show');
}

function deactivateCompany(companyId) {
  fetch(`/contractor/companies/${companyId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({ is_active: false })
  })
  .then(r => {
    if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
    return r.json();
  })
  .then(data => {
    if (data.success) {
      window.showToast(data.message || 'تم إيقاف الشركة بنجاح', 'success');
      setTimeout(() => {
        location.reload();
      }, 1500);
    } else {
      window.showToast(data.message || 'فشل إيقاف الشركة', 'error');
    }
  })
  .catch(e => window.showToast('خطأ: ' + e.message, 'error'));
}

// ============ EDIT COMPANY ============
function saveCompanyEdit(companyId) {
  const data = {
    name: document.getElementById('companyName').value,
    contact_person: document.getElementById('contactPerson').value,
    phone: document.getElementById('phone').value,
    daily_wage: parseFloat(document.getElementById('dailyWage').value),
    payment_cycle: document.getElementById('paymentCycle').value,
    notes: document.getElementById('notes').value
  };

  fetch(`/contractor/companies/${companyId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify(data)
  })
  .then(r => {
    if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
    return r.json();
  })
  .then(data => {
    if (data.success) {
      alert('تم تحديث البيانات بنجاح');
      closeModal('editModal');
      location.reload();
    } else {
      alert('خطأ: ' + (data.message || 'حدث خطأ'));
    }
  })
  .catch(e => alert('خطأ: ' + e.message));
}

// ============ PAYMENT ============
function savePayment(companyId) {
  const date = document.getElementById('paymentDate').value;
  const amount = document.getElementById('paymentAmount').value;
  const method = document.getElementById('paymentMethod').value;
  const type = document.getElementById('paymentType').value;
  const notes = document.getElementById('paymentNotes').value;

  // Client-side validation
  if (!date) {
    alert('الرجاء اختيار التاريخ');
    return;
  }
  if (!amount) {
    alert('الرجاء إدخال المبلغ');
    return;
  }
  if (parseFloat(amount) <= 0) {
    alert('المبلغ يجب أن يكون أكبر من صفر');
    return;
  }
  if (!method) {
    alert('الرجاء اختيار طريقة الدفع');
    return;
  }
  if (!type) {
    alert('الرجاء اختيار نوع الدفعة');
    return;
  }

  const payload = {
    company_id: companyId,
    amount: parseFloat(amount),
    date: date,
    payment_method: method,
    payment_type: type,
    notes: notes || null
  };

  fetch(`/contractor/companies/${companyId}/payments`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify(payload)
  })
  .then(r => r.json().then(data => ({ ok: r.ok, status: r.status, data })))
  .then(({ ok, status, data }) => {
    if (ok && data.success) {
      alert('تم تسجيل الدفعة بنجاح');
      closeModal('paymentModal');
      document.getElementById('paymentForm').reset();
      setTimeout(() => location.reload(), 1500);
    } else {
      console.error('Payment failed:', data);
      alert('يرجى التحقق من البيانات المدخلة');
    }
  })
  .catch(e => {
    console.error('Error:', e);
    alert('حدث خطأ أثناء تسجيل الدفعة');
  });
}

// ============ DISTRIBUTION ============
function saveDistribution(companyId) {
  alert('سيتم إضافة عمل التوزيع قريباً');
  closeModal('distributionModal');
}
</script>

@endsection
