<?php

namespace App\Repositories\Interfaces;

interface ExpenseRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);
}
