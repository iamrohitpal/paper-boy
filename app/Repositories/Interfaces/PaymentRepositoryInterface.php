<?php

namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);
}
