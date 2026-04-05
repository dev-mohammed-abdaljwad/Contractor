@extends('layouts.app')

@section('title', 'التوزيع اليومي')

@section('page-title', 'التوزيع اليومي')

@section('content')
<div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex gap-2">
        <input type="date" name="date" value="{{ $date }}" class="px-4 py-2 border border-gray-300 rounded">
        <button type="submit" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">بحث</button>
    </form>
    <a href="{{ route('distributions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ توزيع جديد</a>
</div>

<div class="grid gap-6">
    @forelse($summary as $companyId => $data)
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold mb-3">{{ $data['company_name'] }}</h4>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-gray-600 text-sm">عدد العمال</p>
                    <p class="text-2xl font-bold">{{ $data['worker_count'] }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">إجمالي الأجور</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($data['total_wages'], 2) }} جنيه</p>
                </div>
            </div>
            <p class="text-sm text-gray-600"><strong>العمال:</strong> {{ implode(', ', $data['worker_names']) }}</p>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500">لا توجد توزيعات في هذا التاريخ</p>
        </div>
    @endforelse
</div>
@endsection
