
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iDara - نظام إدارة العمالة الذكي</title>
    <meta name="description" content="iDara - نظام إدارة العمالة الذكي للمقاولين والشركات">
    
    <!-- PWA Meta Tags (CRITICAL FOR beforeinstallprompt) -->
    <meta name="theme-color" content="#1D9E75">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="iDara">
    <meta name="msapplication-TileColor" content="#1D9E75">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Icons -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
</head>
<body>
<style>
@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

:root {
  --green-dark: #0a4f14;
  --green-mid:  #1D9E75;
  --green-lt:   #4ade80;
  --green-bg:   #e8f5e9;
  --gold:       #c8961a;
  --gold-lt:    #fde68a;
  --cream:      #fafaf3;
  --ink:        #0f1a0f;
  --ink-mid:    #3a4a3a;
  --ink-lt:     #6b7c6b;
  --border:     rgba(15,26,15,0.08);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Tajawal', sans-serif;
  direction: rtl;
  background: var(--cream);
  color: var(--ink);
  overflow-x: hidden;
}

/* ── NAVBAR ── */
.nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  padding: 14px 20px;
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(250,250,243,0.88);
  backdrop-filter: blur(14px);
  border-bottom: 1px solid var(--border);
  animation: fadeDown 0.6s ease both;
}
.nav-logo { display: flex; align-items: center; gap: 10px; }
.nav-logo-icon {
  width: 38px; height: 38px; border-radius: 10px;
  background: var(--green-dark);
  display: flex; align-items: center; justify-content: center;
  font-size: 20px;
}
.nav-logo-text { font-size: 18px; font-weight: 900; color: var(--green-dark); letter-spacing: -0.5px; }
.nav-logo-sub  { font-size: 9px; color: var(--ink-lt); margin-top: -2px; }
.nav-links { display: flex; gap: 28px; }
.nav-link  { font-size: 13px; color: var(--ink-mid); cursor: pointer; transition: color .2s; text-decoration: none; font-weight: 500; }

@media (max-width: 480px) {
  .nav-links-mobile .nav-link {
    font-size: 14px;
    font-weight: 500;
  }
}
.nav-link:hover { color: var(--green-mid); }
.nav-cta {
  background: var(--green-dark); color: #fff;
  font-size: 13px; font-weight: 700; padding: 9px 22px;
  border-radius: 10px; cursor: pointer;
  border: none; font-family: 'Tajawal', sans-serif;
  transition: background .2s;
  text-decoration: none;
  display: inline-block;
}
.nav-cta:hover { background: var(--green-mid); }

/* Mobile Menu Toggle */
.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: var(--ink);
  padding: 8px;
}
.nav-links-mobile {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  width: 100%;
  background: rgba(250,250,243,0.98);
  border-bottom: 1px solid var(--border);
  flex-direction: column;
  gap: 0;
  padding: 16px 0;
}
.nav-links-mobile.active { display: flex; }
.nav-links-mobile .nav-link {
  padding: 12px 20px;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}

@media (max-width: 768px) {
  .nav {
    padding: 12px 16px;
  }
  .nav-logo-text { font-size: 16px; }
  .nav-logo-sub { font-size: 8px; }
  .mobile-menu-btn { display: block; }
  .nav-links { display: none; }
  .nav-cta { display: none; }
  .nav-links-mobile { display: none; }
  .nav-links-mobile.active { display: flex; }
}

/* ── HERO ── */
.hero {
  min-height: 100vh;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 80px 16px 60px;
  position: relative;
  overflow: hidden;
}

@media (min-width: 769px) {
  .hero {
    padding: 120px 24px 80px;
  }
}

/* Decorative background */
.hero-bg {
  position: absolute; inset: 0; z-index: 0;
  background:
    radial-gradient(ellipse 60% 50% at 20% 80%, rgba(29,158,117,0.12) 0%, transparent 70%),
    radial-gradient(ellipse 50% 40% at 80% 20%, rgba(10,79,20,0.08) 0%, transparent 60%),
    radial-gradient(ellipse 40% 30% at 50% 50%, rgba(200,150,26,0.05) 0%, transparent 60%);
}

/* Grain overlay */
.hero-bg::after {
  content: '';
  position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  pointer-events: none;
}

.hero-content { position: relative; z-index: 1; text-align: center; max-width: 700px; }

.hero-eyebrow {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(29,158,117,0.1);
  border: 1px solid rgba(29,158,117,0.25);
  border-radius: 20px; padding: 8px 18px;
  font-size: 12px; font-weight: 700; color: var(--green-mid);
  margin-bottom: 24px;
  animation: fadeUp 0.7s ease 0.1s both;
}

@media (max-width: 480px) {
  .hero-eyebrow {
    font-size: 11px;
    padding: 6px 12px;
  }
}
.eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green-mid); animation: pulse 2s infinite; }
@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }

.hero-title {
  font-size: clamp(28px, 6vw, 68px);
  font-weight: 900;
  line-height: 1.1;
  letter-spacing: -1.5px;
  color: var(--ink);
  margin-bottom: 10px;
  animation: fadeUp 0.7s ease 0.2s both;
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 26px;
  }
}
.hero-title .accent {
  color: var(--green-mid);
  position: relative;
}
.hero-title .accent::after {
  content: '';
  position: absolute; bottom: 4px; left: 0; right: 0;
  height: 4px; border-radius: 2px;
  background: linear-gradient(90deg, var(--green-mid), var(--green-lt));
  opacity: 0.4;
}

.hero-sub {
  font-size: 15px; font-weight: 400;
  color: var(--ink-lt); line-height: 1.8;
  margin: 20px 0 36px;
  animation: fadeUp 0.7s ease 0.35s both;
}

@media (max-width: 480px) {
  .hero-sub {
    font-size: 14px;
    line-height: 1.8;
  }
}

.hero-ctas {
  display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;
  animation: fadeUp 0.7s ease 0.45s both;
}

@media (max-width: 550px) {
  .hero-ctas {
    flex-direction: column;
    width: 100%;
    gap: 10px;
  }
  .hero-ctas button {
    width: 100%;
  }
}

.cta-primary {
  background: var(--green-dark); color: #fff;
  font-size: 15px; font-weight: 700; padding: 14px 32px;
  border-radius: 14px; cursor: pointer; border: none;
  font-family: 'Tajawal', sans-serif;
  transition: all .25s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  box-shadow: 0 4px 20px rgba(10,79,20,0.25);
  text-decoration: none;
}

@media (max-width: 480px) {
  .cta-primary {
    font-size: 14px;
    padding: 12px 28px;
  }
}
.cta-primary:hover { background: var(--green-mid); transform: translateY(-2px); box-shadow: 0 8px 28px rgba(10,79,20,0.3); }
.cta-secondary {
  background: transparent; color: var(--ink);
  font-size: 15px; font-weight: 600; padding: 14px 28px;
  border-radius: 14px; cursor: pointer;
  border: 1.5px solid var(--border);
  font-family: 'Tajawal', sans-serif;
  transition: all .25s;
  text-decoration: none;
  display: flex; align-items: center; justify-content: center;
}
.cta-secondary:hover { border-color: var(--green-mid); color: var(--green-mid); }

/* Stats row in hero */
.hero-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 12px;
  margin-top: 48px;
  background: #fff;
  border-radius: 16px;
  border: 1px solid var(--border);
  overflow: hidden;
  box-shadow: 0 4px 24px rgba(0,0,0,0.06);
  animation: fadeUp 0.7s ease 0.55s both;
  max-width: 100%;
}

@media (min-width: 640px) {
  .hero-stats {
    display: flex;
    gap: 0;
    margin-top: 60px;
    border-radius: 20px;
  }
}

.hero-stat {
  padding: 16px 12px;
  text-align: center;
  border-bottom: 1px solid var(--border);
}

@media (min-width: 640px) {
  .hero-stat {
    padding: 20px 32px;
    border-bottom: none;
    border-left: 1px solid var(--border);
  }
  .hero-stat:first-child { border-left: none; }
}

.hero-stat:last-child { border-bottom: none; }
.hs-val { font-size: 20px; font-weight: 900; color: var(--green-dark); }

@media (min-width: 640px) {
  .hs-val { font-size: 28px; }
}

.hs-lbl { font-size: 10px; color: var(--ink-lt); margin-top: 2px; }

@media (min-width: 640px) {
  .hs-lbl { font-size: 12px; }
}

/* ── SECTION SHARED ── */
.section { padding: 60px 16px; }

@media (min-width: 769px) {
  .section { padding: 96px 24px; }
}

.section-center { text-align: center; max-width: 600px; margin: 0 auto 40px; }

@media (min-width: 769px) {
  .section-center { margin: 0 auto 56px; }
}

.section-tag {
  display: inline-block;
  font-size: 11px; font-weight: 700; color: var(--green-mid);
  text-transform: uppercase; letter-spacing: .1em;
  margin-bottom: 12px;
}

@media (max-width: 480px) {
  .section-tag {
    font-size: 10px;
  }
}
.section-title {
  font-size: clamp(22px, 5vw, 40px);
  font-weight: 900; line-height: 1.2;
  letter-spacing: -0.8px; color: var(--ink);
  margin-bottom: 14px;
}

@media (max-width: 480px) {
  .section-title {
    font-size: 20px;
  }
}

.section-sub { font-size: 15px; color: var(--ink-lt); line-height: 1.8; }

@media (max-width: 480px) {
  .section-sub {
    font-size: 14px;
    line-height: 1.8;
  }
}

/* ── FEATURES ── */
.features-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
  max-width: 1000px;
  margin: 0 auto;
}

@media (min-width: 640px) {
  .features-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
}

@media (min-width: 1024px) {
  .features-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
.feat-card {
  background: #fff; border-radius: 20px;
  border: 1px solid var(--border);
  padding: 28px;
  transition: all .25s;
  position: relative; overflow: hidden;
}
.feat-card::before {
  content: '';
  position: absolute; top: 0; right: 0;
  width: 80px; height: 80px;
  border-radius: 0 20px 0 80px;
  opacity: 0;
  transition: opacity .3s;
}
.feat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
.feat-card:hover::before { opacity: 1; }
.feat-card.green::before  { background: rgba(29,158,117,0.08); }
.feat-card.gold::before   { background: rgba(200,150,26,0.08); }
.feat-card.blue::before   { background: rgba(24,95,165,0.08); }

.feat-icon {
  width: 48px; height: 48px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; margin-bottom: 16px;
}
.fi-green  { background: #ECFDF5; }
.fi-gold   { background: #FFFBEB; }
.fi-blue   { background: #EFF6FF; }
.fi-red    { background: #FEF2F2; }
.fi-purple { background: #F5F3FF; }
.fi-teal   { background: #E0F7FA; }

.feat-title { font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.feat-desc  { font-size: 14px; color: var(--ink-lt); line-height: 1.8; }

@media (max-width: 480px) {
  .feat-title { font-size: 15px; }
  .feat-desc  { font-size: 13px; }
}

/* ── HOW IT WORKS ── */
.how-section { background: var(--green-dark); color: #fff; padding: 60px 16px; }

@media (min-width: 769px) {
  .how-section { padding: 96px 24px; }
}

.how-section .section-tag  { color: var(--green-lt); }
.how-section .section-title { color: #fff; }
.how-section .section-sub   { color: rgba(255,255,255,0.6); }

.steps-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2px;
  max-width: 900px;
  margin: 0 auto;
  background: rgba(255,255,255,0.05);
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.08);
}

@media (min-width: 640px) {
  .steps-grid {
    grid-template-columns: repeat(2, 1fr);
    border-radius: 20px;
  }
}

@media (min-width: 1024px) {
  .steps-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
.step-card {
  padding: 32px 24px; text-align: center;
  background: rgba(255,255,255,0.03);
  transition: background .2s;
  position: relative;
}
.step-card:hover { background: rgba(255,255,255,0.07); }
.step-num {
  width: 44px; height: 44px; border-radius: 50%;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.15);
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; font-weight: 900; color: var(--green-lt);
  margin: 0 auto 16px;
}
.step-icon { font-size: 28px; margin-bottom: 12px; }
.step-card-title { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.step-card-desc  { font-size: 13px; color: rgba(255,255,255,0.65); line-height: 1.7; }

@media (max-width: 480px) {
  .step-card-title { font-size: 14px; }
  .step-card-desc  { font-size: 12px; }
}

/* ── TESTIMONIAL / QUOTE ── */
.quote-section { padding: 60px 16px; background: #fff; }

@media (min-width: 769px) {
  .quote-section { padding: 80px 24px; }
}

.quote-card {
  max-width: 640px; margin: 0 auto;
  text-align: center;
  padding: 32px 20px;
  background: var(--cream);
  border-radius: 20px;
  border: 1px solid var(--border);
  position: relative;
}

@media (min-width: 640px) {
  .quote-card {
    padding: 48px 40px;
    border-radius: 24px;
  }
}
.quote-mark { font-size: 80px; line-height: 1; color: var(--green-mid); opacity: .15; margin-bottom: -20px; font-family: serif; }
.quote-text { font-size: 17px; font-weight: 500; line-height: 1.8; color: var(--ink); margin-bottom: 24px; }

@media (max-width: 480px) {
  .quote-text { font-size: 15px; }
}
.quote-author { display: flex; align-items: center; justify-content: center; gap: 12px; }
.qa-av {
  width: 44px; height: 44px; border-radius: 50%;
  background: var(--green-dark);
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; font-weight: 700; color: #fff;
}
.qa-name { font-size: 14px; font-weight: 700; color: var(--ink); }
.qa-role { font-size: 12px; color: var(--ink-lt); }

/* ── CTA SECTION ── */
.cta-section {
  padding: 60px 16px;
  text-align: center;
  background: linear-gradient(135deg, var(--green-dark) 0%, #0f6e2f 100%);
  position: relative;
  overflow: hidden;
}

@media (min-width: 769px) {
  .cta-section {
    padding: 96px 24px;
  }
}

.cta-section::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse 60% 60% at 50% 100%, rgba(29,158,117,0.3), transparent);
}
.cta-section .section-title { color: #fff; position: relative; }
.cta-section .section-sub   { color: rgba(255,255,255,0.65); position: relative; }
.cta-big-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  background: #fff; color: var(--green-dark);
  font-size: 15px; font-weight: 900; padding: 14px 32px;
  border-radius: 14px; cursor: pointer; border: none;
  font-family: 'Tajawal', sans-serif;
  transition: all .25s; position: relative;
  box-shadow: 0 4px 24px rgba(0,0,0,0.2);
  margin-top: 24px;
  text-decoration: none;
  width: 100%;
  max-width: 300px;
}

@media (min-width: 640px) {
  .cta-big-btn {
    width: auto;
    margin-top: 32px;
  }
}

.cta-big-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 36px rgba(0,0,0,0.3); }

/* ── FOOTER ── */
.footer {
  background: var(--ink);
  color: rgba(255,255,255,0.6);
  padding: 48px 16px 24px;
  font-size: 13px;
}

@media (min-width: 769px) {
  .footer {
    padding: 60px 32px 32px;
  }
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
  margin-bottom: 32px;
}

@media (min-width: 640px) {
  .footer-content {
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
  }
}

@media (min-width: 1024px) {
  .footer-content {
    grid-template-columns: repeat(4, 1fr);
    gap: 48px;
  }
}

.footer-section h4 {
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.footer-section ul {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.footer-section a {
  color: rgba(255,255,255,0.6);
  text-decoration: none;
  transition: color 0.2s;
  font-size: 13px;
}

.footer-section a:hover {
  color: var(--green-lt);
}

.footer-bottom {
  max-width: 1200px;
  margin: 0 auto;
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  align-items: center;
  text-align: center;
}

@media (min-width: 768px) {
  .footer-bottom {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    text-align: left;
  }
}

.footer-logo {
  font-size: 16px;
  font-weight: 900;
  color: var(--green-lt);
  display: flex;
  align-items: center;
  gap: 6px;
}

/* ── ANIMATIONS ── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeDown {
  from { opacity: 0; transform: translateY(-16px); }
  to   { opacity: 1; transform: translateY(0); }
}

.reveal {
  opacity: 0; transform: translateY(30px);
  transition: opacity .7s ease, transform .7s ease;
}
.reveal.visible { opacity: 1; transform: translateY(0); }
</style>

<!-- ── NAVBAR ── -->
<div class="nav">
  <div class="nav-logo">
    <div class="nav-logo-icon" style="background: linear-gradient(135deg, #0a4f14 0%, #1D9E75 100%); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 18px; letter-spacing: -1px;"><span style="color: #a7f3d0;">i</span><span style="color: #fff;">D</span></div>
    <div>
      <div class="nav-logo-text" style="font-family: 'Plus Jakarta Sans', sans-serif;"><span style="color: #1D9E75;">i</span>Dara</div>
      <div class="nav-logo-sub">WORKFORCE MANAGEMENT</div>
    </div>
  </div>
  <div class="nav-links">
    <a href="#features" class="nav-link">المميزات</a>
    <a href="#how" class="nav-link">كيف يشتغل؟</a>
    <a href="#contact" class="nav-link">تواصل معنا</a>
  </div>
  <a href="{{ route('login') }}" class="nav-cta">ابدأ مجاناً</a>
  
  <!-- Mobile menu toggle -->
  <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
  
  <!-- Mobile nav links -->
  <div class="nav-links-mobile" id="mobileMenu">
    <a href="#features" class="nav-link">المميزات</a>
    <a href="#how" class="nav-link">كيف يشتغل؟</a>
    <a href="#contact" class="nav-link">تواصل معنا</a>
    <a href="{{ route('login') }}" class="nav-link" style="padding: 12px 20px; background: var(--green-dark); color: #fff; border-radius: 10px; margin: 8px 12px;">ابدأ مجاناً</a>
  </div>
</div>

<!-- ── HERO ── -->
<div class="hero">
  <div class="hero-bg"></div>

  <div class="hero-content">
    <div class="hero-eyebrow">
      <div class="eyebrow-dot"></div>
      النظام الأول لمقاولي العمالة الزراعية في مصر
    </div>

    <div class="hero-title">
      وزّع عمالك<br>
      <span class="accent">بضغطة واحدة</span>
    </div>

    <p class="hero-sub">
      نظام iDara بيساعد المقاول يوزع عماله على الشركات كل يوم،<br>
      يتابع الأجور والخصومات والسلف، ويحصّل مستحقاته — كل ده من موبايله.
    </p>

    <div class="hero-ctas">
      <a href="{{ route('login') }}" class="cta-primary">ابدأ تجربة مجانية ←</a>
      <a href="#how" class="cta-secondary">شوف كيف بيشتغل</a>
    </div>

    <div class="hero-stats">
      <div class="hero-stat">
        <div class="hs-val">47+</div>
        <div class="hs-lbl">عامل في النظام</div>
      </div>
      <div class="hero-stat">
        <div class="hs-val">6</div>
        <div class="hs-lbl">شركات متعاقدة</div>
      </div>
      <div class="hero-stat">
        <div class="hs-val">100%</div>
        <div class="hs-lbl">دقة الحساب</div>
      </div>
      <div class="hero-stat">
        <div class="hs-val">0</div>
        <div class="hs-lbl">أخطاء يدوية</div>
      </div>
    </div>
  </div>
</div>

<!-- ── FEATURES ── -->
<div class="section" id="features" style="background: var(--cream);">
  <div class="section-center reveal">
    <div class="section-tag">المميزات</div>
    <div class="section-title">كل اللي المقاول محتاجه في مكان واحد</div>
    <div class="section-sub">مش محتاج تدفتر ولا Excel — كل حاجة بتتحسب تلقائي وبتفضل محفوظة.</div>
  </div>

  <div class="features-grid">
    <div class="feat-card green reveal">
      <div class="feat-icon fi-green">👷</div>
      <div class="feat-title">توزيع يومي سريع</div>
      <div class="feat-desc">اختار الشركة، اختار العمال، واضغط تأكيد — الحضور والأجر بيتسجلوا تلقائي.</div>
    </div>
    <div class="feat-card gold reveal" style="transition-delay:.1s">
      <div class="feat-icon fi-gold">💰</div>
      <div class="feat-title">حساب الأجور تلقائي</div>
      <div class="feat-desc">كل شركة ليها أجر مختلف — النظام بيحسب الإجمالي لحظياً لما تختار العمال.</div>
    </div>
    <div class="feat-card blue reveal" style="transition-delay:.2s">
      <div class="feat-icon fi-blue">📊</div>
      <div class="feat-title">تتبع الخصومات والسلف</div>
      <div class="feat-desc">سجّل خصم ربع أو نص أو يوم كامل، وسجّل سلف العمال — كل ده بيتخصم تلقائي.</div>
    </div>
    <div class="feat-card green reveal" style="transition-delay:.1s">
      <div class="feat-icon fi-teal">🏢</div>
      <div class="feat-title">إدارة الشركات</div>
      <div class="feat-desc">تابع كل شركة بشكل مستقل — عمالها، أجورها، ومستحقاتها كلها في مكان واحد.</div>
    </div>
    <div class="feat-card gold reveal" style="transition-delay:.2s">
      <div class="feat-icon fi-gold">💳</div>
      <div class="feat-title">تحصيل مرن</div>
      <div class="feat-desc">كل شركة ليها دورة دفع مختلفة — يومي، أسبوعي، أو نص شهري. النظام يفكّرك تلقائي.</div>
    </div>
    <div class="feat-card blue reveal" style="transition-delay:.3s">
      <div class="feat-icon fi-purple">📱</div>
      <div class="feat-title">من الموبايل</div>
      <div class="feat-desc">شغال تمام على أي موبايل أو تابلت — مش محتاج لابتوب أو مكتب.</div>
    </div>
  </div>
</div>

<!-- ── HOW IT WORKS ── -->
<div class="how-section" id="how">
  <div class="section-center reveal">
    <div class="section-tag">كيف بيشتغل؟</div>
    <div class="section-title">3 خطوات بس كل يوم</div>
    <div class="section-sub" style="color:rgba(255,255,255,0.55);">بدون تدريب ولا خبرة — أي حد يقدر يشتغل عليه من أول يوم.</div>
  </div>

  <div class="steps-grid reveal">
    <div class="step-card">
      <div class="step-num">1</div>
      <div class="step-icon">🏢</div>
      <div class="step-card-title">اختار الشركة</div>
      <div class="step-card-desc">اضغط على الشركة اللي هتبعت ليها عمال النهارده — الأجر بييجي تلقائي.</div>
    </div>
    <div class="step-card">
      <div class="step-num">2</div>
      <div class="step-icon">👥</div>
      <div class="step-card-title">اختار العمال</div>
      <div class="step-card-desc">اضغط على أسماء العمال — الإجمالي بيتحسب لحظياً مع كل ضغطة.</div>
    </div>
    <div class="step-card">
      <div class="step-num">3</div>
      <div class="step-icon">✅</div>
      <div class="step-card-title">اضغط تأكيد</div>
      <div class="step-card-desc">الحضور والأجر بيتسجلوا تلقائي — خلصت الشغل في أقل من دقيقة.</div>
    </div>
    <div class="step-card">
      <div class="step-num">4</div>
      <div class="step-icon">📥</div>
      <div class="step-card-title">حصّل مستحقاتك</div>
      <div class="step-card-desc">النظام بيفكّرك بالمبالغ المستحقة من كل شركة وموعد الدفع.</div>
    </div>
  </div>
</div>

<!-- ── QUOTE ── -->
{{-- <div class="quote-section">
  <div class="quote-card reveal">
    <div class="quote-mark">"</div>
    <div class="quote-text">
      قبل كنت باكتب كل حاجة في دفتر وأحياناً بانسى عامل أو بيضيع منى مبلغ — دلوقتي كل حاجة واضحة وبعرف المستحق من كل شركة في ثانية.
    </div>
    <div class="quote-author">
      <div class="qa-av">أ</div>
      <div>
        <div class="qa-name">أبو خالد</div>
        <div class="qa-role">مقاول عمالة زراعية — الدقهلية</div>
      </div>
    </div>
  </div>
</div> --}}

<!-- ── FINAL CTA ── -->
<div class="cta-section" id="contact">
  <div class="section-title reveal">جاهز تبدأ؟</div>
  <div class="section-sub reveal" style="margin-top:12px;">
    سجّل مجاناً وابدأ تنظم شغلك من النهارده.<br>
    مش محتاج تدريب — 5 دقايق وتبقى شاغل.
  </div>
  <a href="{{ route('login') }}" class="cta-big-btn reveal">
    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 16px;"><span style="color: #a7f3d0;">i</span>D</span> ابدأ مجاناً دلوقتي
  </a>
</div>

<!-- ── FOOTER ── -->
<div class="footer">
  <div class="footer-content">
    <!-- Brand Section -->
    <div class="footer-section">
      <h4>
        <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 16px;"><span style="color: #4ade80;">i</span>Dara</span>
      </h4>
      <p style="font-size: 13px; line-height: 1.6; color: rgba(255,255,255,0.5);">
        نظام إدارة العمالة الذكي. نساعد المقاولين على توزيع عمالهم وإدارة الأجور بكل سهولة واحترافية.
      </p>
    </div>

    <!-- Product Links -->
    <div class="footer-section">
      <h4>المنتج</h4>
      <ul>
        <li><a href="#features">المميزات</a></li>
        <li><a href="#how">كيف يشتغل؟</a></li>
        <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
        <li><a href="{{ route('request-registration') }}">قدم طلب تسجيل</a></li>
      </ul>
    </div>

    <!-- Company Links -->
    <div class="footer-section">
      <h4>الشركة</h4>
      <ul>
        <li><a href="#contact">تواصل معنا</a></li>
        <li><a href="#" onclick="alert('سياسة الخصوصية قريباً')">سياسة الخصوصية</a></li>
        <li><a href="#" onclick="alert('شروط الاستخدام قريباً')">شروط الاستخدام</a></li>
        <li><a href="#" onclick="alert('المدونة قريباً')">المدونة</a></li>
      </ul>
    </div>

    <!-- Contact & Social -->
    <div class="footer-section">
      <h4>تواصل معنا</h4>
      <ul>
        <li><a href="mailto:mhmdbdaljwad759@gmail.com">البريد الإلكتروني: mhmdbdaljwad759@gmail.com</a></li>
        <li><a href="tel:+201029354974">الهاتف: +20 102 935 4974</a></li>
        <li><a href="https://www.facebook.com/mohammed.abd.algawad" target="_blank">فيسبوك</a></li>
        <li><a href="https://wa.me/201029354974" target="_blank">واتساب</a></li>
      </ul>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="footer-logo" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800;">
      <span style="color: #4ade80;">i</span>Dara
    </div>
    <div style="flex: 1; text-align: center;">
      © 2025 iDara · نظام إدارة العمالة الذكي · صُنع في مصر
    </div>
    <div></div>
  </div>
</div>

<script>
// Mobile menu toggle
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

if (mobileMenuBtn) {
  mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('active');
  });

  // Close mobile menu when a link is clicked
  document.querySelectorAll('.nav-links-mobile .nav-link').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('active');
    });
  });
}

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('.nav')) {
    mobileMenu?.classList.remove('active');
  }
});

// Scroll reveal
const observer = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    if (href !== '#' && document.querySelector(href)) {
      e.preventDefault();
      const element = document.querySelector(href);
      const navbar = document.querySelector('.nav');
      const navHeight = navbar ? navbar.offsetHeight : 0;
      const elementPosition = element.getBoundingClientRect().top + window.scrollY - navHeight;
      
      window.scrollTo({
        top: elementPosition,
        behavior: 'smooth'
      });
    }
  });
});

// ======================================
// PWA: Service Worker Registration
// ======================================
if ('serviceWorker' in navigator) {
  console.log('[PWA] Registering Service Worker...');
  navigator.serviceWorker.register('/sw.js', { scope: '/' })
    .then(registration => {
      console.log('[PWA] ✅ Service Worker registered:', registration.scope);
      // Check for updates periodically
      setInterval(() => {
        registration.update();
      }, 60000);
    })
    .catch(error => {
      console.error('[PWA] ❌ Service Worker registration failed:', error);
    });
} else {
  console.warn('[PWA] Service Worker not supported');
}

// ======================================
// PWA: beforeinstallprompt Event
// ======================================
let deferredPrompt = null;
let installPromptShown = false;

window.addEventListener('beforeinstallprompt', (e) => {
  console.log('[PWA] ✅ beforeinstallprompt event fired!');
  console.log('[PWA] Install prompt is ready to be shown');
  
  // Prevent the mini-infobar from appearing
  e.preventDefault();
  
  // Store the event for later use
  deferredPrompt = e;
  installPromptShown = true;
  
  // Optional: Show a custom install button
  showInstallButton();
});

window.addEventListener('appinstalled', () => {
  console.log('[PWA] ✅ App installed successfully!');
  deferredPrompt = null;
  hideInstallButton();
});

// Helper function: Show install toast (small notification)
function showInstallButton() {
  console.log('[PWA] Showing install toast');
  
  if (!document.getElementById('install-toast')) {
    const toast = document.createElement('div');
    toast.id = 'install-toast';
    toast.style.cssText = `
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #1D9E75;
      color: white;
      padding: 12px 16px;
      border-radius: 8px;
      font-family: 'Tajawal', sans-serif;
      font-size: 13px;
      z-index: 9999;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      animation: slideUp 0.3s ease forwards;
    `;
    
    toast.innerHTML = `
      <span>📱 ثبّت التطبيق</span>
      <button id="install-toast-btn" style="
        background: white;
        color: #1D9E75;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Tajawal', sans-serif;
        font-size: 12px;
      ">ثبّت</button>
    `;
    
    const style = document.createElement('style');
    style.innerHTML = `
      @keyframes slideUp {
        from {
          transform: translateY(120px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(toast);
    
    // Install button click handler
    document.getElementById('install-toast-btn').addEventListener('click', async () => {
      if (deferredPrompt) {
        console.log('[PWA] User clicked install toast');
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log(`[PWA] User response: ${outcome}`);
        deferredPrompt = null;
        
        // Remove toast with animation
        toast.style.animation = 'slideUp 0.3s ease reverse forwards';
        setTimeout(() => toast.remove(), 300);
      }
    });
    
    // Auto hide after 8 seconds
    setTimeout(() => {
      if (document.body.contains(toast)) {
        toast.style.animation = 'slideUp 0.3s ease reverse forwards';
        setTimeout(() => toast.remove(), 300);
      }
    }, 8000);
  }
}

// Helper function: Hide install toast
function hideInstallButton() {
  const toast = document.getElementById('install-toast');
  if (toast) {
    toast.style.animation = 'slideUp 0.3s ease reverse forwards';
    setTimeout(() => toast.remove(), 300);
  }
}

// Log PWA status
console.log('[PWA] Welcome page loaded');
console.log('[PWA] beforeinstallprompt ready:', installPromptShown);

</script>
</body>
</html>
