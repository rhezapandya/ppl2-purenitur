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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->json('cart');
            $table->decimal('price_total', 10);
            $table->string('discount_id')->nullable();
            $table->decimal('final_price', 10);
            $table->integer('transaction_id')->default(null)->unique()->nullable();
            $table->string('payment_type');
            $table->string('status_payment')->default("WAITING FOR PAYMENT");
            $table->string('image_payment')->nullable();
            $table->integer('shipment_id')->nullable();
            $table->string('shipment_status')->nullable();
            $table->string('shipment_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
