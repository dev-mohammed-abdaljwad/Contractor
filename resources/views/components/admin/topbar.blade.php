<div class="topbar">
  <div>
    <div class="topbar-title">@yield('topbar-title', 'لوحة المتابعة')</div>
    <div class="topbar-sub">{{ now()->locale('ar')->translatedFormat('l، j F Y') }} · آخر تحديث: منذ دقائق</div>
  </div>
  <div class="topbar-actions">
    <div class="search-bar">
      🔍 <span>بحث...</span>
    </div>
    <div class="notif-btn">
      🔔
      <div class="notif-dot"></div>
    </div>
    <div style="background:#0a4f14;color:#fff;font-size:12px;font-weight:700;padding:7px 14px;border-radius:10px;cursor:pointer;" onclick="openAddContractorModal()">+ مقاول جديد</div>
  </div>
</div>

<!-- Add Contractor Modal -->
<div class="modal-overlay" id="addContractorModal" onclick="if(event.target===this) closeAddContractorModal()">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title">إضافة مقاول جديد</div>
      <button class="modal-close" onclick="closeAddContractorModal()">×</button>
    </div>
    <div class="modal-body">
      <form id="addContractorForm" onsubmit="submitAddContractor(event)">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
          <input type="text" name="first_name" placeholder="الاسم الأول" required style="padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px;"/>
          <input type="text" name="last_name" placeholder="اسم العائلة" required style="padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px;"/>
        </div>
        <input type="text" name="phone" placeholder="رقم التليفون" required style="width: 100%; padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px; margin-bottom: 10px;"/>
        <input type="password" name="password" placeholder="كلمة السر" required minlength="8" style="width: 100%; padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px; margin-bottom: 12px;"/>
        <div style="margin-bottom: 12px;">
          <div style="font-size: 11px; font-weight: 700; color: #888; margin-bottom: 8px;">خطة الاشتراك</div>
          <div class="plan-grid">
            <div class="plan-card sel-free" onclick="selectAddContractorPlan(this,'free')"><div class="plan-icon">🆓</div><div style="font-size: 11px;">مجاني</div></div>
            <div class="plan-card" onclick="selectAddContractorPlan(this,'pro')"><div class="plan-icon">⭐</div><div style="font-size: 11px;">Pro</div></div>
            <div class="plan-card" onclick="selectAddContractorPlan(this,'enterprise')"><div class="plan-icon">👑</div><div style="font-size: 11px;">Enterprise</div></div>
          </div>
        </div>
        <input type="hidden" id="selectedAddContractorPlan" value="free"/>
        <div class="modal-actions">
          <button type="button" class="btn-cancel" onclick="closeAddContractorModal()">إلغاء</button>
          <button type="submit" class="btn-submit">إضافة مقاول</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* Modal */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 100;
  align-items: center;
  justify-content: center;
  padding: 16px;
}

.modal-overlay.open { display: flex; }

.modal {
  background: #fff;
  border-radius: 16px;
  width: 100%;
  max-width: 400px;
  overflow: hidden;
}

.modal-head {
  background: linear-gradient(135deg, #0a4f14, #1D9E75);
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-title { color: #fff; font-size: 14px; font-weight: 700; }

.modal-close {
  background: rgba(255,255,255,0.2);
  border: none;
  border-radius: 6px;
  width: 24px;
  height: 24px;
  color: #fff;
  cursor: pointer;
  font-size: 20px;
}

.modal-body { padding: 20px; }

.plan-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-bottom: 16px;
}

.plan-card {
  border: 2px solid #e8e8e0;
  border-radius: 10px;
  padding: 12px 8px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 12px;
}

.plan-card:hover { border-color: #0a4f14; }
.plan-card.sel-free { border-color: #888; background: #f5f6f0; }
.plan-card.sel-pro { border-color: #185fa5; background: #eff6ff; }
.plan-card.sel-enterprise { border-color: #7c3aed; background: #fdf4ff; }

.plan-icon { font-size: 24px; margin-bottom: 6px; }

.modal-actions {
  display: flex;
  gap: 8px;
}

.btn-cancel {
  flex: 1;
  padding: 10px;
  background: #f0f0e8;
  border: none;
  border-radius: 8px;
  font-family: Tajawal, sans-serif;
  font-weight: 600;
  cursor: pointer;
}

.btn-submit {
  flex: 1;
  padding: 10px;
  background: #0a4f14;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-family: Tajawal, sans-serif;
  font-weight: 700;
  cursor: pointer;
}

.btn-submit:hover { background: #1D9E75; }
</style>

<script>
/**
 * Open add contractor modal
 */
function openAddContractorModal() {
  document.getElementById('addContractorModal').classList.add('open');
}

/**
 * Close add contractor modal
 */
function closeAddContractorModal() {
  document.getElementById('addContractorModal').classList.remove('open');
  document.getElementById('addContractorForm').reset();
  document.getElementById('selectedAddContractorPlan').value = 'free';
  // Reset plan selection
  document.querySelectorAll('.plan-card').forEach(card => card.classList.remove('sel-free', 'sel-pro', 'sel-enterprise'));
  document.querySelector('.plan-card').classList.add('sel-free');
}

/**
 * Select plan for add contractor
 */
function selectAddContractorPlan(element, plan) {
  // Remove all selections
  document.querySelectorAll('.plan-card').forEach(card => {
    card.classList.remove('sel-free', 'sel-pro', 'sel-enterprise');
  });
  // Add selection to clicked
  element.classList.add('sel-' + plan);
  document.getElementById('selectedAddContractorPlan').value = plan;
}

/**
 * Submit add contractor form
 */
async function submitAddContractor(event) {
  event.preventDefault();

  const form = document.getElementById('addContractorForm');
  const formData = new FormData(form);
  formData.append('plan', document.getElementById('selectedAddContractorPlan').value);

  try {
    const response = await fetch('{{ route("admin.contractors.store") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
      },
      body: formData,
    });

    const data = await response.json();

    if (response.ok) {
      // Show toast message
      showToast(data.message || 'تم إضافة المقاول بنجاح', 'success');
      closeAddContractorModal();
      // Reload page after 1.5 seconds
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(data.error || 'حدث خطأ ما', 'error');
    }
  } catch (error) {
    console.error('[Add Contractor] Error:', error);
    showToast('فشل إضافة المقاول. حاول مرة أخرى.', 'error');
  }
}

/**
 * Show toast message
 */
function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    bottom: 24px;
    right: 50%;
    transform: translateX(50%);
    background: ${type === 'success' ? '#0a4f14' : '#dc2626'};
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    z-index: 1000;
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

/**
 * Close modal with Escape key
 */
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeAddContractorModal();
  }
});
</script>
