<?php

namespace Database\Seeders;

use App\Models\Deduction;
use App\Models\DailyDistribution;
use App\Models\Worker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DeductionSeeder extends Seeder
{
    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            // احصل على جميع التوزيعات
            $distributions = DailyDistribution::where('contractor_id', $contractor->id)
                ->with('workers', 'company')
                ->get();

            // 30% من العمال سيكون عندهم خصومات
            $workers = Worker::where('contractor_id', $contractor->id)->get();
            $workersWithDeductions = $workers->random((int)(count($workers) * 0.3));
            $workerDeductionChance = $workersWithDeductions->pluck('id')->toArray();

            foreach ($distributions as $dist) {
                foreach ($dist->workers as $worker) {
                    // فقط العمال المحددين قد يكون لديهم خصومات
                    if (in_array($worker->id, $workerDeductionChance)) {
                        // 15% احتمالية خصم لهذا العامل في هذا اليوم
                        if (rand(1, 100) <= 15) {
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
                                'خصم للغياب',
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
}
