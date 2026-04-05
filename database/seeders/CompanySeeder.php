<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            Company::create([
                'contractor_id' => $contractor->id,
                'name' => "شركة الزراعة {$contractor->id}",
                'contact_person' => 'أحمد الشركة',
                'phone' => '+201005555555',
                'daily_wage' => 350.00,
                'payment_cycle' => 'weekly',
                'weekly_pay_day' => 'Friday',
                'contract_start_date' => Carbon::today()->subMonths(3),
                'notes' => 'عقد مع شركة زراعية',
                'is_active' => true,
            ]);

            Company::create([
                'contractor_id' => $contractor->id,
                'name' => "مزرعة الأرز {$contractor->id}",
                'contact_person' => 'محمود المزرعة',
                'phone' => '+201006666666',
                'daily_wage' => 400.00,
                'payment_cycle' => 'bimonthly',
                'weekly_pay_day' => null,
                'contract_start_date' => Carbon::today()->subMonths(2),
                'notes' => 'مزرعة أرز كبيرة',
                'is_active' => true,
            ]);

            Company::create([
                'contractor_id' => $contractor->id,
                'name' => "مشروع الخضروات {$contractor->id}",
                'contact_person' => 'فاطمة المشروع',
                'phone' => '+201007777777',
                'daily_wage' => 300.00,
                'payment_cycle' => 'daily',
                'weekly_pay_day' => null,
                'contract_start_date' => Carbon::today()->subMonths(1),
                'notes' => 'مشروع خضروات صغير',
                'is_active' => true,
            ]);
        }
    }
}
