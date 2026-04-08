@extends('layouts.dashboard')

@section('title', 'الفواتير')

@section('page-title', 'الفواتير')

@section('content')
<button type="button" onclick="document.getElementById('collection-modal').classList.remove('hidden')" class="mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    + فاتورة جديدة
</button>

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($collections->isEmpty())
        <div class="p-8 text-center text-gray-500">لا توجد فواتير</div>
    @else
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-right">الشركة</th>
                    <th class="px-6 py-3 text-right">الفترة</th>
                    <th class="px-6 py-3 text-right">الصافي</th>
                    <th class="px-6 py-3 text-right">الحالة</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($collections as $col)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $col->company->name }}</td>
                        <td class="px-6 py-4">{{ $col->period_start->format('Y-m-d') }} إلى {{ $col->period_end->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($col->net_amount, 2) }} جنيه</td>
                        <td class="px-6 py-4">
                            @if($col->is_paid)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-xs">مدفوع</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">معلق</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('collections.show', $col->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">عرض</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{ $collections->links() }}

<!-- Modal -->
<div id="collection-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">فاتورة جديدة</h3>
        <form method="POST" action="{{ route('collections.generate') }}">
            @csrf
            <div class="space-y-4">
                <select name="company_id" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    <option value="">-- اختر شركة --</option>
                    @foreach(\App\Models\Company::all() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <div>
                    <label class="text-sm">من</label>
                    <input type="date" name="period_start" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                </div>
                <div>
                    <label class="text-sm">إلى</label>
                    <input type="date" name="period_end" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                </div>
            </div>
            <div class="mt-6 flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('collection-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded">إلغاء</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">إنشاء الفاتورة</button>
            </div>
        </form>
    </div>
</div>
@endsection
