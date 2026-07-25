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
        Schema::create('newspapers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('publisher')->nullable();
            $table->string('language')->default('English');
            $table->decimal('mrp', 8, 2);
            $table->decimal('purchase_price', 8, 2);
            $table->decimal('selling_price', 8, 2);
            $table->decimal('commission', 8, 2)->default(0.00);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newspapers');
    }
};
