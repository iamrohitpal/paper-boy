<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Tenant;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $tenantId = Tenant::first()?->id ?? 1;

        $invoices = Invoice::where('tenant_id', $tenantId)->whereIn('status', ['Paid', 'Partially Paid'])->get();

        if ($invoices->count() > 0 && Payment::where('tenant_id', $tenantId)->count() === 0) {
            foreach ($invoices as $invoice) {
                $payAmount = $invoice->status === 'Paid' ? $invoice->total_amount : round($invoice->total_amount / 2, 2);

                Payment::create([
                    'customer_id' => $invoice->customer_id,
                    'invoice_id' => $invoice->id,
                    'amount' => $payAmount,
                    'payment_date' => Carbon::now()->subDays(rand(1, 15)),
                    'payment_mode' => $faker->randomElement(['Cash', 'UPI', 'Bank Transfer']),
                    'transaction_id' => 'TXN'.rand(100000, 999999),
                    'notes' => 'Monthly collection received.',
                    'tenant_id' => $tenantId,
                ]);
            }
        }
    }
}
