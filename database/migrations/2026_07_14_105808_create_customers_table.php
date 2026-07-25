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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique();
            $table->string('name')->index();
            $table->string('mobile', 15)->index();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->text('delivery_address')->nullable();
            $table->date('start_date');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('notes')->nullable();
            $table->string('customer_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
