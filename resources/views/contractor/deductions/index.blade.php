@extends('layouts.dashboard')

@section('title', 'الخصومات')

@section('page-title', 'الخصومات')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('deductions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ خصم جديد</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($deductions->isEmpty())
        <div class="p-8 text-center text-gray-500">لا توجد خصومات</div>
    @else
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-right">العامل</th>
                    <th class="px-6 py-3 text-right">الشركة</th>
                    <th class="px-6 py-3 text-right">التاريخ</th>
                    <th class="px-6 py-3 text-right">النوع</th>
                    <th class="px-6 py-3 text-right">المبلغ</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($deductions as $ded)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $ded->worker->name }}</td>
                        <td class="px-6 py-4">{{ $ded->company->name }}</td>
                        <td class="px-6 py-4">{{ $ded->deduction_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">{{ $ded->type }}</td>
                        <td class="px-6 py-4 font-semibold">{{ number_format($ded->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('deductions.destroy', $ded->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('هل تريد الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{ $deductions->links() }}
@endsection
