<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Tenant;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        $tenantId = Tenant::first()?->id ?? 1;

        if (Expense::where('tenant_id', $tenantId)->count() === 0) {
            $categories = ['Fuel', 'Boy Salary', 'Office Rent', 'Newspaper Damage', 'Tea & Refreshment'];
            for ($i = 0; $i < 15; $i++) {
                Expense::create([
                    'title' => $faker->randomElement($categories).' Expense',
                    'category' => $faker->randomElement($categories),
                    'amount' => $faker->randomFloat(2, 50, 1500),
                    'expense_date' => Carbon::now()->subDays(rand(1, 30)),
                    'description' => $faker->sentence(6),
                    'tenant_id' => $tenantId,
                ]);
            }
        }
    }
}
