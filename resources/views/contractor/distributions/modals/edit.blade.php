<!-- Edit Distribution Modal -->
<div id="editDistributionModal" class="modal hidden">
  <div class="modal-overlay"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">تعديل التوزيع</h2>
      <button type="button" class="modal-close" onclick="closeModal('editDistributionModal')">&times;</button>
    </div>
    
    <form id="editDistributionForm" method="POST">
      @method('PUT')
      @csrf
      
      <div class="modal-body">
        <!-- Company Info (readonly) -->
        <div class="form-group">
          <label class="form-label">الشركة</label>
          <div class="company-info">
            <span id="editCompanyName"></span>
            <span id="editDailyWage" class="wage-badge"></span>
          </div>
        </div>

        <!-- Workers Selection -->
        <div class="form-group">
          <label for="edit_worker_ids" class="form-label">اختر العمال <span class="required">*</span></label>
          <div id="editWorkersList" class="workers-list">
            <!-- Populated dynamically -->
          </div>
          <div class="error-message" id="edit_worker_ids-error"></div>
        </div>

        <!-- Real-time Earnings Calculation -->
        <div class="earnings-summary">
          <div class="summary-row">
            <span class="summary-label">عدد العمال:</span>
            <span class="summary-value" id="editWorkersCount">0</span>
          </div>
          <div class="summary-row">
            <span class="summary-label">الأجر اليومي:</span>
            <span class="summary-value" id="editDailyWageAmount">0 ج</span>
          </div>
          <div class="summary-row summary-total">
            <span class="summary-label">الإجمالي:</span>
            <span class="summary-value" id="editTotalAmount">0 ج</span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeModal('editDistributionModal')">إلغاء</button>
        <button type="submit" class="btn-primary">حفظ التغييرات</button>
      </div>
    </form>
  </div>
</div>

<style>
.company-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
}

.wage-badge {
  background: #dbeafe;
  color: #185FA5;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

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
function openEditModal(distributionId, companyName, dailyWage, workers, assignedWorkerIds) {
  const form = document.getElementById('editDistributionForm');
  form.action = `/contractor/distributions/${distributionId}`;

  // Set company info
  document.getElementById('editCompanyName').textContent = companyName;
  document.getElementById('editDailyWage').textContent = number_format(dailyWage) + ' ج/يوم';
  document.getElementById('editDailyWageAmount').textContent = number_format(dailyWage) + ' ج';

  // Load all available workers and current assigned workers
  loadEditAvailableWorkers(distributionId, workers, assignedWorkerIds, dailyWage);
  
  openModal('editDistributionModal');
}

async function loadEditAvailableWorkers(distributionId, allWorkers, assignedWorkerIds, dailyWage) {
  const today = new Date().toISOString().split('T')[0];
  
  try {
    const response = await fetch(`/contractor/distributions/assigned-workers?date=${today}`, {
      headers: {
        'Accept': 'application/json'
      }
    });
    const result = await response.json();
    
    if (result.success) {
      const assignedByOtherDistributions = result.data
        .filter(item => item.id !== distributionId) // Exclude current distribution
        .map(item => item.worker_id);
      
      populateEditWorkersList(allWorkers, assignedWorkerIds, assignedByOtherDistributions, dailyWage);
    }
  } catch (error) {
    console.error('Error loading workers:', error);
    populateEditWorkersList(allWorkers, assignedWorkerIds, [], dailyWage);
  }
}

function populateEditWorkersList(allWorkers, assignedWorkerIds, assignedByOtherDistributions, dailyWage) {
  const workersList = document.getElementById('editWorkersList');
  workersList.innerHTML = '';
  
  allWorkers.forEach(worker => {
    const isCurrentlyAssigned = assignedWorkerIds.includes(worker.id);
    const isAssignedToOther = assignedByOtherDistributions.includes(worker.id);
    const isDisabled = isAssignedToOther && !isCurrentlyAssigned;
    
    const label = document.createElement('label');
    label.className = 'worker-checkbox' + (isDisabled ? ' disabled' : '');
    label.innerHTML = `
      <input type="checkbox" name="worker_ids[]" value="${worker.id}" 
             ${isCurrentlyAssigned ? 'checked' : ''} 
             ${isDisabled ? 'disabled' : ''}
             onchange="updateEditEarnings(${dailyWage})">
      <span>${worker.name}${isDisabled ? ' (مسجل بشركة أخرى)' : ''}</span>
    `;
    workersList.appendChild(label);
  });

  updateEditEarnings(dailyWage);
}

function updateEditEarnings(dailyWage) {
  const checkedWorkers = document.querySelectorAll('#editWorkersList input[type="checkbox"]:checked');
  const workersCount = checkedWorkers.length;
  const totalAmount = workersCount * dailyWage;

  document.getElementById('editWorkersCount').textContent = workersCount;
  document.getElementById('editTotalAmount').textContent = number_format(totalAmount) + ' ج';
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

// Handle edit form submission
document.getElementById('editDistributionForm')?.addEventListener('submit', function(e) {
  const checkedWorkers = document.querySelectorAll('#editWorkersList input[type="checkbox"]:checked');

  // Validate workers
  if (checkedWorkers.length === 0) {
    document.getElementById('edit_worker_ids-error').textContent = 'اختر عاملاً واحداً على الأقل';
    document.getElementById('edit_worker_ids-error').classList.add('show');
    e.preventDefault();
  } else {
    document.getElementById('edit_worker_ids-error').classList.remove('show');
  }
});
</script>

<style>
.worker-checkbox.disabled {
  opacity: 0.6;
  cursor: not-allowed;
  background: #f0f0f0;
}

.worker-checkbox.disabled input[type="checkbox"]:disabled {
  cursor: not-allowed;
}

.worker-checkbox.disabled:hover {
  border-color: #e5e7eb;
  background: #f0f0f0;
}

/* Rest of the existing styles... */
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

.company-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
}

.wage-badge {
  background: #dbeafe;
  color: #185FA5;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
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
