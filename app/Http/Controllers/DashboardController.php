<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // KPIs
        $totalCustomers = Customer::where('status', 'Active')->count();
        $totalSubscriptions = Subscription::where('status', 'Active')->count();

        $monthlyRevenue = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyExpenses = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');

        $unpaidInvoices = Invoice::where('status', '!=', 'Paid')->sum(\DB::raw('total_amount - paid_amount'));

        // Recent Activity
        $recentPayments = Payment::with('customer')->orderBy('payment_date', 'desc')->take(5)->get();
        $recentInvoices = Invoice::with('customer')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalCustomers',
            'totalSubscriptions',
            'monthlyRevenue',
            'monthlyExpenses',
            'unpaidInvoices',
            'recentPayments',
            'recentInvoices'
        ));
    }
}
