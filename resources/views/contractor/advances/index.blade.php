@extends('layouts.app')

@section('title', 'المتقدمات')

@section('page-title', 'المتقدمات')

@section('content')
<button type="button" onclick="document.getElementById('advance-modal').classList.remove('hidden')" class="mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    + متقدم جديد
</button>

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($advances->isEmpty())
        <div class="p-8 text-center text-gray-500">لا توجد متقدمات</div>
    @else
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-right">العامل</th>
                    <th class="px-6 py-3 text-right">المبلغ</th>
                    <th class="px-6 py-3 text-right">التاريخ</th>
                    <th class="px-6 py-3 text-right">الحالة</th>
                    <th class="px-6 py-3 text-right">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($advances as $adv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $adv->worker->name }}</td>
                        <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($adv->amount, 2) }}</td>
                        <td class="px-6 py-4">{{ $adv->advance_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            @if($adv->is_settled)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-xs">تم التسوية</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">معلق</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            @if(!$adv->is_settled)
                                <form method="POST" action="{{ route('advances.settle', $adv->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">تسوية</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('advances.destroy', $adv->id) }}" class="inline">
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

{{ $advances->links() }}

<!-- Modal -->
<div id="advance-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">متقدم جديد</h3>
        <form method="POST" action="{{ route('advances.store') }}">
            @csrf
            <div class="space-y-4">
                <select name="worker_id" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    <option value="">-- اختر عاملاً --</option>
                    @foreach(\App\Models\Worker::all() as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="amount" step="0.01" placeholder="المبلغ" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                <input type="date" name="advance_date" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                <textarea name="notes" placeholder="ملاحظات" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded"></textarea>
            </div>
            <div class="mt-6 flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('advance-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded">إلغاء</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
