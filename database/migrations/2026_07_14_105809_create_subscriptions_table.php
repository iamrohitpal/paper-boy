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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('newspaper_id')->constrained('newspapers')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('delivery_days', ['Daily', 'Sunday Only', 'Custom'])->default('Daily');
            $table->json('custom_days')->nullable(); // e.g., ["Monday", "Wednesday"]
            $table->decimal('price', 8, 2); // agreed price for this customer
            $table->enum('status', ['Active', 'Paused', 'Cancelled'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
