<?php

namespace App\Http\Controllers;

use App\Models\Newspaper;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $newspaper_id = $request->input('newspaper_id');

        $query = Purchase::with('newspaper')
            ->whereYear('purchase_date', substr($month, 0, 4))
            ->whereMonth('purchase_date', substr($month, 5, 2))
            ->orderBy('purchase_date', 'desc');

        if ($newspaper_id) {
            $query->where('newspaper_id', $newspaper_id);
        }

        $purchases = $query->paginate(20);
        $newspapers = Newspaper::orderBy('name')->get();

        // Calculate summaries
        $summaryQuery = Purchase::whereYear('purchase_date', substr($month, 0, 4))
            ->whereMonth('purchase_date', substr($month, 5, 2));

        if ($newspaper_id) {
            $summaryQuery->where('newspaper_id', $newspaper_id);
        }

        $totalPurchased = (clone $summaryQuery)->sum('total_amount');
        $totalPaid = (clone $summaryQuery)->sum('amount_paid');
        $currentMonthBalance = (clone $summaryQuery)->sum('balance_due');

        // Calculate Opening Balance (from previous months)
        $monthStart = Carbon::parse($month)->startOfMonth();
        $openingBalanceQuery = Purchase::where('purchase_date', '<', $monthStart);
        if ($newspaper_id) {
            $openingBalanceQuery->where('newspaper_id', $newspaper_id);
        }
        $openingBalance = $openingBalanceQuery->sum('balance_due');

        $totalBalance = $currentMonthBalance + $openingBalance;

        // Outstanding Balances Grouped By Publisher
        $publisherBalances = Purchase::select('newspapers.publisher', DB::raw('SUM(purchases.balance_due) as total_due'))
            ->join('newspapers', 'purchases.newspaper_id', '=', 'newspapers.id')
            ->groupBy('newspapers.publisher')
            ->having('total_due', '>', 0)
            ->get();

        return view('purchases.index', compact('purchases', 'newspapers', 'month', 'newspaper_id', 'totalPurchased', 'totalPaid', 'currentMonthBalance', 'openingBalance', 'totalBalance', 'publisherBalances'));
    }

    public function create()
    {
        $newspapers = Newspaper::where('status', 'Active')->orderBy('name')->get();

        return view('purchases.create', compact('newspapers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'newspaper_id' => 'required|exists:newspapers,id',
            'purchase_date' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'rate' => 'required|numeric|min:0',
            'return_quantity' => 'nullable|integer|min:0',
            'return_rate' => 'nullable|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $quantity = $request->quantity;
        $return_quantity = $request->return_quantity ?? 0;
        $return_rate = $request->return_rate ?? 0;

        $gross_amount = $quantity * $request->rate;
        $return_value = $return_quantity * $return_rate;
        $total_amount = $gross_amount - $return_value;

        $balance_due = $total_amount - $request->amount_paid;

        Purchase::create([
            'newspaper_id' => $request->newspaper_id,
            'purchase_date' => $request->purchase_date,
            'quantity' => $quantity,
            'rate' => $request->rate,
            'return_quantity' => $return_quantity,
            'return_rate' => $return_rate,
            'total_amount' => $total_amount,
            'amount_paid' => $request->amount_paid,
            'balance_due' => $balance_due,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('purchases.index')
            ->with('success', 'Transaction recorded successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase record deleted.');
    }

    public function settleMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'publisher' => 'required|string',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $month = Carbon::createFromFormat('Y-m', $request->month);
        $endOfMonth = $month->copy()->endOfMonth();

        // Get the first newspaper id for this publisher to attach the payment to
        $newspaper = Newspaper::where('publisher', $request->publisher)->first();

        if (! $newspaper) {
            return redirect()->back()->with('error', 'Publisher not found.');
        }

        if ($request->amount_paid <= 0) {
            return redirect()->back()->with('error', 'Payment amount must be greater than zero.');
        }

        try {
            Purchase::create([
                'newspaper_id' => $newspaper->id,
                'purchase_date' => Carbon::now(),
                'quantity' => 0,
                'rate' => 0,
                'return_quantity' => 0,
                'return_rate' => 0,
                'total_amount' => 0,
                'amount_paid' => $request->amount_paid,
                'balance_due' => -abs($request->amount_paid), // Negative balance acts as a credit/payment
                'notes' => 'Settlement Payment for '.$request->publisher,
            ]);

            return redirect()->route('purchases.index', ['month' => $request->month])
                ->with('success', 'Payment of ₹'.number_format($request->amount_paid, 2).' recorded successfully for '.$request->publisher.'.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error recording payment: '.$e->getMessage());
        }
    }
}
