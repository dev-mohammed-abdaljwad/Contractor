@extends('layouts.dashboard')

@section('title', 'إضافة عامل جديد')

@section('page-title', 'إضافة عامل جديد')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('workers.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+20..." class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهوية</label>
                <input type="text" name="national_id" value="{{ old('national_id') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex gap-4 justify-end">
                <a href="{{ route('workers.index') }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">إلغاء</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
