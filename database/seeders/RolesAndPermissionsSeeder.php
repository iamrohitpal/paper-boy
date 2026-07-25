<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage_newspapers',
            'manage_customers',
            'manage_collections',
            'manage_payments',
            'manage_purchases',
            'view_reports',
            'manage_settings'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Ensure Super Admin role exists
        Role::firstOrCreate(['name' => 'Super Admin']);
    }
}
