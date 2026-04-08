<?php

namespace App\Repositories;

use App\Models\Advance;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AdvanceRepository implements AdvanceRepositoryInterface
{
    public function create(array $data): Advance
    {
        return Advance::create($data);
    }

    public function findById(int $id): ?Advance
    {
        return Advance::withRelations()->find($id);
    }

    public function findByWorker(int $workerId): Collection
    {
        return Advance::forWorker($workerId)
            ->withRelations()
            ->orderByDesc('date')
            ->get();
    }

    public function findPendingByWorker(int $workerId): Collection
    {
        return Advance::forWorker($workerId)
            ->pending()
            ->withRelations()
            ->orderByDesc('date')
            ->get();
    }

    public function update(int $id, array $data): bool
    {
        $advance = Advance::findOrFail($id);
        return $advance->update($data);
    }

    public function updateRecoveryMethod(int $id, array $data): bool
    {
        $advance = Advance::findOrFail($id);
        $updateData = [
            'recovery_method' => $data['recovery_method'],
        ];
        
        if ($data['recovery_method'] === 'installments') {
            $updateData['installment_period'] = $data['installment_period'];
            $updateData['installment_count'] = $data['installment_count'];
            $updateData['installment_amount'] = $this->calculateInstallmentAmount(
                $advance->amount_pending,
                $data['installment_count']
            );
        }
        
        return $advance->update($updateData);
    }

    public function recordCollection(int $id, float $amount): bool
    {
        $advance = Advance::findOrFail($id);
        
        $newCollected = $advance->amount_collected + $amount;
        $newPending = $advance->amount - $newCollected;
        $isFullyCollected = $newCollected >= $advance->amount;
        
        return $advance->update([
            'amount_collected' => $newCollected,
            'amount_pending' => max(0, $newPending),
            'is_fully_collected' => $isFullyCollected,
            'fully_collected_at' => $isFullyCollected ? now() : null,
        ]);
    }

    public function getMonthlyTotalForWorker(int $workerId): float
    {
        return Advance::forWorker($workerId)
            ->thisMonth()
            ->sum('amount');
    }

    public function getPendingTotalForWorker(int $workerId): float
    {
        return Advance::forWorker($workerId)
            ->pending()
            ->sum('amount_pending');
    }

    public function getCollectedTotalForWorker(int $workerId): float
    {
        return Advance::forWorker($workerId)
            ->sum('amount_collected');
    }

    public function getByContractor(int $contractorId, array $filters = []): Collection
    {
        $query = Advance::forContractor($contractorId)->withRelations();
        
        if (isset($filters['period'])) {
            switch ($filters['period']) {
                case 'month':
                    $query->thisMonth();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'range':
                    if (isset($filters['from']) && isset($filters['to'])) {
                        $query->dateRange($filters['from'], $filters['to']);
                    }
                    break;
            }
        }
        
        if (isset($filters['recovery_method'])) {
            $query->where('recovery_method', $filters['recovery_method']);
        }
        
        if (isset($filters['status'])) {
            if ($filters['status'] === 'pending') {
                $query->pending();
            } elseif ($filters['status'] === 'collected') {
                $query->collected();
            }
        }
        
        return $query->orderByDesc('date')->get();
    }

    public function getByPeriod($from, $to): Collection
    {
        return Advance::dateRange($from, $to)
            ->withRelations()
            ->orderByDesc('date')
            ->get();
    }

    public function getWorkersWithPendingAdvances(int $contractorId): Collection
    {
        return Advance::forContractor($contractorId)
            ->pending()
            ->with('worker')
            ->get()
            ->unique('worker_id')
            ->values();
    }

    private function calculateInstallmentAmount(float $totalAmount, int $count): float
    {
        return round($totalAmount / $count, 2);
    }
}
