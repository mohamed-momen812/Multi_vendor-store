<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration // annonemous class
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {

            // == $table->bigInteger('id')->unsigned()->autoIncrement()->primary();
            $table->id();

            // can limit character, $table->text('name') without limit character
            $table->string('name');

            // the main diff between unique and primary is unique can be null
            $table->string('slug')->unique();

            $table->string('logo_image')->nullable();

            $table->string('cover_image')->nullable();

            // use enum when want to limit result to specefic values ['active','inactive']
            $table->enum('status', ['active','inactive'])->default('active');

            // all columns in build in migrations is not nullable so if i want to make column nullable use nullable()
            $table->string('description')->nullable();

            // created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
