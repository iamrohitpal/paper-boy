<?php

namespace App\Services;

use App\Models\Invoice;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getPaginatedPayments($search = null, $perPage = 15)
    {
        return $this->paymentRepository->searchAndPaginate($search, $perPage);
    }

    public function createPayment(array $data)
    {
        DB::beginTransaction();
        try {
            if (! empty($data['invoice_id'])) {
                $payment = $this->paymentRepository->create($data);
                $this->updateInvoiceStatus($payment->invoice_id);
                DB::commit();

                return $payment;
            }

            // Auto-allocate generic payment (FIFO)
            $remainingAmount = $data['amount'];
            $unpaidInvoices = Invoice::where('customer_id', $data['customer_id'])
                ->whereIn('status', ['Unpaid', 'Partially Paid'])
                ->orderBy('period_end', 'asc')
                ->get();

            $firstPayment = null;

            foreach ($unpaidInvoices as $invoice) {
                if ($remainingAmount <= 0) {
                    break;
                }

                $due = $invoice->total_amount - $invoice->paid_amount;
                $allocate = min($due, $remainingAmount);

                $paymentData = $data;
                $paymentData['amount'] = $allocate;
                $paymentData['invoice_id'] = $invoice->id;
                $paymentData['notes'] = ($data['notes'] ?? '').' (Auto-allocated)';

                $payment = $this->paymentRepository->create($paymentData);
                $this->updateInvoiceStatus($invoice->id);

                if (! $firstPayment) {
                    $firstPayment = $payment;
                }

                $remainingAmount -= $allocate;
            }

            // If there's still amount left, or no unpaid invoices existed, store it as an unallocated payment
            if ($remainingAmount > 0) {
                $paymentData = $data;
                $paymentData['amount'] = $remainingAmount;
                $paymentData['invoice_id'] = null;
                $payment = $this->paymentRepository->create($paymentData);
                if (! $firstPayment) {
                    $firstPayment = $payment;
                }
            }

            DB::commit();

            return $firstPayment; // Return the primary payment created
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating payment: '.$e->getMessage());
            throw $e;
        }
    }

    public function getPayment($id)
    {
        return $this->paymentRepository->find($id);
    }

    public function updatePayment($id, array $data)
    {
        DB::beginTransaction();
        try {
            $payment = $this->paymentRepository->find($id);
            $oldInvoiceId = $payment->invoice_id;

            $updatedPayment = $this->paymentRepository->update($id, $data);

            if ($oldInvoiceId) {
                $this->updateInvoiceStatus($oldInvoiceId);
            }
            if ($updatedPayment->invoice_id && $updatedPayment->invoice_id != $oldInvoiceId) {
                $this->updateInvoiceStatus($updatedPayment->invoice_id);
            }

            DB::commit();

            return $updatedPayment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment: '.$e->getMessage());
            throw $e;
        }
    }

    public function deletePayment($id)
    {
        DB::beginTransaction();
        try {
            $payment = $this->paymentRepository->find($id);
            $invoiceId = $payment->invoice_id;

            $result = $this->paymentRepository->delete($id);

            if ($invoiceId) {
                $this->updateInvoiceStatus($invoiceId);
            }

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payment: '.$e->getMessage());
            throw $e;
        }
    }

    protected function updateInvoiceStatus($invoiceId)
    {
        $invoice = Invoice::with('payments')->find($invoiceId);
        if (! $invoice) {
            return;
        }

        $totalPaid = $invoice->payments()->sum('amount');
        $invoice->paid_amount = $totalPaid;

        if ($totalPaid >= $invoice->total_amount) {
            $invoice->status = 'Paid';
        } elseif ($totalPaid > 0) {
            $invoice->status = 'Partially Paid';
        } else {
            $invoice->status = 'Unpaid';
        }

        $invoice->save();
    }
}
