@extends('layouts.dashboard')

@section('content')
<div style="padding:28px">
    <a href="{{ route('contractor.companies.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:#185FA5;margin-bottom:20px;text-decoration:none;font-size:13px">
        <span class="ms">arrow_forward</span> عودة للشركات
    </a>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;margin-top:20px">
        <!-- Main Content -->
        <div>
            <!-- Company Info Card -->
            <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:24px;margin-bottom:20px">
                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:24px;gap:12px;flex-wrap:wrap">
                    <div style="flex:1;min-width:200px">
                        <h2 style="font-size:20px;font-weight:700;color:#1a1c19;margin-bottom:4px;word-break:break-word">{{ $company->name }}</h2>
                        <p style="font-size:12px;color:#707a6c">{{ $company->payment_cycle === 'daily' ? 'دفع كل يوم' : ($company->payment_cycle === 'weekly' ? 'دفع كل أسبوع' : ($company->payment_cycle === 'bimonthly' ? 'دفع كل نصف شهر' : 'دفع كل شهر')) }}</p>
                    </div>
                    <button onclick="openCompanyModal(true, {{ $company->id }})" class="btn btn-primary" style="height:40px;white-space:nowrap;cursor:pointer">
                        <span class="ms" style="font-size:16px">edit</span> تعديل
                    </button>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;padding-bottom:24px;border-bottom:0.5px solid #e8e8e3">
                    <div>
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:500">المسؤول</div>
                        <div style="font-size:13px;font-weight:600;color:#1a1c19;word-break:break-word">{{ $company->contact_person }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:500">الجوال</div>
                        <div style="font-size:13px;font-weight:600;color:#1a1c19;direction:ltr;unicode-bidi:plaintext">{{ $company->phone }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:500">أجر العامل اليومي</div>
                        <div style="font-size:13px;font-weight:700;color:#0d631b">{{ number_format($company->daily_wage, 0) }} ج</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;padding-top:24px">
                    <div>
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:500">بدأنا معهم</div>
                        <div style="font-size:13px;font-weight:600">{{ $company->contract_start_date->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:500">يومهم المفضل</div>
                        <div style="font-size:13px;font-weight:600">{{ $company->weekly_pay_day ?? 'أي يوم' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:500">الحالة الحالية</div>
                        <div style="font-size:13px;font-weight:600;color: {{ $company->is_active ? '#0d631b' : '#5F5E5A' }}">
                            {{ $company->is_active ? '✅ نشطة' : '⏸️ متوقفة' }}
                        </div>
                    </div>
                </div>

                @if($company->notes)
                    <div style="margin-top:24px;padding:16px;background:#fafaf8;border-radius:8px;border:0.5px solid #e8e8e3">
                        <div style="font-size:11px;color:#707a6c;margin-bottom:8px;font-weight:600">ملاحظاتك:</div>
                        <p style="font-size:13px;color:#1a1c19;line-height:1.5">{{ $company->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Recent Distributions -->
            <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:24px">
                <h3 style="font-size:15px;font-weight:700;margin-bottom:16px">التوزيعات الأخيرة</h3>
                
                @if($company->distributions && $company->distributions->count() > 0)
                    <div style="overflow-x:auto">
                        <table style="width:100%;border-collapse:collapse;font-size:13px">
                            <thead>
                                <tr style="border-bottom:0.5px solid #e8e8e3">
                                    <th style="padding:12px;text-align:right;font-size:11px;font-weight:600;color:#707a6c">التاريخ</th>
                                    <th style="padding:12px;text-align:right;font-size:11px;font-weight:600;color:#707a6c">العامل</th>
                                    <th style="padding:12px;text-align:right;font-size:11px;font-weight:600;color:#707a6c">الأجر</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company->distributions->take(10) as $dist)
                                    <tr style="border-bottom:0.5px solid #f1f1ec">
                                        <td style="padding:12px">{{ \Carbon\Carbon::parse($dist->distribution_date)->format('d/m/Y') }}</td>
                                        <td style="padding:12px;word-break:break-word">{{ $dist->worker->name ?? 'حذف' }}</td>
                                        <td style="padding:12px;color:#0d631b;font-weight:600">{{ number_format($dist->daily_wage_snapshot, 0) }} ج</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align:center;padding:20px;color:#707a6c;font-size:13px">ما فيش توزيعات بعد</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div>
            <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:20px;position:sticky;top:20px">
                <h3 style="font-size:13px;font-weight:700;margin-bottom:16px">الإحصائيات</h3>
                
                <div style="display:flex;flex-direction:column;gap:12px">
                    <div style="padding:12px;background:#f5f9f8;border-radius:8px;border:0.5px solid #e8f0ee">
                        <div style="font-size:10px;color:#0d631b;margin-bottom:4px;font-weight:600">عمالنا</div>
                        <div style="font-size:16px;font-weight:700;color:#0d631b">{{ $company->total_workers ?? 0 }}</div>
                    </div>

                    <div style="padding:12px;background:#f5f8ff;border-radius:8px;border:0.5px solid #e8eef5">
                        <div style="font-size:10px;color:#185FA5;margin-bottom:4px;font-weight:600">عدد الشغلات</div>
                        <div style="font-size:16px;font-weight:700;color:#185FA5">{{ $company->distributions?->count() ?? 0 }}</div>
                    </div>

                    <div style="padding:12px;background:#fffaf5;border-radius:8px;border:0.5px solid #f5eee8">
                        <div style="font-size:10px;color:#BA7517;margin-bottom:4px;font-weight:600">الراتب المستحق</div>
                        <div style="font-size:16px;font-weight:700;color:#BA7517">{{ number_format($company->pending_amount ?? 0, 0) }} ج</div>
                    </div>

                    <form method="POST" action="{{ route('contractor.companies.destroy', $company) }}" id="delete-form-show" onsubmit="openDeleteModal(this, '{{ $company->name }}'); return false;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;margin-top:8px;background:#fff5f5;border:0.5px solid #f0e0e0;border-radius:8px;color:#ba1a1a;text-decoration:none;font-size:12px;font-weight:500;cursor:pointer">
                            <span class="ms" style="font-size:14px">delete</span> حذف نهائي
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ============ BASE STYLES ============ */
    [style*="padding:28px"] {
        padding: 28px;
    }

    /* ============ DESKTOP (769px+) ============ */
    @media(min-width: 769px) {
        [style*="display:grid;grid-template-columns:1fr 320px"] {
            grid-template-columns: 1fr 320px;
            gap: 20px;
        }

        [style*="position:sticky;top:20px"] {
            position: sticky;
            top: 20px;
        }

        h2[style*="font-size:20px"] {
            font-size: 20px;
        }

        [style*="grid-template-columns:repeat(auto-fit,minmax(150px,1fr)"] {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)) !important;
        }

        div[style*="padding:24px"] {
            padding: 24px;
            margin-bottom: 20px;
        }

        div[style*="padding:20px"][style*="position"] {
            padding: 20px;
        }
    }

    /* ============ TABLET & MOBILE (max-width: 768px) ============ */
    @media(max-width: 768px) {
        [style*="display:grid;grid-template-columns:1fr 320px"] {
            grid-template-columns: 1fr !important;
        }

        [style*="position:sticky;top:20px"] {
            position: relative !important;
            top: auto !important;
            margin-top: 16px;
        }

        [style*="padding:28px"] {
            padding: 16px !important;
            padding-bottom: 100px !important;
        }

        /* Back link */
        a[style*="font-size:13px"] {
            font-size: 12px !important;
            margin-bottom: 16px !important;
        }

        /* Headers */
        h2[style*="font-size:20px"] {
            font-size: 18px !important;
            margin-bottom: 6px !important;
        }

        h3[style*="font-size:15px"] {
            font-size: 14px !important;
            margin-bottom: 12px !important;
        }

        /* Grid layouts */
        [style*="grid-template-columns:repeat(auto-fit,minmax(150px,1fr)"] {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }

        div[style*="display:grid;grid-template-columns:repeat"] {
            gap: 12px !important;
        }

        /* Card padding */
        div[style*="padding:24px"] {
            padding: 16px !important;
            margin-bottom: 16px !important;
        }

        div[style*="padding:20px"] {
            padding: 14px !important;
        }

        /* Header flex - button below on tablet */
        div[style*="display:flex;justify-content:space-between;align-items:start;margin-bottom:24px"] {
            flex-direction: column;
            gap: 12px !important;
        }

        /* Edit button - responsive sizing */
        button.btn-primary[style*="height:40px"] {
            width: 100%;
            padding: 0 14px !important;
            font-size: 13px !important;
        }

        /* Flex div for company name */
        div[style*="flex:1;min-width:200px"] {
            width: 100%;
        }

        /* Table styling */
        div[style*="overflow-x:auto"] {
            overflow-x: auto;
            margin: 0 -16px;
            padding: 0 16px;
        }

        table {
            font-size: 12px !important;
        }

        table th,
        table td {
            padding: 10px !important;
            whitespace: nowrap;
        }

        /* Gaps and margins */
        [style*="gap:20px"] {
            gap: 12px !important;
        }

        [style*="gap:16px"] {
            gap: 12px !important;
        }

        [style*="margin-bottom:24px"] {
            margin-bottom: 12px !important;
        }

        [style*="margin-bottom:20px"] {
            margin-bottom: 12px !important;
        }

        [style*="padding-bottom:24px"] {
            padding-bottom: 16px !important;
        }

        [style*="padding-top:24px"] {
            padding-top: 16px !important;
        }

        /* Notes box */
        div[style*="margin-top:24px;padding:16px"] {
            margin-top: 16px !important;
            padding: 12px !important;
            font-size: 12px !important;
        }

        /* Stats sidebar */
        div[style*="display:flex;flex-direction:column;gap:12px"] {
            gap: 10px !important;
        }

        div[style*="padding:12px;background"] {
            padding: 12px !important;
            border-radius: 8px;
            font-size: 11px !important;
        }

        /* Delete button */
        button[type="submit"][style*="width:100%"] {
            width: 100% !important;
            padding: 10px !important;
            font-size: 12px !important;
        }

        button[type="submit"] .ms {
            font-size: 12px !important;
        }

        /* Icon sizing */
        a .ms {
            font-size: 16px !important;
        }

        button .ms {
            font-size: 14px !important;
        }

        /* Text sizing */
        p[style*="font-size:12px"] {
            font-size: 11px !important;
        }

        div[style*="font-size:13px"] {
            font-size: 12px !important;
        }

        div[style*="font-size:11px"] {
            font-size: 10px !important;
        }

        /* Borders and spacing */
        [style*="border-bottom:0.5px"] {
            border-bottom-color: #e8e8e3;
        }
    }

    /* ============ SMALL MOBILE (max-width: 480px) ============ */
    @media(max-width: 480px) {
        [style*="padding:28px"] {
            padding: 12px !important;
            padding-bottom: 90px !important;
        }

        /* Back link */
        a[style*="font-size:13px"] {
            font-size: 11px !important;
            gap: 6px !important;
            margin-bottom: 12px !important;
        }

        a .ms {
            font-size: 14px !important;
        }

        /* Headers */
        h2[style*="font-size:20px"] {
            font-size: 15px !important;
            margin-bottom: 4px !important;
        }

        h3[style*="font-size:15px"] {
            font-size: 13px !important;
            margin-bottom: 10px !important;
        }

        p[style*="font-size:12px"] {
            font-size: 10px !important;
            line-height: 1.4;
        }

        /* Company info grid - single column */
        [style*="grid-template-columns:repeat(auto-fit,minmax(150px,1fr)"] {
            grid-template-columns: 1fr !important;
            gap: 10px !important;
        }

        div[style*="display:grid;grid-template-columns:repeat"] {
            gap: 10px !important;
        }

        /* Card padding */
        div[style*="padding:24px"] {
            padding: 12px !important;
            margin-bottom: 12px !important;
        }

        div[style*="padding:20px"] {
            padding: 12px !important;
        }

        /* Header flex section */
        div[style*="display:flex;justify-content:space-between;align-items:start;margin-bottom:24px"] {
            flex-direction: column;
            gap: 10px !important;
        }

        /* Edit button - full width, touch friendly */
        button.btn-primary[style*="height:40px"] {
            width: 100% !important;
            height: 42px !important;
            padding: 0 12px !important;
            font-size: 12px !important;
            border-radius: 8px;
        }

        button .ms {
            font-size: 13px !important;
        }

        /* Company name area */
        div[style*="flex:1;min-width:200px"] {
            width: 100%;
            flex: none;
        }

        /* Text sizes */
        div[style*="font-size:13px"] {
            font-size: 11px !important;
        }

        div[style*="font-size:11px"] {
            font-size: 9px !important;
        }

        /* Table */
        div[style*="overflow-x:auto"] {
            -webkit-overflow-scrolling: touch;
            overflow-x: auto;
            margin: 0 -12px;
            padding: 0 12px;
        }

        table {
            font-size: 10px !important;
            min-width: 100%;
        }

        table th,
        table td {
            padding: 6px !important;
            white-space: nowrap;
        }

        /* Notes */
        div[style*="margin-top:24px;padding:16px"] {
            margin-top: 12px !important;
            padding: 10px !important;
            border-radius: 6px;
            font-size: 10px !important;
        }

        /* Stats */
        div[style*="padding:12px;background"] {
            padding: 10px !important;
            border-radius: 6px;
            font-size: 10px !important;
        }

        div[style*="display:flex;flex-direction:column;gap:12px"] {
            gap: 8px !important;
        }

        /* Delete button */
        button[type="submit"][style*="width:100%"] {
            width: 100% !important;
            padding: 10px !important;
            font-size: 11px !important;
            height: auto !important;
            border-radius: 6px;
        }

        /* Gaps and margins */
        [style*="gap:20px"] {
            gap: 10px !important;
        }

        [style*="gap:16px"] {
            gap: 10px !important;
        }

        [style*="gap:12px"] {
            gap: 8px !important;
        }

        [style*="margin-bottom:24px"] {
            margin-bottom: 10px !important;
        }

        [style*="margin-bottom:20px"] {
            margin-bottom: 10px !important;
        }

        [style*="padding-bottom:24px"] {
            padding-bottom: 12px !important;
        }

        [style*="padding-bottom:20px"] {
            padding-bottom: 12px !important;
        }

        [style*="padding-top:24px"] {
            padding-top: 12px !important;
        }

        [style*="margin-bottom:8px"] {
            margin-bottom: 4px !important;
        }

        [style*="margin-bottom:4px"] {
            margin-bottom: 2px !important;
        }
    }

    /* ============ EXTRA SMALL (max-width: 360px) ============ */
    @media(max-width: 360px) {
        [style*="padding:28px"] {
            padding: 10px !important;
            padding-bottom: 85px !important;
        }

        button.btn-primary[style*="height:40px"] {
            width: 100% !important;
            height: 38px !important;
            font-size: 11px !important;
        }

        div[style*="padding:24px"] {
            padding: 10px !important;
            margin-bottom: 10px !important;
        }

        table th,
        table td {
            padding: 5px !important;
            font-size: 9px !important;
        }
    }
</style>

<!-- Include Company Form Modal -->
@include('components.company-form-modal')

<!-- Include Delete Confirmation Modal -->
@include('components.delete-confirmation-modal')

@endsection
