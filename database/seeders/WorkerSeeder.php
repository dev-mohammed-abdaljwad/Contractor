<?php

namespace Database\Seeders;

use App\Models\Worker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WorkerSeeder extends Seeder
{
    private $firstNames = [
        'أحمد', 'علي', 'محمد', 'سعيد', 'خالد', 'عمر', 'إبراهيم',
        'حسن', 'حسين', 'ياسر', 'رشيد', 'نبيل', 'جمال', 'كمال',
        'وائل', 'طارق', 'أنور', 'فايز', 'عماد', 'ممدوح',
        'فاطمة', 'عائشة', 'سارة', 'مريم', 'هند', 'ليلى',
        'نهى', 'سماح', 'إيمان', 'دعاء', 'هناء', 'رانيا',
        'ياسمين', 'نجوى', 'أسماء', 'غادة', 'ندى', 'شذا',
    ];

    private $lastNames = [
        'محمد', 'أحمد', 'علي', 'حسن', 'عبد الله', 'إبراهيم',
        'الشرقاوي', 'النيلي', 'القاهري', 'المصري', 'الدقهلي',
        'السيد', 'الشافعي', 'العطار', 'البحري', 'الساحلي',
    ];

    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            foreach (range(1, 100) as $i) {
                Worker::create([
                    'contractor_id' => $contractor->id,
                    'name' => $this->generateArabicName(),
                    'phone' => '+2010' . str_pad($contractor->id * 10000 + $i, 8, '0', STR_PAD_LEFT),
                    'national_id' => str_pad($contractor->id * 100000 + $i, 14, '0', STR_PAD_LEFT),
                    'joined_date' => Carbon::today()->subDays(rand(1, 365)),
                    'is_active' => rand(0, 1) ? true : false,
                ]);
            }
        }
    }

    private function generateArabicName(): string
    {
        return $this->firstNames[array_rand($this->firstNames)] . ' ' . $this->lastNames[array_rand($this->lastNames)];
    }
}
