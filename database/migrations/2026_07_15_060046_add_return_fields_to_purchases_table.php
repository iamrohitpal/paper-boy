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
        Schema::table('purchases', function (Blueprint $table) {
            $table->integer('return_quantity')->default(0)->after('rate');
            $table->decimal('return_rate', 8, 2)->default(0)->after('return_quantity');

            // Drop type column from previous migration if it exists
            if (Schema::hasColumn('purchases', 'type')) {
                $table->dropColumn('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['return_quantity', 'return_rate']);
            $table->string('type')->default('Purchase')->after('newspaper_id');
        });
    }
};
