<?php

namespace App\Repositories;

use App\Models\Newspaper;
use App\Repositories\Interfaces\NewspaperRepositoryInterface;

class NewspaperRepository extends BaseRepository implements NewspaperRepositoryInterface
{
    public function __construct(Newspaper $model)
    {
        parent::__construct($model);
    }

    public function searchAndPaginate($search = null, $perPage = 15)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('publisher', 'like', '%'.$search.'%')
                ->orWhere('language', 'like', '%'.$search.'%');
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
