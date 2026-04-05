<div class="page fade-in" id="page-collection">
    <!-- Stats row -->
    @php
        $totalPending = $pendingCollections->sum('net_amount');
        $highestCollection = $pendingCollections->max('net_amount');
        $highestCompanyName = $pendingCollections->where('net_amount', $highestCollection)->first()?->company->name ?? 'لا يوجد';
        $collectedThisMonth = \App\Models\Collection::where('contractor_id', auth()->id())->where('is_paid', true)->whereMonth('payment_date', now())->sum('net_amount');
        $lateCount = $pendingCollections->count();
    @endphp
    <div class="stat-grid" style="grid-template-columns:repeat(4,minmax(0,1fr))">
        <x-stat-card label="إجمالي مستحق" value="{{ number_format($totalPending, 0) }} ج" variant="amber" />
        <x-stat-card label="أعلى مستحق" value="{{ number_format($highestCollection, 0) }} ج" subtext="{{ $highestCompanyName }}" />
        <x-stat-card label="محصّل هذا الشهر" value="{{ number_format($collectedThisMonth, 0) }} ج" variant="green" />
        <x-stat-card label="شركات متأخرة" value="{{ $lateCount }}" />
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start">

        <!-- Companies list -->
        <div>
            <x-card title="مستحق من الشركات">
                <div style="padding:14px 20px;border-bottom:0.5px solid #d0d0c8;display:flex;justify-content:space-between">
                    <div style="display:flex;gap:6px">
                        <x-badge variant="amber">مستحق</x-badge>
                        <x-badge variant="gray">تم التحصيل</x-badge>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>الشركة</th>
                            <th>دورة الدفع</th>
                            <th>الفترة</th>
                            <th>المستحق</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingCollections as $collection)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <x-avatar :initial="$collection->company->name[0]" variant="green" size="small" />
                                        <div>
                                            <div style="font-weight:600">{{ $collection->company->name }}</div>
                                            <div style="font-size:11px;color:#707a6c">دفع {{ $collection->company->payment_cycle }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><x-badge variant="gray">{{ $collection->company->payment_cycle }}</x-badge></td>
                                <td style="font-size:12px;color:#707a6c">{{ $collection->period_start->format('d/m') }}–{{ $collection->period_end->format('d/m') }}</td>
                                <td><x-badge variant="amber" style="font-size:13px;padding:5px 12px">{{ number_format($collection->net_amount, 0) }} ج</x-badge></td>
                                @if(\Carbon\Carbon::parse($collection->period_end)->addDays(7)->isPast())
                                    <td><x-badge variant="red">متأخر {{ \Carbon\Carbon::parse($collection->period_end)->diffInDays(now()) }} أيام</x-badge></td>
                                @else
                                    <td><x-badge variant="blue">{{ \Carbon\Carbon::parse($collection->period_end)->diffInDays(now()) }} أيام</x-badge></td>
                                @endif
                                <td><x-button variant="primary" size="small" onclick="openPayModal('{{ $collection->company->name }}','{{ $collection->net_amount }}')">تسجيل دفعة</x-button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#707a6c;padding:20px">لا توجد مستحقات معلقة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>

        <!-- Payment form -->
        <div id="pay-panel">
            <x-card title="تسجيل دفعة" id="pay-title">
                <div style="padding:16px">
                    <div class="form-group">
                        <label class="form-label">الشركة</label>
                        <input class="form-input" id="pay-company" value="شركة المغربي" readonly style="background:#f0f9f0;color:#0d631b;font-weight:700"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">المبلغ المستلم</label>
                        <input class="form-input" id="pay-amount" value="2,100 ج"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">تاريخ الاستلام</label>
                        <input type="date" class="form-input" value="2025-04-06"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">طريقة الدفع</label>
                        <div style="display:flex;gap:8px;flex-wrap:wrap">
                            <button class="disc-btn sel" onclick="selPay(this)">كاش</button>
                            <button class="disc-btn" onclick="selPay(this)">تحويل</button>
                            <button class="disc-btn" onclick="selPay(this)">شيك</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ملاحظات (اختياري)</label>
                        <input class="form-input" placeholder="مثال: نقص 200 ج باقي أسبوع قادم..."/>
                    </div>
                    <div class="sum-box" style="margin-bottom:16px">
                        <div class="sum-row"><span class="sum-key">المستحق قبل</span><span class="sum-val" id="pay-before">2,100 ج</span></div>
                        <div class="sum-row"><span class="sum-key">الدفعة</span><span class="sum-val" style="color:#ba1a1a">- 2,100 ج</span></div>
                        <div class="sum-row sum-total"><span class="sum-key">المتبقي بعد الدفعة</span><span class="sum-val">0 ج</span></div>
                    </div>
                    <x-button variant="primary" icon="check_circle" style="width:100%;justify-content:center">
                        تأكيد الاستلام
                    </x-button>
                </div>
            </x-card>
        </div>

    </div>
</div>
