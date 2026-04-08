@extends('layouts.dashboard')

@section('title', 'لوحة التحكم الإدارية')

@section('page-title', 'لوحة التحكم الإدارية')

@section('content')
<h3 class="text-lg font-semibold mb-6">المقاولون المسجلون</h3>

<div class="grid grid-cols-1 gap-4">
    @forelse($stats as $contractor)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800">{{ $contractor['name'] }}</h4>
                    <p class="text-gray-600 text-sm">{{ $contractor['phone'] }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">عدد الشركات</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $contractor['companies_count'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">عدد العمال</p>
                            <p class="text-2xl font-bold text-green-600">{{ $contractor['workers_count'] }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.contractors.show', $contractor['id']) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">عرض التفاصيل</a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500">لا توجد مقاولون مسجلون</p>
        </div>
    @endforelse
</div>
@endsection
