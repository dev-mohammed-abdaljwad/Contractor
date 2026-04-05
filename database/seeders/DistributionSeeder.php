<?php

namespace Database\Seeders;

use App\Models\DailyDistribution;
use App\Models\Company;
use App\Models\Worker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DistributionSeeder extends Seeder
{
    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            $companies = Company::where('contractor_id', $contractor->id)->get();
            $workers = Worker::where('contractor_id', $contractor->id)->get();

            // Create distributions for the last 14 days
            for ($day = 14; $day >= 1; $day--) {
                $date = Carbon::today()->subDays($day)->toDateString();

                foreach ($companies as $company) {
                    // Randomly assign 2-3 workers to each company per day
                    for ($i = 0; $i < rand(2, 3); $i++) {
                        $worker = $workers->random();
                        
                        // Check if worker already assigned that day
                        $exists = DailyDistribution::where('worker_id', $worker->id)
                            ->where('distribution_date', $date)
                            ->exists();

                        if (!$exists) {
                            DailyDistribution::create([
                                'contractor_id' => $contractor->id,
                                'distribution_date' => $date,
                                'company_id' => $company->id,
                                'worker_id' => $worker->id,
                                'daily_wage_snapshot' => $company->daily_wage,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
