<?php

namespace App\Services;

use App\Models\Advance;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Carbon\Carbon;

class AdvanceService
{
    public function __construct(
        private AdvanceRepositoryInterface $advanceRepository,
    ) {}

    public function storeAdvance(array $data): Advance
    {
        return $this->advanceRepository->create($data);
    }

    public function settleAdvance(int $id): void
    {
        $advance = $this->advanceRepository->findById($id);
        
        if ($advance) {
            $this->advanceRepository->update($id, [
                'is_settled' => true,
                'settled_date' => Carbon::now()->toDateString(),
            ]);
        }
    }

    public function getWorkerAdvances(int $workerId)
    {
        return $this->advanceRepository->getByWorker($workerId);
    }
}
