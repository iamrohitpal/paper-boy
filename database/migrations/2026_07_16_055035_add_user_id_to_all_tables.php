<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        // Ensure user 1 exists, if not create a default admin
        $admin = DB::table('users')->where('id', 1)->first();
        if (! $admin) {
            DB::table('users')->insert([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
                });

                // Assign existing records to user 1
                DB::table($table)->update(['user_id' => 1]);

                // Make user_id non-nullable now that we populated it
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('user_id')->nullable(false)->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_tables', function (Blueprint $table) {
            //
        });
    }
};
