<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ExtraNewspaper;
use App\Models\Newspaper;
use App\Models\Tenant;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ExtraNewspaperSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $tenantId = Tenant::first()?->id ?? 1;

        $customers = Customer::where('tenant_id', $tenantId)->get();
        $newspapers = Newspaper::where('tenant_id', $tenantId)->get();

        if ($customers->count() > 0 && $newspapers->count() > 0 && ExtraNewspaper::where('tenant_id', $tenantId)->count() === 0) {
            for ($i = 0; $i < 25; $i++) {
                $paper = $faker->randomElement($newspapers);
                ExtraNewspaper::create([
                    'customer_id' => $faker->randomElement($customers)->id,
                    'newspaper_id' => $paper->id,
                    'date' => Carbon::now()->subDays(rand(1, 25)),
                    'quantity' => rand(1, 2),
                    'price' => $paper->selling_price,
                    'is_billed' => rand(0, 1),
                    'tenant_id' => $tenantId,
                ]);
            }
        }
    }
}
