<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getAllByContractor(int $contractorId): Collection
    {
        return Company::where('contractor_id', $contractorId)->get();
    }

    public function findById(int $id): ?Company
    {
        return Company::find($id);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(int $id, array $data): Company
    {
        $company = Company::findOrFail($id);
        $company->update($data);
        return $company;
    }

    public function delete(int $id): void
    {
        Company::findOrFail($id)->delete();
    }

    public function getActiveCompanies(int $contractorId): Collection
    {
        return Company::where('contractor_id', $contractorId)
            ->where('is_active', true)
            ->get();
    }
}
