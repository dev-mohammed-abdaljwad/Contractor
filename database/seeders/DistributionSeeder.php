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
            $workers = Worker::where('contractor_id', $contractor->id)
                ->where('is_active', true)
                ->get();

            if ($workers->isEmpty()) {
                continue;
            }

            // أنشئ توزيعات لآخر 60 يوم لتوفير بيانات اختبار أفضل
            for ($day = 60; $day >= 1; $day--) {
                $date = Carbon::today()->subDays($day)->toDateString();

                foreach ($companies as $company) {
                    // عشوائياً عين 3-5 عمال لكل شركة في كل يوم
                    $numWorkers = rand(3, min(5, $workers->count()));
                    $assignedWorkers = $workers->random($numWorkers);
                    
                    // تحقق ما إذا كان التوزيع موجوداً بالفعل لهذه الشركة في هذا التاريخ
                    $existingDistribution = DailyDistribution::where('company_id', $company->id)
                        ->where('distribution_date', $date)
                        ->first();

                    if (!$existingDistribution) {
                        // أنشئ توزيع جديد
                        $distribution = DailyDistribution::create([
                            'contractor_id' => $contractor->id,
                            'distribution_date' => $date,
                            'company_id' => $company->id,
                            'total_amount' => $assignedWorkers->count() * $company->daily_wage,
                        ]);

                        // أرفق العمال بالتوزيع
                        $distribution->workers()->attach($assignedWorkers->pluck('id')->toArray());
                    }
                }
            }
        }
    }
}
