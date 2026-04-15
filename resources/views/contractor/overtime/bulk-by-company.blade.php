@extends('layouts.dashboard')
@section('title', 'تسجيل ساعات سهر جماعي')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Tajawal', sans-serif; }

/* ── TOPBAR ── */
.topbar {
  background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%);
  padding: 18px 20px 22px;
  margin: 0 0 0 0;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; }
.back-btn { color: rgba(255,255,255,0.85); font-size: 13px; cursor: pointer; user-select: none; text-decoration: none; }
.back-btn:hover { color: #fff; }
.top-title { color: #fff; font-size: 16px; font-weight: 700; }
.top-sub { color: rgba(255,255,255,0.65); font-size: 11px; margin-top: 4px; }

/* ── FORM SECTION ── */
.section { margin-bottom: 16px; }
.section-body { padding: 16px; background: #fff; }
.section-title { font-size: 12px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 12px; margin-top: 16px; padding: 0 16px; }

.form-group { margin-bottom: 16px; }
.form-label { font-size: 12px; font-weight: 600; color: #333; margin-bottom: 6px; display: block; }
.form-input, .form-select {
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 13px;
  font-family: inherit;
  outline: none;
  transition: all 0.2s;
}
.form-input:focus, .form-select:focus {
  border-color: #0a4f14;
  box-shadow: 0 0 0 3px rgba(10, 79, 20, 0.1);
}

/* ── BUTTONS ── */
.btn-group { display: flex; gap: 8px; margin-top: 16px; }
.btn {
  flex: 1;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
.btn-primary {
  background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%);
  color: #fff;
}
.btn-primary:hover { box-shadow: 0 4px 12px rgba(10, 79, 20, 0.3); }
.btn-primary:active { transform: scale(0.98); }
.btn-secondary {
  background: #f0f0f0;
  color: #666;
}
.btn-secondary:hover { background: #e0e0e0; }

/* ── WORKERS PREVIEW ── */
.workers-preview {
  background: #fff;
  border-radius: 12px;
  padding: 16px;
  border: 2px dashed #ddd;
  margin-top: 16px;
}
.preview-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
  font-weight: 600;
  color: #333;
}
.preview-count {
  background: #0a4f14;
  color: #fff;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 600;
}
.workers-list {
  max-height: 300px;
  overflow-y: auto;
}
.worker-item {
  display: flex;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
}
.worker-item:last-child { border-bottom: none; }
.worker-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0a4f14, #1D9E75);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 600;
  margin-right: 10px;
  flex-shrink: 0;
}
.worker-name { flex: 1; }

/* ── NO WORKERS MESSAGE ── */
.no-workers-msg {
  text-align: center;
  padding: 24px 16px;
  color: #999;
  font-size: 13px;
}

/* ── LOADING ── */
.loading {
  display: none;
  text-align: center;
  padding: 20px;
  color: #666;
}
.spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid #f0f0f0;
  border-top-color: #0a4f14;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-right: 8px;
  vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── ERROR/SUCCESS MESSAGES ── */
.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 13px;
  display: none;
}
.alert.show { display: block; }
.alert-success {
  background: #ECFDF5;
  color: #0a4f14;
  border-left: 4px solid #0a4f14;
}
.alert-error {
  background: #FEF2F2;
  color: #991b1b;
  border-left: 4px solid #991b1b;
}

/* ── STATS ── */
.stats-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 12px;
}
.stat-card {
  background: linear-gradient(135deg, #f5f5f5 0%, #f9f9f9 100%);
  border-radius: 8px;
  padding: 12px;
  text-align: center;
}
.stat-value {
  font-size: 18px;
  font-weight: 700;
  color: #0a4f14;
}
.stat-label {
  font-size: 11px;
  color: #999;
  margin-top: 4px;
}

/* ── RESPONSIVE ── */
@media (max-width: 600px) {
  .topbar { padding: 14px 16px 18px; }
  .section-body { padding: 12px; }
  .form-input, .form-select { padding: 9px 10px; font-size: 12px; }
}
</style>

<div class="topbar">
  <div class="topbar-row">
    <a href="/contractor/dashboard" class="back-btn">← رجوع</a>
    <div>
      <div class="top-title">تسجيل ساعات السهر الجماعية</div>
      <div class="top-sub">لإضافة ساعات سهر لجميع العمال الموزعين في الشركة</div>
    </div>
  </div>
</div>

<div style="padding: 16px;">
  <div class="alert alert-success" id="successMsg"></div>
  <div class="alert alert-error" id="errorMsg"></div>

  <div class="section">
    <div class="section-body">
      <form id="bulkOvertimeForm">
        @csrf

        <!-- Company Selection -->
        <div class="form-group">
          <label class="form-label">اختر الشركة</label>
          <select id="companyId" class="form-select" required>
            <option value="">-- اختر شركة --</option>
            @foreach($companies as $company)
              <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Date Selection -->
        <div class="form-group">
          <label class="form-label">التاريخ</label>
          <input type="date" id="distributionDate" class="form-input" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Overtime Hours -->
        <div class="form-group">
          <label class="form-label">عدد ساعات السهر</label>
          <input type="number" id="overtimeHours" class="form-input" min="0" max="12" step="0.5" value="0" required>
        </div>

        <!-- Preview Button -->
        <div class="btn-group">
          <button type="button" class="btn btn-secondary" onclick="previewWorkers()">
            👁 عاين العمال المتأثرين
          </button>
          <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
            ✓ تطبيق على الجميع
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Workers Preview Section -->
  <div class="workers-preview" id="workersPreview" style="display: none;">
    <div class="preview-header">
      <span class="preview-count" id="workersCount">0</span>
      <span>عامل سيتأثر بهذا التطبيق</span>
    </div>

    <div class="stats-row" id="statsRow" style="display: none;">
      <div class="stat-card">
        <div class="stat-value" id="totalOvertimeAmount">0 ج</div>
        <div class="stat-label">إجمالي مكافأة السهر</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="currentOvertimeHours">0ساعة</div>
        <div class="stat-label">إجمالي ساعات السهر</div>
      </div>
    </div>

    <div class="loading" id="loading">
      <span class="spinner"></span>
      جارِ تحميل قائمة العمال...
    </div>

    <div class="workers-list" id="workersList"></div>
    <div class="no-workers-msg" id="noWorkersMsg" style="display: none;">
      لا توجد عمال موزعين في هذا التاريخ
    </div>
  </div>
</div>

<script>
const form = document.getElementById('bulkOvertimeForm');
const companySelect = document.getElementById('companyId');
const dateInput = document.getElementById('distributionDate');
const hoursInput = document.getElementById('overtimeHours');
const submitBtn = document.getElementById('submitBtn');
const previewSection = document.getElementById('workersPreview');
const workersList = document.getElementById('workersList');
const noWorkersMsg = document.getElementById('noWorkersMsg');
const loadingDiv = document.getElementById('loading');
const workersCount = document.getElementById('workersCount');
const statsRow = document.getElementById('statsRow');
const successMsg = document.getElementById('successMsg');
const errorMsg = document.getElementById('errorMsg');

// Enable/disable submit button based on form inputs
[companySelect, dateInput, hoursInput].forEach(el => {
  el.addEventListener('change', validateForm);
  el.addEventListener('input', validateForm);
});

function validateForm() {
  const isValid = companySelect.value && dateInput.value && hoursInput.value;
  submitBtn.disabled = !isValid;
}

function hideMessages() {
  successMsg.classList.remove('show');
  errorMsg.classList.remove('show');
}

async function previewWorkers() {
  if (!companySelect.value || !dateInput.value) {
    showError('يرجى تحديد الشركة والتاريخ أولاً');
    return;
  }

  hideMessages();
  previewSection.style.display = 'block';
  loadingDiv.style.display = 'flex';
  workersList.innerHTML = '';
  noWorkersMsg.style.display = 'none';
  statsRow.style.display = 'none';

  try {
    const response = await fetch(
      `/contractor/distributions/company-workers?company_id=${companySelect.value}&date=${dateInput.value}`,
      {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      }
    );

    const data = await response.json();
    loadingDiv.style.display = 'none';

    if (!data.success) {
      showError(data.message || 'حدث خطأ في تحميل البيانات');
      previewSection.style.display = 'none';
      return;
    }

    const workers = data.data.workers || [];
    
    if (workers.length === 0) {
      noWorkersMsg.style.display = 'block';
      workersCount.textContent = '0';
    } else {
      noWorkersMsg.style.display = 'none';
      workersCount.textContent = workers.length;
      
      workersList.innerHTML = workers.map(worker => `
        <div class="worker-item">
          <div class="worker-avatar">${worker.name.charAt(0)}</div>
          <div class="worker-name">${worker.name}</div>
          <div style="font-size: 11px; color: #999;">الحالي: ${worker.current_hours || 0} ساعة</div>
        </div>
      `).join('');

      // Calculate and display statistics
      const overtimeHours = parseFloat(hoursInput.value) || 0;
      const overtimeRate = 20; // Default rate, should come from preferences
      const totalHours = workers.length * overtimeHours;
      const totalAmount = totalHours * overtimeRate;

      document.getElementById('currentOvertimeHours').textContent = totalHours.toFixed(1) + ' ساعة';
      document.getElementById('totalOvertimeAmount').textContent = totalAmount.toFixed(0) + ' ج';
      statsRow.style.display = 'grid';
    }
  } catch (error) {
    loadingDiv.style.display = 'none';
    showError('حدث خطأ في الاتصال بالخادم');
    console.error('Error:', error);
  }
}

function showSuccess(message) {
  successMsg.textContent = message;
  successMsg.classList.add('show');
  setTimeout(() => successMsg.classList.remove('show'), 4000);
}

function showError(message) {
  errorMsg.textContent = message;
  errorMsg.classList.add('show');
  setTimeout(() => errorMsg.classList.remove('show'), 4000);
}

// Form submission
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  hideMessages();
  submitBtn.disabled = true;
  const originalText = submitBtn.textContent;
  submitBtn.innerHTML = '<span class="spinner"></span> جاري الحفظ...';

  try {
    const response = await fetch('/contractor/overtime/bulk-by-company', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        company_id: companySelect.value,
        distribution_date: dateInput.value,
        overtime_hours: hoursInput.value
      })
    });

    const data = await response.json();

    if (data.success) {
      showSuccess(data.message);
      setTimeout(() => {
        form.reset();
        previewSection.style.display = 'none';
        validateForm();
        submitBtn.textContent = originalText;
      }, 1500);
    } else {
      showError(data.message || 'حدث خطأ أثناء الحفظ');
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    showError('حدث خطأ في الاتصال بالخادم');
    console.error('Error:', error);
    submitBtn.textContent = originalText;
    submitBtn.disabled = false;
  }
});

// Initial validation
validateForm();
</script>
@endsection
