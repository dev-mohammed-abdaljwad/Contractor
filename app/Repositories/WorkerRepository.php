<?php

namespace App\Repositories;

use App\Models\Worker;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WorkerRepository implements WorkerRepositoryInterface
{
    private function baseQuery(int $contractorId)
    {
        return Worker::where('contractor_id', $contractorId);
    }

    /**
     * Get all workers with all needed relations in 6 queries total
     * Workers + distributions + distribution.company + advances + deductions
     */
    public function getAllByContractorWithFullData(int $contractorId): Collection
    {
        return $this->baseQuery($contractorId)
            ->select([
                'id', 'contractor_id', 'name', 'phone',
                'national_id', 'joined_date', 'is_active', 'created_at',
            ])
            ->with([
                'distributions:id,distribution_date,company_id',
                'distributions.company:id,name,daily_wage',
                'advances:id,worker_id,amount,is_settled,advance_date,created_at,updated_at',
                'deductions:id,worker_id,amount,type,reason,deduction_date,company_id',
                'deductions.company:id,name',
            ])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getAllByContractor(int $contractorId): Collection
    {
        return $this->baseQuery($contractorId)
            ->select([
                'id', 'contractor_id', 'name', 'phone',
                'national_id', 'joined_date', 'is_active', 'created_at',
            ])
            ->orderBy('name', 'asc')
            ->get();
    }

    public function findById(int $id, array $relations = []): ?Worker
    {
        return Worker::select([
            'id', 'contractor_id', 'name', 'phone',
            'national_id', 'joined_date', 'is_active', 'created_at',
        ])
        ->with($relations)
        ->find($id);
    }

    /**
     * Find worker with all relations for show page
     */
    public function findByIdWithFullData(int $id): ?Worker
    {
        return Worker::select([
            'id', 'contractor_id', 'name', 'phone',
            'national_id', 'joined_date', 'is_active', 'created_at',
        ])
        ->with([
            'distributions:id,distribution_date,company_id',
            'distributions.company:id,name,daily_wage',
            'advances:id,worker_id,amount,is_settled,advance_date,created_at,updated_at',
            'deductions:id,worker_id,amount,type,reason,deduction_date,company_id',
            'deductions.company:id,name',
        ])
        ->find($id);
    }

    public function getActiveWorkers(int $contractorId): Collection
    {
        return $this->baseQuery($contractorId)
            ->select(['id', 'contractor_id', 'name', 'phone', 'is_active'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function create(array $data): Worker
    {
        return Worker::create($data);
    }

    public function update(int $id, array $data): Worker
    {
        $worker = Worker::findOrFail($id);
        $worker->update($data);
        return $worker->fresh();
    }

    public function deactivate(int $id): Worker
    {
        $worker = Worker::findOrFail($id);
        $worker->update(['is_active' => false]);
        return $worker->fresh();
    }
}
