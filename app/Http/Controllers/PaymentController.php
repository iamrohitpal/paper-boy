<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $payments = $this->paymentService->getPaginatedPayments($search);

        return view('payments.index', compact('payments', 'search'));
    }

    public function create(Request $request)
    {
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();

        // If an invoice is pre-selected
        $selectedInvoiceId = $request->input('invoice_id');
        $selectedInvoice = null;
        if ($selectedInvoiceId) {
            $selectedInvoice = Invoice::find($selectedInvoiceId);
        }

        $invoices = Invoice::where('status', '!=', 'Paid')->orderBy('invoice_number', 'desc')->get();

        return view('payments.create', compact('customers', 'invoices', 'selectedInvoice'));
    }

    public function store(PaymentRequest $request)
    {
        $this->paymentService->createPayment($request->validated());

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect(url()->previous())->with('success', 'Payment recorded successfully.');
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function edit($id)
    {
        $payment = $this->paymentService->getPayment($id);
        $customers = Customer::orderBy('name')->get();
        // Include the payment's current invoice even if it's fully paid now
        $invoices = Invoice::where('status', '!=', 'Paid')
            ->orWhere('id', $payment->invoice_id)
            ->orderBy('invoice_number', 'desc')->get();

        return view('payments.edit', compact('payment', 'customers', 'invoices'));
    }

    public function update(PaymentRequest $request, $id)
    {
        $this->paymentService->updatePayment($id, $request->validated());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $this->paymentService->deletePayment($id);

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
