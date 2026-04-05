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
            // Get some distributions
            $distributions = DailyDistribution::where('contractor_id', $contractor->id)
                ->limit(10)
                ->get();

            foreach ($distributions as $dist) {
                // Randomly create deductions
                if (rand(0, 1)) {
                    $type = ['quarter', 'half'][rand(0, 1)];
                    $amount = match($type) {
                        'quarter' => $dist->daily_wage_snapshot * 0.25,
                        'half' => $dist->daily_wage_snapshot * 0.5,
                    };

                    Deduction::create([
                        'contractor_id' => $contractor->id,
                        'worker_id' => $dist->worker_id,
                        'company_id' => $dist->company_id,
                        'deduction_date' => $dist->distribution_date,
                        'type' => $type,
                        'amount' => $amount,
                        'reason' => 'خصم تأديبي',
                    ]);
                }
            }
        }
    }
}
