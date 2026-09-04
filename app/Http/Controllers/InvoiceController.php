<?php

namespace App\Http\Controllers;

use App\Services\InvoiceService;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

    public function sendWhatsapp($id, WhatsAppService $whatsapp)
    {
        $invoice = $this->invoiceService->getInvoice($id);

        $mobile = $invoice->customer->mobile;
        if (! $mobile) {
            return redirect()->back()->with('error', 'Customer does not have a mobile number.');
        }

        // Standardize number (assuming India +91 if 10 digits)
        if (strlen($mobile) == 10) {
            $mobile = '91'.$mobile;
        }

        $pdfUrl = route('invoices.pdf', $invoice->id);
        $message = "Hello {$invoice->customer->name},\n\nYour newspaper bill for ".Carbon::parse($invoice->billing_month)->format('M Y')." is ready.\nTotal amount: *₹".number_format($invoice->total_amount, 2)."*\n\n📄 Download Bill PDF:\n{$pdfUrl}\n\nPlease pay at your earliest convenience.\nThank you!";

        $success = $whatsapp->sendMessage($mobile, $message);

        if ($success) {
            return redirect()->back()->with('success', 'WhatsApp bill sent successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to send WhatsApp message. Please check the WhatsApp connection in Settings.');
        }
    }
}
