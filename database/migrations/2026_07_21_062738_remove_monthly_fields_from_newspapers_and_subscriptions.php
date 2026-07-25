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
        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropColumn('selling_price_monthly');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('billing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newspapers_and_subscriptions', function (Blueprint $table) {
            //
        });
    }
};
