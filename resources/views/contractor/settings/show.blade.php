@extends('layouts.dashboard')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap');

/* ── TOPBAR ── */
.topbar{background:linear-gradient(135deg,#0a4f14 0%,#1D9E75 100%);padding:20px 20px 56px;}
.top-row{display:flex;align-items:center;justify-content:space-between;}
.top-title{color:#fff;font-size:20px;font-weight:900;}
.top-sub{color:rgba(255,255,255,.65);font-size:12px;margin-top:2px;}

/* ── AVATAR CARD ── */
.avatar-card{
  background:#fff;border-radius:20px;margin:0 14px;
  margin-top:-36px;position:relative;z-index:10;
  padding:20px;display:flex;align-items:center;gap:16px;
  box-shadow:0 4px 20px rgba(0,0,0,.09);
  margin-bottom:16px;
}
.av-circle{
  width:68px;height:68px;border-radius:50%;
  background:linear-gradient(135deg,#0a4f14,#1D9E75);
  display:flex;align-items:center;justify-content:center;
  font-size:26px;font-weight:900;color:#fff;flex-shrink:0;
  position:relative;
}
.av-edit-btn{
  position:absolute;bottom:0;left:0;
  width:22px;height:22px;border-radius:50%;
  background:#fff;border:2px solid #1D9E75;
  display:flex;align-items:center;justify-content:center;
  font-size:11px;cursor:pointer;color:#0a4f14;
}
.av-name{font-size:17px;font-weight:800;color:#1a1a1a;}
.av-role{font-size:12px;color:#aaa;margin-top:2px;}
.av-id{font-size:11px;color:#1D9E75;font-weight:600;margin-top:4px;}

/* ── SECTION CARD ── */
.sec-card{
  background:#fff;border-radius:16px;
  margin:0 14px 14px;
  overflow:hidden;
  box-shadow:0 1px 6px rgba(0,0,0,.06);
}
.sec-head{
  display:flex;align-items:center;gap:10px;
  padding:14px 16px;border-bottom:1px solid #f0f0e8;
  cursor:pointer;
}
.sec-icon{
  width:36px;height:36px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:17px;flex-shrink:0;
}
.si-green{background:#ecfdf5;} .si-amber{background:#fffbeb;}
.si-blue{background:#eff6ff;} .si-red{background:#fef2f2;}
.si-purple{background:#f5f3ff;}
.sec-title{font-size:14px;font-weight:700;color:#1a1a1a;flex:1;}
.sec-sub{font-size:11px;color:#aaa;margin-top:1px;}
.sec-chevron{color:#bbb;font-size:16px;transition:transform .2s;}
.sec-chevron.open{transform:rotate(90deg);}
.sec-body{padding:16px;border-top:1px solid #f5f5f0;display:none;}
.sec-body.open{display:block;}

/* ── FIELDS ── */
.field-group{margin-bottom:14px;}
.field-label{font-size:11px;font-weight:700;color:#888;margin-bottom:5px;display:flex;align-items:center;gap:4px;}
.field-required{color:#ef4444;font-size:13px;}
.field-input, select{
  width:100%;background:#f8f9f0;
  border:1.5px solid #e8e8e0;border-radius:10px;
  padding:10px 12px;font-size:13px;
  font-family:'Tajawal',sans-serif;
  outline:none;transition:all .2s;
}
.field-input:focus, select:focus{border-color:#1D9E75;background:#fff;box-shadow:0 0 0 3px rgba(29,158,117,.08);}
.field-input.is-invalid{border-color:#ef4444;background:#fef2f2;}
.field-hint{font-size:11px;color:#bbb;margin-top:4px;}
.field-error{font-size:11px;color:#ef4444;margin-top:4px;display:block;}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px;}

/* ── SAVE BTN ── */
.save-btn{
  width:100%;padding:12px;
  background:#0a4f14;color:#fff;
  border:none;border-radius:12px;
  font-size:14px;font-weight:700;
  font-family:'Tajawal',sans-serif;
  cursor:pointer;margin-top:4px;
  transition:all .2s;
  display:flex;align-items:center;justify-content:center;gap:6px;
}
.save-btn:hover{background:#1D9E75;}
.save-btn:active{transform:scale(.98);}
.save-btn:disabled{background:#aaa;pointer-events:none;}

/* ── TOGGLE SWITCH ── */
.toggle-row{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 0;border-bottom:1px solid #f5f5f0;
}
.toggle-row:last-child{border-bottom:none;}
.toggle-info{flex:1;}
.toggle-title{font-size:13px;font-weight:600;color:#222;}
.toggle-sub{font-size:11px;color:#aaa;margin-top:2px;}
.toggle{
  position:relative;width:44px;height:24px;flex-shrink:0;
}
.toggle input{opacity:0;width:0;height:0;}
.toggle-slider{
  position:absolute;inset:0;
  background:#e0e0d8;border-radius:24px;cursor:pointer;
  transition:background .2s;
}
.toggle-slider::before{
  content:'';position:absolute;
  width:18px;height:18px;border-radius:50%;
  background:#fff;bottom:3px;right:3px;
  transition:transform .2s;
  box-shadow:0 1px 3px rgba(0,0,0,.2);
}
.toggle input:checked + .toggle-slider{background:#1D9E75;}
.toggle input:checked + .toggle-slider::before{transform:translateX(-20px);}

/* ── PASSWORD STRENGTH ── */
.pw-strength{margin-top:6px;}
.pw-bars{display:flex;gap:3px;margin-bottom:4px;}
.pw-bar{flex:1;height:4px;border-radius:2px;background:#f0f0e8;transition:background .3s;}
.pw-bar.weak{background:#ef4444;}
.pw-bar.medium{background:#f59e0b;}
.pw-bar.strong{background:#1D9E75;}
.pw-label{font-size:10px;color:#aaa;}

/* ── LANG SELECT ── */
.lang-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.lang-btn{
  background:#f8f9f0;border:1.5px solid #e8e8e0;
  border-radius:10px;padding:10px 12px;
  display:flex;align-items:center;gap:8px;cursor:pointer;transition:all .2s;
}
.lang-btn.sel{background:#ecfdf5;border-color:#1D9E75;}
.lang-flag{font-size:20px;}
.lang-name{font-size:12px;font-weight:600;color:#555;}
.lang-btn.sel .lang-name{color:#065f46;}

/* ── VERSION ── */
.version-row{text-align:center;padding:20px 0 32px;font-size:11px;color:#ccc;}

/* Alert styling */
.alert { padding: 12px 16px; border-radius: 10px; margin: 0 14px 14px; font-size: 13px; }
.alert-success { background: #ecfdf5; border: 1px solid #1D9E75; color: #065f46; }
.alert-danger { background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; }
.alert ul { margin: 0; padding: 0; list-style: none; }
</style>

<!-- Messages -->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('success'))
<div class="alert alert-success" id="success-alert">
    {{ session('success') }}
</div>
<script>
    setTimeout(() => document.getElementById('success-alert')?.remove(), 3000);
</script>
@endif

<!-- Topbar -->
<div class="topbar">
    <div class="top-row">
        <div>
            <div class="top-title">الإعدادات</div>
            <div class="top-sub">إدارة حسابك وتفضيلات النظام</div>
        </div>
    </div>
</div>

<!-- Avatar Card -->
<div class="avatar-card">
    <div class="av-circle">
        @php
            $names = explode(' ', $user->name);
            $initials = '';
            foreach(array_slice($names, 0, 2) as $name) {
                $initials .= substr($name, 0, 1);
            }
        @endphp
        {{ $initials }}
    </div>
    <div>
        <div class="av-name">{{ $user->name }}</div>
        <div class="av-role">
            @if($user->isAdmin()) مسؤول · نشط @else مقاول عمالة · نشط @endif
        </div>
        <div class="av-id">ID: {{ $user->id }}</div>
    </div>
</div>

<!-- ══ SECTION 1: Personal Info ══ -->
<form method="POST" action="{{ route('settings.profile') }}">
    @csrf
    @method('PATCH')
    <div class="sec-card">
        <div class="sec-head" onclick="toggleSec('s1')">
            <div class="sec-icon si-green">👤</div>
            <div>
                <div class="sec-title">البيانات الشخصية</div>
                <div class="sec-sub">الاسم، التليفون، الإيميل</div>
            </div>
            <div class="sec-chevron open" id="chev-s1">›</div>
        </div>
        <div class="sec-body open" id="s1">
            <div class="field-group">
                <div class="field-label">الاسم الكامل <span class="field-required">*</span></div>
                <input class="field-input @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $user->name) }}" required/>
                @error('name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field-group">
                <div class="field-label">رقم التليفون <span class="field-required">*</span></div>
                <input class="field-input @error('phone') is-invalid @enderror" type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required/>
                <div class="field-hint">بيتم التواصل معاك على الرقم ده</div>
                @error('phone') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field-group">
                <div class="field-label">رقم الهاتف الاحتياطي</div>
                <input class="field-input" type="tel" name="phone_secondary" placeholder="اختياري" value="{{ old('phone_secondary', $user->phone_secondary) }}"/>
            </div>
            <button type="submit" class="save-btn">
                حفظ البيانات
            </button>
        </div>
    </div>
</form>

<!-- ══ SECTION 2: Password ══ -->
<form method="POST" action="{{ route('settings.password') }}">
    @csrf
    @method('PATCH')
    <div class="sec-card">
        <div class="sec-head" onclick="toggleSec('s2')">
            <div class="sec-icon si-amber">🔒</div>
            <div>
                <div class="sec-title">تغيير كلمة السر</div>
                <div class="sec-sub">يُنصح بتغييرها كل 3 أشهر</div>
            </div>
            <div class="sec-chevron" id="chev-s2">›</div>
        </div>
        <div class="sec-body" id="s2">
            <div class="field-group">
                <div class="field-label">كلمة السر الحالية <span class="field-required">*</span></div>
                <input class="field-input @error('current_password') is-invalid @enderror" type="password" name="current_password" placeholder="••••••••" required/>
                @error('current_password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field-group">
                <div class="field-label">كلمة السر الجديدة <span class="field-required">*</span></div>
                <input class="field-input @error('new_password') is-invalid @enderror" type="password" name="new_password" placeholder="••••••••" id="newPw" oninput="checkStrength(this.value)" required/>
                <div class="pw-strength">
                    <div class="pw-bars">
                        <div class="pw-bar" id="b1"></div>
                        <div class="pw-bar" id="b2"></div>
                        <div class="pw-bar" id="b3"></div>
                        <div class="pw-bar" id="b4"></div>
                    </div>
                    <div class="pw-label" id="pwLabel">أدخل كلمة السر الجديدة</div>
                </div>
                @error('new_password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field-group">
                <div class="field-label">تأكيد كلمة السر <span class="field-required">*</span></div>
                <input class="field-input @error('new_password_confirmation') is-invalid @enderror" type="password" name="new_password_confirmation" placeholder="••••••••" required/>
                @error('new_password_confirmation') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="save-btn">
                تحديث كلمة السر
            </button>
        </div>
    </div>
</form>

<!-- ══ SECTION 3: Notifications ══ -->
<form method="POST" action="{{ route('settings.notifications') }}">
    @csrf
    @method('PATCH')
    <div class="sec-card">
        <div class="sec-head" onclick="toggleSec('s3')">
            <div class="sec-icon si-blue">🔔</div>
            <div>
                <div class="sec-title">الإشعارات</div>
                <div class="sec-sub">تحكم في إيه اللي بيوصلك</div>
            </div>
            <div class="sec-chevron" id="chev-s3">›</div>
        </div>
        <div class="sec-body" id="s3">
            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="toggle-title">تذكير الدفعات المتأخرة</div>
                    <div class="toggle-sub">لما شركة تتأخر عن الموعد</div>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notify_overdue_payments" value="1" @checked($preferences->notify_overdue_payments)/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="toggle-title">تذكير التوزيع اليومي</div>
                    <div class="toggle-sub">كل يوم الصبح قبل ما تبدأ</div>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notify_daily_distribution" value="1" @checked($preferences->notify_daily_distribution)/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="toggle-title">تقرير أسبوعي</div>
                    <div class="toggle-sub">ملخص الأجور والتحصيل كل إثنين</div>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notify_weekly_report" value="1" @checked($preferences->notify_weekly_report)/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="toggle-title">إشعارات السلف</div>
                    <div class="toggle-sub">لما يكون عند عامل سلفة معلقة</div>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notify_pending_advances" value="1" @checked($preferences->notify_pending_advances)/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <button type="submit" class="save-btn" style="margin-top:14px;">
                حفظ الإعدادات
            </button>
        </div>
    </div>
</form>

<!-- ══ SECTION 4: System ══ -->
<form method="POST" action="{{ route('settings.system') }}">
    @csrf
    @method('PATCH')
    <div class="sec-card">
        <div class="sec-head" onclick="toggleSec('s4')">
            <div class="sec-icon si-purple">⚙️</div>
            <div>
                <div class="sec-title">إعدادات النظام</div>
                <div class="sec-sub">اللغة، العملة، التقويم، سعر السهر</div>
            </div>
            <div class="sec-chevron" id="chev-s4">›</div>
        </div>
        <div class="sec-body" id="s4">
            <div class="field-group">
                <div class="field-label">اللغة</div>
                <div class="lang-grid">
                    <div class="lang-btn @if($preferences->language === 'ar') sel @endif" onclick="selectLang(this, 'ar')">
                        <span class="lang-flag">🇪🇬</span>
                        <span class="lang-name">العربية</span>
                    </div>
                    <div class="lang-btn @if($preferences->language === 'en') sel @endif" onclick="selectLang(this, 'en')">
                        <span class="lang-flag">🇬🇧</span>
                        <span class="lang-name">English</span>
                    </div>
                </div>
                <input type="hidden" name="language" id="language-input" value="{{ $preferences->language ?? 'ar' }}"/>
            </div>
            <div class="field-group" style="margin-top:14px;">
                <div class="field-label">العملة</div>
                <select class="field-input" name="currency">
                    <option value="EGP" @selected($preferences->currency === 'EGP')>جنيه مصري (EGP)</option>
                    <option value="USD" @selected($preferences->currency === 'USD')>دولار أمريكي (USD)</option>
                    <option value="SAR" @selected($preferences->currency === 'SAR')>ريال سعودي (SAR)</option>
                </select>
            </div>
            <div class="field-group">
                <div class="field-label">تنسيق التاريخ</div>
                <select class="field-input" name="date_format">
                    <option value="DD/MM/YYYY" @selected($preferences->date_format === 'DD/MM/YYYY')>DD/MM/YYYY</option>
                    <option value="MM/DD/YYYY" @selected($preferences->date_format === 'MM/DD/YYYY')>MM/DD/YYYY</option>
                    <option value="YYYY-MM-DD" @selected($preferences->date_format === 'YYYY-MM-DD')>YYYY-MM-DD</option>
                </select>
            </div>
            <div class="field-group">
                <div class="field-label">بداية الأسبوع</div>
                <select class="field-input" name="week_start">
                    <option value="Sunday" @selected($preferences->week_start === 'Sunday')>الأحد</option>
                    <option value="Monday" @selected($preferences->week_start === 'Monday')>الإثنين</option>
                    <option value="Saturday" @selected($preferences->week_start === 'Saturday')>السبت</option>
                </select>
            </div>
            <div class="toggle-row" style="padding-top:4px;">
                <div class="toggle-info">
                    <div class="toggle-title">الوضع الليلي</div>
                    <div class="toggle-sub">تقليل إجهاد العين في الليل</div>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="dark_mode" value="1" @checked($preferences->dark_mode)/>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <button type="submit" class="save-btn" style="margin-top:14px;">
                حفظ الإعدادات
            </button>
        </div>
    </div>
</form>

<!-- Version -->
<div class="version-row">
    iDara v1.0.0 · صُنع في مصر 🇪🇬
</div>

<script>
function toggleSec(id) {
    const body = document.getElementById(id);
    const chev = document.getElementById('chev-' + id);
    const isOpen = body.classList.contains('open');
    body.classList.toggle('open', !isOpen);
    chev.classList.toggle('open', !isOpen);
}

function checkStrength(pw) {
    const bars = [document.getElementById('b1'), document.getElementById('b2'), document.getElementById('b3'), document.getElementById('b4')];
    const label = document.getElementById('pwLabel');
    bars.forEach(b => b.className = 'pw-bar');
    if (!pw) { label.textContent = 'أدخل كلمة السر الجديدة'; return; }
    let score = 0;
    if (pw.length >= 6) score++;
    if (pw.length >= 10) score++;
    if (/[A-Z]/.test(pw) || /[0-9]/.test(pw)) score++;
    if (/[!@#$%^&*]/.test(pw)) score++;
    const cls = score <= 1 ? 'weak' : score <= 2 ? 'medium' : 'strong';
    const lbl = score <= 1 ? 'ضعيفة' : score <= 2 ? 'متوسطة' : 'قوية ✓';
    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
    label.textContent = 'قوة كلمة السر: ' + lbl;
}

function selectLang(el, lang) {
    document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('sel'));
    el.classList.add('sel');
    document.getElementById('language-input').value = lang;
}
</script>
@endsection
