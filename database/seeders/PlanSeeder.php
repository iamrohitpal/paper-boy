<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 299.00,
                'billing_interval' => 'month',
                'customer_limit' => 100,
                'user_limit' => 2,
                'features' => ['Newspaper Scopes', 'Customer Billing', 'WhatsApp Alerts', 'Basic Reports'],
                'trial_days' => 14,
                'status' => 'active',
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'price' => 799.00,
                'billing_interval' => 'month',
                'customer_limit' => 500,
                'user_limit' => 5,
                'features' => ['All Starter Features', 'Daily Collection Tracking', 'Vendor Purchase Analytics', 'Custom Invoicing', 'Priority Support'],
                'trial_days' => 14,
                'status' => 'active',
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 1999.00,
                'billing_interval' => 'month',
                'customer_limit' => 5000,
                'user_limit' => 20,
                'features' => ['Unlimited Customers', 'Multi-tenant Agency Support', 'Dedicated WhatsApp Gateway', 'Custom PDF Export', '24/7 Phone Support'],
                'trial_days' => 30,
                'status' => 'active',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
