@extends('layouts.app')

@section('title', 'إضافة توزيع جديد')

@section('page-title', 'إضافة توزيع جديد')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('distributions.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ التوزيع *</label>
                <input type="date" name="distribution_date" value="{{ old('distribution_date', now()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded">
                @error('distribution_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">التوزيعات *</label>
                <div id="assignments-container" class="space-y-3">
                    <div class="grid grid-cols-4 gap-3 assignment-row">
                        <div>
                            <select name="assignments[0][company_id]" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                                <option value="">-- اختر شركة --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="assignments[0][worker_id]" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                                <option value="">-- اختر عاملاً --</option>
                                @foreach($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div></div>
                        <button type="button" onclick="removeAssignment(this)" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" style="display:none;">حذف</button>
                    </div>
                </div>
                <button type="button" onclick="addAssignment()" class="mt-3 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">+ إضافة توزيع آخر</button>
            </div>

            <div class="flex gap-4 justify-end">
                <a href="{{ route('distributions.index') }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">إلغاء</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">حفظ</button>
            </div>
        </form>
    </div>
</div>

<script>
let assignmentCount = 1;

function addAssignment() {
    const container = document.getElementById('assignments-container');
    const html = `
        <div class="grid grid-cols-4 gap-3 assignment-row">
            <select name="assignments[${assignmentCount}][company_id]" class="px-4 py-2 border border-gray-300 rounded" required>
                <option value="">-- اختر شركة --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            <select name="assignments[${assignmentCount}][worker_id]" class="px-4 py-2 border border-gray-300 rounded" required>
                <option value="">-- اختر عاملاً --</option>
                @foreach($workers as $worker)
                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                @endforeach
            </select>
            <div></div>
            <button type="button" onclick="removeAssignment(this)" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">حذف</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    assignmentCount++;
}

function removeAssignment(btn) {
    btn.parentElement.remove();
}
</script>
@endsection
