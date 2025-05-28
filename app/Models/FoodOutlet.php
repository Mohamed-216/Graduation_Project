<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodOutlet extends Model
{
    use HasFactory;

    protected $table = 'food_outlets'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'name',
        'mobile_no',
        'menu',
    ];

    protected $casts = [
        'menu' => 'array', // Ensures the JSON field is cast to an array
    ];
}

