<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExercise extends Model
{
    use HasFactory;

    protected $table = 'user_exercises'; // Explicitly defining the table name

    protected $fillable = [
        'user_id',
        'exercise_id',
    ];

    /**
     * Define the relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with Exercise.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

}
