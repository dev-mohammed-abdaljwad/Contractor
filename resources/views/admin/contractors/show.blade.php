@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
        <p class="text-gray-600 mt-2">{{ $user->email ?? 'بدون بريد إلكتروني' }}</p>
    </div>

    <!-- Contractor Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm mb-2">الشركات</div>
            <div class="text-3xl font-bold text-green-600">{{ $companies->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm mb-2">العمال</div>
            <div class="text-3xl font-bold text-blue-600">{{ $workers->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm mb-2">الدفعات الأخيرة</div>
            <div class="text-3xl font-bold text-purple-600">{{ $collections->count() }}</div>
        </div>
    </div>

    <!-- Companies Section -->
    <section class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">الشركات</h2>
        @if($companies->isEmpty())
            <p class="text-gray-500">لا توجد شركات</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الاسم</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الأجر اليومي</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">التوزيعات</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الدفعات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($companies as $company)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $company->daily_wage }} ج</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $company->distributions_count ?? 0 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $company->collections_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <!-- Collections Section -->
    <section class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">آخر الدفعات</h2>
        @if($collections->isEmpty())
            <p class="text-gray-500">لا توجد دفعات</p>
        @else
            <div class="space-y-4">
                @foreach($collections as $collection)
                    <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $collection->company->name }}</p>
                            <p class="text-sm text-gray-500">{{ $collection->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">{{ number_format($collection->total_amount, 2) }} ج</p>
                            <p class="text-xs text-gray-500">{{ $collection->workers_count ?? 0 }} عامل</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
