<?php

namespace Database\Seeders;

use App\Models\Deduction;
use App\Models\DailyDistribution;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DeductionSeeder extends Seeder
{
    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            // احصل على بعض التوزيعات مع العمال
            $distributions = DailyDistribution::where('contractor_id', $contractor->id)
                ->with('workers', 'company')
                ->limit(30) // زيادة عدد التوزيعات
                ->get();

            foreach ($distributions as $dist) {
                // لكل عامل في التوزيع، أنشئ خصومات عشوائية متنوعة
                foreach ($dist->workers as $worker) {
                    // 50% احتمالية إنشاء خصم
                    if (rand(0, 100) <= 50) {
                        $type = collect(['quarter', 'half', 'full'])->random();
                        $dailyWage = $dist->company->daily_wage;
                        
                        $amount = match($type) {
                            'quarter' => $dailyWage * 0.25,
                            'half' => $dailyWage * 0.5,
                            'full' => $dailyWage,
                        };
                        
                        $reason = collect([
                            'خصم تأديبي - عدم الالتزام بالوقت',
                            'خصم تأديبي - سوء السلوك',
                            'خصم تأديبي - إهمال في العمل',
                            'خصم لرداءة الأداء',
                            'خصم اتفاقي',
                            'خصم تأديبي عام',
                            'خصم مخالفة',
                            'خصم للأضرار',
                        ])->random();

                        Deduction::create([
                            'contractor_id' => $contractor->id,
                            'worker_id' => $worker->id,
                            'distribution_id' => $dist->id,
                            'type' => $type,
                            'amount' => round($amount, 2),
                            'reason' => $reason,
                        ]);
                    }
                }
            }
        }
    }
}
