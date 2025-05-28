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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('protein', 5, 2)->nullable();
            $table->decimal('fats', 5, 2)->nullable();
            $table->json('vitamins')->nullable();
            $table->integer('cooking_time')->nullable();
            $table->decimal('carbs', 5, 2)->nullable();
            $table->text('instructions');
            $table->text('dietary_info')->nullable();
            $table->integer('trending_level')->nullable();
            $table->foreignId('bid')->constrained('food_outlets')->onDelete('cascade');
            $table->decimal('calories', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
