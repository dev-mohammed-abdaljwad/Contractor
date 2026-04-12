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
            $workers = Worker::where('contractor_id', $contractor->id)
                ->where('is_active', true)
                ->get();
                
            // 50-70% من الموظفين سيكون لديهم سلف
            $workersWithAdvances = $workers->random((int)(count($workers) * rand(50, 70) / 100));

            foreach ($workersWithAdvances as $worker) {
                // إنشاء 1-4 سلفات لكل عامل على مدى 5 أشهر
                $advanceCount = rand(1, 4);
                
                for ($i = 0; $i < $advanceCount; $i++) {
                    // توزيع السلف على 5 أشهر
                    $daysAgo = rand(0, 150);
                    $advanceDate = Carbon::today()->subDays($daysAgo);
                    
                    // تنويع مبالغ السلف
                    $amount = collect([300, 400, 500, 600, 700, 800, 900, 1000, 1200, 1500, 2000, 2500])->random();
                    $recoveryMethod = collect(['immediately', 'installments', 'manually'])->random();
                    
                    // السلف الأقدم عادة تكون محصلة
                    $timeFactor = (150 - $daysAgo) / 150;
                    $scenario = rand(1, 100);
                    
                    if ($scenario <= (40 + $timeFactor * 30)) {
                        // محصلة بالكامل
                        $amountCollected = $amount;
                        $isFullyCollected = true;
                        $fullCollectionDate = $advanceDate->copy()->addDays(rand(3, 30));
                    } elseif ($scenario <= (70 + $timeFactor * 15)) {
                        // محصلة جزئياً
                        $amountCollected = (int)($amount * rand(30, 75) / 100);
                        $isFullyCollected = false;
                        $fullCollectionDate = null;
                    } else {
                        // معلقة
                        $amountCollected = 0;
                        $isFullyCollected = false;
                        $fullCollectionDate = null;
                    }
                    
                    $advance = Advance::create([
                        'worker_id' => $worker->id,
                        'contractor_id' => $contractor->id,
                        'amount' => $amount,
                        'date' => $advanceDate,
                        'recovery_method' => $recoveryMethod,
                        'reason' => collect([
                            'طلب صريح من العامل',
                            'ظروف صحية طارئة',
                            'مساعدة عائلية',
                            'احتياجات شخصية',
                            'حالة طوارئ',
                            'مشاكل مالية مؤقتة',
                            'إجازة',
                            'تكاليف شخصية',
                        ])->random(),
                        'amount_collected' => $amountCollected,
                        'amount_pending' => $amount - $amountCollected,
                        'is_fully_collected' => $isFullyCollected,
                        'fully_collected_at' => $fullCollectionDate,
                    ]);

                    // إذا كانت طريقة التحصيل بالأقساط
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
                            
                            $installmentsPaid = (int)($amountCollected / $installmentAmount);
                            $isPaid = $j < $installmentsPaid;
                            $amountPaid = $isPaid ? $installmentAmount : ($amountCollected > $j * $installmentAmount ? $amountCollected - $j * $installmentAmount : 0);
                            
                            // تاريخ الدفع يجب أن يكون في الماضي فقط
                            $paidDate = null;
                            if ($isPaid) {
                                // إذا كان مدفوع، اختر تاريخ بين تاريخ السلفة واليوم الحالي
                                $paidDate = $advance->date->copy()->addDays(rand(0, max(0, Carbon::today()->diffInDays($advance->date))));
                            }
                            
                            InstallmentSchedule::create([
                                'advance_id' => $advance->id,
                                'installment_number' => $j + 1,
                                'amount' => $installmentAmount,
                                'due_date' => $dueDate,
                                'amount_paid' => round($amountPaid, 2),
                                'is_paid' => $isPaid,
                                'paid_at' => $paidDate,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
