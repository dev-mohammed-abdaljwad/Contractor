@extends('layouts.dashboard')

@section('title', 'سجل الدفعات المقدمة')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">الدفعات المقدمة</h1>
                <div class="mt-2 flex items-center gap-2 text-gray-600">
                    <a href="{{ route('contractor.workers.index') }}" class="hover:text-blue-600">العمال</a>
                    <span>/</span>
                    <a href="{{ route('contractor.workers.show', $worker) }}" class="hover:text-blue-600">{{ $worker->name }}</a>
                    <span>/</span>
                    <span class="font-semibold">الدفعات المقدمة</span>
                </div>
            </div>
            <button onclick="openRecordAdvanceModal()" class="btn btn-primary">
                <span>+ تسجيل دفعة مقدمة</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600 mb-1">إجمالي المعلق</div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-red-600">{{ number_format($summary['total_pending'], 0) }}</span>
                    <span class="text-sm text-gray-500">ج</span>
                </div>
                <div class="text-xs text-gray-400 mt-2">{{ $summary['total_pending_count'] }} دفعة</div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600 mb-1">الشهر الحالي</div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($summary['monthly_total'], 0) }}</span>
                    <span class="text-sm text-gray-500">ج</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600 mb-1">المحصل</div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-green-600">{{ number_format($summary['collected_total'], 0) }}</span>
                    <span class="text-sm text-gray-500">ج</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600 mb-1">الإجمالي</div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900">{{ number_format($summary['monthly_total'] + $summary['collected_total'], 0) }}</span>
                    <span class="text-sm text-gray-500">ج</span>
                </div>
            </div>
        </div>

        <!-- Filter Chips -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterAdvances('all')" class="filter-chip active" data-filter="all">
                    الكل
                </button>
                <button onclick="filterAdvances('pending')" class="filter-chip" data-filter="pending">
                    معلق
                </button>
                <button onclick="filterAdvances('collected')" class="filter-chip" data-filter="collected">
                    مكتمل
                </button>
            </div>
        </div>

        <!-- Advances Timeline -->
        <div class="space-y-6">
            @forelse($advances->groupBy(function($item) { return $item->date->format('Y-m'); }) as $monthGroup => $monthAdvances)
                @php
                    $monthName = \Carbon\Carbon::createFromFormat('Y-m', $monthGroup)->locale('ar')->translatedFormat('F Y');
                @endphp
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-1 h-px bg-gray-300"></div>
                        <h3 class="text-lg font-semibold text-gray-700 whitespace-nowrap">{{ $monthName }}</h3>
                        <div class="flex-1 h-px bg-gray-300"></div>
                    </div>

                    <div class="space-y-4">
                        @foreach($monthAdvances as $advance)
                            <div class="bg-white rounded-lg shadow hover:shadow-md transition p-6" data-filter="{{ $advance->is_fully_collected ? 'collected' : 'pending' }}">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="text-lg font-bold text-gray-900">{{ $advance->date->locale('ar')->translatedFormat('d F Y') }}</span>
                                            <span class="text-xs px-3 py-1 rounded-full font-medium {{ 
                                                $advance->is_fully_collected 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-orange-100 text-orange-800'
                                            }}">
                                                {{ $advance->is_fully_collected ? 'مكتمل' : 'معلق' }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">المبلغ الأصلي:</span>
                                                <span class="font-semibold text-gray-900 mr-1">{{ number_format($advance->amount, 0) }} ج</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">طريقة الاسترجاع:</span>
                                                <span class="font-semibold text-gray-900 mr-1">
                                                    @if($advance->recovery_method === 'immediately')
                                                        فري
                                                    @elseif($advance->recovery_method === 'installments')
                                                        أقساط ({{ $advance->installment_period === 'weekly' ? 'أسبوعي' : 'نصف أسبوعي' }})
                                                    @else
                                                        يدوي
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        @if($advance->reason)
                                            <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-2 rounded border-ر border-gray-200">
                                                <strong>السبب:</strong> {{ $advance->reason }}
                                            </div>
                                        @endif

                                        <!-- Progress Bar -->
                                        <div class="mt-4">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs text-gray-600">التحصيل</span>
                                                <span class="text-xs font-semibold text-gray-900">{{ number_format($advance->amount_collected, 0) }} / {{ number_format($advance->amount, 0) }} ج</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($advance->amount_collected / $advance->amount) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <button onclick="openRecoveryMethodModal({{ $advance->id }})" class="btn btn-outline btn-sm">
                                            تغيير الطريقة
                                        </button>
                                        @if(!$advance->is_fully_collected)
                                            <button onclick="openCollectionModal({{ $advance->id }}, {{ $advance->amount_pending }})" class="btn btn-warning btn-sm">
                                                تسجيل دفعة
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Installments Section (if applicable) -->
                                @if($advance->recovery_method === 'installments' && $advance->installments->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">الأقساط</h4>
                                        <div class="space-y-2">
                                            @foreach($advance->installments as $installment)
                                                <div class="flex items-center justify-between text-sm p-2 bg-gray-50 rounded">
                                                    <span class="text-gray-600">{{ $installment->installment_number }}. {{ $installment->due_date->locale('ar')->translatedFormat('d F Y') }}</span>
                                                    <div class="flex items-center gap-4">
                                                        <span class="font-semibold text-gray-900">{{ number_format($installment->amount, 0) }} ج</span>
                                                        <span class="text-xs px-2 py-1 rounded {{ $installment->is_paid ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                                            {{ $installment->is_paid ? 'مدفوع' : 'معلق' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <div class="text-gray-400 text-6xl mb-4">📋</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد دفعات مقدمة</h3>
                    <p class="text-gray-600 mb-6">لم يتم تسجيل أي دفعات مقدمة لهذا العامل حتى الآن</p>
                    <button onclick="openRecordAdvanceModal()" class="btn btn-primary">
                        تسجيل دفعة مقدمة الآن
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Record Advance Modal -->
<div id="recordAdvanceModal" class="modal modal-lg" style="display: none;">
    <form id="recordAdvanceForm" method="POST" action="{{ route('contractor.advances.store', $worker) }}" class="modal-content p-8">
        @csrf
        <h2 class="text-2xl font-bold text-gray-900 mb-6">تسجيل دفعة مقدمة</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ *</label>
                <input type="number" name="amount" step="0.01" required class="input input-bordered w-full" placeholder="0.00">
                @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">التاريخ *</label>
                <input type="date" name="date" required class="input input-bordered w-full" max="{{ date('Y-m-d') }}">
                @error('date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Installment Fields (hidden by default) -->
            <div id="installmentFields" style="display: none;" class="space-y-4 pt-4 border-t border-gray-300">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">فترة القسط</label>
                    <select name="installment_period" class="input input-bordered w-full">
                        <option value="">-- اختر الفترة --</option>
                        <option value="weekly">أسبوعي</option>
                        <option value="biweekly">نصف أسبوعي</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">عدد الأقساط</label>
                    <input type="number" name="installment_count" min="2" class="input input-bordered w-full" placeholder="2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">السبب (اختياري)</label>
                <textarea name="reason" class="textarea textarea-bordered w-full" placeholder="ملاحظات عن الدفعة المقدمة..." rows="3"></textarea>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeRecordAdvanceModal()" class="btn btn-ghost flex-1">إلغاء</button>
            <button type="submit" class="btn btn-primary flex-1">تسجيل الدفعة</button>
        </div>
    </form>
</div>

<!-- Recovery Method Modal -->
<div id="recoveryMethodModal" class="modal modal-lg" style="display: none;">
    <form id="recoveryMethodForm" method="POST" class="modal-content p-8">
        @csrf
        @method('PATCH')
        <h2 class="text-2xl font-bold text-gray-900 mb-6">تغيير طريقة الاسترجاع</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">طريقة الاسترجاع *</label>
                <select id="updateRecoveryMethod" name="recovery_method" required class="input input-bordered w-full" onchange="toggleUpdateInstallmentFields()">
                    <option value="">-- اختر الطريقة --</option>
                    <option value="immediately">فري (خصم فوري من الراتب التالي)</option>
                    <option value="installments">أقساط</option>
                    <option value="manually">يدوي (تقرر متى تحتسبها)</option>
                </select>
            </div>

            <div id="updateInstallmentFields" style="display: none;" class="space-y-4 pt-4 border-t border-gray-300">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">فترة القسط</label>
                    <select name="installment_period" class="input input-bordered w-full">
                        <option value="">-- اختر الفترة --</option>
                        <option value="weekly">أسبوعي</option>
                        <option value="biweekly">نصف أسبوعي</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">عدد الأقساط</label>
                    <input type="number" name="installment_count" min="2" class="input input-bordered w-full" placeholder="2">
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeRecoveryMethodModal()" class="btn btn-ghost flex-1">إلغاء</button>
            <button type="submit" class="btn btn-primary flex-1">تحديث</button>
        </div>
    </form>
</div>

<!-- Collection Modal -->
<div id="collectionModal" class="modal modal-lg" style="display: none;">
    <form id="collectionForm" method="POST" class="modal-content p-8">
        @csrf
        <h2 class="text-2xl font-bold text-gray-900 mb-6">تسجيل دفعة</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ المعلق *</label>
                <div id="pendingAmount" class="text-2xl font-bold text-red-600 mb-4"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ المدفوع *</label>
                <input type="number" id="collectionAmount" name="amount" step="0.01" required class="input input-bordered w-full" placeholder="0.00">
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeCollectionModal()" class="btn btn-ghost flex-1">إلغاء</button>
            <button type="submit" class="btn btn-primary flex-1">تسجيل الدفعة</button>
        </div>
    </form>
</div>

<style>
    .btn {
        @apply px-4 py-2 rounded font-medium transition duration-200 cursor-pointer inline-flex items-center justify-center gap-2;
    }

    .btn-primary {
        @apply bg-blue-600 text-white hover:bg-blue-700;
    }

    .btn-ghost {
        @apply bg-transparent text-gray-700 hover:bg-gray-100 border border-gray-300;
    }

    .btn-outline {
        @apply border border-gray-300 text-gray-700 hover:bg-gray-100;
    }

    .btn-warning {
        @apply bg-orange-500 text-white hover:bg-orange-600;
    }

    .btn-sm {
        @apply px-3 py-1 text-sm h-8;
    }

    .filter-chip {
        @apply px-4 py-2 rounded-full text-sm font-medium border-2 border-gray-300 text-gray-700 hover:border-blue-600 transition cursor-pointer;
    }

    .filter-chip.active {
        @apply bg-blue-600 text-white border-blue-600;
    }

    .modal {
        @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50;
    }

    .modal-content {
        @apply bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto;
    }

    .input,
    .textarea,
    select {
        @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500;
    }
</style>

<script>
function openRecordAdvanceModal() {
    document.getElementById('recordAdvanceModal').style.display = 'flex';
}

function closeRecordAdvanceModal() {
    document.getElementById('recordAdvanceModal').style.display = 'none';
    document.getElementById('recordAdvanceForm').reset();
    document.getElementById('installmentFields').style.display = 'none';
}

function openRecoveryMethodModal(advanceId) {
    const form = document.getElementById('recoveryMethodForm');
    form.action = `/contractor/advances/${advanceId}/recovery-method`;
    document.getElementById('recoveryMethodModal').style.display = 'flex';
}

function closeRecoveryMethodModal() {
    document.getElementById('recoveryMethodModal').style.display = 'none';
    document.getElementById('recoveryMethodForm').reset();
    document.getElementById('updateInstallmentFields').style.display = 'none';
}

function openCollectionModal(advanceId, pending) {
    const form = document.getElementById('collectionForm');
    form.action = `/contractor/advances/${advanceId}/record-collection`;
    document.getElementById('pendingAmount').textContent = pending.toLocaleString() + ' ج';
    document.getElementById('collectionAmount').placeholder = pending.toFixed(2);
    document.getElementById('collectionModal').style.display = 'flex';
}

function closeCollectionModal() {
    document.getElementById('collectionModal').style.display = 'none';
    document.getElementById('collectionForm').reset();
}

function toggleInstallmentFields() {
    const method = document.getElementById('recoveryMethod').value;
    const fields = document.getElementById('installmentFields');
    fields.style.display = method === 'installments' ? 'block' : 'none';
}

function toggleUpdateInstallmentFields() {
    const method = document.getElementById('updateRecoveryMethod').value;
    const fields = document.getElementById('updateInstallmentFields');
    fields.style.display = method === 'installments' ? 'block' : 'none';
}

function filterAdvances(filter) {
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.classList.remove('active');
    });
    event.target.classList.add('active');

    const advances = document.querySelectorAll('[data-filter]');
    advances.forEach(advance => {
        if (filter === 'all' || advance.getAttribute('data-filter') === filter) {
            advance.style.display = 'block';
        } else {
            advance.style.display = 'none';
        }
    });
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
});
</script>
@endsection
