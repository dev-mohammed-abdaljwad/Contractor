<div class="page fade-in" id="page-distribution">
    <div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start">

        <!-- Form side -->
        <div>
            <x-card title="توزيع العمال لليوم">
                <div style="padding:20px">
                    <p style="font-size:11px;color:#707a6c;margin-bottom:20px">💡 اختر الشركة ثم أضف العمال وسيحسب لك الإجمالي</p>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600">أي شركة تريد توزيع عمال لها؟</label>
                            <p style="font-size:11px;color:#707a6c;margin-bottom:8px">اختر من القائمة</p>
                            <select class="form-input form-select" id="company-select" onchange="updateWage(this)" style="border:0.5px solid #d0d0c8">
                                <option value="">-- اختر شركة --</option>
                                @foreach($companiesWithDistributions as $item)
                                    <option value="{{ $item['company']->daily_wage }}">{{ $item['company']->name }} — {{ number_format($item['company']->daily_wage, 0) }} ج/يوم</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600">الأجر للعامل الواحد</label>
                            <input class="form-input" id="wage-display" value="اختر شركة أولاً" readonly style="background:#f0f9f0;color:#0d631b;font-weight:700;border:0.5px solid #d0d0c8"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight:600">من العمال سيعملون؟</label>
                        <p style="font-size:11px;color:#707a6c;margin-bottom:8px">أضيفهم في الحقل أدناه</p>
                        <div id="chips-container" style="display:flex;flex-wrap:wrap;gap:6px;padding:10px;background:#fafaf5;border:1px solid #d0d0c8;border-radius:8px;min-height:52px">
                            <span class="chip"><span class="chip-dot"></span>محمد سالم</span>
                            <span class="chip"><span class="chip-dot"></span>أحمد علي</span>
                            <span class="chip"><span class="chip-dot"></span>حسن محمود</span>
                            <span onclick="addWorker()" style="display:inline-flex;align-items:center;gap:5px;border:1px dashed #9e9e9e;border-radius:20px;padding:4px 12px;font-size:12px;color:#707a6c;cursor:pointer;font-family:'Tajawal',sans-serif">
                                <span class="ms" style="font-size:14px">add</span> إضافة عامل
                            </span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Summary side -->
        <div>
            <x-card title="ملخص اليوم">
                <div style="padding:16px">
                    <div class="sum-box" style="margin-bottom:16px">
                        <div class="sum-row"><span class="sum-key">الشركة</span><span class="sum-val">غير محددة</span></div>
                        <div class="sum-row"><span class="sum-key">عدد العمال</span><span class="sum-val" id="worker-count">3 عمال</span></div>
                        <div class="sum-row"><span class="sum-key">الأجر للعامل</span><span class="sum-val" id="wage-val">250 ج</span></div>
                        <div class="sum-row sum-total"><span class="sum-key">الإجمالي</span><span class="sum-val" id="total-val">750 ج</span></div>
                    </div>
                    <x-button variant="primary" icon="check_circle" style="width:100%;justify-content:center">
                        تأكيد التوزيع
                    </x-button>
                </div>
            </x-card>

            <!-- Today's summary per company -->
            <x-card title="التوزيعات السابقة">
                <div style="padding:12px 16px">
                @forelse($companiesWithDistributions as $item)
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0@if(!$loop->last);border-bottom:0.5px solid #e8e8e3@endif">
                        <x-avatar :initial="$item['company']->name[0]" variant="green" size="small" />
                        <div style="flex:1">
                            <div style="font-size:13px;font-weight:500">{{ $item['company']->name }}</div>
                            <div style="font-size:11px;color:#707a6c">{{ $item['workers_count'] }} عامل</div>
                        </div>
                        <x-badge variant="green">{{ number_format($item['total_wage'], 0) }} ج</x-badge>
                    </div>
                @empty
                    <div style="text-align:center;color:#707a6c;padding:20px">لا توجد شركات نشطة</div>
                @endforelse
            </div>
            </x-card>
        </div>

    </div>
</div>
