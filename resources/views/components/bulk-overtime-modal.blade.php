<!-- Modal for Bulk Overtime Entry - Modern Design -->
<style>
  #bulkOvertimeModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: modalFadeIn 0.2s ease-out;
  }

  @keyframes modalFadeIn {
    from { background: rgba(0, 0, 0, 0); }
    to { background: rgba(0, 0, 0, 0.5); }
  }

  #bulkOvertimeModal.show {
    display: flex;
  }

  .bulk-modal-content {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
  }

  .bulk-modal-header {
    background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
    color: white;
    padding: 20px;
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
  }
  
  .bulk-modal-header .material-symbols-outlined {
    font-size: 28px;
    font-weight: 400;
  }
  
  .bulk-modal-header h5 {
    font-family: 'Tajawal', sans-serif;
    font-weight: 700;
    font-size: 18px;
    margin: 0;
    letter-spacing: 0.5px;
    flex: 1;
  }
  
  .bulk-modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    border-radius: 6px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }
  
  .bulk-modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
  }
  
  .bulk-modal-body {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0;
    min-height: 0;
  }
  
  .bulk-modal-message {
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 16px;
    font-size: 14px;
    font-weight: 500;
    display: none;
    align-items: center;
    gap: 8px;
    font-family: 'Tajawal', sans-serif;
  }
  
  .bulk-modal-message.show {
    display: flex;
  }
  
  .bulk-modal-message.success {
    background: #ECFDF5;
    color: #065F46;
    border: 1px solid #A7F3D0;
  }
  
  .bulk-modal-message.error {
    background: #FEE2E2;
    color: #991B1B;
    border: 1px solid #FECACA;
  }
  
  .bulk-modal-message .material-symbols-outlined {
    font-size: 18px;
  }
  
  .bulk-form-group {
    margin-bottom: 18px;
  }
  
  .bulk-form-wrapper {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 0;
    margin-bottom: 16px;
  }
  
  .bulk-form-label {
    font-family: 'Tajawal', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: #1a1c19;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 8px;
    display: block;
  }
  
  .bulk-form-input,
  .bulk-form-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e5e5e0;
    border-radius: 10px;
    font-family: 'Tajawal', sans-serif;
    font-size: 14px;
    color: #1a1c19;
    transition: all 0.2s;
    background: #ffffff;
    position: relative;
    z-index: 1;
  }
  
  .bulk-form-input:focus,
  .bulk-form-select:focus {
    outline: none;
    border-color: #1D9E75;
    box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1);
    background: #ffffff;
    z-index: 100;
    position: relative;
  }
  
  .bulk-form-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%231D9E75' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 10px center;
    background-size: 18px;
    padding-right: 36px;
    padding-left: 12px;
  }
  
  /* Fix dropdown clipping on mobile */
  .bulk-form-select:active,
  .bulk-form-select:focus {
    position: relative;
  }
  
  .bulk-preview-section {
    background: #F3FFF9;
    border: 1px solid #A7F3D0;
    border-radius: 10px;
    padding: 16px;
    margin: 0;
    display: none;
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }
  
  .bulk-preview-section.show {
    display: flex;
    flex-direction: column;
  }
  
  .bulk-preview-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
    flex-direction: row-reverse;
    flex-shrink: 0;
  }
  
  .bulk-preview-stat {
    font-family: 'Tajawal', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #065F46;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  
  .bulk-preview-stat .material-symbols-outlined {
    font-size: 20px;
  }
  
  .bulk-workers-list {
    max-height: none;
    overflow-y: auto;
    border-top: 1px solid #A7F3D0;
    padding-top: 10px;
    flex: 1;
  }
  
  .bulk-worker-item {
    padding: 10px 0;
    border-bottom: 1px solid #D1FAE5;
    font-size: 13px;
    color: #1a1c19;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .bulk-worker-item:last-child {
    border-bottom: none;
  }
  
  .bulk-worker-item .material-symbols-outlined {
    font-size: 18px;
    color: #1D9E75;
  }
  
  .bulk-worker-name {
    font-weight: 600;
    font-family: 'Tajawal', sans-serif;
  }
  
  .bulk-worker-current {
    color: #6B7280;
    font-size: 12px;
    font-family: 'Tajawal', sans-serif;
  }
  
  .bulk-loading {
    text-align: center;
    padding: 24px;
    display: none;
  }

  .bulk-loading.show {
    display: block;
  }
  
  .bulk-spinner {
    width: 32px;
    height: 32px;
    border: 3px solid #E5E5E0;
    border-top-color: #1D9E75;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 12px;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  
  .bulk-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e5e0;
    display: flex;
    gap: 12px;
    flex-direction: row-reverse;
    justify-content: flex-end;
    flex-shrink: 0;
  }
  
  .bulk-modal-btn {
    font-family: 'Tajawal', sans-serif;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.3px;
  }
  
  .bulk-modal-btn-close {
    background: #F3F4F6;
    color: #1a1c19;
    border: 1px solid #e5e5e0;
  }
  
  .bulk-modal-btn-close:hover {
    background: #E5E7EB;
  }
  
  .bulk-modal-btn-preview {
    background: #ffffff;
    color: #1D9E75;
    border: 1.5px solid #1D9E75;
  }
  
  .bulk-modal-btn-preview:hover {
    background: #F0FDF4;
  }
  
  .bulk-modal-btn-primary {
    background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
    color: white;
  }
  
  .bulk-modal-btn-primary:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(13, 99, 27, 0.3);
    transform: translateY(-1px);
  }
  
  .bulk-modal-btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .bulk-modal-btn .material-symbols-outlined {
    font-size: 18px;
  }

  /* ============ TABLET/MOBILE (max-width: 768px) ============ */
  @media(max-width: 768px) {
    #bulkOvertimeModal {
      padding: 0;
      align-items: flex-end;
      justify-content: flex-end;
    }

    .bulk-modal-content {
      max-width: 100%;
      max-height: calc(100vh - 20px);
      border-radius: 16px 16px 0 0;
      width: 100%;
      animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
      from { transform: translateY(100%); }
      to { transform: translateY(0); }
    }
    
    .bulk-modal-header {
      padding: 16px;
      gap: 10px;
    }
    
    .bulk-modal-header .material-symbols-outlined {
      font-size: 24px;
    }
    
    .bulk-modal-header h5 {
      font-size: 16px;
    }
    
    .bulk-modal-close {
      width: 32px;
      height: 32px;
    }
    
    .bulk-modal-body {
      padding: 18px;
    }
    
    .bulk-form-group {
      margin-bottom: 14px;
    }
    
    .bulk-form-label {
      font-size: 12px;
    }
    
    .bulk-form-input,
    .bulk-form-select {
      font-size: 13px;
      padding: 9px 10px;
    }
    
    .bulk-preview-section {
      padding: 14px;
      margin: 16px 0;
    }
    
    .bulk-preview-stat {
      font-size: 13px;
    }
    
    .bulk-preview-stat .material-symbols-outlined {
      font-size: 18px;
    }
    
    .bulk-workers-list {
      max-height: 180px;
    }
    
    .bulk-worker-item {
      font-size: 12px;
      padding: 8px 0;
    }
    
    .bulk-modal-footer {
      padding: 12px 18px;
      gap: 8px;
      flex-wrap: wrap;
    }
    
    .bulk-modal-btn {
      padding: 9px 14px;
      font-size: 12px;
      gap: 4px;
      flex: 1;
      min-width: 100px;
    }
    
    .bulk-modal-btn .material-symbols-outlined {
      font-size: 16px;
    }
  }

  /* ============ SMALL PHONE (max-width: 480px) ============ */
  @media(max-width: 480px) {
    #bulkOvertimeModal {
      padding: 0;
      align-items: flex-end;
      justify-content: flex-end;
    }

    .bulk-modal-content {
      max-width: 100%;
      max-height: calc(100vh - 10px);
      border-radius: 16px 16px 0 0;
      width: 100%;
    }
    
    .bulk-modal-header {
      padding: 14px;
      gap: 8px;
    }
    
    .bulk-modal-header .material-symbols-outlined {
      font-size: 22px;
    }
    
    .bulk-modal-header h5 {
      font-size: 14px;
    }
    
    .bulk-modal-close {
      width: 28px;
      height: 28px;
    }
    
    .bulk-modal-body {
      padding: 14px;
    }
    
    .bulk-form-label {
      font-size: 11px;
      margin-bottom: 6px;
    }
    
    .bulk-form-input,
    .bulk-form-select {
      font-size: 12px;
      padding: 8px 9px;
      border-radius: 8px;
    }
    
    .bulk-modal-message {
      padding: 10px 12px;
      font-size: 12px;
      gap: 6px;
    }
    
    .bulk-modal-message .material-symbols-outlined {
      font-size: 16px;
    }
    
    .bulk-preview-section {
      padding: 12px;
      margin: 14px 0;
      border-radius: 8px;
    }
    
    .bulk-preview-header {
      margin-bottom: 12px;
      gap: 8px;
    }
    
    .bulk-preview-stat {
      font-size: 12px;
      gap: 4px;
    }
    
    .bulk-preview-stat .material-symbols-outlined {
      font-size: 16px;
    }
    
    .bulk-workers-list {
      max-height: 150px;
    }
    
    .bulk-worker-item {
      font-size: 11px;
      padding: 6px 0;
      gap: 6px;
    }
    
    .bulk-worker-item .material-symbols-outlined {
      font-size: 16px;
    }
    
    .bulk-modal-footer {
      padding: 10px 14px;
      gap: 6px;
      flex-wrap: wrap;
    }
    
    .bulk-modal-btn {
      padding: 8px 10px;
      font-size: 11px;
      gap: 3px;
      flex: 1;
      min-width: 90px;
    }
    
    .bulk-modal-btn .material-symbols-outlined {
      font-size: 15px;
    }
  }

  /* ============ VERY SMALL PHONE (max-width: 360px) ============ */
  @media(max-width: 360px) {
    #bulkOvertimeModal {
      padding: 0;
      align-items: flex-end;
      justify-content: flex-end;
    }

    .bulk-modal-content {
      max-width: 100%;
      max-height: calc(100vh - 5px);
      border-radius: 16px 16px 0 0;
      width: 100%;
    }
    
    .bulk-modal-header {
      padding: 12px;
      gap: 6px;
    }
    
    .bulk-modal-header .material-symbols-outlined {
      font-size: 20px;
    }
    
    .bulk-modal-header h5 {
      font-size: 13px;
    }
    
    .bulk-modal-close {
      width: 26px;
      height: 26px;
    }
    
    .bulk-modal-content {
      border-radius: 8px;
    }
    
    .bulk-modal-body {
      padding: 12px;
    }
    
    .bulk-form-label {
      font-size: 10px;
      margin-bottom: 5px;
    }
    
    .bulk-form-input,
    .bulk-form-select {
      font-size: 11px;
      padding: 7px 8px;
      border-radius: 6px;
    }
    
    .bulk-modal-message {
      padding: 8px 10px;
      font-size: 11px;
      gap: 4px;
    }
    
    .bulk-modal-message .material-symbols-outlined {
      font-size: 14px;
    }
    
    .bulk-preview-section {
      padding: 10px;
      margin: 12px 0;
      border-radius: 6px;
    }
    
    .bulk-preview-header {
      margin-bottom: 10px;
      gap: 6px;
    }
    
    .bulk-preview-stat {
      font-size: 11px;
      gap: 3px;
    }
    
    .bulk-preview-stat .material-symbols-outlined {
      font-size: 14px;
    }
    
    .bulk-workers-list {
      max-height: 120px;
      font-size: 10px;
    }
    
    .bulk-worker-item {
      font-size: 10px;
      padding: 5px 0;
      gap: 4px;
    }
    
    .bulk-worker-item .material-symbols-outlined {
      font-size: 14px;
    }
    
    .bulk-modal-footer {
      padding: 8px 12px;
      gap: 4px;
      flex-direction: column;
    }
    
    .bulk-modal-btn {
      padding: 7px 8px;
      font-size: 10px;
      gap: 2px;
      width: 100%;
      justify-content: center;
    }
    
    .bulk-modal-btn .material-symbols-outlined {
      font-size: 14px;
    }
  }
</style>

<div id="bulkOvertimeModal">
  <div class="bulk-modal-content">
    <!-- Modern Header with Gradient -->
    <div class="bulk-modal-header">
      <span class="material-symbols-outlined" style="fill: 1;">schedule</span>
      <h5 id="bulkOvertimeLabel">تسجيل ساعات السهر الجماعية</h5>
      <button type="button" class="bulk-modal-close" onclick="closeBulkOvertimeModal()">
        <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
      </button>
    </div>

    <div class="bulk-modal-body">
      <!-- Success Message -->
      <div class="bulk-modal-message success" id="bulkModal_success" role="alert">
        <span class="material-symbols-outlined">check_circle</span>
        <div id="bulkModal_success_text"></div>
      </div>

      <!-- Error Message -->
      <div class="bulk-modal-message error" id="bulkModal_error" role="alert">
        <span class="material-symbols-outlined">error</span>
        <div id="bulkModal_error_text"></div>
      </div>

      <!-- FORM SECTION (FIXED - No Scroll) -->
      <div class="bulk-form-wrapper">
        <form id="bulkOvertimeFormModal">
          @csrf

          <!-- Company Selection -->
          <div class="bulk-form-group">
            <label for="bulkModal_companyId" class="bulk-form-label">اختر الشركة</label>
            <select id="bulkModal_companyId" class="bulk-form-select" required>
              <option value="">-- اختر شركة --</option>
              @forelse($companies ?? [] as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
              @empty
                <option value="" disabled>لا توجد شركات</option>
              @endforelse
            </select>
          </div>

          <!-- Date Selection -->
          <div class="bulk-form-group">
            <label for="bulkModal_date" class="bulk-form-label">التاريخ</label>
            <input type="date" id="bulkModal_date" class="bulk-form-input" value="{{ date('Y-m-d') }}" required>
          </div>

          <!-- Overtime Hours -->
          <div class="bulk-form-group">
            <label for="bulkModal_hours" class="bulk-form-label">عدد ساعات السهر (0-12)</label>
            <input type="number" id="bulkModal_hours" class="bulk-form-input" min="0" max="12" step="0.5" value="0" required>
          </div>
        </form>
      </div>

      <!-- SCROLLABLE PREVIEW SECTION -->
      <div id="bulkModal_preview" class="bulk-preview-section">
        <div class="bulk-preview-header">
          <div class="bulk-preview-stat">
            <span class="material-symbols-outlined">group</span>
            <span>عدد العمال: <span id="bulkModal_workerCount">0</span> عامل</span>
          </div>
          <div class="bulk-preview-stat" id="bulkModal_totalCost">
            <span class="material-symbols-outlined">payments</span>
            <span>الإجمالي: 0 ج</span>
          </div>
        </div>
        <div class="bulk-workers-list" id="bulkModal_workersList"></div>

        <!-- Loading Spinner -->
        <div id="bulkModal_loading">
          <div class="bulk-loading">
            <div class="bulk-spinner"></div>
            <p style="color: #6B7280; font-family: 'Tajawal', sans-serif; margin: 0;">جارِ تحميل العمال...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modern Footer -->
    <div class="bulk-modal-footer">
      <button type="button" class="bulk-modal-btn bulk-modal-btn-primary" id="bulkModal_submitBtn" disabled onclick="bulkModal_submit()">
        <span class="material-symbols-outlined">check</span>
        تطبيق على الجميع
      </button>
      <button type="button" class="bulk-modal-btn bulk-modal-btn-preview" onclick="bulkModal_previewWorkers()">
        <span class="material-symbols-outlined">preview</span>
        عاين العمال
      </button>
      <button type="button" class="bulk-modal-btn bulk-modal-btn-close" onclick="closeBulkOvertimeModal()">
        <span class="material-symbols-outlined">close</span>
        إغلاق
      </button>
    </div>
  </div>
</div>

<script>
// Bulk Overtime Modal Functions
let overtimeRate = 20; // Default rate, will be updated dynamically

// Open and close modal functions
function openBulkOvertimeModal() {
  document.getElementById('bulkOvertimeModal').classList.add('show');
}

function closeBulkOvertimeModal() {
  document.getElementById('bulkOvertimeModal').classList.remove('show');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
  const modal = document.getElementById('bulkOvertimeModal');
  if (event.target === modal) {
    closeBulkOvertimeModal();
  }
});

function bulkModal_validateForm() {
  const companyId = document.getElementById('bulkModal_companyId').value;
  const date = document.getElementById('bulkModal_date').value;
  const hours = document.getElementById('bulkModal_hours').value;
  
  const isValid = companyId && date && hours;
  document.getElementById('bulkModal_submitBtn').disabled = !isValid;
}

function bulkModal_clearMessages() {
  document.getElementById('bulkModal_success').classList.remove('show');
  document.getElementById('bulkModal_error').classList.remove('show');
}

function bulkModal_showError(msg) {
  bulkModal_clearMessages();
  document.getElementById('bulkModal_error_text').textContent = msg;
  document.getElementById('bulkModal_error').classList.add('show');
}

function bulkModal_showSuccess(msg) {
  bulkModal_clearMessages();
  document.getElementById('bulkModal_success_text').textContent = msg;
  document.getElementById('bulkModal_success').classList.add('show');
}

async function bulkModal_previewWorkers() {
  const companyId = document.getElementById('bulkModal_companyId').value;
  const date = document.getElementById('bulkModal_date').value;

  if (!companyId || !date) {
    bulkModal_showError('يرجى تحديد الشركة والتاريخ أولاً');
    return;
  }

  bulkModal_clearMessages();
  document.getElementById('bulkModal_preview').classList.add('show');
  document.getElementById('bulkModal_loading').classList.add('show');
  document.getElementById('bulkModal_workersList').innerHTML = '';

  try {
    const response = await fetch(
      `/contractor/distributions/company-workers?company_id=${companyId}&date=${date}`,
      { headers: { 'Accept': 'application/json' } }
    );

    const data = await response.json();
    document.getElementById('bulkModal_loading').classList.remove('show');

    if (!data.success) {
      bulkModal_showError(data.message || 'خطأ في تحميل البيانات');
      return;
    }

    // Update overtime rate from company
    if (data.data.overtime_rate) {
      overtimeRate = parseFloat(data.data.overtime_rate);
    }

    const workers = data.data.workers || [];
    document.getElementById('bulkModal_workerCount').textContent = workers.length;

    if (workers.length === 0) {
      document.getElementById('bulkModal_workersList').innerHTML = '<p style="text-align: center; color: #6B7280; padding: 20px; font-family: \'Tajawal\', sans-serif;">لا توجد عمال موزعين في هذا اليوم</p>';
      document.getElementById('bulkModal_totalCost').innerHTML = `<span class="material-symbols-outlined">payments</span><span>الإجمالي: 0 ج</span>`;
    } else {
      const hours = parseFloat(document.getElementById('bulkModal_hours').value) || 0;
      const totalCost = workers.length * hours * overtimeRate;

      document.getElementById('bulkModal_totalCost').innerHTML = `<span class="material-symbols-outlined">payments</span><span>الإجمالي: ${totalCost.toFixed(0)} ج <small style="font-size: 11px; color: #6B7280;">(${overtimeRate} ج/ساعة)</small></span>`;
      document.getElementById('bulkModal_workersList').innerHTML = workers.map(w => 
        `<div class="bulk-worker-item">
           <span class="material-symbols-outlined">person</span>
           <span class="bulk-worker-name">${w.name}</span>
           <span class="bulk-worker-current">(حالي: ${w.current_hours || 0} ساعة)</span>
         </div>`
      ).join('');
    }
  } catch (error) {
    document.getElementById('bulkModal_loading').classList.remove('show');
    bulkModal_showError('خطأ في الاتصال بالخادم');
    console.error(error);
  }
}

async function bulkModal_submit() {
  const companyId = document.getElementById('bulkModal_companyId').value;
  const date = document.getElementById('bulkModal_date').value;
  const hours = document.getElementById('bulkModal_hours').value;
  
  if (!companyId || !date || !hours) {
    bulkModal_showError('يرجى ملء جميع الحقول');
    return;
  }

  bulkModal_clearMessages();
  document.getElementById('bulkModal_submitBtn').disabled = true;
  const originalText = document.getElementById('bulkModal_submitBtn').innerHTML;
  document.getElementById('bulkModal_submitBtn').innerHTML = '<span class="material-symbols-outlined">schedule</span>جاري الحفظ...';

  try {
    const response = await fetch('/contractor/overtime/bulk-by-company', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        company_id: companyId,
        distribution_date: date,
        overtime_hours: hours
      })
    });

    const data = await response.json();

    if (data.success) {
      bulkModal_showSuccess(data.message);
      setTimeout(() => {
        document.getElementById('bulkOvertimeFormModal').reset();
        document.getElementById('bulkModal_preview').classList.remove('show');
        document.getElementById('bulkModal_submitBtn').innerHTML = originalText;
        document.getElementById('bulkModal_submitBtn').disabled = true;
        overtimeRate = 20; // Reset to default
        closeBulkOvertimeModal();
        location.reload(); // Refresh to show updates
      }, 2000);
    } else {
      bulkModal_showError(data.message || 'خطأ في الحفظ');
      document.getElementById('bulkModal_submitBtn').innerHTML = originalText;
      document.getElementById('bulkModal_submitBtn').disabled = false;
    }
  } catch (error) {
    bulkModal_showError('خطأ في الاتصال بالخادم');
    document.getElementById('bulkModal_submitBtn').innerHTML = originalText;
    document.getElementById('bulkModal_submitBtn').disabled = false;
    console.error(error);
  }
}

// Initialize form validation
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('bulkModal_companyId').addEventListener('change', bulkModal_validateForm);
  document.getElementById('bulkModal_date').addEventListener('change', bulkModal_validateForm);
  document.getElementById('bulkModal_hours').addEventListener('input', bulkModal_validateForm);
});
</script>
