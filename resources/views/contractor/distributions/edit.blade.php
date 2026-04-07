@extends('layouts.dashboard')
@section('title', 'تعديل التوزيع')

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.topbar {
  background: linear-gradient(135deg, #185FA5 0%, #1D9E75 100%);
  padding: 16px 20px 20px;
}
.topbar-row { display: flex; justify-content: space-between; align-items: center; }
.back-btn { color: rgba(255,255,255,0.85); font-size: 13px; cursor: pointer; }
.back-btn:hover { color: #fff; }
.top-title { color: #fff; font-size: 16px; font-weight: 700; }
.top-date  { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 4px; }

.section-body { padding: 16px; }
.sec-label { font-size: 11px; font-weight: 600; color: #bbb; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 10px; }

.company-info {
  background: #EFF6FF; border-radius: 12px; padding: 12px 14px;
  margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
}
.co-av {
  width: 40px; height: 40px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; font-weight: 700;
  background: #EFF6FF; color: #1D4ED8; flex-shrink: 0;
}
.co-name-edit  { font-size: 13px; font-weight: 700; color: #1D4ED8; }
.co-wage-edit  { font-size: 11px; color: #93C5FD; margin-top: 2px; }

.w-card {
  background: #fff;
  border-radius: 14px;
  border: 2px solid #f0f0f0;
  padding: 12px 14px;
  margin-bottom: 8px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 11px;
}
.w-card:hover { border-color: #6EE7B7; }
.w-card.selected { border-color: #1D9E75; background: #ECFDF5; }
.w-card.already-assigned {
  opacity: 0.45;
  cursor: not-allowed;
  background: #fafafa;
}

.w-av {
  width: 40px; height: 40px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; flex-shrink: 0;
  background: #EFF6FF; color: #1D4ED8;
}

.check-circle {
  width: 22px; height: 22px; border-radius: 50%;
  border: 2px solid #d0d0c8;
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; color: transparent;
  transition: all 0.2s;
}
.w-card.selected .check-circle {
  background: #1D9E75;
  border-color: #1D9E75;
  color: #fff;
}

.w-info { flex: 1; min-width: 0; }
.w-name { font-size: 13px; font-weight: 600; color: #1a1a1a; }
.w-sub  { font-size: 11px; color: #aaa; margin-top: 2px; }
.already-tag { font-size: 10px; font-weight: 600; background: #F3F4F6; color: #9CA3AF; padding: 2px 7px; border-radius: 20px; }

.preview-box {
  background: #fff; border-radius: 14px; border: 1px solid #f0f0f0;
  padding: 14px; margin: 16px; margin-bottom: 80px;
}
.preview-title { font-size: 12px; font-weight: 700; color: #222; margin-bottom: 8px; }
.change-item {
  display: flex; align-items: center; gap: 8px; padding: 8px 0;
  border-bottom: 1px solid #f5f5f5; font-size: 12px;
}
.change-item:last-child { border-bottom: none; }
.change-badge {
  background: #FEF2F2; color: #991B1B; padding: 2px 6px; border-radius: 6px;
  font-size: 10px; font-weight: 600; flex-shrink: 0;
}

.action-buttons {
  position: fixed; bottom: 0; left: 0; right: 0;
  display: flex; gap: 8px; padding: 12px;
  background: #fff; border-top: 1px solid #f0f0f0;
}
.btn {
  flex: 1; padding: 12px; border: none; border-radius: 12px;
  font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-primary { background: #1D9E75; color: #fff; }
.btn-primary:hover { background: #0F6E56; }
.btn-secondary { background: #f0f0f0; color: #185FA5; }
.btn-secondary:hover { background: #e0e0e0; }

/* MOBILE */
@media (max-width: 640px) {
  .topbar { padding: 12px 12px 14px; }
  .top-title { font-size: 14px; }
  .section-body { padding: 12px; }
  
  .company-info { margin-bottom: 12px; }
  .co-av { width: 36px; height: 36px; font-size: 12px; }
  .co-name-edit { font-size: 12px; }
  .co-wage-edit { font-size: 10px; }
  
  .w-card { padding: 10px 12px; margin-bottom: 6px; }
  .w-av { width: 36px; height: 36px; font-size: 12px; }
  .w-name { font-size: 12px; }
  .w-sub { font-size: 10px; }
  .check-circle { width: 20px; height: 20px; }
  
  .preview-box { margin: 12px; margin-bottom: 70px; padding: 12px; }
  .preview-title { font-size: 11px; margin-bottom: 6px; }
  .change-item { padding: 6px 0; font-size: 11px; }
  
  .action-buttons { padding: 10px; gap: 6px; }
  .btn { padding: 10px; font-size: 12px; }
}
</style>

<!-- TOPBAR -->
<div class="topbar">
  <div class="topbar-row">
    <div class="back-btn" onclick="window.history.back()">← رجوع</div>
    <div class="top-title">تعديل التوزيع</div>
    <div style="width:40px;"></div>
  </div>
  <div class="top-date">{{ $distribution->created_at->format('l، j F Y') }}</div>
</div>

<!-- COMPANY INFO -->
<div class="section-body">
  <p class="sec-label">الشركة والأجر</p>
  <div class="company-info">
    <div class="co-av">{{ substr($distribution->company->name, 0, 1) }}</div>
    <div style="flex:1;">
      <div class="co-name-edit">{{ $distribution->company->name }}</div>
      <div class="co-wage-edit">الأجر: {{ $distribution->company->daily_wage }} ج لكل عامل</div>
    </div>
  </div>

  <p class="sec-label">العمال المعينين</p>
</div>

<!-- WORKERS LIST -->
<div style="padding:0 16px;">
  <div class="select-all-row" style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;margin-bottom:6px;">
    <div style="font-size:12px;font-weight:600;color:#185FA5;cursor:pointer;" onclick="toggleSelectAll()">تحديد الكل</div>
    <div style="font-size:12px;color:#aaa;" id="selCount">{{ $distribution->workers_count }} محدد</div>
  </div>

  @foreach($workers as $worker)
  <div class="w-card {{ $distribution->workers->pluck('id')->contains($worker->id) ? 'selected' : '' }}" 
       onclick="toggleWorker(this, {{ $worker->id }}, '{{ $worker->name }}')">
    <div class="check-circle">✓</div>
    <div class="w-av">{{ substr($worker->name, 0, 1) }}</div>
    <div class="w-info">
      <div class="w-name">{{ $worker->name }}</div>
      <div class="w-sub">#{{ str_pad($worker->id, 3, '0', STR_PAD_LEFT) }}</div>
    </div>
  </div>
  @endforeach
</div>

<!-- PREVIEW -->
<div class="preview-box">
  <div class="preview-title">ملخص التغييرات</div>
  <div id="changesPreview">
    <div style="color:#aaa;font-size:12px;padding:8px;">لم يتم إجراء تغييرات بعد</div>
  </div>
</div>

<!-- ACTION BUTTONS -->
<div class="action-buttons">
  <button type="button" class="btn btn-secondary" onclick="window.history.back()">إلغاء</button>
  <button type="button" class="btn btn-primary" onclick="submitEdit()">حفظ التعديلات</button>
</div>

<script>
let originalWorkerIds = @json($distribution->workers->pluck('id')->toArray());
let selectedWorkerIds = [...originalWorkerIds];

function toggleWorker(el, id, name) {
  const idx = selectedWorkerIds.indexOf(id);
  if (idx > -1) {
    selectedWorkerIds.splice(idx, 1);
    el.classList.remove('selected');
  } else {
    selectedWorkerIds.push(id);
    el.classList.add('selected');
  }
  updatePreview();
}

function toggleSelectAll() {
  const cards = document.querySelectorAll('.w-card');
  if (selectedWorkerIds.length === cards.length) {
    selectedWorkerIds = [];
    cards.forEach(c => c.classList.remove('selected'));
  } else {
    selectedWorkerIds = [];
    cards.forEach(c => {
      c.classList.add('selected');
      const idMatch = c.textContent.match(/#(\d+)/);
      if (idMatch) selectedWorkerIds.push(parseInt(idMatch[1]));
    });
  }
  updatePreview();
}

function updatePreview() {
  const preview = document.getElementById('changesPreview');
  const added = selectedWorkerIds.filter(id => !originalWorkerIds.includes(id));
  const removed = originalWorkerIds.filter(id => !selectedWorkerIds.includes(id));
  
  if (added.length === 0 && removed.length === 0) {
    preview.innerHTML = '<div style="color:#aaa;font-size:12px;padding:8px;">لم يتم إجراء تغييرات بعد</div>';
    return;
  }
  
  let html = '';
  if (added.length > 0) {
    html += '<div style="margin-bottom:8px;"><span style="font-size:11px;color:#059669;font-weight:600;">مضاف:</span>' + 
            added.map(id => {
              const card = Array.from(document.querySelectorAll('.w-card')).find(c => c.textContent.includes('#' + String(id).padStart(3, '0')));
              return card ? '<div style="margin-top:4px;color:#222;font-size:11px;">+ ' + card.querySelector('.w-name').textContent + '</div>' : '';
            }).join('') + '</div>';
  }
  if (removed.length > 0) {
    html += '<div><span style="font-size:11px;color:#991B1B;font-weight:600;">مزال:</span>' + 
            removed.map(id => {
              const card = Array.from(document.querySelectorAll('.w-card')).find(c => c.textContent.includes('#' + String(id).padStart(3, '0')));
              return card ? '<div style="margin-top:4px;color:#222;font-size:11px;">- ' + card.querySelector('.w-name').textContent + '</div>' : '';
            }).join('') + '</div>';
  }
  preview.innerHTML = html;
  document.getElementById('selCount').textContent = selectedWorkerIds.length + ' محدد';
}

function submitEdit() {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ route("contractor.distributions.update", $distribution->id) }}';
  form.innerHTML = '@csrf @method("PUT")<input type="hidden" name="worker_ids" value="' + selectedWorkerIds.join(',') + '">';
  document.body.appendChild(form);
  form.submit();
}
</script>
@endsection
