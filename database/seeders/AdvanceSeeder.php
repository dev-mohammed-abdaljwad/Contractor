<?php

namespace Database\Seeders;

use App\Models\Advance;
use App\Models\InstallmentSchedule;
use App\Models\Worker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdvanceSeeder extends Seeder
{
    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            // Get 3-5 random workers for this contractor
            $workers = Worker::where('contractor_id', $contractor->id)
                ->inRandomOrder()
                ->limit(rand(3, 5))
                ->get();

            foreach ($workers as $worker) {
                // Create 1-3 advances per worker
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $date = Carbon::now()->subDays(rand(0, 30));
                    $amount = rand(200, 1000);
                    $recoveryMethod = collect(['immediately', 'installments', 'manually'])->random();
                    $isFullyCollected = rand(0, 1) === 1;
                    $amountCollected = $isFullyCollected ? $amount : rand(0, (int)($amount * 0.5));
                    
                    $advance = Advance::create([
                        'worker_id' => $worker->id,
                        'contractor_id' => $contractor->id,
                        'amount' => $amount,
                        'date' => $date,
                        'recovery_method' => $recoveryMethod,
                        'reason' => collect([
                            'طلب صريح من العامل',
                            'ظروف صحية طارئة',
                            'مساعدة عائلية',
                            'احتياجات شخصية',
                            'بدون سبب محدد',
                            null
                        ])->random(),
                        'amount_collected' => $amountCollected,
                        'amount_pending' => $amount - $amountCollected,
                        'is_fully_collected' => $isFullyCollected,
                        'fully_collected_at' => $isFullyCollected ? Carbon::now()->subDays(rand(1, 15)) : null,
                    ]);

                    // If recovery method is installments, create schedule
                    if ($recoveryMethod === 'installments') {
                        $installmentCount = rand(2, 4);
                        $period = rand(0, 1) === 0 ? 'weekly' : 'biweekly';
                        $installmentAmount = round($advance->amount / $installmentCount, 2);
                        
                        $advance->update([
                            'installment_period' => $period,
                            'installment_count' => $installmentCount,
                            'installment_amount' => $installmentAmount,
                        ]);
                        
                        for ($j = 0; $j < $installmentCount; $j++) {
                            $dueDate = $advance->date->copy();
                            
                            if ($period === 'weekly') {
                                $dueDate->addWeeks($j);
                            } else {
                                $dueDate->addWeeks($j * 2);
                            }
                            
                            $isPaid = $isFullyCollected || ($j < floor($advance->amount_collected / $installmentAmount));
                            
                            InstallmentSchedule::create([
                                'advance_id' => $advance->id,
                                'installment_number' => $j + 1,
                                'amount' => $installmentAmount,
                                'due_date' => $dueDate,
                                'amount_paid' => $isPaid ? $installmentAmount : 0,
                                'is_paid' => $isPaid,
                                'paid_at' => $isPaid ? Carbon::now()->subDays(rand(0, 20)) : null,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
