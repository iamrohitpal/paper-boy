<?php

namespace App\Repositories\Interfaces;

interface InvoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function searchAndPaginate($search = null, $perPage = 15);

    public function generateMonthlyInvoices($monthDate);
}
