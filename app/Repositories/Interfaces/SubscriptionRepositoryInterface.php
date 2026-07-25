<?php

namespace App\Repositories\Interfaces;

interface SubscriptionRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);
}
