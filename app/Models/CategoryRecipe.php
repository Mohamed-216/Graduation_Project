<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRecipe extends Model
{
    use HasFactory;

    protected $table = 'category_recipe'; // Explicitly defining the table name

    protected $fillable = [
        'category_id',
        'recipe_id',
    ];

    /**
     * Define the relationship with Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define the relationship with Recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
