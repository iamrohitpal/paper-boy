<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Tenant;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $tenantId = Tenant::first()?->id ?? 1;

        $customers = Customer::where('tenant_id', $tenantId)->get();

        if ($customers->count() > 0 && Invoice::where('tenant_id', $tenantId)->count() === 0) {
            foreach ($customers->take(30) as $cust) {
                $amount = $faker->randomFloat(2, 180, 450);
                $status = $faker->randomElement(['Paid', 'Unpaid', 'Partially Paid']);

                $invoice = Invoice::create([
                    'customer_id' => $cust->id,
                    'invoice_number' => 'INV-'.strtoupper(uniqid()),
                    'billing_month' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'),
                    'period_start' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'),
                    'period_end' => Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'),
                    'total_amount' => $amount,
                    'due_date' => Carbon::now()->startOfMonth()->addDays(10),
                    'status' => $status,
                    'tenant_id' => $tenantId,
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Monthly Newspaper Billing',
                    'quantity' => 1,
                    'unit_price' => $amount,
                    'total' => $amount,
                ]);
            }
        }
    }
}
