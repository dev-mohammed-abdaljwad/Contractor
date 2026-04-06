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
                    $numWorkers = rand(2, min(3, $workers->count()));
                    $assignedWorkers = $workers->random($numWorkers);
                    
                    // Check if distribution already exists for this company on this date
                    $existingDistribution = DailyDistribution::where('company_id', $company->id)
                        ->where('distribution_date', $date)
                        ->first();

                    if (!$existingDistribution) {
                        // Create new distribution
                        $distribution = DailyDistribution::create([
                            'contractor_id' => $contractor->id,
                            'distribution_date' => $date,
                            'company_id' => $company->id,
                            'total_amount' => $assignedWorkers->count() * $company->daily_wage,
                        ]);

                        // Attach workers to the distribution
                        $distribution->workers()->attach($assignedWorkers->pluck('id')->toArray());
                    }
                }
            }
        }
    }
}
