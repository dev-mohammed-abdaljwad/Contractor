<!-- Create Distribution Modal -->
<div id="createDistributionModal" class="modal hidden">
  <div class="modal-overlay"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">توزيع جديد</h2>
      <button type="button" class="modal-close" onclick="closeModal('createDistributionModal')">&times;</button>
    </div>
    
    <form id="createDistributionForm" method="POST" action="{{ route('contractor.distributions.store') }}">
      @csrf
      
      <div class="modal-body">
        <!-- Company Selection -->
        <div class="form-group">
          <label for="company_id" class="form-label">اختر الشركة <span class="required">*</span></label>
          <select id="company_id" name="company_id" class="form-select" required onchange="updateEarningsCalculation()">
            <option value="">-- اختر شركة --</option>
            @foreach($companies ?? [] as $company)
              <option value="{{ $company->id }}" data-daily-wage="{{ $company->daily_wage }}">
                {{ $company->name }} - {{ number_format($company->daily_wage) }} ج/يوم
              </option>
            @endforeach
          </select>
          <div class="error-message" id="company_id-error"></div>
        </div>

        <!-- Workers Selection -->
        <div class="form-group">
          <label for="worker_ids" class="form-label">اختر العمال <span class="required">*</span></label>
          <div class="workers-list" id="workersList">
            <!-- Populated dynamically -->
            <div style="text-align: center; padding: 20px; color: #999;">
              اختر شركة أولاً لعرض العمال المتاحين
            </div>
          </div>
          <div class="error-message" id="worker_ids-error"></div>
        </div>

        <!-- Real-time Earnings Calculation -->
        <div class="earnings-summary">
          <div class="summary-row">
            <span class="summary-label">عدد العمال:</span>
            <span class="summary-value" id="workersCount">0</span>
          </div>
          <div class="summary-row">
            <span class="summary-label">الأجر اليومي:</span>
            <span class="summary-value" id="dailyWage">0 ج</span>
          </div>
          <div class="summary-row summary-total">
            <span class="summary-label">الإجمالي:</span>
            <span class="summary-value" id="totalAmount">0 ج</span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeModal('createDistributionModal')">إلغاء</button>
        <button type="submit" class="btn-primary">تأكيد التوزيع</button>
      </div>
    </form>
  </div>
</div>

<style>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal.hidden {
  display: none;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  cursor: pointer;
}

.modal-content {
  position: relative;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  direction: rtl;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #f0f0f0;
}

.modal-title {
  font-size: 18px;
  font-weight: 700;
  color: #333;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 28px;
  color: #aaa;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s;
}

.modal-close:hover {
  background: #f0f0f0;
  color: #333;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  padding: 16px 20px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.required {
  color: #dc2626;
}

.form-select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  font-family: inherit;
  background: white;
  color: #333;
}

.form-select:focus {
  outline: none;
  border-color: #185FA5;
  box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.1);
}

.workers-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 10px;
}

.worker-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 14px;
}

.worker-checkbox:hover {
  border-color: #185FA5;
  background: rgba(24, 95, 165, 0.05);
}

.worker-checkbox input[type="checkbox"] {
  cursor: pointer;
}

.worker-checkbox input[type="checkbox"]:checked ~ span {
  color: #185FA5;
  font-weight: 600;
}

.error-message {
  color: #dc2626;
  font-size: 12px;
  margin-top: 4px;
  display: none;
}

.error-message.show {
  display: block;
}

.earnings-summary {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 14px;
  margin-top: 20px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  font-size: 13px;
}

.summary-label {
  color: #666;
  font-weight: 500;
}

.summary-value {
  font-weight: 600;
  color: #333;
}

.summary-total {
  border-top: 2px solid #e5e7eb;
  padding-top: 12px;
  margin-top: 8px;
  font-size: 15px;
}

.summary-total .summary-value {
  color: #059669;
  font-size: 16px;
}

.btn-primary, .btn-secondary {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background: linear-gradient(135deg, #185FA5 0%, #1D9E75 100%);
  color: white;
}

.btn-primary:hover {
  opacity: 0.9;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(24, 95, 165, 0.3);
}

.btn-secondary {
  background: #f3f4f6;
  color: #333;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

@media (max-width: 640px) {
  .modal-content {
    width: 95%;
    max-height: 95vh;
  }
  
  .workers-list {
    grid-template-columns: 1fr;
  }
}
</style>

<script>
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }
}

async function loadAvailableWorkers() {
  const today = new Date().toISOString().split('T')[0];
  try {
    const response = await fetch(`/contractor/distributions/available-workers?date=${today}`, {
      headers: {
        'Accept': 'application/json'
      }
    });
    const result = await response.json();
    
    if (result.success) {
      populateWorkersList(result.data);
    }
  } catch (error) {
    console.error('Error loading available workers:', error);
    document.getElementById('workersList').innerHTML = 
      '<div style="text-align: center; padding: 20px; color: #dc2626;">خطأ في تحميل قائمة العمال</div>';
  }
}

function populateWorkersList(workers) {
  const workersList = document.getElementById('workersList');
  
  if (workers.length === 0) {
    workersList.innerHTML = 
      '<div style="text-align: center; padding: 20px; color: #999;">جميع العمال مسجلين بالفعل لهذا اليوم</div>';
    return;
  }

  workersList.innerHTML = workers.map(worker => `
    <label class="worker-checkbox">
      <input type="checkbox" name="worker_ids[]" value="${worker.id}" 
             onchange="updateEarningsCalculation()">
      <span>${worker.name}</span>
    </label>
  `).join('');
}

function updateEarningsCalculation() {
  const companySelect = document.getElementById('company_id');
  const selectedOption = companySelect.options[companySelect.selectedIndex];
  const dailyWage = parseInt(selectedOption.dataset.dailyWage) || 0;

  const checkedWorkers = document.querySelectorAll('input[name="worker_ids[]"]:checked');
  const workersCount = checkedWorkers.length;
  const totalAmount = workersCount * dailyWage;

  document.getElementById('workersCount').textContent = workersCount;
  document.getElementById('dailyWage').textContent = dailyWage > 0 ? number_format(dailyWage) + ' ج' : '0 ج';
  document.getElementById('totalAmount').textContent = number_format(totalAmount) + ' ج';
}

function number_format(num) {
  return new Intl.NumberFormat('ar-EG').format(num);
}

// Close modal when clicking overlay
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('modal-overlay')) {
    closeModal(event.target.parentElement.id);
  }
});

// Handle company selection
document.getElementById('company_id').addEventListener('change', function() {
  if (this.value) {
    loadAvailableWorkers();
  } else {
    document.getElementById('workersList').innerHTML = 
      '<div style="text-align: center; padding: 20px; color: #999;">اختر شركة أولاً لعرض العمال المتاحين</div>';
  }
  updateEarningsCalculation();
});

// Handle modal form submission
document.getElementById('createDistributionForm')?.addEventListener('submit', function(e) {
  const companyId = document.getElementById('company_id').value;
  const checkedWorkers = document.querySelectorAll('input[name="worker_ids[]"]:checked');

  let hasErrors = false;

  // Validate company
  if (!companyId) {
    document.getElementById('company_id-error').textContent = 'اختر شركة';
    document.getElementById('company_id-error').classList.add('show');
    hasErrors = true;
  } else {
    document.getElementById('company_id-error').classList.remove('show');
  }

  // Validate workers
  if (checkedWorkers.length === 0) {
    document.getElementById('worker_ids-error').textContent = 'اختر عاملاً واحداً على الأقل';
    document.getElementById('worker_ids-error').classList.add('show');
    hasErrors = true;
  } else {
    document.getElementById('worker_ids-error').classList.remove('show');
  }

  if (hasErrors) {
    e.preventDefault();
  }
});

// Initialize on modal open
document.getElementById('createDistributionModal')?.addEventListener('focusin', function() {
  const companySelect = document.getElementById('company_id');
  if (companySelect.value) {
    loadAvailableWorkers();
  }
}, true);
</script>
