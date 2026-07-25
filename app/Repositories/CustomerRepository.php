<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function searchAndPaginate($search = null, $perPage = 15)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('mobile', 'like', '%'.$search.'%')
                ->orWhere('customer_id', 'like', '%'.$search.'%');
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
