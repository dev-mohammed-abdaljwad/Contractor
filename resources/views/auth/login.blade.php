@extends('layouts.auth')
@section('title', 'تسجيل الدخول - iDara')

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
        background: #0d631b;
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

    .brand-dots {
        display: flex;
        gap: 8px;
        margin-top: 28px;
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
    }

    .dot.active {
        background: #66BB6A;
        width: 24px;
        border-radius: 4px;
    }

    .right-panel {
        width: 420px;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 44px;
        min-height: 100vh;
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
        padding: 0 44px 0 50px;
        direction: ltr;
        text-align: left;
        transition: border 0.15s;
    }

    .inp:focus {
        border: 1.5px solid #0d631b;
        background: #fff;
    }

    .inp.has-prefix {
        padding-left: 72px;
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

    .prefix {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        padding: 0 10px;
        border-right: 1px solid #e0e0d8;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: #1a1c19;
        direction: ltr;
    }

    .flag {
        display: inline-flex;
        flex-direction: column;
        width: 18px;
        height: 12px;
        border-radius: 2px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .f1 { flex: 1; background: #CE1126; }
    .f2 { flex: 1; background: #fff; }
    .f3 { flex: 1; background: #000; }

    .eye-btn {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9e9e9e;
        cursor: pointer;
        font-size: 18px;
        font-family: 'Material Symbols Outlined';
        line-height: 1;
        background: none;
        border: none;
        padding: 6px;
        margin-left: -6px;
        transition: color 0.15s;
    }

    .eye-btn:hover {
        color: #0d631b;
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

    .forgot {
        display: block;
        text-align: left;
        font-size: 12px;
        color: #0d631b;
        font-weight: 700;
        text-decoration: none;
        margin-top: -8px;
        margin-bottom: 20px;
    }

    .btn-login {
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

    .btn-login:hover:not(:disabled) {
        background: #0a5216;
    }

    .btn-login:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .footer-text {
        font-size: 11px;
        color: #b0b0a8;
        text-align: center;
        margin-top: 24px;
    }

    .stats-box {
        margin-top: 40px;
        padding: 16px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        width: 100%;
    }

    .stats-label {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 8px;
        text-align: right;
    }

    .stats-row {
        display: flex;
        gap: 12px;
    }

    .stat-item {
        flex: 1;
        text-align: center;
    }

    .stat-number {
        font-size: 22px;
        font-weight: 900;
        color: #66BB6A;
    }

    .stat-label {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 2px;
    }

    .stat-divider {
        width: 0.5px;
        background: rgba(255, 255, 255, 0.15);
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
        }

        .brand-logo {
            width: 56px;
            height: 56px;
        }

        .brand-logo span {
            font-size: 28px;
        }

        .brand-name {
            font-size: 24px;
        }

        .brand-sub {
            font-size: 12px;
            max-width: 200px;
        }

        .stats-box {
            margin-top: 30px;
            padding: 12px;
        }

        .stat-number {
            font-size: 18px;
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

        .btn-login {
            height: 44px;
            font-size: 14px;
        }

        .right-panel {
            justify-content: flex-start;
            padding-top: 20px;
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

        .brand-logo span {
            font-size: 24px;
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

        .stats-row {
            gap: 8px;
        }

        .stat-number {
            font-size: 16px;
        }

        .stat-label {
            font-size: 9px;
        }
    }
</style>

<div class="page" id="login-page">

    <!-- Left Panel -->
    <div class="left-panel">
        <div class="brand-logo" style="background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 28px; letter-spacing: -1px;">
            <span style="color: #a7f3d0;">i</span><span style="color: #fff;">D</span>
        </div>
        <div class="brand-name" style="font-family: 'Plus Jakarta Sans', sans-serif;"><span style="color: #66BB6A;">i</span>Dara</div>
        <div class="brand-sub">نظام إدارة العمالة الذكي للمفاولين في مصر</div>
        <div class="brand-dots">
            <div class="dot active"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

        <!-- Stats Box -->
        <div class="stats-box">
            <div class="stats-label">إحصائيات النظام</div>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-number">{{ $activeWorkers ?? 0 }}</div>
                    <div class="stat-label">عامل نشط</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number">{{ $activeCompanies ?? 0 }}</div>
                    <div class="stat-label">شركات</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="form-title">مرحباً بك</div>
        <div class="form-sub">سجّل دخولك للمتابعة إلى نظام المقاول</div>

        @if($errors->any())
            <div class="error-alert show" id="error-alert">
                <span style="font-family:'Material Symbols Outlined';font-size:16px">warning</span>
                بيانات الدخول غير صحيحة، يرجى المحاولة مرة أخرى.
            </div>
        @else
            <div class="error-alert" id="error-alert"></div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Phone Field -->
            <div class="field">
                <label for="phone">رقم الهاتف</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="inp has-prefix @error('phone') err @enderror"
                        placeholder="01X XXXX XXXX"
                        value="{{ old('phone') }}"
                        dir="ltr"
                    >
                    <div class="prefix">
                        <div class="flag">
                            <div class="f1"></div>
                            <div class="f2"></div>
                            <div class="f3"></div>
                        </div>
                    
                    </div>
                    <span class="icon-r" id="phone-icon">phone</span>
                </div>
                @error('phone')
                    <div class="errmsg">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="field">
                <label for="password">كلمة المرور</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="inp @error('password') err @enderror"
                        placeholder="••••••••"
                    >
                    <span class="icon-r" id="pass-icon">lock</span>
                    <button
                        type="button"
                        class="eye-btn"
                        id="eye-btn"
                        onclick="togglePassword()"
                        tabindex="-1"
                    >visibility</button>
                </div>
                @error('password')
                    <div class="errmsg">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-login">تسجيل الدخول</button>
        </form>

        <div class="footer-text">نظام iDara لإدارة العمالة الذكية © 2025</div>
    </div>

</div>

<script>
    function togglePassword() {
        const inp = document.getElementById('password');
        const btn = document.getElementById('eye-btn');
        const icon = document.getElementById('pass-icon');

        if (inp.type === 'password') {
            inp.type = 'text';
            btn.textContent = 'visibility_off';
        } else {
            inp.type = 'password';
            btn.textContent = 'visibility';
        }
    }

    // Update icon color on focus
    document.getElementById('phone').addEventListener('focus', function() {
        document.getElementById('phone-icon').style.color = '#0d631b';
    });

    document.getElementById('phone').addEventListener('blur', function() {
        if (!this.classList.contains('err')) {
            document.getElementById('phone-icon').style.color = '#9e9e9e';
        }
    });

    document.getElementById('password').addEventListener('focus', function() {
        document.getElementById('pass-icon').style.color = '#0d631b';
    });

    document.getElementById('password').addEventListener('blur', function() {
        if (!this.classList.contains('err')) {
            document.getElementById('pass-icon').style.color = '#9e9e9e';
        }
    });
</script>

@endsection
