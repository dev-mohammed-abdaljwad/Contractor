@extends('layouts.auth')
@section('title', 'تأكيد الرمز — iDara')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@24,400,0&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Tajawal', sans-serif; background: #fafaf5; }

    .page {
        display: flex;
        min-height: 100vh;
        direction: rtl;
        background: #fafaf5;
        overflow: hidden;
    }

    .left-panel {
        flex: 1;
        background: linear-gradient(135deg, #0a4f14 0%, #0d631b 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    .brand-logo {
        width: 72px; height: 72px;
        background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px;
        border: 1.5px solid rgba(255,255,255,0.2);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800; font-size: 28px; letter-spacing: -1px;
    }

    .brand-name {
        font-size: 28px; font-weight: 900; color: #fff;
        margin-bottom: 8px; text-align: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .icon-hero {
        margin-top: 40px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(255,255,255,0.12);
    }

    .icon-hero span {
        font-size: 48px;
        color: #66BB6A;
        font-family: 'Material Symbols Outlined';
    }

    .right-panel {
        width: 450px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 44px;
        min-height: 100vh;
        box-shadow: -10px 0 30px rgba(0,0,0,0.03);
    }

    .form-title { font-size: 24px; font-weight: 800; color: #1a1c19; margin-bottom: 8px; }
    .form-sub { font-size: 14px; color: #707a6c; margin-bottom: 32px; line-height: 1.6; }

    .otp-container {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-bottom: 32px;
        direction: ltr;
    }

    .otp-input {
        width: 50px;
        height: 60px;
        border: 2px solid #e2e2e2;
        border-radius: 12px;
        text-align: center;
        font-size: 24px;
        font-weight: 800;
        color: #0d631b;
        background: #fdfdfd;
        transition: all 0.2s;
        outline: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .otp-input:focus {
        border-color: #0d631b;
        box-shadow: 0 0 0 4px rgba(13, 99, 27, 0.08);
        background: #fff;
    }

    .btn-primary {
        width: 100%; height: 52px;
        background: #0d631b; border: none; border-radius: 12px;
        color: #fff; font-family: 'Tajawal', sans-serif;
        font-size: 16px; font-weight: 700;
        cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-primary:hover { background: #0a5216; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(13, 99, 27, 0.2); }
    .btn-primary:active { transform: translateY(0); }

    .alert {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-danger { background: #fff5f5; border: 1px solid #f0c0c0; color: #ba1a1a; }

    .resend-text {
        text-align: center;
        margin-top: 24px;
        font-size: 13px;
        color: #707a6c;
    }

    .resend-link {
        color: #0d631b;
        text-decoration: none;
        font-weight: 700;
        cursor: pointer;
    }

    @media (max-width: 900px) {
        .page { flex-direction: column; }
        .left-panel { min-height: 250px; padding: 40px 20px; }
        .right-panel { width: 100%; min-height: auto; padding: 40px 24px; border-radius: 24px 24px 0 0; margin-top: -24px; }
    }
</style>

<div class="page">
    <div class="left-panel">
        <div class="brand-logo">
            <span style="color: #a7f3d0;">i</span><span style="color: #fff;">D</span>
        </div>
        <div class="brand-name">iDara</div>
        <div class="icon-hero">
            <span>verified_user</span>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-title">تأكيد رمز التحقق</div>
        <div class="form-sub">تم إرسال رمز مكون من 6 أرقام إلى:<br><strong>{{ $email }}</strong></div>

        @if ($errors->has('code'))
            <div class="alert alert-danger">
                <span class="material-symbols-outlined">error</span>
                {{ $errors->first('code') }}
            </div>
        @endif

        <form action="{{ route('password.verify') }}" method="POST" id="otp-form">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="code" id="final-code">

            <div class="otp-container">
                <input type="text" maxlength="1" class="otp-input" required autofocus>
                <input type="text" maxlength="1" class="otp-input" required>
                <input type="text" maxlength="1" class="otp-input" required>
                <input type="text" maxlength="1" class="otp-input" required>
                <input type="text" maxlength="1" class="otp-input" required>
                <input type="text" maxlength="1" class="otp-input" required>
            </div>

            <button type="submit" class="btn-primary">
                تأكيد الرمز
                <span class="material-symbols-outlined">check_circle</span>
            </button>
        </form>

        <div class="resend-text">
            لم يصلك الرمز؟ 
            <form action="{{ route('password.send-code') }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="resend-link" style="background:none;border:none;padding:0;">أعد الإرسال</button>
            </form>
        </div>

        <div style="margin-top: 32px; text-align: center; font-size: 12px; color: #b0b0a8;">
            نظام iDara © {{ date('Y') }}
        </div>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-input');
    const finalCode = document.getElementById('final-code');
    const form = document.getElementById('otp-form');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length > 1) {
                e.target.value = e.target.value.slice(0, 1);
            }
            if (e.target.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateFinalCode();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        // Handle Paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const data = e.clipboardData.getData('text').slice(0, 6);
            if (!/^\d+$/.test(data)) return;

            data.split('').forEach((char, i) => {
                if (inputs[i]) inputs[i].value = char;
            });
            inputs[Math.min(data.length, 5)].focus();
            updateFinalCode();
        });
    });

    function updateFinalCode() {
        let code = '';
        inputs.forEach(input => code += input.value);
        finalCode.value = code;
    }

    form.addEventListener('submit', (e) => {
        if (finalCode.value.length !== 6) {
            e.preventDefault();
            alert('يرجى إدخال الرمز كاملاً (6 أرقام)');
        }
    });
</script>
@endsection
