<?php

namespace App\Repositories\Interfaces;

interface ExtraNewspaperRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);
}
