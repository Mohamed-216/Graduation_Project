<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    use HasFactory;

    protected $table = 'exercises'; // Explicitly defining the table name (optional)

    protected $fillable = [
        'exercise_name',
    ];

}
