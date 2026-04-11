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
            // احصل على 15-20 عامل عشوائي لإنشاء سلف
            $workers = Worker::where('contractor_id', $contractor->id)
                ->inRandomOrder()
                ->limit(rand(15, 20))
                ->get();

            foreach ($workers as $worker) {
                // إنشاء 2-4 سلفات متنوعة لكل عامل
                for ($i = 0; $i < rand(2, 4); $i++) {
                    $daysAgo = rand(0, 60); // past 2 months
                    $date = Carbon::now()->subDays($daysAgo);
                    $amount = rand(300, 2000); // varied amounts
                    $recoveryMethod = collect(['immediately', 'installments', 'manually'])->random();
                    
                    // Create different scenarios: fully collected, partially collected, pending
                    $scenario = rand(1, 10);
                    if ($scenario <= 4) {
                        // 40% fully collected
                        $amountCollected = $amount;
                        $isFullyCollected = true;
                        $fullCollectionDate = $date->copy()->addDays(rand(1, 20));
                    } elseif ($scenario <= 7) {
                        // 30% partially collected
                        $amountCollected = rand((int)($amount * 0.3), (int)($amount * 0.7));
                        $isFullyCollected = false;
                        $fullCollectionDate = null;
                    } else {
                        // 30% not collected yet (pending)
                        $amountCollected = 0;
                        $isFullyCollected = false;
                        $fullCollectionDate = null;
                    }
                    
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
                            'حالة طوارئ',
                            'مشاكل مالية مؤقتة',
                            'بدون سبب محدد',
                            null
                        ])->random(),
                        'amount_collected' => $amountCollected,
                        'amount_pending' => $amount - $amountCollected,
                        'is_fully_collected' => $isFullyCollected,
                        'fully_collected_at' => $fullCollectionDate,
                    ]);

                    // إذا كانت طريقة التحصيل بالأقساط، أنشئ جدول السداد
                    if ($recoveryMethod === 'installments') {
                        $installmentCount = rand(2, 6);
                        $period = collect(['weekly', 'biweekly'])->random();
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
                            
                            // تحديد ما إذا كان القسط مدفوع بناءً على المجموع المحصل
                            $isPaid = $amountCollected >= ($j + 1) * $installmentAmount;
                            $amountPaid = $isPaid ? $installmentAmount : ($amountCollected > $j * $installmentAmount ? $amountCollected - $j * $installmentAmount : 0);
                            
                            InstallmentSchedule::create([
                                'advance_id' => $advance->id,
                                'installment_number' => $j + 1,
                                'amount' => $installmentAmount,
                                'due_date' => $dueDate,
                                'amount_paid' => $amountPaid,
                                'is_paid' => $isPaid,
                                'paid_at' => $isPaid ? Carbon::now()->subDays(rand(0, max(1, $daysAgo - 5))) : null,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
