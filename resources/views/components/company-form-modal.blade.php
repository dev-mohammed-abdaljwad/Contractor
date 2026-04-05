<!-- Company Form Modal -->
<div id="company-form-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:700px;max-height:90vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.2)">
        <!-- Modal Header -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:0.5px solid #d0d0c8;position:sticky;top:0;background:#fff">
            <h2 style="font-size:18px;font-weight:700;color:#1a1c19" id="modal-title">شركة جديدة</h2>
            <button onclick="closeCompanyModal()" style="background:none;border:none;font-size:24px;cursor:pointer;color:#707a6c">✕</button>
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
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box"
                            placeholder="مثال: شركة المغربي">
                        <p class="form-error" id="error-name" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">جهة الاتصال *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 اسم المسؤول أو المدير</p>
                        <input type="text" name="contact_person" id="form-contact_person"
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box"
                            placeholder="مثال: أحمد محمد">
                        <p class="form-error" id="error-contact_person" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">رقم الهاتف *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 رقم جوال المسؤول الأساسي</p>
                        <input type="text" name="phone" id="form-phone"
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box"
                            placeholder="01001234567">
                        <p class="form-error" id="error-phone" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">الأجر اليومي *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أجر العامل الواحد في اليوم</p>
                        <div style="display:flex;align-items:center;gap:8px">
                            <input type="number" name="daily_wage" id="form-daily_wage" step="0.01"
                                style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box"
                                placeholder="250">
                            <span style="font-size:12px;color:#707a6c;white-space:nowrap;font-weight:500">جنيه</span>
                        </div>
                        <p class="form-error" id="error-daily_wage" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">كم مرة يتم الدفع؟ *</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 الفترة ما بين كل دفعة والتي تليها</p>
                        <select name="payment_cycle" id="form-payment_cycle"
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box">
                            <option value="">-- اختر دورة الدفع --</option>
                            <option value="daily">كل يوم</option>
                            <option value="weekly">كل أسبوع</option>
                            <option value="bimonthly">كل نصف شهر (15 يوم)</option>
                            <option value="monthly">كل شهر</option>
                        </select>
                        <p class="form-error" id="error-payment_cycle" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                    </div>

                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">يوم الدفع المفضل</label>
                        <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 مثال: الجمعة أو الأحد (اختياري)</p>
                        <input type="text" name="weekly_pay_day" id="form-weekly_pay_day"
                            style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box"
                            placeholder="الجمعة (مثال)">
                    </div>
                </div>

                <div style="margin-bottom:24px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">متى بدأت العلاقة معهم؟ *</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 تاريخ بدء التعاقد مع الشركة</p>
                    <input type="date" name="contract_start_date" id="form-contract_start_date"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box">
                    <p class="form-error" id="error-contract_start_date" style="color:#ba1a1a;font-size:11px;margin-top:4px;display:none"></p>
                </div>

                <div style="margin-bottom:24px;display:none" id="status-field">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">هل العلاقة نشطة؟</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 هل تستمر في العمل معهم الآن؟</p>
                    <select name="is_active" id="form-is_active"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;box-sizing:border-box">
                        <option value="1">✅ نعم، نستمر معهم</option>
                        <option value="0">⏸️ لا، توقفنا</option>
                    </select>
                </div>

                <div style="margin-bottom:28px">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1a1c19;margin-bottom:6px">ملاحظات إضافية</label>
                    <p style="display:block;font-size:11px;color:#707a6c;margin-bottom:8px">💡 أي معلومات مهمة عن الشركة (اختياري)</p>
                    <textarea name="notes" id="form-notes" rows="3"
                        style="width:100%;padding:10px 12px;border:0.5px solid #d0d0c8;border-radius:8px;font-size:13px;font-family:inherit;box-sizing:border-box"
                        placeholder="مثال: يفضلون الدفع يومياً"></textarea>
                </div>

                <!-- Modal Footer -->
                <div style="display:flex;gap:12px;justify-content:flex-end;border-top:0.5px solid #d0d0c8;padding-top:20px">
                    <button type="button" onclick="closeCompanyModal()" class="btn btn-outline" style="height:40px;padding:0 20px">
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary" style="height:40px;padding:0 20px;white-space:nowrap">
                        <span class="ms ms-fill" style="font-size:16px">check_circle</span> <span id="submit-btn-text">حفظ الشركة</span>
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
        font-family: 'Tajawal', sans-serif;
    }

    #company-form-modal > div {
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        border-radius: 12px;
    }

    /* Tablet & Mobile: Adjust modal width */
    @media(max-width: 768px) {
        #company-form-modal > div {
            width: 95% !important;
            max-width: 95% !important;
            max-height: 95vh !important;
        }

        #company-form-modal{
            padding: 8px;
        }

        /* Stack form fields to 1 column on tablet */
        [style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
        }
    }

    /* Mobile: Full optimization */
    @media(max-width: 480px) {
        #company-form-modal > div {
            width: 98% !important;
            max-width: 98% !important;
            max-height: 98vh !important;
            border-radius: 8px;
        }

        #company-form-modal {
            padding: 2px;
        }

        /* Modal Header: Reduce padding and font size */
        [style*="padding:20px 24px;border-bottom"] {
            padding: 16px 12px !important;
        }

        [style*="padding:20px 24px;border-bottom"] h2 {
            font-size: 16px !important;
        }

        /* Modal Content: Reduce padding */
        #company-form-modal [style*="padding:24px"] {
            padding: 16px !important;
        }

        /* Form Labels: Slightly smaller */
        #company-form-modal label {
            font-size: 12px !important;
        }

        /* Form Helper Text: Adjust size */
        #company-form-modal p[style*="color:#707a6c"] {
            font-size: 10px !important;
        }

        /* Form Inputs: Better sizing */
        #company-form-modal input,
        #company-form-modal select,
        #company-form-modal textarea {
            font-size: 14px !important;
            padding: 10px 10px !important;
        }

        /* Modal Footer: Stack buttons vertically if needed */
        #company-form-modal [style*="justify-content:flex-end"] {
            padding: 16px 12px !important;
        }

        #company-form-modal .btn {
            font-size: 12px !important;
            flex: 1;
        }

        #company-form-modal .ms {
            font-size: 14px !important;
        }
    }
</style>
