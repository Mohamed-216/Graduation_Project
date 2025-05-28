<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlanRecipe extends Model
{
    use HasFactory;

    protected $table = 'mealplan_recipes'; // Explicitly defining the table name

    protected $fillable = [
        'meal_plan_id',
        'recipe_id',
        'mpname',
        'meal_type',
    ];

    /**
     * Define the relationship with MealPlan.
     */
    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    /**
     * Define the relationship with Recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
