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

            if ($workers->isEmpty() || $companies->isEmpty()) {
                continue;
            }

            // أنشئ توزيعات لآخر 5 أشهر (150 يوم) لكل عامل
            $startDate = Carbon::today()->subMonths(5);
            $endDate = Carbon::today();
            
            // إنشاء سجل حضور منتظم: 4 أيام من 5 (80% حضور)
            $workersDistributions = [];
            foreach ($workers as $worker) {
                $workersDistributions[$worker->id] = [];
                
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $dayOfWeek = $currentDate->dayOfWeek; // 0=الأحد, 5=الجمعة, 6=السبت
                    
                    // تخطي نهاية الأسبوع
                    if (!in_array($dayOfWeek, [5, 6])) {
                        // 80% احتمالية الحضور
                        if (rand(1, 100) <= 80) {
                            $workersDistributions[$worker->id][] = $currentDate->toDateString();
                        }
                    }
                    $currentDate->addDay();
                }
            }

            // أنشئ التوزيعات بناءً على الحضور
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $date = $currentDate->toDateString();
                
                foreach ($companies as $company) {
                    // احصل على العمال المتاحين
                    $workersForDay = [];
                    foreach ($workers as $worker) {
                        if (in_array($date, $workersDistributions[$worker->id])) {
                            $workersForDay[] = $worker;
                        }
                    }
                    
                    if (count($workersForDay) > 0) {
                        $numWorkers = max(1, (int)(count($workersForDay) * rand(60, 100) / 100));
                        $assignedWorkers = collect($workersForDay)->random($numWorkers);
                        
                        $existingDistribution = DailyDistribution::where('company_id', $company->id)
                            ->where('distribution_date', $date)
                            ->first();

                        if (!$existingDistribution) {
                            $distribution = DailyDistribution::create([
                                'contractor_id' => $contractor->id,
                                'distribution_date' => $date,
                                'company_id' => $company->id,
                                'total_amount' => $assignedWorkers->count() * $company->daily_wage,
                            ]);

                            $distribution->workers()->attach($assignedWorkers->pluck('id')->toArray());
                        }
                    }
                }
                
                $currentDate->addDay();
            }
        }
    }
}
