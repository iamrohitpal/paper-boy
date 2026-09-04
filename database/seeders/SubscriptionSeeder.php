<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Newspaper;
use App\Models\Subscription;
use App\Models\Tenant;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $tenantId = Tenant::first()?->id ?? 1;

        $customers = Customer::where('tenant_id', $tenantId)->get();
        $newspapers = Newspaper::where('tenant_id', $tenantId)->get();

        if ($customers->count() > 0 && $newspapers->count() > 0 && Subscription::where('tenant_id', $tenantId)->count() === 0) {
            foreach ($customers as $customer) {
                $assignedPapers = $faker->randomElements($newspapers, rand(1, 2));
                foreach ($assignedPapers as $paper) {
                    Subscription::create([
                        'customer_id' => $customer->id,
                        'newspaper_id' => $paper->id,
                        'start_date' => $customer->start_date,
                        'delivery_days' => 'Daily',
                        'quantity' => 1,
                        'price' => $paper->selling_price,
                        'status' => 'Active',
                        'tenant_id' => $tenantId,
                    ]);
                }
            }
        }
    }
}
