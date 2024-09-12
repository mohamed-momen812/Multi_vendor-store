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
        // consider as a pivot table to orders and products
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable() // This allows the product_id to be nul
                ->constrained('products')
                ->nullOnDelete();

            $table->string('product_name'); // This field stores the name of the product at the time the order was placed. This is important because the name of the product may change later, but the order needs to reflect what was purchased at the time.

            $table->float('price'); // This field stores the price of the product at the time the order was placed. Like the product name, product prices may change over time, so it is important to store the price at the moment the order was made.

            $table->unsignedSmallInteger('quantity')->default(1);

            $table->json('options')->nullable();

            $table->unique(['order_id','product_id']); //  This ensures that the same product can only appear once per order. It prevents a situation where the same product is added multiple times to the same order with separate entries.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
