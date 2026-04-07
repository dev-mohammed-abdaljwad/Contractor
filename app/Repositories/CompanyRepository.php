<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository implements CompanyRepositoryInterface
{
    // ===== Base Query Builders =====

    /**
     * Base query for a specific contractor
     */
    private function baseQuery(int $contractorId)
    {
        return Company::where('contractor_id', $contractorId);
    }

    /**
     * Base query for active companies
     */
    private function baseActiveQuery(int $contractorId)
    {
        return $this->baseQuery($contractorId)->where('is_active', true);
    }

    // ===== Read Methods =====

    /**
     * Get all companies with minimal columns (no N+1)
     */
    public function getAllByContractor(int $contractorId): Collection
    {
        return $this->baseQuery($contractorId)
            ->select([
                'id',
                'contractor_id',
                'name',
                'contact_person',
                'phone',
                'daily_wage',
                'payment_cycle',
                'weekly_pay_day',
                'is_active',
                'contract_start_date',
                'notes',
                'created_at',
                'updated_at'
            ])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get all companies with distributions and collections safely (no N+1)
     * Optimized method that loads all needed data in 4 queries:
     * - Companies: 1 query
     * - Distributions: 1 query (eager loaded with select)
     * - Distribution Workers: 1 query (nested eager load)
     * - Collections: 1 query (eager loaded with select)
     */
    public function getAllByContractorWithFullData(int $contractorId): Collection
    {
        return $this->baseQuery($contractorId)
            ->select([
                'id',
                'contractor_id',
                'name',
                'contact_person',
                'phone',
                'daily_wage',
                'payment_cycle',
                'weekly_pay_day',
                'is_active',
                'contract_start_date',
                'notes',
                'created_at',
            ])
            ->with([
                // Distributions with only needed columns
                'distributions:id,company_id,distribution_date,created_at',
                // Workers nested in distributions with only needed columns from workers table
                'distributions.workers:id,name,phone,national_id',
                // Collections with only needed columns
                'collections:id,company_id,period_start,period_end,net_amount,is_paid,payment_date,created_at',
            ])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Find by ID with optional relations (no N+1)
     */
    public function findById(int $id, array $relations = []): ?Company
    {
        return Company::select([
            'id',
            'contractor_id',
            'name',
            'contact_person',
            'phone',
            'daily_wage',
            'payment_cycle',
            'weekly_pay_day',
            'is_active',
            'contract_start_date',
            'notes',
            'created_at',
            'updated_at'
        ])
            ->with($relations)
            ->find($id);
    }

    /**
     * Get active companies with minimal columns
     */
    public function getActiveCompanies(int $contractorId): Collection
    {
        return $this->baseActiveQuery($contractorId)
            ->select([
                'id',
                'contractor_id',
                'name',
                'daily_wage',
                'payment_cycle',
                'is_active',
            ])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get active companies with distributions and collections for statistics (no N+1)
     * Used for dashboard and list views with statistics
     */
    public function getActiveCompaniesWithRelations(int $contractorId): Collection
    {
        return $this->baseActiveQuery($contractorId)
            ->select([
                'id',
                'contractor_id',
                'name',
                'contact_person',
                'phone',
                'daily_wage',
                'payment_cycle',
                'weekly_pay_day',
                'is_active',
                'contract_start_date',
                'notes',
            ])
            ->with([
                // Eager load distributions (prevent N+1)
                'distributions:id,company_id,distribution_date,created_at',
                // Eager load workers for distributions (prevent N+1 for workers)
                'distributions.workers:id,name,phone,national_id',
                // Eager load collections (prevent N+1)
                'collections:id,company_id,period_start,period_end,net_amount,is_paid,payment_date',
            ])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get company with all details for show page (no N+1)
     */
    public function findByIdWithDetails(int $id): ?Company
    {
        return Company::select([
            'id',
            'contractor_id',
            'name',
            'contact_person',
            'phone',
            'daily_wage',
            'payment_cycle',
            'weekly_pay_day',
            'is_active',
            'contract_start_date',
            'notes',
            'created_at',
            'updated_at'
        ])
            ->with([
                // Distributions with workers for detail view
                'distributions:id,company_id,distribution_date,created_at',
                'distributions.workers:id,name,phone,national_id',
                // Collections for payment history
                'collections:id,company_id,period_start,period_end,net_amount,is_paid,payment_date,created_at',
            ])
            ->find($id);
    }

    /**
     * Get company count by contractor
     */
    public function getActiveCompanyCount(int $contractorId): int
    {
        return $this->baseActiveQuery($contractorId)->count();
    }

    // ===== Write Methods =====

    /**
     * Create a new company
     */
    public function create(array $data): Company
    {
        return Company::create($data);
    }

    /**
     * Update a company and return fresh data
     */
    public function update(int $id, array $data): Company
    {
        $company = Company::findOrFail($id);
        $company->update($data);
        
        // Return fresh data from database (prevents stale data)
        return $company->fresh();
    }

    /**
     * Delete a company
     */
    public function delete(int $id): void
    {
        Company::findOrFail($id)->delete();
    }
}


