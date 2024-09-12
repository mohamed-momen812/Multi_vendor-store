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
        // this table consideres as pivot tabel between user and product
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // use string as id to not go to limit
            $table->uuid('cookie_id'); // for creating cookie_id to send to user, cookie to the user, not primary because the same user may use more than order have the same cookie id each order have the same cookie id
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
