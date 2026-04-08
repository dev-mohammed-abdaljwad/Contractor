@extends('layouts.dashboard')

@section('title', 'تعديل العامل')

@section('page-title', 'تعديل ' . $worker->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('workers.update', $worker->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم *</label>
                <input type="text" name="name" value="{{ old('name', $worker->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone', $worker->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهوية</label>
                <input type="text" name="national_id" value="{{ old('national_id', $worker->national_id) }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded">
                    <option value="1" {{ old('is_active', $worker->is_active) ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ !old('is_active', $worker->is_active) ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            <div class="flex gap-4 justify-end">
                <a href="{{ route('workers.show', $worker->id) }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">إلغاء</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>
@endsection
