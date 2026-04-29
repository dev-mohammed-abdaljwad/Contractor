@extends('layouts.auth')
@section('title', 'تعيين كلمة السر — iDara')

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

    .field { margin-bottom: 24px; }
    .field label {
        display: block; font-size: 13px; font-weight: 700;
        color: #1a1c19; margin-bottom: 8px;
    }

    .input-wrap { position: relative; }

    .inp {
        width: 100%; height: 52px;
        border: 1.5px solid #e2e2e2;
        border-radius: 12px;
        background: #fdfdfd;
        font-family: 'Tajawal', sans-serif;
        font-size: 14px; color: #1a1c19;
        outline: none;
        padding: 0 48px 0 48px;
        transition: all 0.2s;
    }

    .inp:focus { border-color: #0d631b; background: #fff; box-shadow: 0 0 0 4px rgba(13, 99, 27, 0.08); }
    .inp.err { border-color: #ba1a1a; }

    .icon-r {
        position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
        color: #9e9e9e; font-size: 20px;
        font-family: 'Material Symbols Outlined';
    }

    .eye-btn {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: #9e9e9e; cursor: pointer;
        font-size: 20px; font-family: 'Material Symbols Outlined';
        background: none; border: none; padding: 4px;
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

    .password-rules {
        margin-top: 12px;
        padding: 12px;
        background: #f8faf5;
        border-radius: 10px;
        font-size: 12px;
        color: #707a6c;
    }
    .rule { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    .rule span { font-size: 16px; }
    .rule.valid { color: #166534; }

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
            <span>lock_open</span>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-title">كلمة سر جديدة</div>
        <div class="form-sub">اختر كلمة سر قوية وسهلة التذكر لحماية حسابك.</div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <span class="material-symbols-outlined">error</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('password.reset') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label for="password">كلمة السر الجديدة</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" class="inp" placeholder="••••••••" required autofocus>
                    <span class="icon-r">lock</span>
                    <button type="button" class="eye-btn" onclick="togglePass('password', this)">visibility</button>
                </div>
                <div class="password-rules">
                    <div class="rule" id="rule-length">
                        <span class="material-symbols-outlined">radio_button_unchecked</span>
                        على الأقل 8 أحرف
                    </div>
                </div>
            </div>

            <div class="field">
                <label for="password_confirmation">تأكيد كلمة السر</label>
                <div class="input-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="inp" placeholder="••••••••" required>
                    <span class="icon-r">lock_reset</span>
                    <button type="button" class="eye-btn" onclick="togglePass('password_confirmation', this)">visibility</button>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                حفظ كلمة السر
                <span class="material-symbols-outlined">save</span>
            </button>
        </form>

        <div style="margin-top: 32px; text-align: center; font-size: 12px; color: #b0b0a8;">
            نظام iDara © {{ date('Y') }}
        </div>
    </div>
</div>

<script>
    function togglePass(id, btn) {
        const inp = document.getElementById(id);
        if (inp.type === 'password') {
            inp.type = 'text';
            btn.textContent = 'visibility_off';
        } else {
            inp.type = 'password';
            btn.textContent = 'visibility';
        }
    }

    const passInput = document.getElementById('password');
    const ruleLength = document.getElementById('rule-length');

    passInput.addEventListener('input', () => {
        if (passInput.value.length >= 8) {
            ruleLength.classList.add('valid');
            ruleLength.querySelector('span').textContent = 'check_circle';
        } else {
            ruleLength.classList.remove('valid');
            ruleLength.querySelector('span').textContent = 'radio_button_unchecked';
        }
    });
</script>
@endsection
