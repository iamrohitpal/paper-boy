<?php

namespace App\Repositories;

use App\Models\ExtraNewspaper;
use App\Repositories\Interfaces\ExtraNewspaperRepositoryInterface;

class ExtraNewspaperRepository extends BaseRepository implements ExtraNewspaperRepositoryInterface
{
    public function __construct(ExtraNewspaper $model)
    {
        parent::__construct($model);
    }

    public function searchAndPaginate($search = null, $perPage = 15)
    {
        $query = $this->model->with(['customer', 'newspaper']);

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('mobile', 'like', '%'.$search.'%');
            })->orWhereHas('newspaper', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        return $query->orderBy('date', 'desc')->paginate($perPage);
    }
}
