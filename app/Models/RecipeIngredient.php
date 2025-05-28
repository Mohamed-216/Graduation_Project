<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $table = 'recipe_ingredient'; // Explicitly defining the table name

    protected $fillable = [
        'recipe_id',
        'ingredient_id',
    ];

    /**
     * Define the relationship with Recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Define the relationship with Ingredient.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
