<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository extends BaseRepository implements ExpenseRepositoryInterface
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    public function searchAndPaginate($search = null, $perPage = 15)
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where('title', 'like', '%'.$search.'%')
                ->orWhere('category', 'like', '%'.$search.'%');
        }

        return $query->orderBy('expense_date', 'desc')->paginate($perPage);
    }
}
