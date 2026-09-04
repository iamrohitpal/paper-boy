<?php

namespace App\Services;

use App\Models\CustomerLeave;
use App\Models\ExtraNewspaper;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    protected InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getPaginatedInvoices($search = null, $perPage = 15)
    {
        return $this->invoiceRepository->searchAndPaginate($search, $perPage);
    }

    public function getInvoice($id)
    {
        return $this->invoiceRepository->find($id)->load('items', 'customer');
    }

    public function generateMonthlyInvoices($monthDate)
    {
        try {
            return $this->invoiceRepository->generateMonthlyInvoices($monthDate);
        } catch (\Exception $e) {
            Log::error('Error generating invoices: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteInvoice($id)
    {
        try {
            DB::beginTransaction();

            $invoice = $this->invoiceRepository->find($id);
            if ($invoice) {
                if ($invoice->period_start && $invoice->period_end) {
                    CustomerLeave::where('customer_id', $invoice->customer_id)
                        ->whereBetween('date', [$invoice->period_start, $invoice->period_end])
                        ->update(['is_billed' => false]);

                    ExtraNewspaper::where('customer_id', $invoice->customer_id)
                        ->whereBetween('date', [$invoice->period_start, $invoice->period_end])
                        ->update(['is_billed' => false]);
                }

                // Reset last_billed_date for customer's subscriptions to allow re-billing
                $customer = $invoice->customer;
                if ($customer) {
                    $prevInvoice = $customer->invoices()
                        ->where('id', '!=', $invoice->id)
                        ->latest('period_end')
                        ->first();

                    $newLastBilled = $prevInvoice ? $prevInvoice->period_end : null;
                    foreach ($customer->subscriptions as $sub) {
                        $sub->update(['last_billed_date' => $newLastBilled]);
                    }
                }

                $this->invoiceRepository->delete($id);
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting invoice: '.$e->getMessage());
            throw $e;
        }
    }
}
