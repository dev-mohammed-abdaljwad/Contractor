<div class="page active fade-in" id="page-dashboard">
    <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap">
        <div style="flex:1;min-width:200px">
            <h2 style="font-size:20px;font-weight:700;color:#1a1c19">مرحباً!</h2>
            <p style="font-size:12px;color:#707a6c;margin-top:4px">هنا تديك توزيع العمال والرواتب</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="{{ route('contractor.companies.create') }}" class="btn btn-primary" style="height:40px;padding:0 16px;white-space:nowrap">
                <span class="ms ms-fill" style="font-size:16px">business</span> شركة جديدة
            </a>
            <a href="distribution" onclick="showPage('distribution'); return false;" class="btn btn-primary" style="height:40px;padding:0 16px;white-space:nowrap">
                <span class="ms ms-fill" style="font-size:16px">group_add</span> توزيع عمال
            </a>
        </div>
    </div>

    <div class="stat-grid">
        <x-stat-card label="إجمالي العمال" value="{{ $totalWorkersCount }}" subtext="عامل مسجل عندك" />
        <x-stat-card label="موزعين النهاردة" value="{{ $workersDistributedToday }}" variant="green" subtext="من {{ $totalWorkersCount }} عامل" />
        <x-stat-card label="إجمالي الأجر" value="{{ number_format($totalWagesToday, 0) }} ج" variant="amber" subtext="من {{ $activeCompaniesCount }} شركات" />
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <!-- Companies today -->
        <x-card title="الشركات اليومية" action="+ توزيع" actionUrl="distribution">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الشركة</th>
                        <th>العمال</th>
                        <th>الأجر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companiesWithDistributions as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <x-avatar :initial="$item['company']->name[0]" variant="green" size="small" />
                                    <span>{{ $item['company']->name }}</span>
                                </div>
                            </td>
                            <td>{{ $item['workers_count'] }}</td>
                            <td>{{ number_format($item['company']->daily_wage, 0) }} ج</td>
                            <td><x-badge variant="green">{{ number_format($item['total_wage'], 0) }} ج</x-badge></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:#707a6c;padding:20px">ما فيش توزيعات النهاردة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

        <!-- Pending collections -->
        <x-card title="الأموال المستحقة" action="عرض الكل" actionUrl="collection">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الشركة</th>
                        <th>الفترة</th>
                        <th>المستحق</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingCollections->take(3) as $collection)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <x-avatar :initial="$collection->company->name[0]" variant="green" size="small" />
                                    <span>{{ $collection->company->name }}</span>
                                </div>
                            </td>
                            <td><x-badge variant="gray">{{ $collection->company->payment_cycle }}</x-badge></td>
                            <td><x-badge variant="amber">{{ number_format($collection->net_amount, 0) }} ج</x-badge></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:#707a6c;padding:20px">ما فيش مستحقات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
