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
        Schema::create('mealplan_recipes', function (Blueprint $table) {
        $table->foreignId('meal_plan_id')->constrained()->onDelete('cascade');
        $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
        $table->string('mpname');
        $table->string('meal_type')->nullable();
        // Composite Primary Key (Meal Plan + Recipe)
        $table->primary(['meal_plan_id', 'recipe_id']);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mealplan_recipes');
    }
};
