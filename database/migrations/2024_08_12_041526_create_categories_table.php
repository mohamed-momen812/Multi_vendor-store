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
        Schema::create('categories', function (Blueprint $table) {

            $table->id();

            /*
                in relation between to table must columns in relation be in the same type
               foreignId() unsigned big integer

               ->constrained() Create a foreign key constraint on this column
               referencing the "id" column of the conventionally related table

               ->nullOnDelete() make value null in foreignId on tables based on this column if delete
               ->nullable() must be before constrained()
            */
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories', 'id')
                ->nullOnDelete(); // Behavior: When a parent record is deleted, the foreign key in the related child records is set to NULL.

            $table->string('name');

            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->string('image')->nullable();

            $table->enum('status', ['active','archived']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
