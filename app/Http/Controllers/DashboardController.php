<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\SubscriptionLimitService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(SubscriptionLimitService $limitService)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Limits (Respect Impersonation)
        $tenant = auth()->user()->tenant;
        if (auth()->user()->hasRole('Super Admin') && session()->has('active_tenant_id')) {
            $tenant = Tenant::find(session('active_tenant_id'));
        }

        $customerLimit = $limitService->getCustomerLimit($tenant);
        $usedCustomers = $limitService->getUsedCustomers($tenant);

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
            'customerLimit',
            'usedCustomers',
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
