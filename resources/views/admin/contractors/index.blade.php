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
  <div class="chip chip-active" role="button" tabindex="0" onclick="setFilter(this, 'all')">الكل <span class="chip-count">{{ $stats['total_contractors'] }}</span></div>
  <div class="chip chip-idle" role="button" tabindex="0" onclick="setFilter(this, 'active')">نشط <span class="chip-count">{{ $stats['active_contractors'] }}</span></div>
  <div class="chip chip-idle" role="button" tabindex="0" onclick="setFilter(this, 'inactive')">موقوف <span class="chip-count">{{ $stats['inactive_contractors'] }}</span></div>
  <div class="chip chip-idle" role="button" tabindex="0" onclick="setFilter(this, 'pro')">Pro <span class="chip-count">{{ $stats['pro_contractors'] }}</span></div>
  <div class="chip chip-idle" role="button" tabindex="0" onclick="setFilter(this, 'enterprise')">Enterprise <span class="chip-count">{{ $stats['enterprise_contractors'] }}</span></div>
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
    <div class="modal-handle"></div>
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
        <div class="status-cards-grid">
          <div class="status-card sel-active" role="button" tabindex="0" data-status="active" onclick="selectStatus(this,'active')">
            <div class="status-card-icon">✅</div>
            <div class="status-card-label" style="color: #065f46;">نشط</div>
          </div>
          <div class="status-card" role="button" tabindex="0" data-status="inactive" onclick="selectStatus(this,'inactive')">
            <div class="status-card-icon">⏸️</div>
            <div class="status-card-label" style="color: #991b1b;">متوقف</div>
          </div>
          <div class="status-card" role="button" tabindex="0" data-status="other" onclick="selectStatus(this,'other')">
            <div class="status-card-icon">❓</div>
            <div class="status-card-label" style="color: #92400e;">آخر</div>
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
        <div class="tb-field">
          <label class="tb-label" for="add_first_name">الاسم بالكامل</label>
          <div class="tb-name-row">
            <div class="tb-input-wrap">
              <input type="text" id="add_first_name" name="first_name" placeholder="الاسم الأول" class="tb-input" required />
            </div>
            <div class="tb-input-wrap">
              <input type="text" name="last_name" placeholder="اسم العائلة" aria-label="اسم العائلة" class="tb-input" required />
            </div>
          </div>
        </div>

        <div class="tb-field">
          <label class="tb-label" for="add_email">البريد الإلكتروني</label>
          <div class="tb-input-wrap">
            <input type="email" id="add_email" name="email" placeholder="example@mail.com" class="tb-input" required />
          </div>
        </div>

        <div class="tb-field">
          <label class="tb-label" for="add_phone">رقم الهاتف</label>
          <div class="tb-input-wrap">
            <input type="text" id="add_phone" name="phone" placeholder="01X XXXX XXXX" class="tb-input" required />
          </div>
        </div>

        <div class="tb-field">
          <label class="tb-label" for="add_password">كلمة السر</label>
          <div class="tb-input-wrap">
            <input type="password" id="add_password" name="password" placeholder="••••••••" class="tb-input" required />
          </div>
        </div>

        <div class="tb-field">
          <label class="tb-label">خطة الاشتراك</label>
          <div class="tb-plan-grid">
            <div class="tb-plan-card tb-sel-free" role="button" tabindex="0" onclick="selectAddPlan(this,'free')">
              <div class="tb-plan-icon">🆓</div>
              <div class="tb-plan-name">مجاني</div>
            </div>
            <div class="tb-plan-card" role="button" tabindex="0" onclick="selectAddPlan(this,'pro')">
              <div class="tb-plan-icon">⭐</div>
              <div class="tb-plan-name">Pro</div>
            </div>
            <div class="tb-plan-card" role="button" tabindex="0" onclick="selectAddPlan(this,'enterprise')">
              <div class="tb-plan-icon">👑</div>
              <div class="tb-plan-name">Enterprise</div>
            </div>
          </div>
        </div>

        <input type="hidden" id="selectedAddPlan" value="free"/>
        <div class="tb-modal-actions">
          <button type="button" class="tb-btn-cancel" onclick="closeModal()">إلغاء</button>
          <button type="submit" class="tb-btn-submit">إضافة مقاول</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<style>
/* ============================================
   CONTRACTORS GRID & CARDS
   ============================================ */
.contractors-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}

.contractor-card {
  background: #fff;
  border-radius: 20px;
  border: 1px solid #e0e4db;
  border-right: 5px solid #0a4f14;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.contractor-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.1);
  border-color: #c8d0c2;
}

.contractor-card.bdr-green { border-right-color: #1D9E75; }
.contractor-card.bdr-red   { border-right-color: #ba1a1a; }

.card-header {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 18px;
  border-bottom: 1px solid #f1f3f0;
}

.co-av {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 17px;
  font-weight: 800;
  flex-shrink: 0;
}

.av-g { background: #e8f5e9; color: #0a4f14; }
.av-b { background: #e3f2fd; color: #185fa5; }
.av-a { background: #fff3e0; color: #e65100; }

.co-info { flex: 1; min-width: 0; }
.co-name { font-size: 14px; font-weight: 800; color: #1a1c19; margin-bottom: 3px; }

.co-badges {
  display: flex;
  gap: 5px;
  flex-wrap: wrap;
  margin-top: 7px;
}

.pill {
  font-size: 10px;
  font-weight: 800;
  padding: 3px 9px;
  border-radius: 20px;
  white-space: nowrap;
}

.pill-active   { background: #e8f5e9; color: #0a4f14; }
.pill-inactive { background: #fdeaea; color: #ba1a1a; }
.pill-free     { background: #f1f3f0; color: #707a6c; }
.pill-pro      { background: #e3f2fd; color: #185fa5; }
.pill-ent      { background: #f3e5f5; color: #7c3aed; }
.pill-city     { background: #f1f3f0; color: #43493e; border: 1px solid #e0e4db; }

.card-id { font-size: 11px; font-weight: 700; color: #9e9e9e; text-align: left; }

.card-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  padding: 14px 18px;
  background: #fafaf7;
}

.stat {
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.stat-val   { font-size: 15px; font-weight: 800; color: #1a1c19; }
.stat-label { font-size: 10px; color: #707a6c; font-weight: 700; }

.card-actions {
  display: flex;
  gap: 8px;
  padding: 14px 18px;
  border-top: 1px solid #f1f3f0;
}

.btn {
  flex: 1;
  height: 36px;
  border: none;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  font-family: 'Tajawal', sans-serif;
  transition: all 0.2s;
}

.btn-view   { background: #f1f3f0; color: #43493e; }
.btn-view:hover { background: #e0e4db; }
.btn-plan   { background: #f3e5f5; color: #7c3aed; }
.btn-plan:hover { background: #ebd4f1; }
.btn-toggle { background: #e8f5e9; color: #0a4f14; }
.btn-toggle:hover { background: #c8e6c9; }

/* ============================================
   MODAL
   ============================================ */
.modal-overlay {
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

.modal-overlay.open { display: flex; }

.modal {
  background: #fff;
  border-radius: 24px;
  width: 100%;
  max-width: 460px;
  overflow: clip; /* clip keeps border-radius but allows inner flex scroll */
  box-shadow: 0 24px 48px rgba(0,0,0,0.18);
  animation: modalIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-handle {
  display: none; /* shown only on mobile via media query */
}

@keyframes modalIn {
  from { opacity: 0; transform: translateY(24px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0)    scale(1);    }
}

.modal-head {
  background: linear-gradient(135deg, #0a4f14, #1D9E75);
  padding: 20px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-title { color: #fff; font-size: 16px; font-weight: 800; }

.modal-close {
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

.modal-close:hover { background: rgba(255,255,255,0.28); transform: rotate(90deg); }

.modal-body { padding: 24px; }

/* ---- Fields ---- */
.name-fields-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.modal-field { margin-bottom: 18px; }

.modal-field label {
  display: block;
  font-size: 13px;
  font-weight: 800;
  color: #1a1c19;
  margin-bottom: 8px;
  text-align: right;
}

.modal-input-wrap { position: relative; }

.modal-input {
  width: 100%;
  height: 48px;
  padding: 0 16px 0 44px;
  border: 2px solid #e0e4db;
  border-radius: 14px;
  font-family: 'Tajawal', sans-serif;
  font-size: 14px;
  color: #1a1c19;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  background: #fafaf7;
  direction: ltr;
  text-align: left;
  box-sizing: border-box;
}

.modal-input:focus {
  border-color: #0a4f14;
  background: #fff;
  box-shadow: 0 0 0 4px rgba(10, 79, 20, 0.1);
}

.modal-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #9e9e9e;
  font-size: 20px;
  font-family: 'Material Symbols Outlined';
  pointer-events: none;
  line-height: 1;
}

/* ---- Plan cards ---- */
.plan-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.plan-card {
  border: 2px solid #e0e4db;
  border-radius: 16px;
  padding: 16px 8px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  background: #fafaf7;
}

.plan-card:hover { border-color: #0a4f14; background: #f0faf2; }
.plan-card.sel-free       { border-color: #707a6c; background: #f1f3f0; }
.plan-card.sel-pro        { border-color: #185fa5; background: #e3f2fd; }
.plan-card.sel-enterprise { border-color: #7c3aed; background: #f3e5f5; }

.plan-icon { font-size: 30px; margin-bottom: 8px; }

/* ---- Status cards ---- */
.status-card {
  border: 2px solid #e0e4db;
  border-radius: 14px;
  padding: 16px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 700;
}

.status-card:hover      { border-color: #0a4f14; background: #fafaf7; }
.status-card.sel-active   { border-color: #0a4f14; background: #e8f5e9; }
.status-card.sel-inactive { border-color: #ba1a1a; background: #fdeaea; }
.status-card.sel-other    { border-color: #e65100; background: #fff3e0; }

.status-cards-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
  margin-bottom: 20px;
}

.status-card-icon {
  font-size: 24px;
  margin-bottom: 8px;
}

.status-card-label {
  font-size: 13px;
  font-weight: 700;
}

/* ---- Actions ---- */
.modal-actions { display: flex; gap: 12px; margin-top: 24px; }

.btn-cancel {
  flex: 1;
  height: 48px;
  background: #f1f3f0;
  border: none;
  border-radius: 14px;
  font-family: 'Tajawal', sans-serif;
  font-weight: 700;
  font-size: 14px;
  color: #43493e;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-cancel:hover { background: #e0e4db; }

.btn-submit {
  flex: 1.5;
  height: 48px;
  background: linear-gradient(135deg, #0a4f14, #1D9E75);
  color: #fff;
  border: none;
  border-radius: 14px;
  font-family: 'Tajawal', sans-serif;
  font-weight: 800;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(10, 79, 20, 0.25);
  transition: all 0.2s;
}

.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(10, 79, 20, 0.3); }

/* ============================================
   TOAST
   ============================================ */
.toast {
  position: fixed;
  bottom: 32px;
  left: 50%;
  transform: translateX(-50%);
  background: #1a1c19;
  color: #fff;
  padding: 14px 28px;
  border-radius: 16px;
  font-size: 14px;
  font-weight: 700;
  opacity: 0;
  visibility: hidden;
  transition: all 0.35s cubic-bezier(0.18, 0.89, 0.32, 1.28);
  z-index: 2000;
  box-shadow: 0 12px 28px rgba(0,0,0,0.25);
  white-space: nowrap;
}

.toast.show { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(-8px); }

/* ============================================
   STATS STRIP
   ============================================ */
.stats-strip {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.stat-card {
  background: #fff;
  border-radius: 18px;
  padding: 20px 16px;
  border: 1px solid #e0e4db;
  text-align: center;
  transition: all 0.2s;
  cursor: default;
}

.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.07); }

.stat-val  { font-size: 26px; font-weight: 900; margin-bottom: 4px; }
.stat-lbl  { font-size: 11px; color: #707a6c; font-weight: 700; letter-spacing: 0.3px; }

.sv-green  { color: #0a4f14; }
.sv-blue   { color: #185fa5; }
.sv-red    { color: #ba1a1a; }
.sv-purple { color: #7c3aed; }

/* ============================================
   FILTER BAR
   ============================================ */
.filter-bar {
  background: #fff;
  border-radius: 18px;
  border: 1px solid #e0e4db;
  padding: 12px 16px;
  margin-bottom: 24px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

.chip {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 18px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  border: 1.5px solid transparent;
}

.chip-active { background: #0a4f14; color: #fff; box-shadow: 0 4px 12px rgba(10,79,20,0.2); }
.chip-idle   { background: #f1f3f0; color: #707a6c; }
.chip-idle:hover { background: #e0e4db; color: #1a1c19; }

.chip-count {
  background: rgba(255,255,255,0.2);
  padding: 2px 8px;
  border-radius: 10px;
  font-size: 11px;
}

.chip-idle .chip-count { background: rgba(0,0,0,0.07); }

.sort-sel {
  background: #f1f3f0;
  border: 2px solid #e0e4db;
  border-radius: 12px;
  padding: 10px 16px;
  font-family: 'Tajawal', sans-serif;
  font-size: 13px;
  font-weight: 700;
  color: #1a1c19;
  outline: none;
  cursor: pointer;
  margin-right: auto;
  transition: border-color 0.2s;
}

.sort-sel:focus { border-color: #0a4f14; }

/* ============================================
   RESPONSIVE — MODAL + CONTRACTORS GRID
   ============================================ */

/* ── TABLET (≤ 1024px) ── */
@media (max-width: 1024px) {
  .contractors-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
  }

  .modal {
    max-width: 420px;
  }

  .modal-head {
    padding: 16px 20px;
  }

  .modal-title {
    font-size: 15px;
  }

  .modal-body {
    padding: 20px;
  }

  .modal-input {
    height: 44px;
    font-size: 13px;
  }

  .modal-field label {
    font-size: 12px;
    margin-bottom: 6px;
  }

  .plan-icon {
    font-size: 26px;
    margin-bottom: 6px;
  }

  .btn-cancel,
  .btn-submit {
    height: 44px;
    font-size: 13px;
  }

  .stats-strip {
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 10px;
  }

  .filter-bar {
    padding: 10px 12px;
    gap: 8px;
  }

  .chip {
    padding: 7px 14px;
    font-size: 12px;
  }
}

/* ── MOBILE (≤ 767px) ── */
@media (max-width: 767px) {
  .contractors-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }

  .contractor-card {
    border-radius: 14px;
  }

  .card-header {
    padding: 14px;
    gap: 10px;
  }

  .co-av {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    font-size: 15px;
  }

  .co-name {
    font-size: 13px;
  }

  .pill {
    font-size: 9px;
    padding: 2px 7px;
  }

  .card-stats {
    padding: 10px 14px;
    gap: 8px;
  }

  .stat-val {
    font-size: 14px;
  }

  .stat-label {
    font-size: 9px;
  }

  .card-actions {
    padding: 10px 14px;
    gap: 6px;
  }

  .btn {
    height: 34px;
    font-size: 12px;
    border-radius: 8px;
  }

  /* ── MODAL MOBILE ── */
  .modal-overlay {
    padding: 0;
    align-items: flex-end;
  }

  .modal {
    max-width: 100%;
    width: 100%;
    border-radius: 24px 24px 0 0;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    animation: modalSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  }

  /* Show drag handle on mobile */
  .modal-handle {
    display: block;
    width: 36px;
    height: 4px;
    background: rgba(255,255,255,0.5);
    border-radius: 2px;
    margin: 10px auto -6px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
  }

  @keyframes modalSlideUp {
    from { opacity: 0; transform: translateY(100%); }
    to   { opacity: 1; transform: translateY(0);    }
  }

  .modal-head {
    padding: 16px 18px;
    border-radius: 24px 24px 0 0;
    flex-shrink: 0;
  }

  .modal-title {
    font-size: 15px;
  }

  .modal-close {
    width: 30px;
    height: 30px;
    font-size: 18px;
  }

  .modal-body {
    padding: 18px 16px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
    min-height: 0;
  }

  /* Stack name fields vertically */
  .name-fields-grid {
    grid-template-columns: 1fr;
    gap: 10px;
  }

  .modal-field {
    margin-bottom: 14px;
  }

  .modal-field label {
    font-size: 12px;
    margin-bottom: 6px;
  }

  .modal-input {
    height: 44px;
    padding: 0 14px 0 40px;
    font-size: 13px;
    border-radius: 12px;
    border-width: 1.5px;
  }

  .modal-input:focus {
    box-shadow: 0 0 0 3px rgba(10, 79, 20, 0.08);
  }

  .modal-icon {
    left: 12px;
    font-size: 18px;
  }

  .plan-grid {
    gap: 8px;
  }

  .plan-card {
    padding: 12px 6px;
    border-radius: 12px;
    border-width: 1.5px;
  }

  .plan-icon {
    font-size: 24px;
    margin-bottom: 4px;
  }

  .plan-card div[style*="font-size: 11px"] {
    font-size: 10px !important;
  }

  .plan-card div[style*="font-size:10px"] {
    font-size: 9px !important;
  }

  .modal-actions {
    gap: 10px;
    margin-top: 18px;
    position: sticky;
    bottom: 0;
    background: #fff;
    padding-top: 12px;
    padding-bottom: env(safe-area-inset-bottom, 0);
  }

  .btn-cancel,
  .btn-submit {
    height: 44px;
    font-size: 13px;
    border-radius: 12px;
  }

  .btn-submit {
    box-shadow: 0 3px 10px rgba(10, 79, 20, 0.2);
  }

  /* Status cards on mobile */
  .status-card {
    padding: 12px;
    border-radius: 10px;
  }

  .status-card-icon {
    font-size: 20px;
    margin-bottom: 4px;
  }

  .status-card-label {
    font-size: 12px;
  }

  .status-cards-grid {
    gap: 10px;
    margin-bottom: 16px;
  }

  /* Stats strip mobile */
  .stats-strip {
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
  }

  .stat-card {
    padding: 12px 8px;
    border-radius: 12px;
  }

  .stats-strip .stat-val {
    font-size: 20px;
  }

  .stats-strip .stat-lbl {
    font-size: 9px;
  }

  /* Filter bar mobile */
  .filter-bar {
    padding: 10px;
    gap: 6px;
    border-radius: 12px;
    overflow-x: auto;
    flex-wrap: nowrap;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  .filter-bar::-webkit-scrollbar {
    display: none;
  }

  .chip {
    padding: 7px 12px;
    font-size: 11px;
    border-radius: 20px;
    white-space: nowrap;
    flex-shrink: 0;
  }

  .chip-count {
    font-size: 10px;
    padding: 1px 6px;
  }

  .sort-sel {
    padding: 8px 12px;
    font-size: 12px;
    border-radius: 10px;
    flex-shrink: 0;
  }

  /* Toast mobile */
  .toast {
    bottom: 80px;
    font-size: 13px;
    padding: 12px 22px;
    border-radius: 12px;
    max-width: calc(100vw - 32px);
    white-space: normal;
    text-align: center;
  }
}

/* ── SMALL MOBILE (≤ 480px) ── */
@media (max-width: 480px) {
  .modal {
    max-height: 95vh;
    border-radius: 20px 20px 0 0;
  }

  .modal-head {
    padding: 14px 16px;
    border-radius: 20px 20px 0 0;
  }

  .modal-title {
    font-size: 14px;
  }

  .modal-body {
    padding: 14px 14px;
  }

  .modal-field {
    margin-bottom: 12px;
  }

  .modal-field label {
    font-size: 11px;
    margin-bottom: 5px;
  }

  .modal-input {
    height: 42px;
    font-size: 12px;
    border-radius: 10px;
    padding: 0 12px 0 36px;
  }

  .modal-icon {
    left: 10px;
    font-size: 16px;
  }

  .plan-grid {
    gap: 6px;
  }

  .plan-card {
    padding: 10px 4px;
    border-radius: 10px;
  }

  .plan-icon {
    font-size: 20px;
    margin-bottom: 3px;
  }

  .modal-actions {
    gap: 8px;
    margin-top: 14px;
  }

  .btn-cancel,
  .btn-submit {
    height: 42px;
    font-size: 12px;
    border-radius: 10px;
  }

  .status-card {
    padding: 10px;
  }

  .status-card-icon {
    font-size: 18px;
    margin-bottom: 3px;
  }

  .status-card-label {
    font-size: 11px;
  }

  .status-cards-grid {
    gap: 8px;
    margin-bottom: 12px;
  }

  /* Stats strip small mobile */
  .stats-strip {
    grid-template-columns: repeat(2, 1fr);
    gap: 6px;
  }

  .stats-strip .stat-val {
    font-size: 18px;
  }

  .stat-card {
    padding: 10px 6px;
    border-radius: 10px;
  }

  /* Cards */
  .contractor-card {
    border-radius: 12px;
    border-right-width: 4px;
  }

  .card-header {
    padding: 12px;
    gap: 8px;
  }

  .co-av {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 14px;
  }

  .co-name {
    font-size: 12px;
  }

  .pill {
    font-size: 8px;
    padding: 2px 6px;
  }

  .card-stats {
    padding: 8px 12px;
  }

  .card-actions {
    padding: 8px 12px;
    gap: 5px;
  }

  .btn {
    height: 32px;
    font-size: 11px;
  }

  /* Toast */
  .toast {
    bottom: 76px;
    font-size: 12px;
    padding: 10px 18px;
  }
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
  document.getElementById('statusForm').style.display = 'none';
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
    
    // Select the current status using data attribute
    const statusElement = document.querySelector(`#statusForm .status-card[data-status="${status}"]`);
    if (statusElement) {
      statusElement.classList.add(`sel-${status}`);
    }
    
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
  document.querySelectorAll('#addForm .tb-plan-card').forEach(c => c.classList.remove('tb-sel-free', 'tb-sel-pro', 'tb-sel-enterprise'));
  el.classList.add('tb-sel-' + plan);
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
    showPageToast(data.message || 'تم إضافة المقاول');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showPageToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
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
    showPageToast(data.message || 'تم التحديث');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showPageToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
  });
}

function toggleStatus(id) {
  if (!confirm('هل تريد تغيير الحالة؟')) return;
  
  fetch(`{{ route('admin.contractors.toggle-status', ['contractor' => 'ID']) }}`.replace('ID', id), {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
  }).then(r => r.json()).then(data => {
    showPageToast(data.message || 'تم التحديث');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showPageToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
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
    showPageToast(data.message || 'تم التحديث');
    setTimeout(() => location.reload(), 1500);
  }).catch(e => {
    console.error(e);
    showPageToast('خطأ: ' + (e.message || 'حدث خطأ'), 'error');
  });
}

function viewContractor(id) {
  showPageToast('جاري تحميل الملف...');
}

let toastTimer;
function showPageToast(msg, type = '') {
  const t = document.getElementById('toast');
  if (!t) return;
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

  // Hook add button in topbar to use this page's modal instead
  const addBtn = document.querySelector('.topbar-add-btn');
  if (addBtn) {
    addBtn.onclick = (e) => {
      e.preventDefault();
      openAddModal();
    };
  }
});
</script>

@endsection
