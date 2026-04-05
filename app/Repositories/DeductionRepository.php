<?php

namespace App\Repositories;

use App\Models\Deduction;
use App\Repositories\Interfaces\DeductionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DeductionRepository implements DeductionRepositoryInterface
{
    public function getByWorkerAndDate(int $workerId, string $date): Collection
    {
        return Deduction::where('worker_id', $workerId)
            ->where('deduction_date', $date)
            ->get();
    }

    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): Collection
    {
        return Deduction::where('company_id', $companyId)
            ->whereBetween('deduction_date', [$from, $to])
            ->get();
    }

    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection
    {
        return Deduction::where('worker_id', $workerId)
            ->whereBetween('deduction_date', [$from, $to])
            ->get();
    }

    public function create(array $data): Deduction
    {
        return Deduction::create($data);
    }

    public function delete(int $id): void
    {
        Deduction::findOrFail($id)->delete();
    }

    public function findById(int $id): ?Deduction
    {
        return Deduction::find($id);
    }
}
