@extends('layouts.dashboard')

@section('content')
<div style="padding:28px;max-width:820px">
    <a href="{{ route('contractor.companies.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:#185FA5;margin-bottom:20px;text-decoration:none;font-size:13px">
        <span class="ms">arrow_forward</span> عودة إلى الشركات
    </a>

    <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:28px">
        <h2 style="font-size:18px;font-weight:700;margin-bottom:8px">شركة جديدة</h2>
        <p style="font-size:12px;color:#707a6c;margin-bottom:24px">أضف بيانات الشركة التي تعمل معها</p>

        <form method="POST" action="{{ route('contractor.companies.store') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">اسم الشركة *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الاسم الرسمي للشركة</p>
                    <input type="text" name="name" value="{{ old('name') }}" 
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('name') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:13px;box-sizing:border-box"
                        placeholder="مثال: شركة المغربي">
                    @error('name') <p style="color:#ba1a1a;font-size:11px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">جهة الاتصال *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 اسم المسؤول أو المدير</p>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('contact_person') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:13px;box-sizing:border-box"
                        placeholder="مثال: أحمد محمد">
                    @error('contact_person') <p style="color:#ba1a1a;font-size:11px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">رقم الهاتف *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 رقم جوال المسؤول الأساسي</p>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('phone') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:13px;box-sizing:border-box"
                        placeholder="01001234567">
                    @error('phone') <p style="color:#ba1a1a;font-size:11px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">الأجر اليومي *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أجر العامل الواحد في اليوم</p>
                    <div style="display:flex;align-items:center;gap:8px">
                        <input type="number" name="daily_wage" value="{{ old('daily_wage') }}" step="0.01"
                            style="width:100%;padding:10px 12px;border:0.5px solid @error('daily_wage') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:13px;box-sizing:border-box"
                            placeholder="250">
                        <span style="font-size:12px;color:#707a6c;white-space:nowrap;font-weight:500">جنيه</span>
                    </div>
                    @error('daily_wage') <p style="color:#ba1a1a;font-size:11px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">كم مرة يتم الدفع؟ *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الفترة ما بين كل دفعة والتي تليها</p>
                    <select name="payment_cycle" 
                        style="width:100%;padding:10px 12px;border:0.5px solid @error('payment_cycle') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:13px;box-sizing:border-box">
                        <option value="">-- اختر دورة الدفع --</option>
                        <option value="daily" {{ old('payment_cycle') === 'daily' ? 'selected' : '' }}>كل يوم</option>
                        <option value="weekly" {{ old('payment_cycle') === 'weekly' ? 'selected' : '' }}>كل أسبوع</option>
                        <option value="bimonthly" {{ old('payment_cycle') === 'bimonthly' ? 'selected' : '' }}>كل نصف شهر (15 يوم)</option>
                        <option value="monthly" {{ old('payment_cycle') === 'monthly' ? 'selected' : '' }}>كل شهر</option>
                    </select>
                    @error('payment_cycle') <p style="color:#ba1a1a;font-size:11px;margin-top:4px">❌ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">يوم الدفع المفضل</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 مثال: الجمعة أو الأحد (اختياري)</p>
                    <input type="text" name="weekly_pay_day" value="{{ old('weekly_pay_day') }}"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box"
                        placeholder="الجمعة (مثال)">
                </div>
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">متى بدأت العلاقة معهم؟ *</label>
                <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 تاريخ بدء التعاقد مع الشركة</p>
                <input type="date" name="contract_start_date" value="{{ old('contract_start_date') }}"
                    style="width:100%;padding:10px 12px;border:0.5px solid @error('contract_start_date') #ba1a1a @else #d0d0c8 @enderror;border-radius:8px;font-size:13px;box-sizing:border-box">
                @error('contract_start_date') <p style="color:#ba1a1a;font-size:11px;margin-top:4px">❌ {{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:28px">
                <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">ملاحظات إضافية</label>
                <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أي معلومات مهمة عن الشركة (اختياري)</p>
                <textarea name="notes" rows="3"
                    style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;font-family:inherit;box-sizing:border-box"
                    placeholder="مثال: العمال يفضلون الدفع يوميًا">{{ old('notes') }}</textarea>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap">
                <a href="{{ route('contractor.companies.index') }}" class="btn btn-outline" style="height:40px;padding:0 20px">العودة</a>
                <button type="submit" class="btn btn-primary" style="height:40px;padding:0 20px;white-space:nowrap">
                    <span class="ms ms-fill" style="font-size:16px">check_circle</span> حفظ الشركة
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @media(max-width: 768px) {
        [style*="padding:28px;max-width:820px"] {
            padding: 16px !important;
            max-width: 100% !important;
        }

        [style*="display:grid;grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }

        h2[style*="font-size:18px"] {
            font-size: 16px !important;
        }
    }

    @media(max-width: 480px) {
        [style*="padding:28px"] {
            padding: 12px !important;
        }

        [style*="border-radius:12px;padding:28px"] {
            padding: 16px !important;
        }

        input,
        select,
        textarea {
            font-size: 16px !important;
        }

        .btn {
            font-size: 12px !important;
        }

        [style*="display:flex;gap:12px;justify-content:flex-end"] {
            flex-direction: column !important;
        }

        [style*="display:flex;gap:12px;justify-content:flex-end"] > * {
            width: 100% !important;
        }
    }
</style>
@endsection
