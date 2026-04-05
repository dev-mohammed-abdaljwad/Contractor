@extends('layouts.dashboard')

@section('content')
<div style="padding:28px">
    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px">
        <h1 style="font-size:24px;font-weight:700;color:#1a1c19;margin:0">العمال</h1>
        <button onclick="openWorkerModal(false)" style="display:inline-flex;align-items:center;gap:8px;padding:0 20px;height:42px;background:#0d631b;color:#fff;border:none;border-radius:8px;font-family:'Tajawal','sans-serif';font-size:14px;font-weight:700;cursor:pointer;transition:all 0.15s"
                onmouseover="this.style.background='#0a5216'"
                onmouseout="this.style.background='#0d631b'">
            <span class="ms" style="font-size:18px">add</span> عامل جديد
        </button>
    </div>

    <!-- Search and Filter -->
    <div style="margin-bottom:20px">
        <!-- Filter Chips -->
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px">
            <a href="{{ route('contractor.workers.index', array_merge(request()->query(), ['filter' => 'all'])) }}" style="padding:8px 16px;border-radius:20px;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s;border:1px solid {{ $filter === 'all' ? '#0d631b' : '#d0d0c8' }};background:{{ $filter === 'all' ? '#E1F5EE' : '#fff' }};color:{{ $filter === 'all' ? '#0d631b' : '#707a6c' }};text-decoration:none"
               onmouseover="this.style.background='{{ $filter === 'all' ? '#0d631b' : '#fafaf5' }}'; this.style.color='{{ $filter === 'all' ? '#fff' : '#1a1c19' }}'"
               onmouseout="this.style.background='{{ $filter === 'all' ? '#E1F5EE' : '#fff' }}'; this.style.color='{{ $filter === 'all' ? '#0d631b' : '#707a6c' }}'">
                🔄 الكل
            </a>
            <a href="{{ route('contractor.workers.index', array_merge(request()->query(), ['filter' => 'assigned'])) }}" style="padding:8px 16px;border-radius:20px;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s;border:1px solid {{ $filter === 'assigned' ? '#0d631b' : '#d0d0c8' }};background:{{ $filter === 'assigned' ? '#E1F5EE' : '#fff' }};color:{{ $filter === 'assigned' ? '#0d631b' : '#707a6c' }};text-decoration:none"
               onmouseover="this.style.background='{{ $filter === 'assigned' ? '#0d631b' : '#fafaf5' }}'; this.style.color='{{ $filter === 'assigned' ? '#fff' : '#1a1c19' }}'"
               onmouseout="this.style.background='{{ $filter === 'assigned' ? '#E1F5EE' : '#fff' }}'; this.style.color='{{ $filter === 'assigned' ? '#0d631b' : '#707a6c' }}'">
                ✅ موكلين اليوم
            </a>
            <a href="{{ route('contractor.workers.index', array_merge(request()->query(), ['filter' => 'unassigned'])) }}" style="padding:8px 16px;border-radius:20px;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s;border:1px solid {{ $filter === 'unassigned' ? '#0d631b' : '#d0d0c8' }};background:{{ $filter === 'unassigned' ? '#E1F5EE' : '#fff' }};color:{{ $filter === 'unassigned' ? '#0d631b' : '#707a6c' }};text-decoration:none"
               onmouseover="this.style.background='{{ $filter === 'unassigned' ? '#0d631b' : '#fafaf5' }}'; this.style.color='{{ $filter === 'unassigned' ? '#fff' : '#1a1c19' }}'"
               onmouseout="this.style.background='{{ $filter === 'unassigned' ? '#E1F5EE' : '#fff' }}'; this.style.color='{{ $filter === 'unassigned' ? '#0d631b' : '#707a6c' }}'">
                ⏳ متاحين
            </a>
            <a href="{{ route('contractor.workers.index', array_merge(request()->query(), ['filter' => 'has_advance'])) }}" style="padding:8px 16px;border-radius:20px;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s;border:1px solid {{ $filter === 'has_advance' ? '#0d631b' : '#d0d0c8' }};background:{{ $filter === 'has_advance' ? '#E1F5EE' : '#fff' }};color:{{ $filter === 'has_advance' ? '#0d631b' : '#707a6c' }};text-decoration:none"
               onmouseover="this.style.background='{{ $filter === 'has_advance' ? '#0d631b' : '#fafaf5' }}'; this.style.color='{{ $filter === 'has_advance' ? '#fff' : '#1a1c19' }}'"
               onmouseout="this.style.background='{{ $filter === 'has_advance' ? '#E1F5EE' : '#fff' }}'; this.style.color='{{ $filter === 'has_advance' ? '#0d631b' : '#707a6c' }}'">
                💰 لديهم متقدمات
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap">
            <input 
                type="hidden" 
                name="filter" 
                value="{{ $filter }}"
            >
            <input 
                type="text" 
                name="search" 
                placeholder="ابحث عن اسم أو رقم أو جوال..."
                value="{{ $search }}"
                style="flex:1;min-width:200px;height:42px;border:1px solid #d0d0c8;border-radius:8px;background:#fafaf5;font-family:'Tajawal','sans-serif';font-size:14px;color:#1a1c19;padding:0 12px;outline:none"
                onfocus="this.style.borderColor='#0d631b'; this.style.background='#fff'"
                onblur="this.style.borderColor='#d0d0c8'; this.style.background='#fafaf5'"
                oninput="this.form.submit()"
            >
            <button 
                type="submit"
                style="height:42px;padding:0 20px;background:#0d631b;color:#fff;border:none;border-radius:8px;font-family:'Tajawal','sans-serif';font-size:14px;font-weight:700;cursor:pointer;transition:all 0.15s"
                onmouseover="this.style.background='#0a5216'"
                onmouseout="this.style.background='#0d631b'"
            >
                بحث
            </button>
        </form>
    </div>

    <!-- Active Workers List -->
    @if($activeWorkers && $activeWorkers->count() > 0)
        <div style="margin-bottom:32px">
            <h2 style="font-size:18px;font-weight:700;color:#1a1c19;margin:0 0 16px 0">العمال النشطين</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px">
                @foreach($activeWorkers as $worker)
                    <div onclick="window.location.href='{{ route('contractor.workers.show', $worker) }}'" style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:20px;cursor:pointer;transition:all 0.2s;display:flex;flex-direction:column;gap:16px"
                         onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'; this.style.transform='translateY(-2px)'"
                         onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                        
                        <!-- Worker Header -->
                        <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                            <div style="flex:1">
                                <h3 style="font-size:15px;font-weight:700;color:#1a1c19;margin:0 0 4px 0;word-break:break-word">{{ $worker->name }}</h3>
                                <p style="font-size:11px;color:#707a6c;margin:0">رقم: #{{ $worker->id }}</p>
                                @if($worker->has_pending_advance)
                                    <p style="font-size:10px;color:#BA7517;margin:4px 0 0 0;font-weight:500">💰 متقدمات: {{ number_format($worker->pending_advance_amount, 2) }} جنيه</p>
                                @endif
                            </div>
                            <span style="padding:4px 10px;border-radius:6px;font-size:10px;font-weight:500;background:{{ $worker->assigned_today ? '#E1F5EE' : '#fffaf5' }};color:{{ $worker->assigned_today ? '#0F6E56' : '#BA7517' }};white-space:nowrap">
                                {{ $worker->assigned_today ? '✅ موجود' : '⏳ متاح' }}
                            </span>
                        </div>

                        <!-- Today's Assignment -->
                        <div style="padding:12px;background:#fafaf5;border-radius:8px;border:0.5px solid #e8e8e3">
                            <div style="font-size:10px;color:#707a6c;margin-bottom:6px;font-weight:500">اليوم</div>
                            <div style="font-size:13px;font-weight:600;color:#1a1c19;word-break:break-word">
                                {{ $worker->assigned_company ?? '❌ غير موكل' }}
                            </div>
                        </div>

                        <!-- Monthly Attendance -->
                        <div style="display:flex;flex-direction:column;gap:8px">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span style="font-size:10px;color:#707a6c;font-weight:500">الحضور الشهري</span>
                                <span style="font-size:11px;font-weight:600;color:#0d631b">{{ $worker->attendance_rate }}%</span>
                            </div>
                            <!-- Progress Bar -->
                            <div style="width:100%;height:6px;background:#e8e8e3;border-radius:3px;overflow:hidden">
                                <div style="width:{{ $worker->attendance_rate }}%;height:100%;background:{{ $worker->attendance_rate >= 80 ? '#0d631b' : ($worker->attendance_rate >= 50 ? '#BA7517' : '#ba1a1a') }};transition:width 0.3s ease">
                                </div>
                            </div>
                            <span style="font-size:10px;color:#707a6c">{{ $worker->days_worked }} أيام من أصل شهر</span>
                        </div>

                        <!-- Quick Actions -->
                        <div style="display:flex;gap:8px;border-top:0.5px solid #e8e8e3;padding-top:12px">
                            <button 
                                onclick="event.stopPropagation(); openWorkerModal(true, {{ $worker->id }})"
                                style="flex:1;height:36px;background:#fff;border:1px solid #d0d0c8;border-radius:6px;color:#0d631b;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s"
                                onmouseover="this.style.background='#E1F5EE'"
                                onmouseout="this.style.background='#fff'"
                            >
                                تعديل
                            </button>
                            <button 
                                onclick="event.stopPropagation(); deactivateWorker({{ $worker->id }})"
                                style="flex:1;height:36px;background:#fff5f5;border:1px solid #f0e0e0;border-radius:6px;color:#ba1a1a;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s"
                                onmouseover="this.style.background='#ffe8e8'"
                                onmouseout="this.style.background='#fff5f5'"
                            >
                                إيقاف
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:40px;text-align:center;margin-bottom:32px">
            <div style="font-size:48px;margin-bottom:12px">{{ $search || $filter !== 'all' ? '🔍' : '👷' }}</div>
            <h3 style="font-size:16px;font-weight:700;color:#1a1c19;margin:0 0 8px 0">
                {{ $search || $filter !== 'all' ? 'لم نجد عمال بهذه المعايير' : 'لا توجد عمال بعد' }}
            </h3>
            <p style="font-size:13px;color:#707a6c;margin:0 0 16px 0">
                {{ $search || $filter !== 'all' ? 'جرب تغيير معايير البحث أو الفلترة' : 'ابدأ الآن بإضافة عمالك للنظام' }}
            </p>
            @if($search || $filter !== 'all')
                <button 
                    onclick="window.location.href='{{ route('contractor.workers.index') }}'"
                    style="display:inline-flex;align-items:center;gap:8px;padding:0 20px;height:42px;background:#707a6c;color:#fff;border:none;border-radius:8px;font-family:'Tajawal','sans-serif';font-size:14px;font-weight:700;cursor:pointer;transition:all 0.15s;margin-right:8px"
                    onmouseover="this.style.background='#5a6259'"
                    onmouseout="this.style.background='#707a6c'"
                >
                    إعادة تعيين البحث
                </button>
            @endif
            <button 
                onclick="openWorkerModal(false)"
                style="display:inline-flex;align-items:center;gap:8px;padding:0 20px;height:42px;background:#0d631b;color:#fff;border:none;border-radius:8px;font-family:'Tajawal','sans-serif';font-size:14px;font-weight:700;cursor:pointer;transition:all 0.15s"
                onmouseover="this.style.background='#0a5216'"
                onmouseout="this.style.background='#0d631b'"
            >
                <span class="ms" style="font-size:18px">add</span> إضافة عامل جديد
            </button>
        </div>
    @endif

    <!-- Inactive Workers Section -->
    @if($inactiveWorkers && $inactiveWorkers->count() > 0)
        <div style="margin-bottom:32px">
            <h2 style="font-size:18px;font-weight:700;color:#ba1a1a;margin:0 0 16px 0">العمال المحذوفة مؤقتاً</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px">
                @foreach($inactiveWorkers as $worker)
                    <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:20px;display:flex;flex-direction:column;gap:16px;opacity:0.75">
                        
                        <!-- Worker Header -->
                        <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                            <div style="flex:1">
                                <h3 style="font-size:15px;font-weight:700;color:#1a1c19;margin:0 0 4px 0;word-break:break-word">{{ $worker->name }}</h3>
                                <p style="font-size:11px;color:#707a6c;margin:0">رقم: #{{ $worker->id }}</p>
                                @if($worker->has_pending_advance)
                                    <p style="font-size:10px;color:#BA7517;margin:4px 0 0 0;font-weight:500">💰 متقدمات: {{ number_format($worker->pending_advance_amount, 2) }} جنيه</p>
                                @endif
                            </div>
                            <span style="padding:4px 10px;border-radius:6px;font-size:10px;font-weight:500;background:#f5f5f5;color:#707a6c;white-space:nowrap">
                                ⏸️ موقوف
                            </span>
                        </div>

                        <!-- Worker Contact Info -->
                        <div style="padding:12px;background:#fafaf5;border-radius:8px;border:0.5px solid #e8e8e3">
                            <div style="font-size:10px;color:#707a6c;margin-bottom:6px;font-weight:500">جهات الاتصال</div>
                            <div style="font-size:13px;font-weight:600;color:#1a1c19;word-break:break-word">
                                {{ $worker->phone }}
                            </div>
                        </div>

                        <!-- Monthly Attendance (for reference) -->
                        <div style="display:flex;flex-direction:column;gap:8px">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span style="font-size:10px;color:#707a6c;font-weight:500">الحضور الشهري</span>
                                <span style="font-size:11px;font-weight:600;color:#0d631b">{{ $worker->attendance_rate }}%</span>
                            </div>
                            <!-- Progress Bar -->
                            <div style="width:100%;height:6px;background:#e8e8e3;border-radius:3px;overflow:hidden">
                                <div style="width:{{ $worker->attendance_rate }}%;height:100%;background:{{ $worker->attendance_rate >= 80 ? '#0d631b' : ($worker->attendance_rate >= 50 ? '#BA7517' : '#ba1a1a') }};transition:width 0.3s ease">
                                </div>
                            </div>
                            <span style="font-size:10px;color:#707a6c">{{ $worker->days_worked }} أيام من أصل شهر</span>
                        </div>

                        <!-- Quick Actions -->
                        <div style="display:flex;gap:8px;border-top:0.5px solid #e8e8e3;padding-top:12px">
                            <button 
                                onclick="reactivateWorker({{ $worker->id }})"
                                style="flex:1;height:36px;background:#E1F5EE;border:1px solid #0d631b;border-radius:6px;color:#0d631b;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s"
                                onmouseover="this.style.background='#0d631b'; this.style.color='#fff'"
                                onmouseout="this.style.background='#E1F5EE'; this.style.color='#0d631b'"
                            >
                                ✅ تفعيل مجدداً
                            </button>
                            <button 
                                onclick="if(confirm('هل تريد حذف هذا العامل نهائياً؟')) { document.getElementById('deleteWorkerForm{{ $worker->id }}').submit(); }"
                                style="flex:1;height:36px;background:#fff5f5;border:1px solid #f0e0e0;border-radius:6px;color:#ba1a1a;font-family:'Tajawal','sans-serif';font-size:12px;font-weight:600;cursor:pointer;transition:all 0.15s"
                                onmouseover="this.style.background='#ffe8e8'"
                                onmouseout="this.style.background='#fff5f5'"
                            >
                                حذف نهائياً
                            </button>
                            <form id="deleteWorkerForm{{ $worker->id }}" method="POST" action="{{ route('contractor.workers.destroy', $worker) }}" style="display:none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    @media(max-width: 768px) {
        [style*="grid-template-columns:repeat(auto-fill,minmax(280px,1fr)"] {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
        }

        [style*="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px"] {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px !important;
        }

        h1[style*="font-size:24px"] {
            font-size: 20px !important;
        }

        [style*="padding:28px"] {
            padding: 16px !important;
            padding-bottom: 100px !important;
        }
    }

    @media(max-width: 480px) {
        [style*="grid-template-columns:repeat(auto-fill,minmax(280px,1fr)"] {
            grid-template-columns: 1fr !important;
        }

        [style*="padding:28px"] {
            padding: 12px !important;
            padding-bottom: 95px !important;
        }

        h1[style*="font-size:24px"] {
            font-size: 18px !important;
        }

        [style*="display:flex;justify-content:space-between;align-items:start;gap:12px"] {
            gap: 8px !important;
        }

        [style*="display:flex;gap:12px;flex-wrap:wrap"] {
            gap: 8px !important;
        }

        input[type="text"],
        button[type="submit"] {
            font-size: 13px !important;
            height: 40px !important;
        }

        [style*="padding:20px"] {
            padding: 14px !important;
        }

        h3[style*="font-size:15px"] {
            font-size: 13px !important;
        }

        [style*="padding:12px;background:#fafaf5"] {
            padding: 10px !important;
            gap: 8px !important;
        }

        [style*="display:flex;gap:8px;border-top"] {
            gap: 6px !important;
        }
    }
</style>

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
</script>

@endsection

