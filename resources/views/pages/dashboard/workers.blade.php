<div class="page fade-in" id="page-workers">
    <div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start">

        <!-- Workers list -->
        <x-card title="قائمة العمال" action="+ إضافة">
            <div style="padding:10px 12px;border-bottom:0.5px solid #d0d0c8">
                <input class="form-input" style="height:36px;font-size:13px" placeholder="بحث باسم العامل..."/>
            </div>
            <div id="workers-list">
                <div class="worker-row active-worker" onclick="selectWorker(this,'محمد سالم حسن','047')" style="display:flex;align-items:center;gap:10px;padding:12px 14px;cursor:pointer;background:#f0f9f0;border-bottom:0.5px solid #e8e8e3">
                    <x-avatar initial="م" variant="green" size="small" />
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:700">محمد سالم حسن</div>
                        <div style="font-size:11px;color:#707a6c">رقم 047 · نشط</div>
                    </div>
                    <x-badge variant="green" style="font-size:10px">نشط</x-badge>
                </div>
                <div class="worker-row" onclick="selectWorker(this,'أحمد علي محمود','023')" style="display:flex;align-items:center;gap:10px;padding:12px 14px;cursor:pointer;border-bottom:0.5px solid #e8e8e3">
                    <x-avatar initial="أ" variant="blue" size="small" />
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:700">أحمد علي محمود</div>
                        <div style="font-size:11px;color:#707a6c">رقم 023 · نشط</div>
                    </div>
                    <x-badge variant="green" style="font-size:10px">نشط</x-badge>
                </div>
                <div class="worker-row" onclick="selectWorker(this,'حسن محمود سالم','031')" style="display:flex;align-items:center;gap:10px;padding:12px 14px;cursor:pointer;border-bottom:0.5px solid #e8e8e3">
                    <x-avatar initial="ح" variant="amber" size="small" />
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:700">حسن محمود سالم</div>
                        <div style="font-size:11px;color:#707a6c">رقم 031 · نشط</div>
                    </div>
                    <x-badge variant="green" style="font-size:10px">نشط</x-badge>
                </div>
                <div class="worker-row" onclick="selectWorker(this,'عمر عبد الله','018')" style="display:flex;align-items:center;gap:10px;padding:12px 14px;cursor:pointer">
                    <x-avatar initial="ع" variant="purple" size="small" />
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:700">عمر عبد الله</div>
                        <div style="font-size:11px;color:#707a6c">رقم 018 · نشط</div>
                    </div>
                    <x-badge variant="green" style="font-size:10px">نشط</x-badge>
                </div>
            </div>
        </x-card>

        <!-- Worker detail -->
        <div>
            <x-card>
                <div style="padding:20px;background:#0d631b;display:flex;align-items:center;gap:14px">
                    <x-avatar initial="م" variant="green" size="large" />
                    <div>
                        <div style="font-size:18px;font-weight:700;color:#fff" id="worker-name">محمد سالم حسن</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.65)" id="worker-num">عامل · رقم 047</div>
                    </div>
                    <div style="margin-right:auto;display:flex;gap:8px">
                        <x-button variant="outline" size="small" icon="edit" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2)">
                            تعديل
                        </x-button>
                    </div>
                </div>
                <div class="tab-bar">
                    <div class="tab-item active" onclick="switchTab(this,'tab-attendance')">الحضور</div>
                    <div class="tab-item" onclick="switchTab(this,'tab-deductions')">الخصومات</div>
                    <div class="tab-item" onclick="switchTab(this,'tab-advances')">السلف</div>
                    <div class="tab-item" onclick="switchTab(this,'tab-account')">الحساب</div>
                </div>

                <!-- Attendance tab -->
                <div id="tab-attendance" style="padding:0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الشركة</th>
                                <th>الحالة</th>
                                <th>الأجر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>الأحد 6 أبريل</td>
                                <td>شركة رجب</td>
                                <td><x-badge variant="green">حاضر</x-badge></td>
                                <td style="font-weight:700;color:#0d631b">300 ج</td>
                            </tr>
                            <tr>
                                <td>السبت 5 أبريل</td>
                                <td>شركة المغربي</td>
                                <td><x-badge variant="amber">خصم ¼</x-badge></td>
                                <td style="font-weight:700;color:#BA7517">187 ج</td>
                            </tr>
                            <tr>
                                <td>الجمعة 4 أبريل</td>
                                <td>شركة رجب</td>
                                <td><x-badge variant="green">حاضر</x-badge></td>
                                <td style="font-weight:700;color:#0d631b">300 ج</td>
                            </tr>
                            <tr>
                                <td>الخميس 3 أبريل</td>
                                <td>شركة المغربي</td>
                                <td><x-badge variant="green">حاضر</x-badge></td>
                                <td style="font-weight:700;color:#0d631b">250 ج</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="padding:16px;background:#fafaf5;border-top:0.5px solid #d0d0c8">
                        <div style="display:flex;gap:12px;flex-wrap:wrap">
                            <div style="flex:1;min-width:160px">
                                <div class="sum-box">
                                    <div class="sum-row"><span class="sum-key">إجمالي الأجر</span><span class="sum-val green" style="color:#0d631b">787 ج</span></div>
                                    <div class="sum-row"><span class="sum-key">سلف مخصومة</span><span class="sum-val" style="color:#ba1a1a">- 200 ج</span></div>
                                    <div class="sum-row sum-total"><span class="sum-key">صافي المستحق</span><span class="sum-val">587 ج</span></div>
                                </div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:8px;justify-content:center">
                                <x-button variant="outline" size="small" icon="remove_circle">تسجيل خصم</x-button>
                                <x-button variant="outline" size="small" icon="account_balance_wallet">تسجيل سلفة</x-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deductions tab -->
                <div id="tab-deductions" style="display:none;padding:20px">
                    <div style="margin-bottom:16px">
                        <div style="font-size:13px;font-weight:700;margin-bottom:10px;color:#1a1c19">تسجيل خصم جديد</div>
                        <div class="form-grid" style="margin-bottom:12px">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">تاريخ الخصم</label>
                                <input type="date" class="form-input" value="2025-04-06"/>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">سبب الخصم</label>
                                <input class="form-input" placeholder="مثال: تأخر في الوصول..."/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">مقدار الخصم</label>
                            <div style="display:flex;gap:8px">
                                <button class="disc-btn sel" onclick="selDisc(this,'ربع يوم')">ربع يوم</button>
                                <button class="disc-btn" onclick="selDisc(this,'نص يوم')">نص يوم</button>
                                <button class="disc-btn" onclick="selDisc(this,'يوم كامل')">يوم كامل</button>
                                <button class="disc-btn" onclick="selDisc(this,'مخصص')">مخصص</button>
                            </div>
                        </div>
                        <x-button variant="primary" size="small" icon="check">تسجيل الخصم</x-button>
                    </div>
                    <div style="border-top:0.5px solid #d0d0c8;padding-top:16px">
                        <div style="font-size:12px;font-weight:700;color:#707a6c;margin-bottom:8px">سجل الخصومات</div>
                        <table class="data-table">
                            <thead><tr><th>التاريخ</th><th>الشركة</th><th>النوع</th><th>المبلغ</th></tr></thead>
                            <tbody>
                                <tr><td>5 أبريل</td><td>شركة المغربي</td><td><x-badge variant="amber">ربع يوم</x-badge></td><td style="color:#BA7517;font-weight:700">- 62.5 ج</td></tr>
                                <tr><td>30 مارس</td><td>شركة رجب</td><td><x-badge variant="red">يوم كامل</x-badge></td><td style="color:#ba1a1a;font-weight:700">- 300 ج</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Advances tab -->
                <div id="tab-advances" style="display:none;padding:20px">
                    <div style="margin-bottom:16px">
                        <div style="font-size:13px;font-weight:700;margin-bottom:10px">تسجيل سلفة جديدة</div>
                        <div class="form-grid" style="margin-bottom:12px">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">المبلغ</label>
                                <input class="form-input" placeholder="مثال: 200 ج"/>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">التاريخ</label>
                                <input type="date" class="form-input" value="2025-04-06"/>
                            </div>
                        </div>
                        <x-button variant="primary" size="small" icon="check">تسجيل السلفة</x-button>
                    </div>
                    <div style="border-top:0.5px solid #d0d0c8;padding-top:16px">
                        <table class="data-table">
                            <thead><tr><th>التاريخ</th><th>المبلغ</th><th>الحالة</th><th></th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>6 أبريل</td>
                                    <td style="font-weight:700">200 ج</td>
                                    <td><x-badge variant="amber">غير مسدد</x-badge></td>
                                    <td><x-button variant="outline" size="small">تسوية</x-button></td>
                                </tr>
                                <tr>
                                    <td>20 مارس</td>
                                    <td style="font-weight:700">300 ج</td>
                                    <td><x-badge variant="green">مسدد</x-badge></td>
                                    <td><span style="font-size:12px;color:#707a6c">25 مارس</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Account tab -->
                <div id="tab-account" style="display:none;padding:20px">
                    <div class="sum-box">
                        <div class="sum-row"><span class="sum-key">إجمالي الأجر (الشهر)</span><span class="sum-val green" style="color:#0d631b">5,500 ج</span></div>
                        <div class="sum-row"><span class="sum-key">إجمالي الخصومات</span><span class="sum-val" style="color:#ba1a1a">- 362.5 ج</span></div>
                        <div class="sum-row"><span class="sum-key">إجمالي السلف</span><span class="sum-val" style="color:#BA7517">- 500 ج</span></div>
                        <div class="sum-row sum-total"><span class="sum-key">صافي المستحق</span><span class="sum-val">4,637.5 ج</span></div>
                    </div>
                </div>

            </x-card>
        </div>

    </div>
</div>
