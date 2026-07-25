<?php

namespace App\Services;

use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ExpenseService
{
    protected ExpenseRepositoryInterface $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getPaginatedExpenses($search = null, $perPage = 15)
    {
        return $this->expenseRepository->searchAndPaginate($search, $perPage);
    }

    public function createExpense(array $data)
    {
        try {
            return $this->expenseRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating expense: '.$e->getMessage());
            throw $e;
        }
    }

    public function getExpense($id)
    {
        return $this->expenseRepository->find($id);
    }

    public function updateExpense($id, array $data)
    {
        try {
            return $this->expenseRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating expense: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteExpense($id)
    {
        try {
            return $this->expenseRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting expense: '.$e->getMessage());
            throw $e;
        }
    }
}
