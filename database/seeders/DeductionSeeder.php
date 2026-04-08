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
            // Get some distributions with workers
            $distributions = DailyDistribution::where('contractor_id', $contractor->id)
                ->with('workers', 'company')
                ->limit(10)
                ->get();

            foreach ($distributions as $dist) {
                // For each worker in the distribution, randomly create deductions
                foreach ($dist->workers as $worker) {
                    if (rand(0, 1)) {
                        $type = ['quarter', 'half'][rand(0, 1)];
                        $dailyWage = $dist->company->daily_wage;
                        $amount = match($type) {
                            'quarter' => $dailyWage * 0.25,
                            'half' => $dailyWage * 0.5,
                        };

                        Deduction::create([
                            'contractor_id' => $contractor->id,
                            'worker_id' => $worker->id,
                            'distribution_id' => $dist->id,
                            'type' => $type,
                            'amount' => $amount,
                            'reason' => 'خصم تأديبي',
                        ]);
                    }
                }
            }
        }
    }
}
