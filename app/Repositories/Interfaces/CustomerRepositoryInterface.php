<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);
}
