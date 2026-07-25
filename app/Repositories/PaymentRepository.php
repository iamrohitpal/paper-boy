<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function searchAndPaginate($search = null, $perPage = 15)
    {
        $query = $this->model->with(['customer', 'invoice']);

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })->orWhereHas('invoice', function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%'.$search.'%');
            })->orWhere('transaction_id', 'like', '%'.$search.'%');
        }

        return $query->orderBy('payment_date', 'desc')->paginate($perPage);
    }
}
