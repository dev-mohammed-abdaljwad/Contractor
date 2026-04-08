<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    private $companyNames = [
        'شركة الزراعة المتقدمة',
        'مزرعة الأرز الذهبي',
        'مشروع الخضروات الطازجة',
        'شركة الفواكه المختارة',
        'مزرعة الدواجن العملاقة',
        'مشروع الألبان النقي',
        'شركة الحبوب والأعلاف',
        'مزرعة النحل والعسل',
        'مشروع الزهور الملونة',
        'شركة المحاصيل الموسمية',
        'مزرعة الماشية الحديثة',
        'مشروع الدفيئات الزراعية',
        'شركة الثروة السمكية',
        'مزرعة العنب الفاخر',
        'مشروع الحدائق الحضرية',
        'شركة المحاصيل العضوية',
        'مزرعة الدراجن المحسنة',
        'مشروع الأسمدة الطبيعية',
        'شركة المعدات الزراعية',
        'مزرعة الكفاءة والإنتاجية',
    ];

    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            foreach (range(0, 19) as $index) {
                Company::create([
                    'contractor_id' => $contractor->id,
                    'name' => $this->companyNames[$index],
                    'contact_person' => $this->generateArabicName(),
                    'phone' => '+20100' . str_pad($contractor->id * 1000 + $index, 7, '0', STR_PAD_LEFT),
                    'daily_wage' => rand(250, 500),
                    'payment_cycle' => collect(['daily', 'weekly', 'bimonthly'])->random(),
                    'weekly_pay_day' => ['Monday', 'Wednesday', 'Friday'][array_rand(['Monday', 'Wednesday', 'Friday'])],
                    'contract_start_date' => Carbon::today()->subMonths(rand(1, 12)),
                    'notes' => 'عقد عمل مع شركة متعاونة',
                    'is_active' => true,
                ]);
            }
        }
    }

    private function generateArabicName(): string
    {
        $firstNames = [
            'أحمد', 'علي', 'محمد', 'سعيد', 'خالد', 'عمر', 'إبراهيم',
            'حسن', 'حسين', 'ياسر', 'رشيد', 'نبيل', 'جمال', 'كمال',
        ];

        $lastNames = [
            'الشركة', 'للزراعة', 'للإنتاج', 'للتوزيع', 'للخدمات',
            'المحترف', 'الخبير', 'الميزان', 'النور', 'الروضة',
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}
