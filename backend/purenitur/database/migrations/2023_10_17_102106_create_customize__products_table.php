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
        Schema::create('customize__products', function (Blueprint $table) {
            $table->id();
            $table->string('name_product');
            $table->integer('user_id');
            $table->json('cabinets_cart');
            $table->json('beds_cart');
            $table->decimal('price_total', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customize__products');
    }
};
