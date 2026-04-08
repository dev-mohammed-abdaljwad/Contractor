@extends('layouts.dashboard')

@section('title', 'إضافة خصم جديد')

@section('page-title', 'إضافة خصم جديد')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('deductions.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العامل *</label>
                <select name="worker_id" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    <option value="">-- اختر عاملاً --</option>
                    @foreach($workers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الشركة *</label>
                <select name="company_id" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    <option value="">-- اختر شركة --</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">التاريخ *</label>
                <input type="date" name="deduction_date" value="{{ $today }}" class="w-full px-4 py-2 border border-gray-300 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نوع الخصم *</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    <option value="quarter">ربع الأجر</option>
                    <option value="half">نصف الأجر</option>
                    <option value="full">الأجر الكامل</option>
                    <option value="custom">مبلغ محدد</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">المبلغ (للخصم المخصص)</label>
                <input type="number" name="amount" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">السبب</label>
                <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded"></textarea>
            </div>

            <div class="flex gap-4 justify-end">
                <a href="{{ route('deductions.index') }}" class="px-6 py-2 border border-gray-300 rounded">إلغاء</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
