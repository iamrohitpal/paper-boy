<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\CustomerLeave;
use App\Models\Newspaper;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Services\CustomerService;
use App\Services\SubscriptionLimitService;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request, SubscriptionLimitService $limitService)
    {
        $search = $request->input('search');
        $customers = $this->customerService->getPaginatedCustomers($search);

        $canAddCustomer = $limitService->canCreateCustomer(auth()->user()->tenant);

        return view('customers.index', compact('customers', 'search', 'canAddCustomer'));
    }

    public function create()
    {
        $lastCustomer = Customer::orderBy('id', 'desc')->first();
        $nextNum = $lastCustomer ? ($lastCustomer->id + 1) : 1;
        $nextId = 'CUST'.str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        return view('customers.create', compact('nextId'));
    }

    public function store(CustomerRequest $request, SubscriptionLimitService $limitService)
    {
        if ($limitService->hasReachedCustomerLimit(auth()->user()->tenant)) {
            return redirect()->route('customers.index')->with('error', 'You have reached your customer limit. Upgrade your plan to add more customers.');
        }

        $data = $request->validated();
        if (empty($data['start_date'])) {
            $data['start_date'] = now()->format('Y-m-d');
        }
        if (empty($data['payment_frequency'])) {
            $data['payment_frequency'] = 'Monthly';
        }

        if ($request->hasFile('customer_photo')) {
            $data['customer_photo'] = $request->file('customer_photo')->store('customers', 'public');
        }

        $this->customerService->createCustomer($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::with(['invoices', 'payments', 'subscriptions.newspaper'])->findOrFail($id);
        $newspapers = Newspaper::where('status', 'Active')->orderBy('name')->get();

        $ledger = collect();

        foreach ($customer->invoices as $invoice) {
            $ledger->push([
                'id' => $invoice->id,
                'invoice_id' => $invoice->id,
                'date' => $invoice->invoice_date ?? $invoice->created_at, // Use created_at if invoice_date is not available
                'type' => 'Invoice',
                'description' => 'Invoice #'.$invoice->invoice_number,
                'charge' => $invoice->total_amount,
                'payment' => 0,
                'status' => $invoice->status,
                'due_amount' => $invoice->total_amount - $invoice->paid_amount,
            ]);
        }

        foreach ($customer->payments as $payment) {
            $ledger->push([
                'date' => $payment->payment_date,
                'type' => 'Payment',
                'description' => 'Payment - '.$payment->payment_mode.($payment->notes ? ' ('.$payment->notes.')' : ''),
                'charge' => 0,
                'payment' => $payment->amount,
            ]);
        }

        $ledger = $ledger->sortBy('date')->values();

        $balance = 0;
        $ledger = $ledger->map(function ($item) use (&$balance) {
            $balance += $item['charge'];
            $balance -= $item['payment'];
            $item['balance'] = $balance;

            return $item;
        });

        $totalBalance = $balance;

        // Calculate real-time unbilled balance
        $invoiceRepo = app(InvoiceRepositoryInterface::class);
        $unbilledData = $invoiceRepo->calculateUnbilledAmount($customer, Carbon::now());
        $unbilledAmount = $unbilledData['totalAmount'] ?? 0;

        $totalBalance += $unbilledAmount;

        // Group consecutive leaves
        $leaves = $customer->leaves()->orderBy('date')->get();
        $groupedLeaves = collect();
        $currentGroup = null;

        foreach ($leaves as $leave) {
            if (! $currentGroup) {
                $currentGroup = [
                    'start_date' => $leave->date,
                    'end_date' => $leave->date,
                    'newspaper_id' => $leave->newspaper_id,
                    'newspaper_name' => $leave->newspaper ? $leave->newspaper->name : 'All Newspapers',
                ];
            } else {
                if ($currentGroup['newspaper_id'] == $leave->newspaper_id &&
                    Carbon::parse($currentGroup['end_date'])->addDay()->format('Y-m-d') == $leave->date->format('Y-m-d')) {
                    $currentGroup['end_date'] = $leave->date;
                } else {
                    $groupedLeaves->push($currentGroup);
                    $currentGroup = [
                        'start_date' => $leave->date,
                        'end_date' => $leave->date,
                        'newspaper_id' => $leave->newspaper_id,
                        'newspaper_name' => $leave->newspaper ? $leave->newspaper->name : 'All Newspapers',
                    ];
                }
            }
        }
        if ($currentGroup) {
            $groupedLeaves->push($currentGroup);
        }

        // Sort descending so newest is first
        $groupedLeaves = $groupedLeaves->sortByDesc('start_date')->values();

        return view('customers.show', compact('customer', 'ledger', 'totalBalance', 'newspapers', 'unbilledAmount', 'groupedLeaves'));
    }

    public function edit($id)
    {
        $customer = $this->customerService->getCustomer($id);

        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->hasFile('customer_photo')) {
            $data['customer_photo'] = $request->file('customer_photo')->store('customers', 'public');
        }

        $this->customerService->updateCustomer($id, $data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $this->customerService->deleteCustomer($id);

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'newspaper_id' => 'nullable|exists:newspapers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            CustomerLeave::updateOrCreate([
                'customer_id' => $request->customer_id,
                'newspaper_id' => $request->newspaper_id,
                'date' => $date->format('Y-m-d'),
            ]);
        }

        // Notify customer on WhatsApp
        try {
            $customer = Customer::find($request->customer_id);
            if ($customer && $customer->mobile) {
                $mobile = strlen($customer->mobile) == 10 ? '91'.$customer->mobile : $customer->mobile;
                $newspaperName = $request->newspaper_id ? Newspaper::find($request->newspaper_id)?->name : 'All Newspapers';
                $fromFmt = Carbon::parse($request->start_date)->format('d M, Y');
                $toFmt = Carbon::parse($request->end_date)->format('d M, Y');

                $msg = "Hello {$customer->name},\n\nYour newspaper delivery pause (Leave) has been logged successfully.\n\n*Details:*\n- Item: {$newspaperName}\n- From: {$fromFmt}\n- To: {$toFmt}\n\nYou will not be billed for these days.\nThank you!";
                app(WhatsAppService::class)->sendMessage($mobile, $msg);
            }
        } catch (\Exception $e) {
            Log::warning('WhatsApp leave notification failed: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Leave logged successfully and notification sent on WhatsApp.');
    }

    public function updateLeave(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'old_start_date' => 'required|date',
            'old_end_date' => 'required|date',
            'old_newspaper_id' => 'nullable|exists:newspapers,id',
            'newspaper_id' => 'nullable|exists:newspapers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Delete old leave group
        $query = CustomerLeave::where('customer_id', $request->customer_id)
            ->whereBetween('date', [$request->old_start_date, $request->old_end_date])
            ->where('is_billed', false);

        if ($request->old_newspaper_id) {
            $query->where('newspaper_id', $request->old_newspaper_id);
        } else {
            $query->whereNull('newspaper_id');
        }
        $query->delete();

        // Create new leave group
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            CustomerLeave::updateOrCreate([
                'customer_id' => $request->customer_id,
                'newspaper_id' => $request->newspaper_id,
                'date' => $date->format('Y-m-d'),
            ]);
        }

        return redirect()->back()->with('success', 'Leave updated successfully.');
    }

    public function destroyLeave(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'newspaper_id' => 'nullable|exists:newspapers,id',
        ]);

        $query = CustomerLeave::where('customer_id', $request->customer_id)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->where('is_billed', false);

        if ($request->newspaper_id) {
            $query->where('newspaper_id', $request->newspaper_id);
        } else {
            $query->whereNull('newspaper_id');
        }

        $query->delete();

        return redirect()->back()->with('success', 'Leave removed successfully.');
    }

    public function generateBill(Request $request, $id)
    {
        $request->validate([
            'bill_date' => 'required|date',
        ]);

        $customer = $this->customerService->getCustomer($id);
        $invoiceRepo = app(InvoiceRepositoryInterface::class);

        $invoice = $invoiceRepo->generateInvoiceForCustomer($customer, Carbon::parse($request->bill_date));

        if ($invoice) {
            return redirect()->back()->with('success', 'Bill generated successfully up to '.Carbon::parse($request->bill_date)->format('d M, Y').'.');
        } else {
            return redirect()->back()->with('error', 'No unbilled charges found for the selected period.');
        }
    }

    public function bulkActions(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'action' => 'required|in:generate_bills,send_whatsapp_bills',
            'bill_date' => 'required_if:action,generate_bills|nullable|date',
        ]);

        $customerIds = $request->input('customer_ids');
        $action = $request->input('action');
        $invoiceRepo = app(InvoiceRepositoryInterface::class);
        $whatsapp = app(WhatsAppService::class);

        $successCount = 0;
        $messageDetails = [];

        if ($action === 'generate_bills') {
            $billDate = Carbon::parse($request->input('bill_date', now()));
            foreach ($customerIds as $id) {
                $customer = Customer::find($id);
                if ($customer) {
                    $invoice = $invoiceRepo->generateInvoiceForCustomer($customer, $billDate);
                    if ($invoice) {
                        $successCount++;
                    }
                }
            }

            return redirect()->back()->with('success', "Generated bills for {$successCount} customer(s).");
        } elseif ($action === 'send_whatsapp_bills') {
            foreach ($customerIds as $id) {
                $customer = Customer::with('invoices')->find($id);
                if ($customer && $customer->mobile) {
                    $latestInvoice = $customer->invoices()->latest()->first();
                    if ($latestInvoice) {
                        $pdfUrl = route('invoices.pdf', $latestInvoice->id);
                        $msg = "Hello {$customer->name},\n\nYour newspaper bill balance is *₹".number_format($latestInvoice->total_amount, 2)."*.\nBilling Month: ".Carbon::parse($latestInvoice->billing_month)->format('M Y')."\nInvoice #: {$latestInvoice->invoice_number}\n\n📄 Download Bill PDF:\n{$pdfUrl}\n\nPlease settle your payment at your earliest convenience.\nThank you!";
                        $sent = $whatsapp->sendMessage($mobile, $msg);
                        if ($sent) {
                            $successCount++;
                        }
                    }
                }
            }

            return redirect()->back()->with('success', "Sent WhatsApp bill notification to {$successCount} customer(s).");
        }

        return redirect()->back()->with('error', 'Invalid action selected.');
    }
}
