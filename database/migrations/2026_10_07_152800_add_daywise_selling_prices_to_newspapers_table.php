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
            $table->decimal('selling_price_monday', 8, 2)->nullable()->after('selling_price');
            $table->decimal('selling_price_tuesday', 8, 2)->nullable()->after('selling_price_monday');
            $table->decimal('selling_price_wednesday', 8, 2)->nullable()->after('selling_price_tuesday');
            $table->decimal('selling_price_thursday', 8, 2)->nullable()->after('selling_price_wednesday');
            $table->decimal('selling_price_friday', 8, 2)->nullable()->after('selling_price_thursday');
            $table->decimal('selling_price_saturday', 8, 2)->nullable()->after('selling_price_friday');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->decimal('price_monday', 8, 2)->nullable()->after('price');
            $table->decimal('price_tuesday', 8, 2)->nullable()->after('price_monday');
            $table->decimal('price_wednesday', 8, 2)->nullable()->after('price_tuesday');
            $table->decimal('price_thursday', 8, 2)->nullable()->after('price_wednesday');
            $table->decimal('price_friday', 8, 2)->nullable()->after('price_thursday');
            $table->decimal('price_saturday', 8, 2)->nullable()->after('price_friday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropColumn([
                'selling_price_monday',
                'selling_price_tuesday',
                'selling_price_wednesday',
                'selling_price_thursday',
                'selling_price_friday',
                'selling_price_saturday',
            ]);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'price_monday',
                'price_tuesday',
                'price_wednesday',
                'price_thursday',
                'price_friday',
                'price_saturday',
            ]);
        });
    }
};
