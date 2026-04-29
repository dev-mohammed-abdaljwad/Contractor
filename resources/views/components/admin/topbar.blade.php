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
    <div class="topbar-add-btn" onclick="openAddContractorModal()">+ مقاول جديد</div>
  </div>
</div>

<!-- Add Contractor Modal -->
<div class="tb-modal-overlay" id="addContractorModal" onclick="if(event.target===this) closeAddContractorModal()">
  <div class="tb-modal">
    <div class="tb-modal-handle"></div>
    <div class="tb-modal-head">
      <div class="tb-modal-title">إضافة مقاول جديد</div>
      <button class="tb-modal-close" onclick="closeAddContractorModal()">×</button>
    </div>
    <div class="tb-modal-body">
      <form id="addContractorForm" onsubmit="submitAddContractor(event)">
        <div class="tb-field">
          <label class="tb-label">الاسم بالكامل</label>
          <div class="tb-name-row">
            <div class="tb-input-wrap">
              <input type="text" name="first_name" placeholder="الاسم الأول" required class="tb-input"/>
            </div>
            <div class="tb-input-wrap">
              <input type="text" name="last_name" placeholder="اسم العائلة" required class="tb-input"/>
            </div>
          </div>
        </div>
        <div class="tb-field">
          <label class="tb-label">البريد الإلكتروني</label>
          <div class="tb-input-wrap">
            <input type="email" name="email" placeholder="example@mail.com" required class="tb-input"/>
          </div>
        </div>
        <div class="tb-field">
          <label class="tb-label">رقم التليفون</label>
          <div class="tb-input-wrap">
            <input type="text" name="phone" placeholder="01X XXXX XXXX" required class="tb-input"/>
          </div>
        </div>
        <div class="tb-field">
          <label class="tb-label">كلمة السر</label>
          <div class="tb-input-wrap">
            <input type="password" name="password" placeholder="••••••••" required minlength="8" class="tb-input"/>
          </div>
        </div>
        <div class="tb-field">
          <label class="tb-label">خطة الاشتراك</label>
          <div class="tb-plan-grid">
            <div class="tb-plan-card tb-sel-free" onclick="selectAddContractorPlan(this,'free')"><div class="tb-plan-icon">🆓</div><div class="tb-plan-name">مجاني</div></div>
            <div class="tb-plan-card" onclick="selectAddContractorPlan(this,'pro')"><div class="tb-plan-icon">⭐</div><div class="tb-plan-name">Pro</div></div>
            <div class="tb-plan-card" onclick="selectAddContractorPlan(this,'enterprise')"><div class="tb-plan-icon">👑</div><div class="tb-plan-name">Enterprise</div></div>
          </div>
        </div>
        <input type="hidden" id="selectedAddContractorPlan" value="free"/>
        <div class="tb-modal-actions">
          <button type="button" class="tb-btn-cancel" onclick="closeAddContractorModal()">إلغاء</button>
          <button type="submit" class="tb-btn-submit">إضافة مقاول</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* ── Topbar Add Button ── */
.topbar-add-btn {
  background: #0a4f14;
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 7px 14px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}
.topbar-add-btn:hover { background: #1D9E75; transform: translateY(-1px); }

/* ── Topbar Modal ── */
.tb-modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  z-index: 1000;
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.tb-modal-overlay.open { display: flex; }

.tb-modal-handle { display: none; }

.tb-modal {
  background: #fff;
  border-radius: 24px;
  width: 100%;
  max-width: 460px;
  overflow: hidden;
  box-shadow: 0 24px 48px rgba(0,0,0,0.18);
  animation: tbModalIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes tbModalIn {
  from { opacity: 0; transform: translateY(24px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0)    scale(1);    }
}

.tb-modal-head {
  background: linear-gradient(135deg, #0a4f14, #1D9E75);
  padding: 20px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.tb-modal-title { color: #fff; font-size: 16px; font-weight: 800; }

.tb-modal-close {
  background: rgba(255,255,255,0.15);
  border: none;
  border-radius: 10px;
  width: 32px;
  height: 32px;
  color: #fff;
  cursor: pointer;
  font-size: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  line-height: 1;
}
.tb-modal-close:hover { background: rgba(255,255,255,0.28); transform: rotate(90deg); }

.tb-modal-body { padding: 24px; }

/* ── Fields ── */
.tb-field { margin-bottom: 16px; }
.tb-label {
  display: block;
  font-size: 13px;
  font-weight: 800;
  color: #1a1c19;
  margin-bottom: 8px;
  text-align: right;
}

.tb-name-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.tb-input-wrap { position: relative; }

.tb-input {
  width: 100%;
  height: 46px;
  padding: 0 16px;
  border: 2px solid #e0e4db;
  border-radius: 12px;
  font-family: 'Tajawal', sans-serif;
  font-size: 13px;
  color: #1a1c19;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  background: #fafaf7;
  direction: ltr;
  text-align: left;
  box-sizing: border-box;
}
.tb-input:focus {
  border-color: #0a4f14;
  background: #fff;
  box-shadow: 0 0 0 4px rgba(10, 79, 20, 0.1);
}

/* ── Plan Cards ── */
.tb-plan-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}

.tb-plan-card {
  border: 2px solid #e0e4db;
  border-radius: 14px;
  padding: 14px 8px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  background: #fafaf7;
}
.tb-plan-card:hover { border-color: #0a4f14; background: #f0faf2; }
.tb-plan-card.tb-sel-free       { border-color: #707a6c; background: #f1f3f0; }
.tb-plan-card.tb-sel-pro        { border-color: #185fa5; background: #e3f2fd; }
.tb-plan-card.tb-sel-enterprise { border-color: #7c3aed; background: #f3e5f5; }

.tb-plan-icon { font-size: 28px; margin-bottom: 6px; }
.tb-plan-name { font-size: 11px; font-weight: 700; }

/* ── Actions ── */
.tb-modal-actions { display: flex; gap: 12px; margin-top: 20px; }

.tb-btn-cancel {
  flex: 1;
  height: 46px;
  background: #f1f3f0;
  border: none;
  border-radius: 12px;
  font-family: 'Tajawal', sans-serif;
  font-weight: 700;
  font-size: 14px;
  color: #43493e;
  cursor: pointer;
  transition: background 0.2s;
}
.tb-btn-cancel:hover { background: #e0e4db; }

.tb-btn-submit {
  flex: 1.5;
  height: 46px;
  background: linear-gradient(135deg, #0a4f14, #1D9E75);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-family: 'Tajawal', sans-serif;
  font-weight: 800;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(10, 79, 20, 0.25);
  transition: all 0.2s;
}
.tb-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(10, 79, 20, 0.3); }

/* ═══ RESPONSIVE ═══ */

/* ── Tablet ── */
@media (max-width: 1024px) {
  .topbar-add-btn { font-size: 11px; padding: 6px 12px; }
  .tb-modal { max-width: 420px; }
  .tb-modal-head { padding: 16px 20px; }
  .tb-modal-body { padding: 20px; }
  .tb-input { height: 42px; font-size: 12px; }
  .tb-btn-cancel, .tb-btn-submit { height: 42px; font-size: 13px; }
}

/* ── Mobile ── */
@media (max-width: 767px) {
  .topbar-add-btn { font-size: 10px; padding: 5px 10px; border-radius: 8px; }

  .tb-modal-overlay {
    padding: 0;
    align-items: flex-end;
  }

  .tb-modal {
    max-width: 100%;
    width: 100%;
    border-radius: 24px 24px 0 0;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    animation: tbModalSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  }

  @keyframes tbModalSlideUp {
    from { opacity: 0; transform: translateY(100%); }
    to   { opacity: 1; transform: translateY(0);    }
  }

  .tb-modal-handle {
    display: block;
    width: 36px;
    height: 4px;
    background: #d0d0d0;
    border-radius: 2px;
    margin: 10px auto 0;
    flex-shrink: 0;
  }

  .tb-modal-head {
    padding: 14px 16px;
    border-radius: 0;
    flex-shrink: 0;
  }
  .tb-modal-title { font-size: 14px; }
  .tb-modal-close { width: 28px; height: 28px; font-size: 16px; }

  .tb-modal-body {
    padding: 16px 14px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
    min-height: 0;
  }

  .tb-name-row { grid-template-columns: 1fr; gap: 10px; }

  .tb-field { margin-bottom: 12px; }
  .tb-label { font-size: 12px; margin-bottom: 6px; }
  .tb-input { height: 42px; font-size: 12px; border-radius: 10px; border-width: 1.5px; }
  .tb-input:focus { box-shadow: 0 0 0 3px rgba(10, 79, 20, 0.08); }

  .tb-plan-grid { gap: 8px; }
  .tb-plan-card { padding: 10px 6px; border-radius: 10px; border-width: 1.5px; }
  .tb-plan-icon { font-size: 22px; margin-bottom: 3px; }
  .tb-plan-name { font-size: 10px; }

  .tb-modal-actions {
    gap: 10px;
    margin-top: 16px;
    position: sticky;
    bottom: 0;
    background: #fff;
    padding: 12px 0 env(safe-area-inset-bottom, 0) 0;
  }
  .tb-btn-cancel, .tb-btn-submit { height: 42px; font-size: 12px; border-radius: 10px; }
}

/* ── Small Mobile ── */
@media (max-width: 480px) {
  .topbar-add-btn { font-size: 9px; padding: 4px 8px; }
  .tb-modal { max-height: 95vh; border-radius: 20px 20px 0 0; }
  .tb-modal-head { padding: 12px 14px; }
  .tb-modal-title { font-size: 13px; }
  .tb-modal-body { padding: 14px 12px; }
  .tb-field { margin-bottom: 10px; }
  .tb-label { font-size: 11px; }
  .tb-input { height: 40px; font-size: 11px; border-radius: 8px; }
  .tb-plan-card { padding: 8px 4px; border-radius: 8px; }
  .tb-plan-icon { font-size: 18px; margin-bottom: 2px; }
  .tb-plan-name { font-size: 9px; }
  .tb-btn-cancel, .tb-btn-submit { height: 40px; font-size: 11px; border-radius: 8px; }
}
</style>

<script>
function openAddContractorModal() {
  const modal = document.getElementById('addContractorModal');
  if (modal) modal.classList.add('open');
}

function closeAddContractorModal() {
  const modal = document.getElementById('addContractorModal');
  const form = document.getElementById('addContractorForm');
  const planInput = document.getElementById('selectedAddContractorPlan');
  
  if (modal) modal.classList.remove('open');
  if (form) form.reset();
  if (planInput) planInput.value = 'free';
  
  document.querySelectorAll('.tb-plan-card').forEach(card => {
    card.classList.remove('tb-sel-free', 'tb-sel-pro', 'tb-sel-enterprise');
  });
  
  const defaultPlan = document.querySelector('.tb-plan-card');
  if (defaultPlan) defaultPlan.classList.add('tb-sel-free');
}

function selectAddContractorPlan(element, plan) {
  document.querySelectorAll('.tb-plan-card').forEach(card => {
    card.classList.remove('tb-sel-free', 'tb-sel-pro', 'tb-sel-enterprise');
  });
  if (element) element.classList.add('tb-sel-' + plan);
  const planInput = document.getElementById('selectedAddContractorPlan');
  if (planInput) planInput.value = plan;
}

async function submitAddContractor(event) {
  event.preventDefault();

  const form = document.getElementById('addContractorForm');
  if (!form) return;
  
  const formData = new FormData(form);
  const planInput = document.getElementById('selectedAddContractorPlan');
  if (planInput) formData.append('plan', planInput.value);

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
      showToast(data.message || 'تم إضافة المقاول بنجاح', 'success');
      closeAddContractorModal();
      setTimeout(() => { window.location.reload(); }, 1500);
    } else {
      showToast(data.error || 'حدث خطأ ما', 'error');
    }
  } catch (error) {
    console.error('[Add Contractor] Error:', error);
    showToast('فشل إضافة المقاول. حاول مرة أخرى.', 'error');
  }
}

function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    bottom: 24px;
    right: 50%;
    transform: translateX(50%);
    background: ${type === 'success' ? '#0a4f14' : '#dc2626'};
    color: #fff;
    padding: 12px 24px;
    border-radius: 12px;
    z-index: 2000;
    font-family: Tajawal, sans-serif;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    max-width: calc(100vw - 32px);
    text-align: center;
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => { toast.remove(); }, 3000);
}

document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeAddContractorModal();
  }
});
</script>

