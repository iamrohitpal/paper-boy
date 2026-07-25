<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    public function __construct(Subscription $model)
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

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
