@extends('layouts.dashboard')

@section('content')
<div style="padding:28px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;gap:16px;flex-wrap:wrap">
        <div>
            <h2 style="font-size:24px;font-weight:700;margin-bottom:4px">إدارة الشركات</h2>
            <p style="font-size:12px;color:#707a6c">الشركات التي تعمل معك</p>
        </div>
        <button onclick="openCompanyModal(false)" class="btn btn-primary">
            <span class="ms ms-fill" style="font-size:18px">add_circle</span> شركة جديدة
        </button>
    </div>

    @if($companies->isEmpty())
        <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:12px;border:0.5px solid #d0d0c8">
            <span class="ms" style="font-size:48px;color:#c0c0b8">business</span>
            <p style="margin-top:16px;font-size:16px;color:#707a6c">لا توجد شركات مسجلة حتى الآن</p>
            <p style="font-size:12px;color:#999;margin-top:8px">ابدأ بإضافة شركة جديدة للبدء</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
            @foreach($companies as $company)
                <div style="background:#fff;border:0.5px solid #d0d0c8;border-radius:12px;padding:16px;transition:all 0.2s;display:flex;flex-direction:column">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px;gap:8px">
                        <div style="flex:1;min-width:0">
                            <h3 style="font-size:15px;font-weight:700;color:#1a1c19;margin-bottom:4px;word-break:break-word">{{ $company->name }}</h3>
                            <p style="font-size:11px;color:#707a6c">{{ $company->payment_cycle ?? 'لا محدد' }}</p>
                        </div>
                        @if($company->is_active)
                            <span style="background:#E1F5EE;color:#0F6E56;padding:4px 10px;border-radius:4px;font-size:11px;font-weight:600;white-space:nowrap">✅ نشطة</span>
                        @else
                            <span style="background:#f1f1ec;color:#5F5E5A;padding:4px 10px;border-radius:4px;font-size:11px;font-weight:600;white-space:nowrap">⏸️ متوقفة</span>
                        @endif
                    </div>

                    <div style="margin-bottom:12px;padding-bottom:12px;border-bottom:0.5px solid #e8e8e3">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div>
                                <div style="font-size:10px;color:#707a6c;margin-bottom:3px">الأجر اليومي</div>
                                <div style="font-size:13px;font-weight:700;color:#0d631b">{{ number_format($company->daily_wage, 0) }} ج</div>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#707a6c;margin-bottom:3px">عدد العمال</div>
                                <div style="font-size:13px;font-weight:700">{{ $company->total_workers }}</div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom:12px;min-height:50px">
                        <div style="font-size:10px;color:#707a6c;margin-bottom:3px">جهة الاتصال</div>
                        <div style="font-size:12px;color:#1a1c19;font-weight:500;word-break:break-word">{{ $company->contact_person }}</div>
                        <div style="font-size:11px;color:#707a6c;direction:ltr;unicode-bidi:plaintext">{{ $company->phone }}</div>
                    </div>

                    @if($company->pending_amount > 0)
                        <div style="background:#FAEEDA;padding:8px 10px;border-radius:6px;margin-bottom:12px;font-size:10px">
                            <div style="color:#BA7517;margin-bottom:2px;font-weight:500">مستحق التحصيل</div>
                            <div style="font-size:13px;font-weight:700;color:#BA7517">{{ number_format($company->pending_amount, 0) }} ج</div>
                        </div>
                    @endif

                    <div style="display:flex;gap:6px;flex:1;flex-direction:column;margin-top:auto">
                        <a href="{{ route('contractor.companies.show', $company) }}" class="btn btn-outline" style="flex:1;justify-content:center;height:32px;padding:0;font-size:12px" title="التفاصيل الكاملة">
                            <span class="ms" style="font-size:14px">visibility</span> التفاصيل
                        </a>
                        <button onclick="openCompanyModal(true, {{ $company->id }})" class="btn btn-outline" style="flex:1;justify-content:center;height:32px;padding:0;font-size:12px" title="تعديل البيانات">
                            <span class="ms" style="font-size:14px">edit</span> تعديل
                        </button>
                        <form method="POST" action="{{ route('contractor.companies.destroy', $company) }}" id="delete-form-{{ $company->id }}" style="flex:1" onsubmit="openDeleteModal(this, '{{ $company->name }}'); return false;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;height:32px;padding:0;font-size:12px" title="حذف نهائي">
                                <span class="ms" style="font-size:14px">delete</span> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Include Modals -->
@include('components.company-form-modal')
@include('components.delete-confirmation-modal')

<style>
    @media(max-width: 768px) {
        [style*="padding:28px"] {
            padding: 16px !important;
        }
        
        [style*="grid-template-columns:repeat(auto-fill,minmax(280px,1fr)"] {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
        }
    }

    @media(max-width: 480px) {
        h2[style*="font-size:24px"] {
            font-size: 18px !important;
        }
        
        [style*="grid-template-columns:repeat(auto-fill,minmax(280px,1fr)"] {
            grid-template-columns: 1fr !important;
        }

        .btn[style*="flex:1"] {
            font-size: 11px !important;
        }

        .btn .ms {
            display: none;
        }
    }
</style>
@endsection
