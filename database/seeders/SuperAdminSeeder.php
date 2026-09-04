<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Super Admin role exists
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // 2. Create or find Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin'),
                'tenant_id' => 1,
            ]
        );

        if (! $admin->tenant_id) {
            $admin->update(['tenant_id' => 1]);
        }

        // 3. Assign Super Admin Role
        if (! $admin->hasRole('Super Admin')) {
            $admin->assignRole($superAdminRole);
        }

        $this->command->info('Super Admin user created/updated successfully!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: admin');
    }
}
