<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating_no',
    ];

    /**
     * Define the relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with Recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

}
