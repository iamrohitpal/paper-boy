<?php

namespace App\Repositories\Interfaces;

interface NewspaperRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);
}
