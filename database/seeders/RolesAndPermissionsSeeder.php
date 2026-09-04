<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // All Permissions
        $permissions = [
            // Newspapers
            'view_newspapers', 'create_newspapers', 'edit_newspapers', 'delete_newspapers',
            // Customers
            'view_customers', 'create_customers', 'edit_customers', 'delete_customers',
            // Subscriptions
            'view_subscriptions', 'create_subscriptions', 'edit_subscriptions', 'delete_subscriptions',
            // Extra Newspapers
            'view_extranewspapers', 'create_extranewspapers', 'edit_extranewspapers', 'delete_extranewspapers',
            // Invoices
            'view_invoices', 'create_invoices', 'delete_invoices',
            // Collections
            'view_collections', 'create_collections',
            // Payments
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
            // Expenses
            'view_expenses', 'create_expenses', 'edit_expenses', 'delete_expenses',
            // Purchases
            'view_purchases', 'create_purchases', 'delete_purchases',
            // Reports
            'view_reports',
            // Settings
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 1. Super Admin Role (All system & tenant permissions implicitly granted)
        Role::firstOrCreate(['name' => 'Super Admin']);

        // 2. Tenant Admin / Basic Vendor Role (Full operational control over tenant resources)
        $basicVendor = Role::firstOrCreate(['name' => 'Basic Vendor']);
        $basicVendor->syncPermissions($permissions);

        // 3. Delivery Boy / Collector Role (Operational field access for collections & customer views)
        $deliveryBoy = Role::firstOrCreate(['name' => 'Delivery Boy']);
        $deliveryBoy->syncPermissions([
            'view_customers',
            'view_collections', 'create_collections',
            'view_payments', 'create_payments',
        ]);

        // 4. Staff / Manager Role (Day-to-day management without system settings modifications)
        $staffManager = Role::firstOrCreate(['name' => 'Staff Manager']);
        $staffManager->syncPermissions([
            'view_newspapers', 'create_newspapers', 'edit_newspapers',
            'view_customers', 'create_customers', 'edit_customers',
            'view_subscriptions', 'create_subscriptions', 'edit_subscriptions',
            'view_extranewspapers', 'create_extranewspapers', 'edit_extranewspapers',
            'view_invoices', 'create_invoices',
            'view_collections', 'create_collections',
            'view_payments', 'create_payments',
            'view_expenses', 'create_expenses',
            'view_purchases', 'create_purchases',
            'view_reports',
        ]);
    }
}
