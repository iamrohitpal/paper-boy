<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'customers',
            'newspapers',
            'subscriptions',
            'extra_newspapers',
            'invoices',
            'invoice_items',
            'payments',
            'purchases',
            'customer_leaves',
            'agency_holidays',
            'expenses',
        ];

        // Ensure default tenant exists
        $tenant = DB::table('tenants')->where('id', 1)->first();
        if (! $tenant) {
            DB::table('tenants')->insert([
                'id' => 1,
                'name' => 'Default Newspaper Agency',
                'owner_id' => 1, // Assuming admin is user 1
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->foreignId('tenant_id')->nullable()->after('user_id')->constrained('tenants')->onDelete('cascade');
                });

                // Assign existing records to tenant 1
                DB::table($table)->update(['tenant_id' => 1]);

                // Make tenant_id non-nullable
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('tenant_id')->nullable(false)->change();
                });
            }
        }

        // Update users table
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'tenant_id')) {
            Schema::table('users', function (Blueprint $t) {
                $t->foreignId('tenant_id')->nullable()->after('owner_id')->constrained('tenants')->onDelete('cascade');
            });
            DB::table('users')->update(['tenant_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'customers',
            'newspapers',
            'subscriptions',
            'extra_newspapers',
            'invoices',
            'invoice_items',
            'payments',
            'purchases',
            'customer_leaves',
            'agency_holidays',
            'expenses',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['tenant_id']);
                    $t->dropColumn('tenant_id');
                });
            }
        }
    }
};
