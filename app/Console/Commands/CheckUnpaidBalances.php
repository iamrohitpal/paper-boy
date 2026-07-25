<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\User;
use App\Notifications\UnpaidCustomerNotification;
use App\Notifications\UnpaidVendorNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckUnpaidBalances extends Command
{
    protected $signature = 'notify:unpaid-balances';

    protected $description = 'Check for unpaid vendor and customer balances and send notifications';

    public function handle()
    {
        // Get all admin users to notify
        $admins = User::all();

        if ($admins->isEmpty()) {
            $this->error('No admin users found to notify.');

            return;
        }

        $this->info('Checking Vendor Balances...');

        // 1. Check Vendor Balances
        $publisherBalances = Purchase::select('newspapers.publisher', DB::raw('SUM(purchases.balance_due) as total_due'))
            ->join('newspapers', 'purchases.newspaper_id', '=', 'newspapers.id')
            ->groupBy('newspapers.publisher')
            ->having('total_due', '>', 0)
            ->get();

        foreach ($publisherBalances as $balance) {
            foreach ($admins as $admin) {
                $admin->notify(new UnpaidVendorNotification($balance->publisher, $balance->total_due));
            }
            $this->line("Notified for publisher: {$balance->publisher} (Amount: {$balance->total_due})");
        }

        $this->info('Checking Customer Balances...');

        // 2. Check Customer Balances
        // Customers with unpaid invoices
        $unpaidCustomers = Customer::whereHas('invoices', function ($query) {
            $query->whereIn('status', ['Unpaid', 'Partially Paid']);
        })->with(['invoices' => function ($query) {
            $query->whereIn('status', ['Unpaid', 'Partially Paid']);
        }])->get();

        foreach ($unpaidCustomers as $customer) {
            $totalOwed = $customer->invoices->sum(function ($invoice) {
                return $invoice->total_amount - $invoice->paid_amount;
            });

            if ($totalOwed > 0) {
                foreach ($admins as $admin) {
                    $admin->notify(new UnpaidCustomerNotification($customer->id, $customer->name, $totalOwed));
                }
                $this->line("Notified for customer: {$customer->name} (Amount: {$totalOwed})");
            }
        }

        $this->info('Balance checking and notifications completed.');
    }
}
