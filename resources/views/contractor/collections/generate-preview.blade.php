@extends('layouts.dashboard')

@section('title', 'معاينة الفاتورة')

@section('page-title', 'معاينة الفاتورة')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4">ملخص الفاتورة</h3>
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <p><strong>الشركة:</strong> {{ \App\Models\Company::find($statement['company_id'])->name }}</p>
            <p><strong>الفترة:</strong> {{ $statement['period_start'] }} إلى {{ $statement['period_end'] }}</p>
        </div>
        <div class="text-right">
            <p><strong>عدد الأيام:</strong> {{ $statement['total_days_worked'] }}</p>
            <p><strong>إجمالي الأجور:</strong> <span class="text-blue-600">{{ number_format($statement['total_wages'], 2) }} جنيه</span></p>
            <p><strong>الخصومات:</strong> <span class="text-red-600">{{ number_format($statement['total_deductions'], 2) }} جنيه</span></p>
            <p class="text-lg"><strong>الصافي:</strong> <span class="text-green-600 font-bold">{{ number_format($statement['net_amount'], 2) }} جنيه</span></p>
        </div>
    </div>

    <h4 class="font-semibold mb-2">تفاصيل التوزيعات</h4>
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-right">التاريخ</th>
                    <th class="px-4 py-2 text-right">العامل</th>
                    <th class="px-4 py-2 text-right">الأجر</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($statement['distribution_details'] as $dist)
                    <tr>
                        <td class="px-4 py-2">{{ $dist['date']->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">{{ $dist['worker_name'] }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($dist['wage'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($statement['deduction_details']) > 0)
        <h4 class="font-semibold mb-2">الخصومات</h4>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-right">التاريخ</th>
                        <th class="px-4 py-2 text-right">العامل</th>
                        <th class="px-4 py-2 text-right">النوع</th>
                        <th class="px-4 py-2 text-right">المبلغ</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($statement['deduction_details'] as $ded)
                        <tr>
                            <td class="px-4 py-2">{{ $ded['date']->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $ded['worker_name'] }}</td>
                            <td class="px-4 py-2">{{ $ded['type'] }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($ded['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <form method="POST" action="{{ route('collections.store') }}" class="flex gap-4 justify-end">
        @csrf
        <input type="hidden" name="contractor_id" value="{{ auth()->id() }}">
        <input type="hidden" name="company_id" value="{{ $statement['company_id'] }}">
        <input type="hidden" name="period_start" value="{{ $statement['period_start'] }}">
        <input type="hidden" name="period_end" value="{{ $statement['period_end'] }}">
        <input type="hidden" name="total_days_worked" value="{{ $statement['total_days_worked'] }}">
        <input type="hidden" name="total_wages" value="{{ $statement['total_wages'] }}">
        <input type="hidden" name="total_deductions" value="{{ $statement['total_deductions'] }}">
        <input type="hidden" name="net_amount" value="{{ $statement['net_amount'] }}">

        <a href="{{ route('collections.index') }}" class="px-6 py-2 border border-gray-300 rounded">إلغاء</a>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">حفظ الفاتورة</button>
    </form>
</div>
@endsection
