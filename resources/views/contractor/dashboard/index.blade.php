@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('page-title', 'لوحة التحكم')

@section('content')
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-1">
                <p class="text-gray-600 text-sm">العمال الموزعين اليوم</p>
                <p class="text-3xl font-bold text-blue-600">{{ $workersDistributedToday }}</p>
            </div>
            <div class="text-4xl text-blue-200">👷</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-1">
                <p class="text-gray-600 text-sm">الشركات النشطة</p>
                <p class="text-3xl font-bold text-green-600">{{ $activeCompaniesCount }}</p>
            </div>
            <div class="text-4xl text-green-200">🏢</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-1">
                <p class="text-gray-600 text-sm">إجمالي الأجور اليوم</p>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($totalWagesToday, 2) }} جنيه</p>
            </div>
            <div class="text-4xl text-purple-200">💰</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-1">
                <p class="text-gray-600 text-sm">الفواتير المعلقة</p>
                <p class="text-3xl font-bold text-orange-600">{{ $pendingCollections }}</p>
            </div>
            <div class="text-4xl text-orange-200">📊</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Quick Actions -->
    <div class="col-span-1 space-y-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">عمليات سريعة</h3>
            <div class="space-y-2">
                <a href="{{ route('distributions.create') }}" class="block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center text-sm">
                    توزيع جديد
                </a>
                <a href="{{ route('companies.create') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-center text-sm">
                    شركة جديدة
                </a>
                <a href="{{ route('workers.create') }}" class="block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-center text-sm">
                    عامل جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Today's Distribution -->
    <div class="col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">التوزيع اليومي</h3>
        
        @if($todayDistributions->isEmpty())
            <p class="text-gray-500 text-center py-8">لا توجد توزيعات لهذا اليوم</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-2 text-right font-semibold">الشركة</th>
                            <th class="px-4 py-2 text-right font-semibold">العامل</th>
                            <th class="px-4 py-2 text-right font-semibold">الأجر اليومي</th>
                            <th class="px-4 py-2 text-right font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayDistributions as $dist)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $dist->company->name }}</td>
                                <td class="px-4 py-2">{{ $dist->worker->name }}</td>
                                <td class="px-4 py-2 font-semibold">{{ number_format($dist->daily_wage_snapshot, 2) }} جنيه</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('distributions.destroy', $dist->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs" onclick="return confirm('هل تريد الحذف؟')">
                                            حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
