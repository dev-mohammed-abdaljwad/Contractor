@extends('layouts.auth')
@section('title', 'استعادة كلمة المرور - iDara')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@24,400,0&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Tajawal', sans-serif; background: #fafaf5; }

    .page {
        display: flex;
        min-height: 100vh;
        font-family: 'Tajawal', sans-serif;
        direction: rtl;
        background: #fafaf5;
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
        top: -60px; left: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .left-panel::after {
        content: '';
        position: absolute;
        bottom: -40px; right: -40px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
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

    .brand-sub {
        font-size: 14px;
        color: rgba(255,255,255,0.7);
        text-align: center;
        line-height: 1.6;
        max-width: 220px;
    }

    .lock-icon-wrap {
        margin-top: 40px;
        width: 80px; height: 80px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(255,255,255,0.12);
    }

    .lock-icon-wrap span {
        font-size: 36px;
        color: #66BB6A;
        font-family: 'Material Symbols Outlined';
    }

    .right-panel {
        width: 420px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 44px;
        min-height: 100vh;
    }

    .form-title { font-size: 22px; font-weight: 700; color: #1a1c19; margin-bottom: 4px; }
    .form-sub { font-size: 13px; color: #707a6c; margin-bottom: 28px; line-height: 1.6; }

    .field { margin-bottom: 18px; }
    .field label {
        display: block; font-size: 12px; font-weight: 700;
        color: #1a1c19; margin-bottom: 6px; text-align: right;
    }

    .input-wrap { position: relative; }

    .inp {
        width: 100%; height: 46px;
        border: 1px solid #d0d0c8;
        border-radius: 8px;
        background: #fafaf5;
        font-family: 'Tajawal', sans-serif;
        font-size: 13px; color: #1a1c19;
        outline: none;
        padding: 0 44px 0 50px;
        direction: ltr; text-align: left;
        transition: border 0.15s;
    }

    .inp:focus { border: 1.5px solid #0d631b; background: #fff; }
    .inp.has-prefix { padding-left: 72px; }
    .inp.err { border: 1.5px solid #ba1a1a; background: #fff; }
    .inp.pass-inp { padding: 0 44px 0 44px; direction: ltr; text-align: left; }

    .icon-r {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        color: #9e9e9e; font-size: 18px;
        font-family: 'Material Symbols Outlined'; line-height: 1;
        pointer-events: none;
    }

    .prefix {
        position: absolute; left: 0; top: 0; bottom: 0;
        display: flex; align-items: center;
        padding: 0 10px;
        border-right: 1px solid #e0e0d8;
        gap: 5px; font-size: 11px; font-weight: 700;
        color: #1a1c19; direction: ltr;
    }

    .flag {
        display: inline-flex; flex-direction: column;
        width: 18px; height: 12px;
        border-radius: 2px; overflow: hidden; flex-shrink: 0;
    }
    .f1 { flex: 1; background: #CE1126; }
    .f2 { flex: 1; background: #fff; }
    .f3 { flex: 1; background: #000; }

    .eye-btn {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: #9e9e9e; cursor: pointer;
        font-size: 18px; font-family: 'Material Symbols Outlined'; line-height: 1;
        background: none; border: none; padding: 6px; margin-left: -6px;
        transition: color 0.15s;
    }
    .eye-btn:hover { color: #0d631b; }

    .errmsg {
        font-size: 11px; color: #ba1a1a; font-weight: 500;
        margin-top: 5px;
        display: flex; align-items: center; gap: 4px; direction: rtl;
    }

    .alert-box {
        margin-bottom: 16px;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-family: 'Tajawal', sans-serif;
        direction: rtl;
        display: flex; align-items: center; gap: 6px;
    }

    .alert-box.error { background: #fff5f5; border: 1px solid #f0c0c0; color: #ba1a1a; }
    .alert-box.success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }

    .btn-primary {
        width: 100%; height: 48px;
        background: #0d631b; border: none; border-radius: 8px;
        color: #fff; font-family: 'Tajawal', sans-serif;
        font-size: 15px; font-weight: 700;
        cursor: pointer; transition: background 0.15s;
    }
    .btn-primary:hover:not(:disabled) { background: #0a5216; }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-secondary {
        width: 100%; height: 44px;
        background: transparent; border: 1.5px solid #d0d0c8; border-radius: 8px;
        color: #707a6c; font-family: 'Tajawal', sans-serif;
        font-size: 13px; font-weight: 700;
        cursor: pointer; transition: all 0.15s;
        margin-top: 10px;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        text-decoration: none;
    }
    .btn-secondary:hover { border-color: #0d631b; color: #0d631b; }

    .step { display: none; }
    .step.active { display: block; }

    .step-indicator {
        display: flex; gap: 8px; margin-bottom: 20px; justify-content: center;
    }
    .step-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #e0e0d8; transition: all 0.3s;
    }
    .step-dot.active { background: #0d631b; width: 24px; border-radius: 4px; }
    .step-dot.done { background: #66BB6A; }

    .password-rules {
        margin-top: 8px;
        padding: 10px 12px;
        background: #f8faf5;
        border-radius: 8px;
        font-size: 11px;
        color: #707a6c;
        line-height: 1.8;
    }
    .password-rules .rule { display: flex; align-items: center; gap: 4px; }
    .password-rules .rule span { font-family: 'Material Symbols Outlined'; font-size: 14px; }
    .password-rules .rule.valid { color: #059669; }
    .password-rules .rule.valid span { color: #059669; }

    .footer-text {
        font-size: 11px; color: #b0b0a8;
        text-align: center; margin-top: 24px;
    }

    @media (max-width: 768px) {
        .page { flex-direction: column; min-height: auto; }
        .left-panel { min-height: auto; padding: 30px 20px; border-radius: 16px 16px 0 0; }
        .right-panel { width: 100%; padding: 30px 20px; min-height: auto; }
        .brand-logo { width: 56px; height: 56px; font-size: 22px; }
        .brand-name { font-size: 24px; }
        .brand-sub { font-size: 12px; max-width: 200px; }
        .lock-icon-wrap { width: 64px; height: 64px; margin-top: 28px; }
        .lock-icon-wrap span { font-size: 28px; }
        .form-title { font-size: 20px; }
        .form-sub { font-size: 12px; margin-bottom: 20px; }
        .inp { height: 42px; font-size: 12px; }
        .btn-primary { height: 44px; font-size: 14px; }
        .right-panel { justify-content: flex-start; padding-top: 20px; }
    }

    @media (max-width: 480px) {
        .left-panel { padding: 24px 16px; }
        .right-panel { padding: 20px 16px; }
        .brand-logo { width: 48px; height: 48px; font-size: 20px; }
        .brand-name { font-size: 20px; }
        .brand-sub { font-size: 11px; max-width: 180px; }
        .form-title { font-size: 18px; }
    }
</style>

<div class="page">

    <!-- Left Panel -->
    <div class="left-panel">
        <div class="brand-logo">
            <span style="color: #a7f3d0;">i</span><span style="color: #fff;">D</span>
        </div>
        <div class="brand-name"><span style="color: #66BB6A;">i</span>Dara</div>
        <div class="brand-sub">استعادة كلمة المرور<br>أدخل رقم هاتفك المسجل لتعيين كلمة مرور جديدة</div>

        <div class="lock-icon-wrap">
            <span>lock_reset</span>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">

        <!-- Step 1: Verify Phone -->
        <div class="step active" id="step1">
            <div class="step-indicator">
                <div class="step-dot active" id="dot1"></div>
                <div class="step-dot" id="dot2"></div>
            </div>

            <div class="form-title">نسيت كلمة المرور؟</div>
            <div class="form-sub">أدخل رقم الهاتف المسجّل وسنتحقق من هويتك</div>

            @if(session('error'))
                <div class="alert-box error">
                    <span style="font-family:'Material Symbols Outlined';font-size:16px">warning</span>
                    {{ session('error') }}
                </div>
            @endif

            <div id="phone-error" class="alert-box error" style="display:none;"></div>

            <form id="verify-form">
                @csrf
                <div class="field">
                    <label for="verify-phone">رقم الهاتف</label>
                    <div class="input-wrap">
                        <input
                            type="text"
                            id="verify-phone"
                            name="phone"
                            class="inp has-prefix"
                            placeholder="01X XXXX XXXX"
                            dir="ltr"
                            autocomplete="tel"
                        >
                        <div class="prefix">
                            <div class="flag">
                                <div class="f1"></div>
                                <div class="f2"></div>
                                <div class="f3"></div>
                            </div>
                        </div>
                        <span class="icon-r">phone</span>
                    </div>
                </div>

                <button type="submit" class="btn-primary" id="verify-btn">
                    تحقق من الرقم
                </button>
            </form>

            <a href="{{ route('login') }}" class="btn-secondary">
                <span style="font-family:'Material Symbols Outlined';font-size:16px">arrow_forward</span>
                العودة لتسجيل الدخول
            </a>
        </div>

        <!-- Step 2: Set New Password -->
        <div class="step" id="step2">
            <div class="step-indicator">
                <div class="step-dot done" id="dot1b"></div>
                <div class="step-dot active" id="dot2b"></div>
            </div>

            <div class="form-title">تعيين كلمة مرور جديدة</div>
            <div class="form-sub">أهلاً <strong id="user-name-display"></strong>، اختر كلمة مرور جديدة لحسابك</div>

            <div id="reset-error" class="alert-box error" style="display:none;"></div>
            <div id="reset-success" class="alert-box success" style="display:none;"></div>

            <form id="reset-form">
                @csrf
                <input type="hidden" id="reset-phone" name="phone">

                <div class="field">
                    <label for="new-password">كلمة المرور الجديدة</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="new-password"
                            name="password"
                            class="inp pass-inp"
                            placeholder="••••••••"
                            autocomplete="new-password"
                        >
                        <span class="icon-r">lock</span>
                        <button type="button" class="eye-btn" onclick="togglePass('new-password', this)" tabindex="-1">visibility</button>
                    </div>

                    <div class="password-rules" id="password-rules">
                        <div class="rule" id="rule-length">
                            <span>radio_button_unchecked</span>
                            ٦ أحرف على الأقل
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label for="confirm-password">تأكيد كلمة المرور</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="confirm-password"
                            name="password_confirmation"
                            class="inp pass-inp"
                            placeholder="••••••••"
                            autocomplete="new-password"
                        >
                        <span class="icon-r">lock</span>
                        <button type="button" class="eye-btn" onclick="togglePass('confirm-password', this)" tabindex="-1">visibility</button>
                    </div>
                    <div class="errmsg" id="match-error" style="display:none;">
                        <span style="font-family:'Material Symbols Outlined';font-size:14px">error</span>
                        كلمتا المرور غير متطابقتين
                    </div>
                </div>

                <button type="submit" class="btn-primary" id="reset-btn" disabled>
                    تعيين كلمة المرور
                </button>
            </form>

            <button type="button" class="btn-secondary" onclick="goBack()">
                <span style="font-family:'Material Symbols Outlined';font-size:16px">arrow_forward</span>
                رجوع
            </button>
        </div>

        <div class="footer-text">نظام iDara لإدارة العمالة الذكية © 2025</div>
    </div>
</div>

<script>
    // === Step 1: Verify Phone ===
    document.getElementById('verify-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const phone = document.getElementById('verify-phone').value.trim();
        const btn = document.getElementById('verify-btn');
        const errorBox = document.getElementById('phone-error');

        if (!phone) {
            showError(errorBox, 'يرجى إدخال رقم الهاتف');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'جاري التحقق...';
        errorBox.style.display = 'none';

        try {
            const res = await fetch('{{ route("password.verify-phone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ phone })
            });

            const data = await res.json();

            if (data.success) {
                document.getElementById('user-name-display').textContent = data.name;
                document.getElementById('reset-phone').value = phone;
                document.getElementById('step1').classList.remove('active');
                document.getElementById('step2').classList.add('active');
            } else {
                showError(errorBox, data.message || 'رقم الهاتف غير مسجل في النظام');
            }
        } catch (err) {
            showError(errorBox, 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى');
        }

        btn.disabled = false;
        btn.textContent = 'تحقق من الرقم';
    });

    // === Step 2: Reset Password ===
    document.getElementById('reset-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const phone = document.getElementById('reset-phone').value;
        const password = document.getElementById('new-password').value;
        const confirmation = document.getElementById('confirm-password').value;
        const btn = document.getElementById('reset-btn');
        const errorBox = document.getElementById('reset-error');
        const successBox = document.getElementById('reset-success');

        errorBox.style.display = 'none';
        successBox.style.display = 'none';

        if (password !== confirmation) {
            showError(errorBox, 'كلمتا المرور غير متطابقتين');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'جاري التحديث...';

        try {
            const res = await fetch('{{ route("password.reset") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ phone, password, password_confirmation: confirmation })
            });

            const data = await res.json();

            if (data.success) {
                successBox.innerHTML = '<span style="font-family:\'Material Symbols Outlined\';font-size:16px">check_circle</span>' + data.message;
                successBox.style.display = 'flex';
                document.getElementById('reset-form').style.display = 'none';
                document.querySelector('#step2 .btn-secondary').style.display = 'none';

                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 2000);
            } else {
                showError(errorBox, data.message || 'حدث خطأ، يرجى المحاولة مرة أخرى');
                btn.disabled = false;
                btn.textContent = 'تعيين كلمة المرور';
            }
        } catch (err) {
            showError(errorBox, 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى');
            btn.disabled = false;
            btn.textContent = 'تعيين كلمة المرور';
        }
    });

    // === Password Validation ===
    const newPass = document.getElementById('new-password');
    const confirmPass = document.getElementById('confirm-password');

    function validatePassword() {
        const pass = newPass.value;
        const confirm = confirmPass.value;
        const ruleLength = document.getElementById('rule-length');
        const matchErr = document.getElementById('match-error');
        const btn = document.getElementById('reset-btn');

        const isLong = pass.length >= 6;

        updateRule(ruleLength, isLong);

        if (confirm.length > 0 && pass !== confirm) {
            matchErr.style.display = 'flex';
        } else {
            matchErr.style.display = 'none';
        }

        btn.disabled = !(isLong && pass === confirm && confirm.length > 0);
    }

    function updateRule(el, valid) {
        if (valid) {
            el.classList.add('valid');
            el.querySelector('span').textContent = 'check_circle';
        } else {
            el.classList.remove('valid');
            el.querySelector('span').textContent = 'radio_button_unchecked';
        }
    }

    newPass.addEventListener('input', validatePassword);
    confirmPass.addEventListener('input', validatePassword);

    // === Helpers ===
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

    function showError(el, msg) {
        el.innerHTML = '<span style="font-family:\'Material Symbols Outlined\';font-size:16px">warning</span>' + msg;
        el.style.display = 'flex';
    }

    function goBack() {
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');
    }

    // Focus effects
    document.getElementById('verify-phone').addEventListener('focus', function() {
        this.closest('.input-wrap').querySelector('.icon-r').style.color = '#0d631b';
    });
    document.getElementById('verify-phone').addEventListener('blur', function() {
        this.closest('.input-wrap').querySelector('.icon-r').style.color = '#9e9e9e';
    });
</script>

@endsection
