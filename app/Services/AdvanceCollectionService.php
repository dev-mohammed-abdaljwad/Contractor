<?php

namespace App\Services;

use App\Models\Advance;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdvanceCollectionService
{
    /**
     * Automatically collect pending advances from salary payment
     * 
     * @param int $workerId
     * @param int $paymentAmount - الأجر المدفوع
     * @param string $paymentDate
     * @return array - ['collected_amount', 'pending_amount']
     */
    public function collectAdvancesFromPayment(int $workerId, float $paymentAmount, string $paymentDate): array
    {
        return DB::transaction(function () use ($workerId, $paymentAmount, $paymentDate) {
            // احصل على كل السلف المعلقة للعامل
            $pendingAdvances = Advance::where('worker_id', $workerId)
                ->where('is_fully_collected', false)
                ->orderBy('date', 'asc') // الأقدم أولاً
                ->get();

            $remainingPayment = $paymentAmount;
            $totalCollected = 0;

            foreach ($pendingAdvances as $advance) {
                $advancePending = $advance->amount - $advance->amount_collected;

                if ($advancePending <= 0) {
                    continue; // لا يوجد رصيد معلق
                }

                if ($remainingPayment <= 0) {
                    break; // انتهى الأجر
                }

                // حساب المبلغ المخصوم من هذه السلفة
                $deductionAmount = min($advancePending, $remainingPayment);

                // تحديث السلفة
                $newAmountCollected = $advance->amount_collected + $deductionAmount;
                $newAmountPending = $advance->amount - $newAmountCollected;

                $advance->update([
                    'amount_collected' => $newAmountCollected,
                    'amount_pending' => $newAmountPending,
                    'is_fully_collected' => $newAmountPending <= 0,
                    'fully_collected_at' => $newAmountPending <= 0 ? now() : null,
                ]);

                $remainingPayment -= $deductionAmount;
                $totalCollected += $deductionAmount;
            }

            return [
                'collected_amount' => $totalCollected,
                'remaining_payment' => $remainingPayment,
            ];
        });
    }
}
