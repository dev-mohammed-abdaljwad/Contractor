@extends('layouts.dashboard')

@section('content')
<div style="padding:28px;max-width:800px">
    <a href="{{ route('contractor.companies.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:#185FA5;margin-bottom:20px;text-decoration:none;font-size:14px">
        <span class="ms">arrow_forward</span> عودة إلى الشركات
    </a>

    <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:28px">
        <h2 style="font-size:20px;font-weight:700;margin-bottom:8px">تعديل بيانات الشركة</h2>
        <p style="font-size:12px;color:#707a6c;margin-bottom:24px">تحديث معلومات: {{ $company->name }}</p>

        <form method="POST" action="{{ route('contractor.companies.update', $company) }}">
            @csrf
            @method('PATCH')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">اسم الشركة *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الاسم الرسمي للشركة</p>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" 
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('name') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:14px;box-sizing:border-box">
                    @error('name') <p style="color:#ba1a1a;font-size:12px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">جهة الاتصال *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 اسم المسؤول أو المدير</p>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $company->contact_person) }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('contact_person') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:14px;box-sizing:border-box">
                    @error('contact_person') <p style="color:#ba1a1a;font-size:12px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">رقم الهاتف *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 رقم جوال المسؤول الأساسي</p>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('phone') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:14px;box-sizing:border-box">
                    @error('phone') <p style="color:#ba1a1a;font-size:12px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">الأجر اليومي *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أجر العامل الواحد في اليوم</p>
                    <div style="display:flex;align-items:center;gap:8px">
                        <input type="number" name="daily_wage" value="{{ old('daily_wage', $company->daily_wage) }}" step="0.01"
                            style="width:100%;padding:10px 12px;border:0.5px solid @error('daily_wage') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:14px;box-sizing:border-box">
                        <span style="font-size:13px;color:#707a6c;white-space:nowrap;font-weight:500">جنيه</span>
                    </div>
                    @error('daily_wage') <p style="color:#ba1a1a;font-size:12px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">كم مرة يتم الدفع؟ *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الفترة ما بين كل دفعة والتي تليها</p>
                    <select name="payment_cycle" 
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('payment_cycle') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:14px;box-sizing:border-box">
                        <option value="daily" {{ old('payment_cycle', $company->payment_cycle) === 'daily' ? 'selected' : '' }}>كل يوم</option>
                        <option value="weekly" {{ old('payment_cycle', $company->payment_cycle) === 'weekly' ? 'selected' : '' }}>كل أسبوع</option>
                        <option value="bimonthly" {{ old('payment_cycle', $company->payment_cycle) === 'bimonthly' ? 'selected' : '' }}>كل نصف شهر (15 يوم)</option>
                        <option value="monthly" {{ old('payment_cycle', $company->payment_cycle) === 'monthly' ? 'selected' : '' }}>كل شهر</option>
                    </select>
                    @error('payment_cycle') <p style="color:#ba1a1a;font-size:12px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">يوم الدفع المفضل</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 مثال: الجمعة أو الأحد (اختياري)</p>
                    <input type="text" name="weekly_pay_day" value="{{ old('weekly_pay_day', $company->weekly_pay_day) }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:14px;box-sizing:border-box"
                        placeholder="الجمعة (مثال)">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">متى بدأت العلاقة معهم؟ *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 تاريخ بدء التعاقد مع الشركة</p>
                    <input type="date" name="contract_start_date" value="{{ old('contract_start_date', $company->contract_start_date->format('Y-m-d')) }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('contract_start_date') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:14px;box-sizing:border-box">
                    @error('contract_start_date') <p style="color:#ba1a1a;font-size:12px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">هل العلاقة نشطة؟</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 هل تستمر في العمل معهم الآن؟</p>
                    <select name="is_active"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:14px;box-sizing:border-box">
                        <option value="1" {{ old('is_active', $company->is_active) ? 'selected' : '' }}>✅ نعم، نستمر معهم</option>
                        <option value="0" {{ !old('is_active', $company->is_active) ? 'selected' : '' }}>⏸️ لا، توقفنا</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom:28px">
                <label style="display:block;font-size:14px;font-weight:600;color:#1a1c19;margin-bottom:6px">ملاحظات إضافية</label>
                <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أي معلومات مهمة عن الشركة (اختياري)</p>
                <textarea name="notes" rows="3"
                    style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:14px;font-family:inherit;box-sizing:border-box"
                    placeholder="مثال: يفضلون الدفع يومياً">{{ old('notes', $company->notes) }}</textarea>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap">
                <a href="{{ route('contractor.companies.show', $company) }}" class="btn btn-outline" style="height:40px;padding:0 20px">الرجوع</a>
                <button type="submit" class="btn btn-primary" style="height:40px;padding:0 20px">
                    <span class="ms ms-fill" style="font-size:16px">check_circle</span> تحديث البيانات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
