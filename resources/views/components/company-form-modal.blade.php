<!-- Company Form Modal -->
<div id="company-form-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;flex-direction:column">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:700px;max-height:90vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.2)">
        <!-- Modal Header -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:0.5px solid #d0d0c8;position:sticky;top:0;background:#fff;z-index:10">
            <h2 style="font-size:18px;font-weight:700;color:#1a1c19" id="modal-title">شركة جديدة</h2>
            <button onclick="closeCompanyModal()" style="background:none;border:none;font-size:24px;cursor:pointer;color:#707a6c;padding:0;width:32px;height:32px;display:flex;align-items:center;justify-content:center">✕</button>
        </div>

        <!-- Modal Content -->
        <div style="padding:24px">
            <form id="company-form" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">اسم الشركة *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الاسم الرسمي للشركة</p>
                        <input type="text" name="name" id="form-name"
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;outline:none"
                            placeholder="مثال: شركة المغربي">
                        <p class="form-error" id="error-name" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">الأجر اليومي *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أجر العامل الواحد في اليوم</p>
                        <div style="display:flex;align-items:center;gap:8px">
                            <input type="number" name="daily_wage" id="form-daily_wage" step="0.01"
                                style="flex:1;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;outline:none"
                                placeholder="250.50">
                            <span style="color:#707a6c;font-weight:600">ج</span>
                        </div>
                        <p class="form-error" id="error-daily_wage" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">كم مرة يتم الدفع؟ *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الفترة ما بين كل دفعة والتي تليها</p>
                        <select name="payment_cycle" id="form-payment_cycle"
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;outline:none">
                            <option value="">-- اختر دورة الدفع --</option>
                            <option value="daily">كل يوم</option>
                            <option value="weekly">كل أسبوع</option>
                            <option value="bimonthly">كل نصف شهر (15 يوم)</option>
                        </select>
                        <p class="form-error" id="error-payment_cycle" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>
                </div>

                <div style="margin-bottom:24px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">متى بدأت العلاقة معهم؟ *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 تاريخ بدء التعاقد مع الشركة</p>
                    <input type="date" name="contract_start_date" id="form-contract_start_date"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;outline:none">
                    <p class="form-error" id="error-contract_start_date" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                </div>

                <div style="margin-bottom:24px;display:none" id="status-field">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">هل العلاقة نشطة؟</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 هل تستمر في العمل معهم الآن؟</p>
                    <select name="is_active" id="form-is_active"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box;outline:none">
                        <option value="1">✅ نعم، نستمر معهم</option>
                        <option value="0">⏸️ لا، توقفنا</option>
                    </select>
                </div>

                <!-- Modal Footer -->
                <div style="display:flex;gap:12px;justify-content:flex-end;border-top:0.5px solid #d0d0c8;padding-top:20px">
                    <button type="button" onclick="closeCompanyModal()" class="btn btn-outline" style="height:40px;padding:0 20px;font-size:13px;font-weight:600;border:0.5px solid #d0d0c8;background:#fff;border-radius:8px;cursor:pointer">
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary" style="height:40px;padding:0 20px;white-space:nowrap;font-size:13px;font-weight:600;background:#0d631b;color:#fff;border:none;border-radius:8px;cursor:pointer">
                        <span id="submit-btn-text">حفظ الشركة</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #company-form-modal {
        display: none;
        padding: 0;
    }

    #company-form-modal.show {
        display: flex !important;
    }

    #company-form-modal input,
    #company-form-modal select,
    #company-form-modal textarea {
        font-family: 'Segoe UI', sans-serif;
        transition: border-color 0.2s;
    }

    #company-form-modal input:focus,
    #company-form-modal select:focus,
    #company-form-modal textarea:focus {
        border-color: #0d631b !important;
        background: #fff !important;
    }

    #company-form-modal > div {
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
    }

    #company-form-modal > div > div:last-child {
        flex: 1;
        overflow-y: auto;
    }

    /* Tablet & Mobile: Adjust modal width */
    @media(max-width: 768px) and (min-width: 481px) {
        #company-form-modal > div {
            width: 95% !important;
            max-width: 95% !important;
            max-height: 90vh !important;
            border-radius: 12px;
        }

        #company-form-modal {
            padding: 8px;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Stack form fields to 1 column on tablet */
        [style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
        }

        #company-form-modal input,
        #company-form-modal select,
        #company-form-modal textarea {
            font-size: 14px !important;
        }

        #company-form-modal label {
            font-size: 12px !important;
        }

        #company-form-modal [style*="padding:20px 24px"] {
            padding: 18px 18px !important;
        }

        #company-form-modal [style*="padding:24px"] {
            padding: 18px !important;
        }

        .btn {
            height: 38px !important;
            font-size: 12px !important;
        }
    }

    /* Desktop (769px+): Centered modal with animation */
    @media(min-width: 769px) {
        #company-form-modal {
            align-items: center !important;
            justify-content: center !important;
        }

        #company-form-modal > div {
            width: 90%;
            max-width: 700px;
            max-height: 85vh;
            border-radius: 12px;
            animation: fadeSlideDown 0.3s ease-out;
        }

        @keyframes fadeSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    /* Mobile: Full optimization with slide-up effect */
    @media(max-width: 480px) {
        #company-form-modal {
            padding: 0;
            align-items: flex-end !important;
            justify-content: flex-end !important;
        }

        #company-form-modal > div {
            width: 100% !important;
            max-width: 100% !important;
            max-height: 95vh !important;
            border-radius: 16px 16px 0 0;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Modal Header: Reduce padding and font size */
        #company-form-modal [style*="padding:20px 24px"] {
            padding: 14px 12px !important;
        }

        #company-form-modal [style*="padding:20px 24px"] h2 {
            font-size: 15px !important;
            font-weight: 700;
        }

        #company-form-modal [style*="padding:20px 24px"] button {
            width: 28px !important;
            height: 28px !important;
            font-size: 20px !important;
        }

        /* Modal Content: Reduce padding */
        #company-form-modal > div > div:nth-child(3) {
            padding: 14px 12px !important;
            padding-bottom: 16px !important;
        }

        /* Form Labels: Slightly smaller */
        #company-form-modal label {
            font-size: 11px !important;
            font-weight: 600;
        }

        /* Form Helper Text: Adjust size */
        #company-form-modal p[style*="color:#707a6c"] {
            font-size: 9px !important;
            margin-bottom: 6px !important;
        }

        /* Form Inputs: Better sizing for touch */
        #company-form-modal input,
        #company-form-modal select,
        #company-form-modal textarea {
            font-size: 14px !important;
            padding: 10px 10px !important;
            height: 38px;
        }

        #company-form-modal textarea {
            height: auto !important;
            min-height: 80px;
            padding: 10px 10px !important;
        }

        /* Grid columns stack */
        #company-form-modal [style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 14px !important;
            margin-bottom: 16px !important;
        }

        /* Form sections */
        #company-form-modal > div > div:nth-child(3) > form > div {
            margin-bottom: 16px !important;
        }

        /* Modal Footer: Adjust button layout */
        #company-form-modal [style*="justify-content:flex-end"] {
            padding: 14px 12px !important;
            gap: 8px !important;
            border-top: 0.5px solid #d0d0c8;
        }

        #company-form-modal .btn {
            font-size: 12px !important;
            height: 36px !important;
            padding: 0 14px !important;
            flex: 1;
            border-radius: 8px;
        }

        #company-form-modal button[type="button"] {
            background: #f5f6f8 !important;
            border: 0.5px solid #d0d0c8 !important;
            color: #1a1c19 !important;
        }

        #company-form-modal button[type="submit"] {
            background: #0d631b !important;
            color: #fff !important;
            border: none !important;
        }

        /* Error messages */
        #company-form-modal .form-error {
            font-size: 10px !important;
            margin-top: 3px !important;
        }
    }
</style>

