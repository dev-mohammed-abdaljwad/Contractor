@extends('layouts.auth')
@section('title', 'قدم طلب التسجيل - iDara')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@24,400,0&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Tajawal', sans-serif;
        background: #fafaf5;
    }

    .page {
        display: flex;
        min-height: 100vh;
        font-family: 'Tajawal', sans-serif;
        direction: rtl;
        background: #fafaf5;
        border-radius: 0;
        overflow: hidden;
    }

    .left-panel {
        flex: 1;
        background: linear-gradient(135deg, #0d631b 0%, #0a5216 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    .left-panel::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    .left-panel::after {
        content: '';
        position: absolute;
        bottom: -40px;
        right: -40px;
        width: 160px;
        height: 160px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    .brand-logo {
        width: 72px;
        height: 72px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        border: 1.5px solid rgba(255, 255, 255, 0.2);
    }

    .brand-name {
        font-size: 28px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 8px;
        text-align: center;
    }

    .brand-sub {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        text-align: center;
        line-height: 1.6;
        max-width: 220px;
    }

    .info-box {
        margin-top: 40px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 100%;
    }

    .info-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 12px;
    }

    .info-text {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.8;
    }

    .right-panel {
        width: 420px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 44px;
        min-height: 100vh;
        overflow-y: auto;
    }

    .form-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a1c19;
        margin-bottom: 4px;
    }

    .form-sub {
        font-size: 13px;
        color: #707a6c;
        margin-bottom: 32px;
    }

    .field {
        margin-bottom: 18px;
    }

    .field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #1a1c19;
        margin-bottom: 6px;
        text-align: right;
    }

    .input-wrap {
        position: relative;
    }

    .inp {
        width: 100%;
        height: 46px;
        border: 1px solid #d0d0c8;
        border-radius: 8px;
        background: #fafaf5;
        font-family: 'Tajawal', sans-serif;
        font-size: 13px;
        color: #1a1c19;
        outline: none;
        padding: 0 44px 0 16px;
        direction: ltr;
        text-align: left;
        transition: border 0.15s;
    }

    .inp:focus {
        border: 1.5px solid #0d631b;
        background: #fff;
    }

    .inp.err {
        border: 1.5px solid #ba1a1a;
        background: #fff;
    }

    .icon-r {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9e9e9e;
        font-size: 18px;
        font-family: 'Material Symbols Outlined';
        line-height: 1;
        pointer-events: none;
    }

    .textarea {
        width: 100%;
        border: 1px solid #d0d0c8;
        border-radius: 8px;
        background: #fafaf5;
        font-family: 'Tajawal', sans-serif;
        font-size: 13px;
        color: #1a1c19;
        outline: none;
        padding: 12px 16px;
        direction: rtl;
        text-align: right;
        transition: border 0.15s;
        resize: vertical;
        min-height: 100px;
    }

    .textarea:focus {
        border: 1.5px solid #0d631b;
        background: #fff;
    }

    .errmsg {
        font-size: 11px;
        color: #ba1a1a;
        font-weight: 500;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
        direction: rtl;
    }

    .success-alert {
        display: none;
        margin-bottom: 16px;
        padding: 12px 14px;
        background: #f0f9f4;
        border: 1px solid #a8e0ce;
        border-radius: 8px;
        font-size: 12px;
        color: #0d631b;
        font-family: 'Tajawal', sans-serif;
        direction: rtl;
        align-items: center;
        gap: 6px;
    }

    .success-alert.show {
        display: flex;
    }

    .error-alert {
        display: none;
        margin-bottom: 16px;
        padding: 10px 14px;
        background: #fff5f5;
        border: 1px solid #f0c0c0;
        border-radius: 8px;
        font-size: 12px;
        color: #ba1a1a;
        font-family: 'Tajawal', sans-serif;
        direction: rtl;
        align-items: center;
        gap: 6px;
    }

    .error-alert.show {
        display: flex;
    }

    .btn-submit {
        width: 100%;
        height: 48px;
        background: #0d631b;
        border: none;
        border-radius: 8px;
        color: #fff;
        font-family: 'Tajawal', sans-serif;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-submit:hover:not(:disabled) {
        background: #0a5216;
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .back-link {
        text-align: center;
        margin-top: 16px;
        font-size: 12px;
        color: #707a6c;
    }

    .back-link a {
        color: #0d631b;
        font-weight: 700;
        text-decoration: none;
    }

    .back-link a:hover {
        text-decoration: underline;
    }

    .footer-text {
        font-size: 11px;
        color: #b0b0a8;
        text-align: center;
        margin-top: 24px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .page {
            flex-direction: column;
            min-height: auto;
        }

        .left-panel {
            min-height: auto;
            padding: 30px 20px;
            border-radius: 16px 16px 0 0;
            margin: 0;
        }

        .right-panel {
            width: 100%;
            padding: 30px 20px;
            min-height: auto;
            border-radius: 0;
            justify-content: flex-start;
            padding-top: 20px;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
        }

        .brand-name {
            font-size: 24px;
        }

        .brand-sub {
            font-size: 12px;
            max-width: 200px;
        }

        .form-title {
            font-size: 20px;
        }

        .form-sub {
            font-size: 12px;
            margin-bottom: 24px;
        }

        .inp {
            height: 42px;
            font-size: 12px;
        }

        .btn-submit {
            height: 44px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .left-panel {
            padding: 24px 16px;
        }

        .right-panel {
            padding: 20px 16px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
        }

        .brand-name {
            font-size: 20px;
        }

        .brand-sub {
            font-size: 11px;
            max-width: 180px;
        }

        .form-title {
            font-size: 18px;
        }

        .info-box {
            padding: 16px;
            margin-top: 30px;
        }

        .info-title {
            font-size: 13px;
        }

        .info-text {
            font-size: 11px;
        }
    }
</style>

<div class="page" id="request-registration-page">

    <!-- Left Panel -->
    <div class="left-panel">
        <div class="brand-logo" style="background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 28px; letter-spacing: -1px;">
            <span style="color: #a7f3d0;">i</span><span style="color: #fff;">D</span>
        </div>
        <div class="brand-name" style="font-family: 'Plus Jakarta Sans', sans-serif;"><span style="color: #66BB6A;">i</span>Dara</div>
        <div class="brand-sub">نظام إدارة العمالة الذكي في مصر</div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-title">كيفية التسجيل</div>
            <div class="info-text">
                قدم طلب تسجيل بـ بياناتك وسيتواصل معك فريق الدعم خلال 24 ساعة لتأكيد البيانات وتفعيل حسابك.
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="form-title">قدم طلب تسجيل</div>
        <div class="form-sub">ملء البيانات التالية وسنتواصل معك قريباً</div>

        @if(session('success'))
            <div class="success-alert show" id="success-alert">
                <span style="font-family:'Material Symbols Outlined';font-size:16px">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-alert show" id="error-alert">
                <span style="font-family:'Material Symbols Outlined';font-size:16px">warning</span>
                يوجد خطأ في البيانات المدخلة
            </div>
        @else
            <div class="error-alert" id="error-alert"></div>
        @endif

        <form method="POST" action="{{ route('request-registration.submit') }}">
            @csrf

            <!-- Name Field -->
            <div class="field">
                <label for="name">الاسم الكامل</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="inp @error('name') err @enderror"
                        placeholder="أحمد محمد"
                        value="{{ old('name') }}"
                    >
                    <span class="icon-r">person</span>
                </div>
                @error('name')
                    <div class="errmsg">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Phone Field -->
            <div class="field">
                <label for="phone">رقم الهاتف</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="inp @error('phone') err @enderror"
                        placeholder="01X XXXX XXXX"
                        value="{{ old('phone') }}"
                        dir="ltr"
                    >
                    <span class="icon-r" id="phone-icon">phone</span>
                </div>
                @error('phone')
                    <div class="errmsg">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Company Name Field -->
            <div class="field">
                <label for="company_name">اسم الشركة / المقاولة</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        class="inp @error('company_name') err @enderror"
                        placeholder="اسم الشركة"
                        value="{{ old('company_name') }}"
                    >
                    <span class="icon-r">business</span>
                </div>
                @error('company_name')
                    <div class="errmsg">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Message Field -->
            <div class="field">
                <label for="message">ملاحظات إضافية (اختياري)</label>
                <textarea
                    id="message"
                    name="message"
                    class="textarea @error('message') err @enderror"
                    placeholder="أضف أي ملاحظات تود إخبارنا بها..."
                >{{ old('message') }}</textarea>
                @error('message')
                    <div class="errmsg">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">قدم الطلب</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← العودة لصفحة تسجيل الدخول</a>
        </div>

        <div class="footer-text">نظام iDara لإدارة العمالة الذكية © 2025</div>
    </div>

</div>

@endsection
