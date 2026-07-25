<?php

namespace App\Http\Controllers;

use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $invoices = $this->invoiceService->getPaginatedInvoices($search);

        return view('invoices.index', compact('invoices', 'search'));
    }

    public function show($id)
    {
        $invoice = $this->invoiceService->getInvoice($id);

        return view('invoices.show', compact('invoice'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'billing_month' => 'required|date',
        ]);

        $monthDate = $request->input('billing_month');
        $generatedCount = $this->invoiceService->generateMonthlyInvoices($monthDate);

        return redirect()->route('invoices.index')
            ->with('success', "Generated $generatedCount new invoices for the selected month.");
    }

    public function downloadPdf($id)
    {
        $invoice = $this->invoiceService->getInvoice($id);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function destroy($id)
    {
        $invoice = $this->invoiceService->getInvoice($id);

        Gate::authorize('delete', $invoice);

        $this->invoiceService->deleteInvoice($id);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}
