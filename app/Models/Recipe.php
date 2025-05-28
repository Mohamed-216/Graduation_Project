<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'title',
        'protein',
        'fats',
        'vitamins',
        'cooking_time',
        'carbs',
        'instructions',
        'dietary_info',
        'trending_level',
        'bid',
        'calories',
    ];

    protected $casts = [
        'vitamins' => 'array', // Ensures the JSON field is cast to an array
    ];

    /**
     * Define the relationship with FoodOutlet.
     */
    public function foodOutlet()
    {
        return $this->belongsTo(FoodOutlet::class, 'bid');
    }
}
