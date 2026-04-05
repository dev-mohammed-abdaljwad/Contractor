<?php

namespace App\Repositories\Interfaces;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface
{
    public function getAllByContractor(int $contractorId): Collection;
    
    public function findById(int $id): ?Company;
    
    public function create(array $data): Company;
    
    public function update(int $id, array $data): Company;
    
    public function delete(int $id): void;
    
    public function getActiveCompanies(int $contractorId): Collection;
}
