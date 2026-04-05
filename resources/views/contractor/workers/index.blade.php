@extends('layouts.dashboard')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.topbar {
  background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 100%);
  padding: 16px 20px 20px;
  margin: 0 0 0 0;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.page-title { color: #fff; font-size: 18px; font-weight: 700; }
.add-btn {
  background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);
  color: #fff; font-size: 12px; font-weight: 600;
  padding: 7px 14px; border-radius: 20px; cursor: pointer;
  display: flex; align-items: center; gap: 5px;
}

.search-wrap {
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 12px; padding: 9px 14px;
  display: flex; align-items: center; gap: 8px;
}
.search-icon { color: rgba(255,255,255,0.7); font-size: 14px; }
.search-input { 
  background: transparent; border: none; color: #fff; font-size: 13px;
  font-family: inherit; flex: 1; outline: none;
}
.search-input::placeholder { color: rgba(255,255,255,0.6); }

.stats-strip {
  display: grid; grid-template-columns: repeat(4, minmax(0,1fr));
  gap: 10px; padding: 14px 16px;
  background: #fff; border-bottom: 1px solid #f0f0f0;
}
.strip-stat { text-align: center; }
.strip-val { font-size: 18px; font-weight: 700; }
.strip-lbl { font-size: 10px; color: #aaa; margin-top: 2px; }
.sv-green { color: #059669; } .sv-amber { color: #D97706; }
.sv-blue { color: #2563EB; } .sv-red { color: #DC2626; }

.filter-row {
  display: flex; gap: 7px; padding: 12px 16px;
  background: #fff; border-bottom: 1px solid #f0f0f0;
  overflow-x: auto;
}
.chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 13px; border-radius: 20px; font-size: 12px;
  white-space: nowrap; cursor: pointer; font-weight: 500;
  border: 1.5px solid transparent; transition: all 0.15s;
  text-decoration: none;
}
.chip-all { background: #ECFDF5; color: #065F46; border-color: #6EE7B7; }
.chip-neutral { background: #f5f5f5; color: #888; border-color: #f5f5f5; }
.chip-amber { background: #FFFBEB; color: #92400E; border-color: #FCD34D; }
.chip-red { background: #FEF2F2; color: #991B1B; border-color: #FCA5A5; }
.chip-gray { background: #F3F4F6; color: #6B7280; border-color: #E5E7EB; }
.chip-count {
  background: rgba(0,0,0,0.08); border-radius: 20px;
  padding: 1px 6px; font-size: 10px; font-weight: 700;
}

.sort-bar {
  display: flex; justify-content: space-between; align-items: center;
  padding: 8px 16px; background: #f5f6f8;
}
.sort-label { font-size: 11px; color: #bbb; }
.sort-select { font-size: 11px; color: #185FA5; font-weight: 600; cursor: pointer; background: none; border: none; }

.list-body { padding: 0; padding-bottom: 20px; }

.w-card {
  background: #fff; border-radius: 14px; margin-bottom: 10px;
  box-shadow: 0 1px 6px rgba(0,0,0,0.06);
  overflow: hidden; cursor: pointer;
  transition: box-shadow 0.2s;
}
.w-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }

.w-card-main { display: flex; align-items: center; gap: 12px; padding: 13px 14px 10px; }

.w-avatar {
  width: 46px; height: 46px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; font-weight: 700; flex-shrink: 0;
  position: relative;
}
.status-ring-active { box-shadow: 0 0 0 2.5px #10B981; }
.status-ring-inactive { box-shadow: 0 0 0 2.5px #D1D5DB; }
.online-dot {
  position: absolute; bottom: 1px; right: 1px;
  width: 11px; height: 11px; border-radius: 50%;
  border: 2px solid #fff;
}
.dot-online { background: #10B981; }
.dot-offline { background: #9CA3AF; }

.av-teal { background: #ECFDF5; color: #065F46; }
.av-blue { background: #EFF6FF; color: #1D4ED8; }
.av-amber { background: #FFFBEB; color: #92400E; }
.av-purple { background: #F5F3FF; color: #5B21B6; }
.av-coral { background: #FFF1EE; color: #9A3412; }
.av-gray { background: #F3F4F6; color: #6B7280; }

.w-info { flex: 1; min-width: 0; }
.w-name { font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 3px; }
.w-badges { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }
.w-id { font-size: 10px; color: #bbb; background: #f5f5f5; padding: 2px 7px; border-radius: 20px; }
.w-today-badge {
  font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.today-assigned { background: #ECFDF5; color: #065F46; }
.today-unassigned { background: #F3F4F6; color: #9CA3AF; }

.w-right { text-align: left; flex-shrink: 0; }
.w-earn { font-size: 14px; font-weight: 700; }
.earn-green { color: #059669; }
.earn-amber { color: #D97706; }
.earn-gray { color: #D1D5DB; }
.w-days { font-size: 10px; color: #bbb; margin-top: 2px; text-align: left; }

.w-card-bottom { padding: 0 14px 12px; }
.prog-row { display: flex; align-items: center; gap: 8px; }
.prog-bar { flex: 1; height: 4px; background: #f0f0f0; border-radius: 2px; overflow: hidden; }
.prog-fill { height: 100%; border-radius: 2px; }
.prog-green { background: #10B981; }
.prog-amber { background: #F59E0B; }
.prog-red { background: #EF4444; }
.prog-lbl { font-size: 10px; font-weight: 600; min-width: 28px; text-align: left; }

.w-tags { display: flex; gap: 5px; margin-top: 7px; flex-wrap: wrap; }
.w-tag { font-size: 10px; padding: 2px 8px; border-radius: 20px; }
.tag-company { background: #EFF6FF; color: #1D4ED8; }
.tag-deduct { background: #FEF3C7; color: #92400E; }
.tag-advance { background: #FEF3C7; color: #B45309; border: 1px dashed #FCD34D; }
.tag-inactive { background: #F3F4F6; color: #9CA3AF; }

.inactive-header {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 16px 6px; cursor: pointer;
}
.inactive-label { font-size: 12px; font-weight: 600; color: #9CA3AF; }
.inactive-count { font-size: 11px; background: #F3F4F6; color: #9CA3AF; padding: 2px 8px; border-radius: 20px; }
.inactive-toggle { font-size: 11px; color: #bbb; margin-left: auto; }

.w-card-inactive { opacity: 0.6; }

.fab {
  position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
  background: #1D9E75; color: #fff; border: none; border-radius: 28px;
  padding: 13px 28px; font-size: 14px; font-weight: 700;
  box-shadow: 0 4px 20px rgba(29,158,117,0.4);
  cursor: pointer; display: flex; align-items: center; gap: 8px;
  white-space: nowrap;
}

@media(max-width: 768px) {
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 8px; padding: 10px 0; }
  .strip-val { font-size: 16px; }
  .strip-lbl { font-size: 9px; }
  .page-title { font-size: 16px; }
  .add-btn { font-size: 11px; padding: 6px 12px; }
  .topbar { padding: 12px 16px 16px; margin: 0; }
  .topbar-row { gap: 8px; margin-bottom: 12px; }
  .search-wrap { padding: 8px 12px; }
  .search-icon { font-size: 13px; }
  .search-input { font-size: 12px; }
  .filter-row { gap: 6px; padding: 10px 0; }
  .chip { padding: 5px 11px; font-size: 11px; }
  .chip-count { font-size: 9px; padding: 1px 5px; }
  .sort-bar { padding: 7px 0; }
  .sort-label { font-size: 10px; }
  .sort-select { font-size: 10px; }
  .list-body { padding: 0; padding-bottom: 80px; }
  .w-card-main { gap: 10px; padding: 11px 0 8px; }
  .w-avatar { width: 42px; height: 42px; font-size: 14px; }
  .w-name { font-size: 13px; }
  .w-badges { gap: 4px; }
  .w-id { font-size: 9px; padding: 1px 6px; }
  .w-today-badge { font-size: 9px; padding: 1px 6px; }
  .w-right { gap: 1px; }
  .w-earn { font-size: 13px; }
  .w-days { font-size: 9px; }
  .w-card-bottom { padding: 0 0 10px; }
  .prog-lbl { font-size: 9px; min-width: 25px; }
  .w-tags { gap: 4px; }
  .w-tag { font-size: 9px; padding: 2px 6px; }
  .inactive-header { padding: 10px 0 4px; }
  .inactive-label { font-size: 11px; }
  .inactive-count { font-size: 10px; }
  .fab { padding: 10px 20px; font-size: 13px; bottom: 20px; }
}

@media(max-width: 480px) {
  .topbar { padding: 10px 12px 12px; margin: 0; }
  .topbar-row { flex-wrap: wrap; margin-bottom: 10px; }
  .page-title { font-size: 14px; flex: 1; }
  .add-btn { font-size: 10px; padding: 5px 10px; }
  .search-wrap { width: 100%; padding: 7px 10px; margin-top: 8px; }
  .search-input { font-size: 11px; }
  .stats-strip { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 6px; padding: 8px 0; }
  .strip-val { font-size: 14px; }
  .strip-lbl { font-size: 8px; }
  .filter-row { gap: 5px; padding: 8px 0; overflow-x: auto; }
  .chip { padding: 4px 9px; font-size: 10px; }
  .chip-count { font-size: 8px; padding: 0px 4px; }
  .sort-bar { padding: 6px 0; flex-direction: column; align-items: flex-start; gap: 6px; }
  .sort-label { font-size: 9px; }
  .sort-select { font-size: 9px; width: 100%; }
  .list-body { padding: 0; padding-bottom: 90px; }
  .w-card { margin-bottom: 8px; border-radius: 10px; }
  .w-card-main { gap: 8px; padding: 9px 0 6px; }
  .w-card { cursor: pointer; }
  .w-card-actions { display: flex; gap: 6px; margin-top: 8px; padding: 8px 0 0; border-top: 0.5px solid #e8e8e3; }
  .w-card-actions button { flex: 1; height: 32px; font-size: 11px; padding: 0 6px; border-radius: 6px; border: 1px solid; cursor: pointer; font-weight: 600; font-family: 'Tajawal', sans-serif; transition: all 0.15s; }
  .btn-edit { background: #fff; border-color: #d0d0c8; color: #0d631b; }
  .btn-edit:hover { background: #E1F5EE; }
  .btn-deactivate { background: #fff5f5; border-color: #f0e0e0; color: #ba1a1a; }
  .btn-deactivate:hover { background: #ffe8e8; }
  .w-info { }
  .w-name { font-size: 12px; margin-bottom: 2px; }
  .w-badges { gap: 3px; }
  .w-id { font-size: 8px; padding: 1px 5px; }
  .w-today-badge { font-size: 8px; padding: 1px 5px; }
  .w-right { }
  .w-earn { font-size: 12px; }
  .w-days { font-size: 8px; }
  .w-card-bottom { padding: 0 0 8px; }
  .prog-row { gap: 6px; }
  .prog-bar { height: 3px; }
  .prog-lbl { font-size: 8px; min-width: 22px; }
  .w-tags { gap: 3px; }
  .w-tag { font-size: 8px; padding: 1px 5px; }
  .fab { padding: 9px 16px; font-size: 12px; bottom: 16px; left: 50%; transform: translateX(-50%); width: calc(100% - 20px); max-width: calc(100vw - 20px); }
  .inactive-header { padding: 8px 0 3px; }
  .inactive-label { font-size: 10px; }
  .inactive-count { font-size: 9px; }
}
</style>

<div class="topbar">
  <div class="topbar-row">
    <div class="page-title">العمال</div>
    <button class="add-btn" onclick="openWorkerModal(false)">+ إضافة عامل</button>
  </div>
  <div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" class="search-input" placeholder="ابحث بالاسم أو رقم العامل...">
  </div>
</div>

<!-- Stats strip -->
<div class="stats-strip">
  <div class="strip-stat"><div class="strip-val sv-blue">{{ $total_workers ?? 0 }}</div><div class="strip-lbl">إجمالي العمال</div></div>
  <div class="strip-stat"><div class="strip-val sv-green">{{ $assigned_today ?? 0 }}</div><div class="strip-lbl">موزعين اليوم</div></div>
  <div class="strip-stat"><div class="strip-val sv-amber">{{ $has_advances ?? 0 }}</div><div class="strip-lbl">عندهم سلف</div></div>
  <div class="strip-stat"><div class="strip-val sv-red">{{ $unassigned ?? 0 }}</div><div class="strip-lbl">غير موزعين</div></div>
</div>

<!-- Filters -->
<div class="filter-row">
  <a href="{{ route('contractor.workers.index', ['filter' => 'all']) }}" class="chip {{ request('filter', 'all') === 'all' ? 'chip-all' : 'chip-neutral' }}">الكل <span class="chip-count">{{ $total_workers ?? 0 }}</span></a>
  <a href="{{ route('contractor.workers.index', ['filter' => 'assigned']) }}" class="chip {{ request('filter') === 'assigned' ? 'chip-all' : 'chip-neutral' }}">موزع اليوم <span class="chip-count">{{ $assigned_today ?? 0 }}</span></a>
  <a href="{{ route('contractor.workers.index', ['filter' => 'unassigned']) }}" class="chip {{ request('filter') === 'unassigned' ? 'chip-all' : 'chip-neutral' }}">غير موزع <span class="chip-count">{{ $unassigned ?? 0 }}</span></a>
  <a href="{{ route('contractor.workers.index', ['filter' => 'advance']) }}" class="chip {{ request('filter') === 'advance' ? 'chip-amber' : 'chip-neutral' }}">عنده سلفة <span class="chip-count">{{ $has_advances ?? 0 }}</span></a>
  <a href="{{ route('contractor.workers.index', ['filter' => 'inactive']) }}" class="chip {{ request('filter') === 'inactive' ? 'chip-gray' : 'chip-neutral' }}">غير نشط <span class="chip-count">{{ $inactive_count ?? 0 }}</span></a>
</div>

<!-- Sort -->
<div class="sort-bar">
  <span class="sort-label">{{ $workers->count() ?? 0 }} عامل</span>
  <select class="sort-select" onchange="window.location.href='{{ route('contractor.workers.index') }}?sort=' + this.value">
    <option value="assigned">ترتيب: موزع أولاً</option>
    <option value="name">ترتيب: الاسم</option>
    <option value="attendance">ترتيب: نسبة الحضور</option>
  </select>
</div>

<!-- Active Workers -->
<div class="list-body">
  @forelse($workers as $worker)
    <div class="w-card" onclick="window.location.href='{{ route('contractor.workers.show', $worker) }}'">
      <div class="w-card-main">
        <div class="w-avatar av-teal status-ring-{{ $worker->is_active ? 'active' : 'inactive' }}">
          {{ substr($worker->name, 0, 1) }}{{ strpos($worker->name, ' ') ? substr($worker->name, strpos($worker->name, ' ') + 1, 1) : '' }}
          <div class="online-dot {{ $worker->distribution_today ? 'dot-online' : 'dot-offline' }}"></div>
        </div>
        <div class="w-info">
          <div class="w-name">{{ $worker->name }}</div>
          <div class="w-badges">
            <span class="w-id">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }}</span>
            <span class="w-today-badge {{ $worker->distribution_today ? 'today-assigned' : 'today-unassigned' }}">
              {{ $worker->distribution_today ? $worker->company_today : 'غير موزع' }}
            </span>
          </div>
        </div>
        <div class="w-right">
          <div class="w-earn {{ $worker->distribution_today ? 'earn-green' : 'earn-gray' }}">{{ $worker->daily_wage ?? '—' }} ج</div>
          <div class="w-days">{{ $worker->days_worked ?? 0 }}/28 يوم</div>
        </div>
      </div>
      <div class="w-card-bottom">
        <div class="prog-row">
          <div class="prog-bar"><div class="prog-fill prog-{{ $worker->attendance_rate >= 80 ? 'green' : ($worker->attendance_rate >= 50 ? 'amber' : 'red') }}" style="width:{{ $worker->attendance_rate ?? 0 }}%;"></div></div>
          <div class="prog-lbl" style="color:{{ $worker->attendance_rate >= 80 ? '#059669' : ($worker->attendance_rate >= 50 ? '#D97706' : '#EF4444') }};">{{ $worker->attendance_rate ?? 0 }}%</div>
        </div>
        <div class="w-tags">
          @if($worker->distribution_today)
            <span class="w-tag tag-company">{{ $worker->company_today }} · {{ $worker->daily_wage ?? 0 }} ج</span>
          @endif
          @if($worker->has_deduction)
            <span class="w-tag tag-deduct">خصم: {{ $worker->deduction_amount ?? 0 }} ج</span>
          @endif
          @if($worker->has_advance)
            <span class="w-tag tag-advance">سلفة: {{ $worker->advance_amount ?? 0 }} ج</span>
          @endif
        </div>
        <div class="w-card-actions" onclick="event.stopPropagation()">
          <button class="btn-edit" onclick="openWorkerModal(true, {{ $worker->id }})">تعديل</button>
          <button class="btn-deactivate" onclick="deactivateWorker({{ $worker->id }})">إيقاف</button>
        </div>
      </div>
    </div>
  @empty
    <div style="text-align:center;padding:40px;color:#aaa;">لا توجد عمال</div>
  @endforelse

  @if($inactiveWorkers && $inactiveWorkers->count() > 0)
    <div class="inactive-header">
      <div class="inactive-label">عمال غير نشطين</div>
      <div class="inactive-count">{{ $inactiveWorkers->count() }}</div>
      <div class="inactive-toggle" onclick="document.getElementById('inactiveSection').style.display = document.getElementById('inactiveSection').style.display === 'none' ? 'block' : 'none'">عرض ▾</div>
    </div>

    <div id="inactiveSection" style="display:none;">
      @foreach($inactiveWorkers as $worker)
        <div class="w-card w-card-inactive" onclick="window.location.href='{{ route('contractor.workers.show', $worker) }}'">
          <div class="w-card-main">
            <div class="w-avatar av-gray status-ring-inactive">
              {{ substr($worker->name, 0, 1) }}
              <div class="online-dot dot-offline"></div>
            </div>
            <div class="w-info">
              <div class="w-name" style="color:#9CA3AF;">{{ $worker->name }}</div>
              <div class="w-badges">
                <span class="w-id">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }}</span>
                <span class="w-today-badge" style="background:#F3F4F6;color:#9CA3AF;">موقوف</span>
              </div>
            </div>
            <div class="w-right">
              <div class="w-earn earn-gray">— ج</div>
              <div class="w-days" style="color:#D1D5DB;">آخر يوم: {{ $worker->last_worked_date ?? 'غير معروف' }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>


<!-- Include Worker Form Modal -->
@include('components.worker-form-modal')

<script>
    /**
     * Deactivate a worker (soft delete) - hides from daily distribution but keeps history
     */
    function deactivateWorker(workerId) {
        if (!confirm('هل تريد إيقاف هذا العامل؟\nسيتم إخفاؤه من التوزيعات اليومية ولكن سيبقى سجله محفوظاً')) {
            return;
        }

        fetch(`/contractor/workers/${workerId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ is_active: false })
        })
        .then(response => {
            if (!response.ok) throw new Error('حدث خطأ');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload to reflect changes
                window.location.reload();
            } else {
                alert(data.message || 'فشل إيقاف العامل');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إيقاف العامل');
        });
    }

    /**
     * Reactivate a deactivated worker
     */
    function reactivateWorker(workerId) {
        if (!confirm('هل تريد تفعيل هذا العامل مرة أخرى؟')) {
            return;
        }

        fetch(`/contractor/workers/${workerId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ is_active: true })
        })
        .then(response => {
            if (!response.ok) throw new Error('حدث خطأ');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload to reflect changes
                window.location.reload();
            } else {
                alert(data.message || 'فشل تفعيل العامل');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تفعيل العامل');
        });
    }

    /**
     * Open worker modal for create or edit
     */
    function openWorkerModal(isEdit = false, workerId = null) {
        const modal = document.getElementById('workerModal');
        const form = document.getElementById('workerForm');
        const titleEl = document.getElementById('workerModalTitle');
        
        if (!modal) return;
        
        // Reset form
        form.reset();
        document.getElementById('workerId').value = '';
        
        // Set title
        if (isEdit && workerId) {
            titleEl.textContent = 'تعديل بيانات العامل';
            // Load worker data
            fetch(`/contractor/workers/${workerId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('workerId').value = data.id;
                document.getElementById('workerName').value = data.name;
                document.getElementById('workerPhone').value = data.phone;
                document.getElementById('workerNationalId').value = data.national_id || '';
                if (data.joined_date) {
                    document.getElementById('workerJoinedDate').value = data.joined_date;
                }
            })
            .catch(error => {
                console.error('Error loading worker:', error);
                alert('فشل تحميل بيانات العامل');
                closeWorkerModal();
            });
        } else {
            titleEl.textContent = 'إضافة عامل جديد';
        }
        
        // Show modal
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Focus on first input
        setTimeout(() => {
            document.getElementById('workerName').focus();
        }, 100);
    }

    /**
     * Close worker modal
     */
    function closeWorkerModal() {
        const modal = document.getElementById('workerModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * Handle modal form submission
     */
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('workerForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const workerId = document.getElementById('workerId').value;
                const method = workerId ? 'PUT' : 'POST';
                const url = workerId ? `/contractor/workers/${workerId}` : '/contractor/workers';
                
                const formData = {
                    name: document.getElementById('workerName').value,
                    phone: document.getElementById('workerPhone').value,
                    national_id: document.getElementById('workerNationalId').value,
                    joined_date: document.getElementById('workerJoinedDate').value
                };

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ أثناء الحفظ');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء الحفظ');
                });
            });
        }
        
        // Close modal when clicking outside
        const modal = document.getElementById('workerModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeWorkerModal();
                }
            });
        }
    });

@endsection

