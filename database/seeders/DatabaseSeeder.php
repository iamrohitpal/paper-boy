<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initial System Setup
        $this->call([
            RolesAndPermissionsSeeder::class,
            PlanSeeder::class,
        ]);

        // 2. Default Tenant & Super Admin
        $defaultTenant = Tenant::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Default Agency',
                'email' => 'admin@admin.com',
                'mobile' => '9876543210',
                'status' => 'active',
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin'),
                'tenant_id' => $defaultTenant->id,
            ]
        );

        if (! $admin->tenant_id) {
            $admin->update(['tenant_id' => $defaultTenant->id]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        if (! $admin->hasRole('Super Admin')) {
            $admin->assignRole($superAdminRole);
        }

        // Login as Super Admin context so BelongsToTenant and BelongsToUser automatically map scopes
        Auth::login($admin);

        // 3. Global Settings
        Setting::firstOrCreate([], [
            'app_name' => 'Paper Boy',
            'primary_color' => '#4f46e5',
        ]);

        // 4. Call All Specialized Domain Seeders
        $this->call([
            NewspaperSeeder::class,
            CustomerSeeder::class,
            SubscriptionSeeder::class,
            ExtraNewspaperSeeder::class,
            ExpenseSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
