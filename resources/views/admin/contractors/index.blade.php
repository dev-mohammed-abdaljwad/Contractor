                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    @extends('layouts.admin-dashboard')

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @section('title', 'المقاولون')                                                                                                                                                                                                                                                              
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            @section('topbar-title', 'المقاولون')

@section('content')

<!-- Stats Strip -->
<div class="stats-strip">
  <div class="stat-card"><div class="stat-val sv-green">{{ $stats['total_contractors'] }}</div><div class="stat-lbl">إجمالي المقاولين</div></div>
  <div class="stat-card"><div class="stat-val sv-green">{{ $stats['active_contractors'] }}</div><div class="stat-lbl">نشطون</div></div>
  <div class="stat-card"><div class="stat-val sv-red">{{ $stats['inactive_contractors'] }}</div><div class="stat-lbl">موقوفون</div></div>
  <div class="stat-card"><div class="stat-val sv-blue">{{ $stats['pro_contractors'] }}</div><div class="stat-lbl">Pro</div></div>
  <div class="stat-card"><div class="stat-val sv-purple">{{ $stats['enterprise_contractors'] }}</div><div class="stat-lbl">Enterprise</div></div>
                                                                                                                                                                            </div>

<!-- Filter Bar -->
<div class="filter-bar">
  <div class="chip chip-active" onclick="setFilter(this, 'all')">الكل <span class="chip-count">{{ $stats['total_contractors'] }}</span></div>
  <div class="chip chip-idle" onclick="setFilter(this, 'active')">نشط <span class="chip-count">{{ $stats['active_contractors'] }}</span></div>
  <div class="chip chip-idle" onclick="setFilter(this, 'inactive')">موقوف <span class="chip-count">{{ $stats['inactive_contractors'] }}</span></div>
  <div class="chip chip-idle" onclick="setFilter(this, 'pro')">Pro <span class="chip-count">{{ $stats['pro_contractors'] }}</span></div>
  <div class="chip chip-idle" onclick="setFilter(this, 'enterprise')">Enterprise <span class="chip-count">{{ $stats['enterprise_contractors'] }}</span></div>
  <select class="sort-sel" id="sortSelect" onchange="changeSort(this.value)">
    <option value="recent">ترتيب: الأحدث</option>
    <option value="name">ترتيب: الاسم</option>
    <option value="workers">ترتيب: عدد العمال</option>
    <option value="activity">ترتيب: آخر نشاط</option>
  </select>
</div>

<!-- Contractors Grid -->
<div class="contractors-grid">
  @forelse($contractors as $contractor)
    @php
      $borderClass = $contractor['status'] === 'active' ? 'bdr-green' : 'bdr-red';
      $avClass = $contractor['plan'] === 'pro' ? 'av-g' : ($contractor['plan'] === 'enterprise' ? 'av-b' : 'av-a');
    @endphp
    
    <div class="contractor-card {{ $borderClass }}" style="{{ $contractor['status'] === 'inactive' ? 'opacity:.7;' : '' }}">
      <div class="card-header">
        <div class="co-av {{ $avClass }}">{{ $contractor['avatar_initials'] }}</div>
        <div class="co-info">
          <div class="co-name">{{ $contractor['name'] }}</div>
          <div style="font-size:11px;color:#aaa;">{{ $contractor['email'] }}</div>
          <div class="co-badges">
            <span class="pill {{ $contractor['status'] === 'active' ? 'pill-active' : 'pill-inactive' }}">{{ $contractor['status'] === 'active' ? 'نشط' : 'موقوف' }}</span>
            <span class="pill {{ $contractor['plan'] === 'pro' ? 'pill-pro' : ($contractor['plan'] === 'enterprise' ? 'pill-ent' : 'pill-free') }}">{{ ucfirst($contractor['plan']) }}</span>
            <span class="pill pill-city">{{ $contractor['city'] }}</span>
          </div>
        </div>
        <div class="card-id">#{{ str_pad($contractor['id'], 3, '0', STR_PAD_LEFT) }}</div>
      </div>

      <div class="card-stats">
        <div class="stat"><span class="stat-val">{{ $contractor['workers_count'] }}</span><span class="stat-label">عامل</span></div>
        <div class="stat"><span class="stat-val">{{ $contractor['companies_count'] }}</span><span class="stat-label">شركة</span></div>
        <div class="stat"><span class="stat-val">{{ number_format($contractor['collection_amount'], 0) }}</span><span class="stat-label">ج</span></div>
      </div>

      <div class="card-actions">
        <button class="btn btn-view" onclick="viewContractor({{ $contractor['id'] }})">عرض</button>
        <button class="btn btn-plan" onclick="openModal('plan', '{{ $contractor['id'] }}', '{{ $contractor['name'] }}')">الخطة</button>
        <button class="btn btn-toggle" onclick="openModal('status', '{{ $contractor['id'] }}', '{{ $contractor['name'] }}', '{{ $contractor['status'] }}')">الحالة</button>
      </div>
    </div>
  @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 48px 24px; background: #fff; border-radius: 16px; border: 1px solid #e8e8e0;">
      <div style="font-size: 18px; font-weight: 700; color: #555; margin-bottom: 8px;">لا توجد مقاولين</div>
    </div>
  @endforelse
</div>

<!-- Modal -->
<div class="modal-overlay" id="modal" onclick="if(event.target===this) closeModal()">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modalTitle"></div>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <!-- Plan change form -->
      <form id="planForm" style="display:none;" onsubmit="submitPlan(event)">
        <div class="plan-grid">
          <div class="plan-card sel-free" onclick="selectPlan(this,'free')"><div class="plan-icon">🆓</div><div>مجاني</div><div style="font-size:10px;color:#aaa;">20 عامل</div></div>
          <div class="plan-card" onclick="selectPlan(this,'pro')"><div class="plan-icon">⭐</div><div>Pro</div><div style="font-size:10px;color:#aaa;">100 عامل</div></div>
          <div class="plan-card" onclick="selectPlan(this,'enterprise')"><div class="plan-icon">👑</div><div>Enterprise</div><div style="font-size:10px;color:#aaa;">غير محدود</div></div>
        </div>
        <input type="hidden" id="contractorId" value=""/>
        <input type="hidden" id="selectedPlan" value="free"/>
        <div class="modal-actions">
          <button type="button" class="btn-cancel" onclick="closeModal()">إلغاء</button>
          <button type="submit" class="btn-submit">حفظ</button>
        </div>
      </form>

      <!-- Status change form -->
      <form id="statusForm" style="display:none;" onsubmit="submitStatus(event)">
        <div style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 20px;">
          <div class="status-card sel-active" onclick="selectStatus(this,'active')" style="padding: 14px; border: 2px solid #e8e8e0; border-radius: 10px; cursor: pointer; text-align: center; font-weight: 700; transition: all 0.2s;">
            <div style="font-size: 24px; margin-bottom: 8px;">✅</div>
            <div style="color: #065f46; font-size: 13px;">نشط</div>
          </div>
          <div class="status-card" onclick="selectStatus(this,'inactive')" style="padding: 14px; border: 2px solid #e8e8e0; border-radius: 10px; cursor: pointer; text-align: center; font-weight: 700; transition: all 0.2s;">
            <div style="font-size: 24px; margin-bottom: 8px;">⏸️</div>
            <div style="color: #991b1b; font-size: 13px;">متوقف</div>
          </div>
          <div class="status-card" onclick="selectStatus(this,'other')" style="padding: 14px; border: 2px solid #e8e8e0; border-radius: 10px; cursor: pointer; text-align: center; font-weight: 700; transition: all 0.2s;">
            <div style="font-size: 24px; margin-bottom: 8px;">❓</div>
            <div style="color: #92400e; font-size: 13px;">آخر</div>
          </div>
        </div>
        <input type="hidden" id="contractorIdStatus" value=""/>
        <input type="hidden" id="selectedStatus" value="active"/>
        <div class="modal-actions">
          <button type="button" class="btn-cancel" onclick="closeModal()">إلغاء</button>
          <button type="submit" class="btn-submit">حفظ</button>
        </div>
      </form>

      <!-- Add contractor form -->
      <form id="addForm" style="display:none;" onsubmit="submitAdd(event)">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
          <input type="text" name="first_name" placeholder="الاسم الأول" required style="padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px;"/>
          <input type="text" name="last_name" placeholder="اسم العائلة" required style="padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px;"/>
        </div>
        <input type="text" name="phone" placeholder="رقم التليفون" required style="width: 100%; padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px; margin-bottom: 10px;"/>
        <input type="password" name="password" placeholder="كلمة السر" required style="width: 100%; padding: 8px; border: 1px solid #e8e8e0; border-radius: 8px; font-family: Tajawal; font-size: 12px; margin-bottom: 12px;"/>
        <div style="margin-bottom: 12px;">
          <div style="font-size: 11px; font-weight: 700; color: #888; margin-bottom: 8px;">خطة الاشتراك</div>
          <div class="plan-grid">
            <div class="plan-card sel-free" onclick="selectAddPlan(this,'free')"><div class="plan-icon">🆓</div><div style="font-size: 11px;">مجاني</div></div>
            <div class="plan-card" onclick="selectAddPlan(this,'pro')"><div class="plan-icon">⭐</div><div style="font-size: 11px;">Pro</div></div>
            <div class="plan-card" onclick="selectAddPlan(this,'enterprise')"><div class="plan-icon">👑</div><div style="font-size: 11px;">Enterprise</div></div>
          </div>
        </div>
        <input type="hidden" id="selectedAddPlan" value="free"/>
        <div class="modal-actions">
          <button type="button" class="btn-cancel" onclick="closeModal()">إلغاء</button>
          <button type="submit" class="btn-submit">إضافة مقاول</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<style>
.contractors-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.contractor-card {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e8e8e0;
  border-right: 4px solid #0a4f14;
  overflow: hidden;
  transition: box-shadow 0.2s;
}

.contractor-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.contractor-card.bdr-green { border-right-color: #1D9E75; }
.contractor-card.bdr-red { border-right-color: #ef4444; }

.card-header {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 14px;
  border-bottom: 1px solid #f5f5f0;
}

.co-av {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  font-weight: 700;
  flex-shrink: 0;
}

.av-g { background: #ecfdf5; color: #065f46; }
.av-b { background: #eff6ff; color: #1d4ed8; }
.av-a { background: #fffbeb; color: #92400e; }

.co-info { flex: 1; min-width: 0; }
.co-name { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 2px; }

.co-badges {
  display: flex;
  gap: 4px;
  flex-wrap: wrap;
  margin-top: 6px;
}

.pill {
  font-size: 9px;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 12px;
  white-space: nowrap;
}

.pill-active { background: #ecfdf5; color: #065f46; }
.pill-inactive { background: #fef2f2; color: #991b1b; }
.pill-free { background: #f5f6f0; color: #888; }
.pill-pro { background: #eff6ff; color: #1d4ed8; }
.pill-ent { background: #fdf4ff; color: #7e22ce; }
.pill-city { background: #f5f6f0; color: #666; }

.card-id {
  font-size: 11px;
  color: #aaa;
  text-align: left;
}

.card-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  padding: 10px 14px;
}

.stat {
  background: #f8f9f0;
  border-radius: 8px;
  padding: 8px;
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.stat-val {
  font-size: 14px;
  font-weight: 700;
  color: #0a4f14;
}

.stat-label {
  font-size: 9px;
  color: #aaa;
}

.card-actions {
  display: flex;
  gap: 6px;
  padding: 10px 14px;
  border-top: 1px solid #f5f5f0;
}

.btn {
  flex: 1;
  padding: 8px;
  border: none;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  font-family: Tajawal, sans-serif;
  transition: all 0.2s;
}

.btn-view { background: #f0f0e8; color: #555; }
.btn-view:hover { background: #e0e0d8; }
.btn-plan { background: #fdf4ff; color: #7e22ce; }
.btn-plan:hover { background: #fce7ff; }
.btn-toggle { background: #ecfdf5; color: #065f46; }
.btn-toggle:hover { background: #d1fae5; }

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

.status-card {
  border: 2px solid #e8e8e0;
  border-radius: 10px;
  padding: 12px 8px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 12px;
}

.status-card:hover { border-color: #0a4f14; }
.status-card.sel-active { border-color: #065f46; background: #ecfdf5; }
.status-card.sel-inactive { border-color: #991b1b; background: #fef2f2; }
.status-card.sel-other { border-color: #92400e; background: #fffbeb; }

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

/* Toast */
.toast {
  position: fixed;
  bottom: 24px;
  right: 50%;
  transform: translateX(50%);
  background: #0a4f14;
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
  z-index: 200;
}

.toast.show { opacity: 1; }

/* Stats & Filter */
.stats-strip {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 12px;
  margin-bottom: 20px;
}

.stat-card {
  background: #fff;
  border-radius: 12px;
  padding: 14px;
  border: 1px solid #e8e8e0;
  text-align: center;
}

.stat-val {
  font-size: 22px;
  font-weight: 900;
  margin-bottom: 2px;
}

.stat-lbl {
  font-size: 10px;
  color: #aaa;
}

.sv-green { color: #0a4f14; }
.sv-blue { color: #185fa5; }
.sv-red { color: #dc2626; }
.sv-purple { color: #7c3aed; }

.filter-bar {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e8e8e0;
  padding: 12px 16px;
  margin-bottom: 16px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

.chip {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  border: 1.5px solid transparent;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.chip-active { background: #ecfdf5; color: #065f46; border-color: #6ee7b7; }
.chip-idle { background: #f5f6f0; color: #888; }
.chip-idle:hover { border-color: #0a4f14; color: #0a4f14; }

.chip-count {
  background: rgba(0,0,0,0.08);
  padding: 0 6px;
  border-radius: 10px;
  font-size: 10px;
  font-weight: 700;
}

.sort-sel {
  background: #f5f6f0;
  border: 1px solid #e8e8e0;
  border-radius: 8px;
  padding: 8px 12px;
  font-family: Tajawal, sans-serif;
  font-size: 12px;
  color: #666;
  outline: none;
  cursor: pointer;
}
</style>

<script>
function setFilter(el, status) {
  document.querySelectorAll('.chip').forEach(c => c.classList.remove('chip-active'));
  document.querySelectorAll('.chip').forEach(c => c.classList.add('chip-idle'));
  el.classList.remove('chip-idle');
  el.classList.add('chip-active');
  const sort = document.getElementById('sortSelect').value || 'recent';
  window.location.href = `{{ route('admin.contractors.index') }}?status=${status}&sort=${sort}`;
}

function changeSort(sort) {
  const status = new URLSearchParams(window.location.search).get('status') || 'all';
  window.location.href = `{{ route('admin.contractors.index') }}?status=${status}&sort=${sort}`;
}

function openAddModal() {
  document.getElementById('addForm').style.display = 'block';
  document.getElementById('planForm').style.display = 'none';
  document.getElementById('modalTitle').textContent = 'إضافة مقاول جديد';
  document.getElementById('modal').classList.add('open');
}

function openModal(type, id, name, status) {
  if (type === 'add') {
    openAddModal();
  } else if (type === 'plan') {
    document.getElementById('addForm').style.display = 'none';
    document.getElementById('statusForm').style.display = 'none';
    document.getElementById('planForm').style.display = 'block';
    document.getElementById('contractorId').value = id;
    document.getElementById('modalTitle').textContent = `تبديل الخطة — ${name}`;
    document.getElementById('modal').classList.add('open');
  } else if (type === 'status') {
    document.getElementById('addForm').style.display = 'none';
    document.getElementById('planForm').style.display = 'none';
    document.getElementById('statusForm').style.display = 'block';
    document.getElementById('contractorIdStatus').value = id;
    document.getElementById('selectedStatus').value = status;
    document.getElementById('modalTitle').textContent = `تبديل الحالة — ${name}`;
    
    // Reset status cards selection
    document.querySelectorAll('#statusForm .status-card').forEach(c => c.classList.remove('sel-active', 'sel-inactive', 'sel-other'));
    // Select the current status
    document.querySelector(`#statusForm .status-card[onclick*="'${status}'"]`).classList.add(`sel-${status}`);
    
    document.getElementById('modal').classList.add('open');
  }
}

function closeModal() {
  document.getElementById('modal').classList.remove('open');
}

function selectPlan(el, plan) {
  document.querySelectorAll('#planForm .plan-card').forEach(c => c.classList.remove('sel-free', 'sel-pro', 'sel-enterprise'));
  el.classList.add('sel-' + plan);
  document.getElementById('selectedPlan').value = plan;
}

function selectAddPlan(el, plan) {
  document.querySelectorAll('#addForm .plan-card').forEach(c => c.classList.remove('sel-free', 'sel-pro', 'sel-enterprise'));
  el.classList.add('sel-' + plan);
  document.getElementById('selectedAddPlan').value = plan;
}

function selectStatus(el, status) {
  document.querySelectorAll('#statusForm .status-card').forEach(c => c.classList.remove('sel-active', 'sel-inactive', 'sel-other'));
  el.classList.add('sel-' + status);
  document.getElementById('selectedStatus').value = status;
}

function submitAdd(e) {
  e.preventDefault();
  const form = new FormData(document.getElementById('addForm'));
  form.set('plan', document.getElementById('selectedAddPlan').value);

  fetch(`{{ route('admin.contractors.store') }}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: form
  }).then(r => r.json()).then(data => {
    closeModal();
    showToast(data.message || 'تم إضافة المقاول');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
  });
}

function submitPlan(e) {
  e.preventDefault();
  const contractorId = document.getElementById('contractorId').value;
  const plan = document.getElementById('selectedPlan').value;

  fetch(`{{ route('admin.contractors.update-plan', ['contractor' => 'ID']) }}`.replace('ID', contractorId), {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ plan })
  }).then(r => r.json()).then(data => {
    closeModal();
    showToast(data.message || 'تم التحديث');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
  });
}

function toggleStatus(id) {
  if (!confirm('هل تريد تغيير الحالة؟')) return;
  
  fetch(`{{ route('admin.contractors.toggle-status', ['contractor' => 'ID']) }}`.replace('ID', id), {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
  }).then(r => r.json()).then(data => {
    showToast(data.message || 'تم التحديث');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
  });
}

function submitStatus(e) {
  e.preventDefault();
  const contractorId = document.getElementById('contractorIdStatus').value;
  const status = document.getElementById('selectedStatus').value;

  fetch(`{{ route('admin.contractors.toggle-status', ['contractor' => 'ID']) }}`.replace('ID', contractorId), {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ status })
  }).then(r => r.json()).then(data => {
    closeModal();
    showToast(data.message || 'تم التحديث');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
  });
}

function viewContractor(id) {
  showToast('جاري تحميل الملف...');
}

let toastTimer;
function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
}

// Set active chip from URL and hook add button
document.addEventListener('DOMContentLoaded', () => {
  const status = new URLSearchParams(window.location.search).get('status') || 'all';
  const sort = new URLSearchParams(window.location.search).get('sort') || 'recent';
  document.getElementById('sortSelect').value = sort;

  // Hook add button in topbar
  const addBtn = document.querySelector('[style*="background:#0a4f14"][style*="color:#fff"]');
  if (addBtn) {
    addBtn.onclick = () => openAddModal();
  }
});
</script>

@endsection
