<?php

namespace App\Services;

use App\Exceptions\AdvanceException;
use App\Models\Advance;
use App\Models\InstallmentSchedule;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvanceService
{
    public function __construct(
        private AdvanceRepositoryInterface $repository
    ) {}

    /**
     * Record a new advance for a worker with automatic installment generation
     */
    public function recordAdvance(array $data, int $contractorId): Advance
    {
        return DB::transaction(function () use ($data, $contractorId) {
            $data['contractor_id'] = $contractorId;
            $data['amount_pending'] = $data['amount'];
            $data['amount_collected'] = 0;
            
            // Set default recovery method if not provided
            $data['recovery_method'] = $data['recovery_method'] ?? 'immediately';
            
            $advance = $this->repository->create($data);
            
            // Generate installment schedule if recovery method is installments
            if ($data['recovery_method'] === 'installments') {
                $this->generateInstallmentSchedule(
                    $advance,
                    $data['installment_period'] ?? 'weekly',
                    $data['installment_count'] ?? 2
                );
            }
            
            return $advance->load(['worker', 'contractor', 'installments']);
        });
    }

    /**
     * Change recovery method for an existing advance
     */
    public function setRecoveryMethod(int $advanceId, string $method, ?array $details = null): Advance
    {
        return DB::transaction(function () use ($advanceId, $method, $details) {
            $advance = Advance::findOrFail($advanceId);
            
            if ($advance->is_fully_collected) {
                throw AdvanceException::alreadyCollected();
            }
            
            $updateData = ['recovery_method' => $method];
            
            if ($method === 'installments' && $details) {
                $updateData['installment_period'] = $details['period'];
                $updateData['installment_count'] = $details['count'];
                $updateData['installment_amount'] = round(
                    $advance->amount_pending / $details['count'],
                    2
                );
                
                // Clear old installment schedule
                $advance->installments()->delete();
                
                // Generate new schedule
                $this->generateInstallmentSchedule(
                    $advance,
                    $details['period'],
                    $details['count']
                );
            } else if ($method !== 'installments') {
                // Clear installment schedule if changing to non-installment method
                $advance->installments()->delete();
                $updateData['installment_period'] = null;
                $updateData['installment_count'] = null;
                $updateData['installment_amount'] = null;
            }
            
            $advance->update($updateData);
            
            return $advance->load(['worker', 'contractor', 'installments']);
        });
    }

    /**
     * Record a collection against an advance
     */
    public function recordCollection(int $advanceId, float $amount, ?string $notes = null): Advance
    {
        return DB::transaction(function () use ($advanceId, $amount, $notes) {
            $advance = Advance::findOrFail($advanceId);
            
            if ($advance->is_fully_collected) {
                throw AdvanceException::alreadyCollected();
            }
            
            $this->repository->recordCollection($advanceId, $amount);
            
            // Update installments if method is installments
            if ($advance->recovery_method === 'installments') {
                $pending = $advance->installments()
                    ->pending()
                    ->orderBy('installment_number')
                    ->first();
                
                if ($pending) {
                    $payAmount = min($amount, $pending->remaining_amount);
                    $pending->update([
                        'amount_paid' => $pending->amount_paid + $payAmount,
                        'is_paid' => $payAmount >= $pending->amount,
                        'paid_at' => $payAmount >= $pending->amount ? now() : null,
                    ]);
                    
                    $remaining = $amount - $payAmount;
                    while ($remaining > 0) {
                        $nextPending = $advance->installments()
                            ->pending()
                            ->orderBy('installment_number')
                            ->first();
                        
                        if (!$nextPending) break;
                        
                        $payAmount = min($remaining, $nextPending->remaining_amount);
                        $nextPending->update([
                            'amount_paid' => $nextPending->amount_paid + $payAmount,
                            'is_paid' => $payAmount >= $nextPending->amount,
                            'paid_at' => $payAmount >= $nextPending->amount ? now() : null,
                        ]);
                        
                        $remaining -= $payAmount;
                    }
                }
            }
            
            return $advance->fresh()->load(['worker', 'contractor', 'installments']);
        });
    }

    /**
     * Get worker's advance position summary
     */
    public function getWorkerAdvanceSummary(int $workerId): array
    {
        $pending = $this->repository->findPendingByWorker($workerId);
        
        return [
            'total_pending' => $pending->sum('amount_pending'),
            'total_pending_count' => $pending->count(),
            'pending_advances' => $pending,
            'monthly_total' => $this->repository->getMonthlyTotalForWorker($workerId),
            'collected_total' => $this->repository->getCollectedTotalForWorker($workerId),
        ];
    }

    /**
     * Generate installment schedule for an advance
     */
    private function generateInstallmentSchedule(
        Advance $advance,
        string $period,
        int $count
    ): void {
        $installmentAmount = round($advance->amount / $count, 2);
        $startDate = Carbon::parse($advance->date);
        
        for ($i = 0; $i < $count; $i++) {
            $dueDate = $startDate->copy();
            
            if ($period === 'weekly') {
                $dueDate->addWeeks($i);
            } elseif ($period === 'biweekly') {
                $dueDate->addWeeks($i * 2);
            }
            
            InstallmentSchedule::create([
                'advance_id' => $advance->id,
                'installment_number' => $i + 1,
                'amount' => $installmentAmount,
                'due_date' => $dueDate,
                'amount_paid' => 0,
                'is_paid' => false,
            ]);
        }
    }
}
