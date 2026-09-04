<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Tenant;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $tenantId = Tenant::first()?->id ?? 1;

        if (Customer::where('tenant_id', $tenantId)->count() === 0) {
            $areas = ['Civil Lines', 'Model Town', 'Sector 15', 'Green Park', 'Vasant Kunj', 'Rajouri Garden'];

            for ($i = 1; $i <= 50; $i++) {
                Customer::create([
                    'customer_id' => 'CUST'.str_pad($i, 4, '0', STR_PAD_LEFT),
                    'name' => $faker->name,
                    'mobile' => $faker->numerify('9#########'),
                    'email' => $faker->unique()->safeEmail,
                    'address' => $faker->streetAddress,
                    'area' => $faker->randomElement($areas),
                    'city' => 'Delhi',
                    'pincode' => '1100'.rand(10, 99),
                    'start_date' => Carbon::now()->subMonths(rand(1, 12))->startOfMonth(),
                    'status' => 'Active',
                    'tenant_id' => $tenantId,
                ]);
            }
        }
    }
}
