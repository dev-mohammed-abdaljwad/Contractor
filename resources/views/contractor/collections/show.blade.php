@extends('layouts.dashboard')

@section('title', 'الفاتورة')

@section('page-title', 'الفاتورة - ' . $collection->company->name)

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold mb-4">تفاصيل الفاتورة</h3>
            <div class="space-y-2 text-sm">
                <p><strong>الشركة:</strong> {{ $collection->company->name }}</p>
                <p><strong>الفترة:</strong> {{ $collection->period_start->format('Y-m-d') }} إلى {{ $collection->period_end->format('Y-m-d') }}</p>
                <p><strong>عدد الأيام:</strong> {{ $collection->total_days_worked }}</p>
            </div>
        </div>
        <div>
            <h3 class="text-lg font-semibold mb-4">الحسابات</h3>
            <div class="space-y-2 text-sm">
                <p><strong>إجمالي الأجور:</strong> <span class="text-blue-600">{{ number_format($collection->total_wages, 2) }} جنيه</span></p>
                <p><strong>الخصومات:</strong> <span class="text-red-600">{{ number_format($collection->total_deductions, 2) }} جنيه</span></p>
                <p class="border-t pt-2"><strong>الصافي:</strong> <span class="text-green-600 font-bold text-lg">{{ number_format($collection->net_amount, 2) }} جنيه</span></p>
            </div>
        </div>
    </div>
</div>

@if(!$collection->is_paid)
    <div class="bg-blue-50 rounded-lg p-6 mb-6">
        <h3 class="font-semibold mb-4">تسجيل الدفع</h3>
        <form method="POST" action="{{ route('collections.pay', $collection->id) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <select name="payment_method" class="px-4 py-2 border border-gray-300 rounded" required>
                    <option value="">-- اختر طريقة الدفع --</option>
                    <option value="cash">نقداً</option>
                    <option value="transfer">تحويل بنكي</option>
                    <option value="cheque">شيك</option>
                </select>
                <input type="date" name="payment_date" class="px-4 py-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">تسجيل الدفع</button>
        </form>
    </div>
@else
    <div class="bg-green-50 rounded-lg p-6 mb-6">
        <p class="font-semibold text-green-800">تم تسجيل الدفع في {{ $collection->payment_date->format('Y-m-d') }} بطريقة {{ $collection->payment_method }}</p>
    </div>
@endif
@endsection
