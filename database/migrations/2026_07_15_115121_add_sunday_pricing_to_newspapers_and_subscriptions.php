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
            $table->decimal('mrp_sunday', 8, 2)->nullable()->after('mrp');
            $table->decimal('purchase_price_sunday', 8, 2)->nullable()->after('purchase_price');
            $table->decimal('selling_price_sunday', 8, 2)->nullable()->after('selling_price');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->decimal('price_sunday', 8, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropColumn(['mrp_sunday', 'purchase_price_sunday', 'selling_price_sunday']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['price_sunday']);
        });
    }
};
