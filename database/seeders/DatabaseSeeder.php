<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExtraNewspaper;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Newspaper;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');

        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Update email to admin@admin.com to match defaults
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin'), // Match the password from our migrations
            ]
        );

        // Login so that BelongsToUser trait intercepts and assigns user_id automatically
        Auth::login($admin);

        // 2. Settings
        Setting::firstOrCreate([], [
            'app_name' => 'Paper Boy',
            'primary_color' => '#4f46e5',
        ]);

        // 3. Newspapers (10 records)
        $newspapers = [];
        $paperNames = ['The Times of India', 'The Hindu', 'Hindustan Times', 'Deccan Chronicle', 'Economic Times', 'Dainik Jagran', 'Malayala Manorama', 'Navbharat Times', 'Amar Ujala', 'The Telegraph'];

        foreach ($paperNames as $name) {
            $mrp = $faker->randomFloat(2, 3, 10);
            $newspapers[] = Newspaper::create([
                'name' => $name,
                'publisher' => $name.' Group',
                'language' => $faker->randomElement(['English', 'Hindi', 'Regional']),
                'mrp' => $mrp,
                'purchase_price' => $mrp * 0.7,
                'selling_price' => $mrp,
                'commission' => $mrp * 0.3,
                'status' => 'Active',
            ]);
        }

        // 4. Customers (100 records)
        $customers = [];
        for ($i = 1; $i <= 100; $i++) {
            $customers[] = Customer::create([
                'customer_id' => 'CUST'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'mobile' => $faker->numerify('9#########'),
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->streetAddress,
                'area' => $faker->randomElement(['North Block', 'South Block', 'East Block', 'West Block']),
                'city' => 'Delhi',
                'pincode' => $faker->postcode,
                'start_date' => Carbon::now()->subMonths(rand(1, 24)),
                'status' => 'Active',
            ]);
        }

        // 5. Subscriptions (60 monthly, 40 daily)
        // 60 Monthly
        for ($i = 0; $i < 60; $i++) {
            $paper = $faker->randomElement($newspapers);
            Subscription::create([
                'customer_id' => $customers[$i]->id,
                'newspaper_id' => $paper->id,
                'start_date' => Carbon::now()->subMonths(rand(1, 12))->startOfMonth(),
                'delivery_days' => 'Daily',
                'quantity' => 1,
                'price' => $paper->mrp * 30, // Monthly bulk approximation
                'status' => 'Active',
            ]);
        }

        // 40 Daily
        for ($i = 60; $i < 100; $i++) {
            $paper = $faker->randomElement($newspapers);
            Subscription::create([
                'customer_id' => $customers[$i]->id,
                'newspaper_id' => $paper->id,
                'start_date' => Carbon::now()->subMonths(rand(1, 12))->startOfMonth(),
                'delivery_days' => 'Daily',
                'quantity' => 1,
                'price' => $paper->mrp, // Daily price
                'status' => 'Active',
            ]);
        }

        // 6. Extra Newspapers
        for ($i = 0; $i < 50; $i++) {
            $paper = $faker->randomElement($newspapers);
            ExtraNewspaper::create([
                'customer_id' => $faker->randomElement($customers)->id,
                'newspaper_id' => $paper->id,
                'date' => Carbon::now()->subDays(rand(1, 30)),
                'quantity' => rand(1, 3),
                'price' => $paper->mrp,
                'is_billed' => rand(0, 1),
            ]);
        }

        // 7. Expenses
        $expenseCategories = ['Fuel', 'Salary', 'Stationery', 'Maintenance', 'Miscellaneous'];
        for ($i = 0; $i < 30; $i++) {
            Expense::create([
                'title' => $faker->sentence(3),
                'category' => $faker->randomElement($expenseCategories),
                'amount' => $faker->randomFloat(2, 100, 5000),
                'expense_date' => Carbon::now()->subDays(rand(1, 60)),
                'description' => $faker->sentence(10),
            ]);
        }

        // 8. Invoices and Payments
        // Generate a few random invoices to populate reports
        for ($i = 0; $i < 80; $i++) {
            $customer = $faker->randomElement($customers);
            $amount = $faker->randomFloat(2, 150, 1200);
            $status = $faker->randomElement(['Paid', 'Unpaid', 'Partially Paid']);

            $invoice = Invoice::create([
                'customer_id' => $customer->id,
                'invoice_number' => 'INV-'.strtoupper(uniqid()),
                'billing_month' => Carbon::now()->subMonths(rand(1, 6))->startOfMonth()->format('Y-m-d'),
                'period_start' => Carbon::now()->subMonths(rand(1, 6))->startOfMonth()->format('Y-m-d'),
                'period_end' => Carbon::now()->subMonths(rand(1, 6))->endOfMonth()->format('Y-m-d'),
                'total_amount' => $amount,
                'due_date' => Carbon::now()->subMonths(rand(0, 5))->addDays(5),
                'status' => $status,
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => 'Monthly Newspaper Subscription',
                'quantity' => 1,
                'unit_price' => $amount,
                'total' => $amount,
            ]);

            if ($status === 'Paid' || $status === 'Partially Paid') {
                $payAmount = $status === 'Paid' ? $amount : $amount / 2;
                Payment::create([
                    'customer_id' => $customer->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $payAmount,
                    'payment_date' => Carbon::now()->subDays(rand(1, 30)),
                    'payment_mode' => $faker->randomElement(['Cash', 'UPI', 'Bank Transfer']),
                    'transaction_id' => 'TXN'.rand(100000, 999999),
                    'notes' => 'Payment received.',
                ]);
            }
        }

        // Seed some Vendor Purchases and Returns
        if (Purchase::count() === 0) {
            $months = [
                Carbon::now()->subMonths(2),
                Carbon::now()->subMonth(),
                Carbon::now(),
            ];

            foreach ($newspapers as $newspaper) {
                // Each newspaper has some purchases in the last 3 months
                foreach ($months as $month) {
                    // Make 1-3 purchases per month per paper
                    $numPurchases = rand(1, 3);
                    for ($i = 0; $i < $numPurchases; $i++) {
                        $purchaseDate = clone $month;
                        $purchaseDate->day(rand(1, 28));

                        $quantity = rand(50, 200);
                        $rate = $newspaper->purchase_price;

                        // 30% chance of returning some papers (different rate sometimes)
                        $returnQuantity = (rand(1, 100) <= 30) ? rand(2, 10) : 0;
                        $returnRate = $returnQuantity > 0 ? (rand(0, 1) ? $rate : $rate + 1) : 0;

                        $total = ($quantity * $rate) - ($returnQuantity * $returnRate);

                        // Pay either full, 80%, or 50%
                        $paidRatio = [1, 1, 0.8, 0.5][rand(0, 3)];
                        $amountPaid = round($total * $paidRatio, 2);

                        Purchase::create([
                            'newspaper_id' => $newspaper->id,
                            'purchase_date' => $purchaseDate,
                            'quantity' => $quantity,
                            'rate' => $rate,
                            'return_quantity' => $returnQuantity,
                            'return_rate' => $returnRate,
                            'total_amount' => $total,
                            'amount_paid' => $amountPaid,
                            'balance_due' => $total - $amountPaid,
                            'payment_method' => ['Cash', 'UPI', 'Bank Transfer'][rand(0, 2)],
                            'notes' => $returnQuantity > 0 ? 'Returned unsold copies' : null,
                        ]);
                    }
                }
            }
            echo "Created Vendor Purchases\n";
        }

        echo "Database seeded successfully!\n";
    }
}
